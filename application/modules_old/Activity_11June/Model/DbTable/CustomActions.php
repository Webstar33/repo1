<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Activity
 * @author     Stars Developer
 */

class Activity_Model_DbTable_CustomActions extends Activity_Model_DbTable_Actions
{
  protected $_rowClass = 'Activity_Model_Action';
  protected $_name = 'activity_actions';
  protected $_serializedColumns = array('params');

  protected $_actionTypes;

  public function getActivity(User_Model_User $user, array $params = array())
  {
    // Proc args
    extract($this->_getInfo($params)); // action_id, limit, min_id, max_id

    // Prepare main query
    $streamTable = Engine_Api::_()->getDbtable('stream', 'activity');
    $db = $streamTable->getAdapter();
    $union = new Zend_Db_Select($db);

    // Prepare action types
    $masterActionTypes = Engine_Api::_()->getDbtable('actionTypes', 'activity')->getActionTypes();
    $mainActionTypes = array();

    // Filter out types set as not displayable
    foreach( $masterActionTypes as $type ) {
      if( $type->displayable & 4 ) {
        $mainActionTypes[] = $type->type;
      }
    }

    // Filter types based on user request
    if( isset($showTypes) && is_array($showTypes) && !empty($showTypes) ) {
      $mainActionTypes = array_intersect($mainActionTypes, $showTypes);
    } else if( isset($hideTypes) && is_array($hideTypes) && !empty($hideTypes) ) {
      $mainActionTypes = array_diff($mainActionTypes, $hideTypes);
    }

    // Nothing to show
    if( empty($mainActionTypes) ) {
      return null;
    }
    // Show everything
    else if( count($mainActionTypes) == count($masterActionTypes) ) {
      $mainActionTypes = true;
    }
    // Build where clause
    else {
      $mainActionTypes = "'" . join("', '", $mainActionTypes) . "'";
    }

    // Prepare sub queries
    $event = Engine_Hooks_Dispatcher::getInstance()->callEvent('getActivity', array(
      'for' => $user,
    ));
    $responses = (array) $event->getResponses();

    if( empty($responses) ) {
      return null;
    }

    foreach( $responses as $response )
    {
      if( empty($response) ) continue;

      $select = $streamTable->select()
        ->from($streamTable->info('name'), 'action_id')
        ->where('target_type = ?', $response['type'])
        ;

      if( empty($response['data']) ) {
        // Simple
        $select->where('target_id = ?', 0);
      } else if( is_scalar($response['data']) || count($response['data']) === 1 ) {
        // Single
        if( is_array($response['data']) ) {
          list($response['data']) = $response['data'];
        }
        $select->where('target_id = ?', $response['data']);
      } else if( is_array($response['data']) ) {
        // Array
        $select->where('target_id IN(?)', (array) $response['data']);
      } else {
        // Unknown
        continue;
      }

      // Add action_id/max_id/min_id
      if( null !== $action_id ) {
        $select->where('action_id = ?', $action_id);
      } else {
        if( null !== $min_id ) {
          $select->where('action_id >= ?', $min_id);
        } else if( null !== $max_id ) {
          $select->where('action_id <= ?', $max_id);
        }
      }

      if( $mainActionTypes !== true ) {
        $select->where('type IN(' . $mainActionTypes . ')');
      }

      // Add order/limit
      $select
        ->order('action_id DESC')
        ;

      // Add to main query
      $union->union(array('('.$select->__toString().')')); // (string) not work before PHP 5.2.0
    }

    // Finish main query
    $union
      ->order('action_id DESC')
      ;

    // Get actions
    $actions = $db->fetchAll($union);

    // No visible actions
    if( empty($actions) )
    {
      return null;
    }

    // Process ids
    $ids = array();
    foreach( $actions as $data )
    {
      $ids[] = $data['action_id'];
    }
    $ids = array_unique($ids);

    $tableName = $this->info("name");
    $select =  $this->select()
        ->where("`$tableName`.".'`action_id` IN('.join(',', $ids).')')        
        ->limit(150);
    
    if(!empty($params['category_id'])){
        $select->where("category_id = ?",$params['category_id']);
    }
    if(!empty($params['subcategory_id'])){
        $select->where("subcategory_id = ?",$params['subcategory_id']);
    }
    
    $likesTable = Engine_Api::_()->getDbtable('likes', 'activity');
    $likesTableName = $likesTable->info("name");
    $clikesTable = Engine_Api::_()->getDbtable('likes', 'core');
    $clikesTableName = $clikesTable->info("name");

    $commentsTable = Engine_Api::_()->getDbtable('comments', 'activity');
    $commentsTableName = $commentsTable->info("name");
    $ccommentsTable = Engine_Api::_()->getDbtable('comments', 'core');
    $ccommentsTableName = $ccommentsTable->info("name");
    if(!empty($params['popular'])){
        $pastMonthDate = date("Y-m-d",strtotime("-1 month",time()));                
        $select->from($this->info("name"),array("*",new Zend_Db_Expr("(comment_count+like_count) as total_count")))
                ->group("$tableName.action_id")->order("total_count DESC")->having("total_count > 0");
//        $select->setIntegrityCheck(false)->joinLeft($likesTableName,"($likesTableName.resource_id = $tableName.action_id AND $likesTableName.creation_date > '$pastMonthDate') OR $likesTableName.resource_id IS NULL",array())
//                ->joinLeft($commentsTableName,"($commentsTableName.resource_id = $tableName.action_id AND $commentsTableName.creation_date > '$pastMonthDate') OR $commentsTableName.resource_id IS NULL",array())
//                ->joinLeft($ccommentsTableName,"(($ccommentsTableName.resource_id = $tableName.object_id AND $ccommentsTableName.resource_type = $tableName.object_type AND $ccommentsTableName.creation_date > '$pastMonthDate') OR $ccommentsTableName.resource_id IS NULL)",array())
//                ->joinLeft($clikesTableName,"(($clikesTableName.resource_id = $tableName.object_id AND $clikesTableName.resource_type = $tableName.object_type AND $clikesTableName.creation_date > '$pastMonthDate') OR $clikesTableName.resource_id IS NULL)",array("(COUNT($likesTableName.like_id) + COUNT($clikesTableName.like_id) + COUNT($commentsTableName.comment_id) + COUNT($ccommentsTableName.comment_id) ) as likes_count"))
//                ->group("$tableName.action_id")->order("likes_count DESC")->having("likes_count > 0");
        
    }
    
    if(!empty($params['mostpopular'])){
        $select->from($this->info("name"),array("*",new Zend_Db_Expr("(comment_count+like_count) as total_count")))
                ->group("$tableName.action_id")->order("total_count DESC")->having("total_count > 0");
//        $select->setIntegrityCheck(false)->joinLeft($likesTableName,"$likesTableName.resource_id = $tableName.action_id OR $likesTableName.resource_id IS NULL",array())
//                ->joinLeft($commentsTableName,"$commentsTableName.resource_id = $tableName.action_id OR $commentsTableName.resource_id IS NULL",array())
//                ->joinLeft($ccommentsTableName,"(($ccommentsTableName.resource_id = $tableName.object_id AND $ccommentsTableName.resource_type = $tableName.object_type) OR $ccommentsTableName.resource_id IS NULL)",array())
//                ->joinLeft($clikesTableName,"(($clikesTableName.resource_id = $tableName.object_id AND $clikesTableName.resource_type = $tableName.object_type) OR $clikesTableName.resource_id IS NULL)",array("(COUNT($likesTableName.like_id) + COUNT($clikesTableName.like_id) + COUNT($commentsTableName.comment_id) + COUNT($ccommentsTableName.comment_id) ) as likes_count"))
//                ->group("$tableName.action_id")->order("likes_count DESC")->having("likes_count > 0");
    }
    
    if(!empty($params['locationParams'])){
        $locationParams = (array)@json_decode($params['locationParams']);
        if(!empty($locationParams['latitude']) && !empty($locationParams['longitude'])){
            $radius = 10; //in miles
            $latitude = $locationParams['latitude'];
            $longitude = $locationParams['longitude'];
            $flage = Engine_Api::_()->getApi('settings', 'core')->getSetting('seaocore.proximity.search.kilometer', 0);
            if (!empty($flage)) {
              $radius = $radius * (0.621371192);
            }
            $latitudeSin = "sin(radians($latitude))";
            $latitudeCos = "cos(radians($latitude))";
            $usersTable = Engine_Api::_()->getDbtable('users', 'user');
            $usersTableName = $usersTable->info("name");
			if(empty($params['popular']) && empty($params['mostpopular'])){
				
				$select->from($this->info("name"),array("*")); // This has been Removed to fix conflict between multiple from field
				$select->setIntegrityCheck(false)->joinLeft($usersTableName, "$usersTableName.user_id = $tableName.subject_id AND $tableName.subject_type = 'user'", array("(degrees(acos($latitudeSin * sin(radians($usersTableName.latitude)) + $latitudeCos * cos(radians($usersTableName.latitude)) * cos(radians($longitude - $usersTableName.longitude)))) * 69.172) AS distance", $usersTableName.'.location AS locationName'));
				$sqlstring = "(degrees(acos($latitudeSin * sin(radians($usersTableName.latitude)) + $latitudeCos * cos(radians($usersTableName.latitude)) * cos(radians($longitude - $usersTableName.longitude)))) * 69.172 <= " . "'" . $radius . "'";
				$sqlstring .= ")";
				$select->where($sqlstring);
				$select->order("distance");
				
			}else{
				
				// $select->from($this->info("name"),array("*")); // This has been Removed to fix conflict between multiple from field
				$select->setIntegrityCheck(false)->joinLeft($usersTableName, "$usersTableName.user_id = $tableName.subject_id AND $tableName.subject_type = 'user'", array("(degrees(acos($latitudeSin * sin(radians($usersTableName.latitude)) + $latitudeCos * cos(radians($usersTableName.latitude)) * cos(radians($longitude - $usersTableName.longitude)))) * 69.172) AS distance", $usersTableName.'.location AS locationName'));
				$sqlstring = "(degrees(acos($latitudeSin * sin(radians($usersTableName.latitude)) + $latitudeCos * cos(radians($usersTableName.latitude)) * cos(radians($longitude - $usersTableName.longitude)))) * 69.172 <= " . "'" . $radius . "'";
				$sqlstring .= ")";
				$select->where($sqlstring);
				$select->order("distance");
			}
        }
    }
    
    
    $select->order("$tableName.".'action_id DESC');
	$sql = $select->__toString();
    file_put_contents(getcwd().'/tada.txt', print_r($sql, true));	
	
	/* 
	echo"<pre>";print_r($sql);die('done');
	$db = Engine_Db_Table::getDefaultAdapter();
	$stmt = $db->query($sql);
	$data =  $stmt->fetchAll();
	
	return $finalDAta; */
    // Finally get activity
    return $this->fetchAll($select);
  }

  public function getActivityAbout(Core_Model_Item_Abstract $about, User_Model_User $user,
          array $params = array())
  {
    // Proc args
    extract($this->_getInfo($params)); // action_id, limit, min_id, max_id

    // Prepare main query
    $streamTable = Engine_Api::_()->getDbtable('stream', 'activity');
    $db = $streamTable->getAdapter();
    $union = new Zend_Db_Select($db);

    // Prepare action types
    $masterActionTypes = Engine_Api::_()->getDbtable('actionTypes', 'activity')->getActionTypes();
    $subjectActionTypes = array();
    $objectActionTypes = array();

    // Filter types based on displayable
    foreach( $masterActionTypes as $type ) {
      if( $type->displayable & 1 ) {
        $subjectActionTypes[] = $type->type;
      }
      if( $type->displayable & 2 ) {
        $objectActionTypes[] = $type->type;
      }
    }

    // Filter types based on user request
    if( isset($showTypes) && is_array($showTypes) && !empty($showTypes) ) {
      $subjectActionTypes = array_intersect($subjectActionTypes, $showTypes);
      $objectActionTypes = array_intersect($objectActionTypes, $showTypes);
    } else if( isset($hideTypes) && is_array($hideTypes) && !empty($hideTypes) ) {
      $subjectActionTypes = array_diff($subjectActionTypes, $hideTypes);
      $objectActionTypes = array_diff($objectActionTypes, $hideTypes);
    }

    // Nothing to show
    if( empty($subjectActionTypes) && empty($objectActionTypes) ) {
      return null;
    }

    if( empty($subjectActionTypes) ) {
      $subjectActionTypes = null;
    } else if( count($subjectActionTypes) == count($masterActionTypes) ) {
      $subjectActionTypes = true;
    } else {
      $subjectActionTypes = "'" . join("', '", $subjectActionTypes) . "'";
    }

    if( empty($objectActionTypes) ) {
      $objectActionTypes = null;
    } else if( count($objectActionTypes) == count($masterActionTypes) ) {
      $objectActionTypes = true;
    } else {
      $objectActionTypes = "'" . join("', '", $objectActionTypes) . "'";
    }

    // Prepare sub queries
    $event = Engine_Hooks_Dispatcher::getInstance()->callEvent('getActivity', array(
      'for' => $user,
      'about' => $about,
    ));
    $responses = (array) $event->getResponses();

    if( empty($responses) ) {
      return null;
    }

    foreach( $responses as $response )
    {
      if( empty($response) ) continue;

      // Target info
      $select = $streamTable->select()
        ->from($streamTable->info('name'), 'action_id')
        ->where('target_type = ?', $response['type'])
        ;

      if( empty($response['data']) ) {
        // Simple
        $select->where('target_id = ?', 0);
      } else if( is_scalar($response['data']) || count($response['data']) === 1 ) {
        // Single
        if( is_array($response['data']) ) {
          list($response['data']) = $response['data'];
        }
        $select->where('target_id = ?', $response['data']);
      } else if( is_array($response['data']) ) {
        // Array
        $select->where('target_id IN(?)', (array) $response['data']);
      } else {
        // Unknown
        continue;
      }

      // Add action_id/max_id/min_id
      if( null !== $action_id ) {
        $select->where('action_id = ?', $action_id);
      } else {
        if( null !== $min_id ) {
          $select->where('action_id >= ?', $min_id);
        } else if( null !== $max_id ) {
          $select->where('action_id <= ?', $max_id);
        }
      }

      // Add order/limit
      $select
        ->order('action_id DESC')
        ;


      // Add subject to main query
      $selectSubject = clone $select;
      if( $subjectActionTypes !== null ) {
        if( $subjectActionTypes !== true ) {
          $selectSubject->where('type IN('.$subjectActionTypes.')');
        }
        $selectSubject
          ->where('subject_type = ?', $about->getType())
          ->where('subject_id = ?', $about->getIdentity());
        $union->union(array('('.$selectSubject->__toString().')')); // (string) not work before PHP 5.2.0
      }

      // Add object to main query
      $selectObject = clone $select;
      if( $objectActionTypes !== null ) {
        if( $objectActionTypes !== true ) {
          $selectObject->where('type IN('.$objectActionTypes.')');
        }
        $selectObject
          ->where('object_type = ?', $about->getType())
          ->where('object_id = ?', $about->getIdentity());
        $union->union(array('('.$selectObject->__toString().')')); // (string) not work before PHP 5.2.0
      }
    }

    // Finish main query
    $union
      ->order('action_id DESC')
      ;

    // Get actions
    $actions = $db->fetchAll($union);

    // No visible actions
    if( empty($actions) )
    {
      return null;
    }

    // Process ids
    $ids = array();
    foreach( $actions as $data )
    {
      $ids[] = $data['action_id'];
    }
    $ids = array_unique($ids);

    $select =  $this->select()
        ->where('action_id IN('.join(',', $ids).')')
        ->order('action_id DESC')
        ->limit($limit);
    
    if(!empty($params['category_id'])){
        $select->where("category_id = ?",$params['category_id']);
    }
    if(!empty($params['subcategory_id'])){
        $select->where("subcategory_id = ?",$params['subcategory_id']);
    }
    
    // Finally get activity
    return $this->fetchAll($select);
  }
  
	public function getCustomActivityIdForNotification(User_Model_User $user, array $params = array())
	{
		// Proc args
		extract($this->_getInfo($params)); // action_id, limit, min_id, max_id

		// Prepare main query
		$streamTable = Engine_Api::_()->getDbtable('stream', 'activity');
		$db = $streamTable->getAdapter();
		$union = new Zend_Db_Select($db);

		// Prepare action types
		$masterActionTypes = Engine_Api::_()->getDbtable('actionTypes', 'activity')->getActionTypes();
		$mainActionTypes = array();

		// Filter out types set as not displayable
		foreach( $masterActionTypes as $type ) {
		  if( $type->displayable & 4 ) {
			$mainActionTypes[] = $type->type;
		  }
		}

		// Filter types based on user request
		if( isset($showTypes) && is_array($showTypes) && !empty($showTypes) ) {
			$mainActionTypes = array_intersect($mainActionTypes, $showTypes);
		} else if( isset($hideTypes) && is_array($hideTypes) && !empty($hideTypes) ) {
			$mainActionTypes = array_diff($mainActionTypes, $hideTypes);
		}

		// Nothing to show
		if( empty($mainActionTypes) ) {
			return null;
		}
		// Show everything
		else if( count($mainActionTypes) == count($masterActionTypes) ) {
			$mainActionTypes = true;
		}
		// Build where clause
		else {
			$mainActionTypes = "'" . join("', '", $mainActionTypes) . "'";
		}

		// Prepare sub queries
		$event = Engine_Hooks_Dispatcher::getInstance()->callEvent('getActivity', array(
			'for' => $user,
		));
		$responses = (array) $event->getResponses();

		if( empty($responses) ) {
			return null;
		}

		foreach( $responses as $response )
		{
			if( empty($response) ) continue;

			$select = $streamTable->select()
				->from($streamTable->info('name'), 'action_id')
				->where('target_type = ?', $response['type'])
				;

			if( empty($response['data']) ) {
				// Simple
				$select->where('target_id = ?', 0);
			} else if( is_scalar($response['data']) || count($response['data']) === 1 ) {
				// Single
				if( is_array($response['data']) ) {
					list($response['data']) = $response['data'];
				}
				$select->where('target_id = ?', $response['data']);
			} else if( is_array($response['data']) ) {
				// Array
				$select->where('target_id IN(?)', (array) $response['data']);
			} else {
				// Unknown
				continue;
			}

			// Add action_id/max_id/min_id
			if( null !== $action_id ) {
				$select->where('action_id = ?', $action_id);
			} else {
				if( null !== $min_id ) {
					$select->where('action_id >= ?', $min_id);
				} else if( null !== $max_id ) {
					$select->where('action_id <= ?', $max_id);
				}
			}

			if( $mainActionTypes !== true ) {
				$select->where('type IN(' . $mainActionTypes . ')');
			}

			// Add order/limit
			$select
				->order('action_id DESC')
				;

			// Add to main query
			$union->union(array('('.$select->__toString().')')); // (string) not work before PHP 5.2.0
		}

		// Finish main query
		$union
			->order('action_id DESC')
			;

		// Get actions
		$actions = $db->fetchAll($union);

		// No visible actions
		if( empty($actions) )
		{
			return null;
		}

		// Process ids
		$ids = array();
		foreach( $actions as $data )
		{
		  $ids[] = $data['action_id'];
		}
		$ids = array_unique($ids);

		$tableName = $this->info("name");
		$select =  $this->select()
			->where("`$tableName`.".'`action_id` IN('.join(',', $ids).')')        
			->limit($limit);

		if(!empty($params['category_id'])){
			$select->where("category_id = ?",$params['category_id']);
		}
		if(!empty($params['subcategory_id'])){
			$select->where("subcategory_id = ?",$params['subcategory_id']);
		}

		$likesTable = Engine_Api::_()->getDbtable('likes', 'activity');
		$likesTableName = $likesTable->info("name");
		$clikesTable = Engine_Api::_()->getDbtable('likes', 'core');
		$clikesTableName = $clikesTable->info("name");

		$commentsTable = Engine_Api::_()->getDbtable('comments', 'activity');
		$commentsTableName = $commentsTable->info("name");
		$ccommentsTable = Engine_Api::_()->getDbtable('comments', 'core');
		$ccommentsTableName = $ccommentsTable->info("name");
		if(!empty($params['popular'])){
			$pastMonthDate = date("Y-m-d",strtotime("-1 month",time()));                
			$select->from($this->info("name"),array("action_id"));
			$select->setIntegrityCheck(false)->joinLeft($likesTableName,"($likesTableName.resource_id = $tableName.action_id AND $likesTableName.creation_date > '$pastMonthDate') OR $likesTableName.resource_id IS NULL",array())
					->joinLeft($commentsTableName,"($commentsTableName.resource_id = $tableName.action_id AND $commentsTableName.creation_date > '$pastMonthDate') OR $commentsTableName.resource_id IS NULL",array())
					->joinLeft($ccommentsTableName,"(($ccommentsTableName.resource_id = $tableName.object_id AND $ccommentsTableName.resource_type = $tableName.object_type AND $ccommentsTableName.creation_date > '$pastMonthDate') OR $ccommentsTableName.resource_id IS NULL)",array())
					->joinLeft($clikesTableName,"(($clikesTableName.resource_id = $tableName.object_id AND $clikesTableName.resource_type = $tableName.object_type AND $clikesTableName.creation_date > '$pastMonthDate') OR $clikesTableName.resource_id IS NULL)",array("(COUNT($likesTableName.like_id) + COUNT($clikesTableName.like_id) + COUNT($commentsTableName.comment_id) + COUNT($ccommentsTableName.comment_id) ) as likes_count"))
					->group("$tableName.action_id")->order("likes_count DESC")->having("likes_count > 0");
			
		}

		if(!empty($params['mostpopular'])){
			$select->from($this->info("name"),array("action_id"));
			$select->setIntegrityCheck(false)->joinLeft($likesTableName,"$likesTableName.resource_id = $tableName.action_id OR $likesTableName.resource_id IS NULL",array())
					->joinLeft($commentsTableName,"$commentsTableName.resource_id = $tableName.action_id OR $commentsTableName.resource_id IS NULL",array())
					->joinLeft($ccommentsTableName,"(($ccommentsTableName.resource_id = $tableName.object_id AND $ccommentsTableName.resource_type = $tableName.object_type) OR $ccommentsTableName.resource_id IS NULL)",array())
					->joinLeft($clikesTableName,"(($clikesTableName.resource_id = $tableName.object_id AND $clikesTableName.resource_type = $tableName.object_type) OR $clikesTableName.resource_id IS NULL)",array("(COUNT($likesTableName.like_id) + COUNT($clikesTableName.like_id) + COUNT($commentsTableName.comment_id) + COUNT($ccommentsTableName.comment_id) ) as likes_count"))
					->group("$tableName.action_id")->order("likes_count DESC")->having("likes_count > 0");
		}

		if(!empty($params['locationParams'])){
			$locationParams = (array)@json_decode($params['locationParams']);
			if(!empty($locationParams['latitude']) && !empty($locationParams['longitude'])){
				$radius = 10; //in miles
				$latitude = $locationParams['latitude'];
				$longitude = $locationParams['longitude'];
				$flage = Engine_Api::_()->getApi('settings', 'core')->getSetting('seaocore.proximity.search.kilometer', 0);
				if (!empty($flage)) {
				  $radius = $radius * (0.621371192);
				}
				$latitudeSin = "sin(radians($latitude))";
				$latitudeCos = "cos(radians($latitude))";
				$usersTable = Engine_Api::_()->getDbtable('users', 'user');
				$usersTableName = $usersTable->info("name");
				if(empty($params['popular']) && empty($params['mostpopular'])){
					
					$select->from($this->info("name"),array("action_id")); // This has been Removed to fix conflict between multiple from field
					$select->setIntegrityCheck(false)->joinLeft($usersTableName, "$usersTableName.user_id = $tableName.subject_id AND $tableName.subject_type = 'user'", array("(degrees(acos($latitudeSin * sin(radians($usersTableName.latitude)) + $latitudeCos * cos(radians($usersTableName.latitude)) * cos(radians($longitude - $usersTableName.longitude)))) * 69.172) AS distance", $usersTableName.'.location AS locationName'));
					$sqlstring = "(degrees(acos($latitudeSin * sin(radians($usersTableName.latitude)) + $latitudeCos * cos(radians($usersTableName.latitude)) * cos(radians($longitude - $usersTableName.longitude)))) * 69.172 <= " . "'" . $radius . "'";
					$sqlstring .= ")";
					$select->where($sqlstring);
					$select->order("distance");
					
				}else{
					
					// $select->from($this->info("name"),array("*")); // This has been Removed to fix conflict between multiple from field
					$select->setIntegrityCheck(false)->joinLeft($usersTableName, "$usersTableName.user_id = $tableName.subject_id AND $tableName.subject_type = 'user'", array("(degrees(acos($latitudeSin * sin(radians($usersTableName.latitude)) + $latitudeCos * cos(radians($usersTableName.latitude)) * cos(radians($longitude - $usersTableName.longitude)))) * 69.172) AS distance", $usersTableName.'.location AS locationName'));
					$sqlstring = "(degrees(acos($latitudeSin * sin(radians($usersTableName.latitude)) + $latitudeCos * cos(radians($usersTableName.latitude)) * cos(radians($longitude - $usersTableName.longitude)))) * 69.172 <= " . "'" . $radius . "'";
					$sqlstring .= ")";
					$select->where($sqlstring);
					$select->order("distance");
				}
			}
		}


		$select->order("$tableName.".'action_id DESC');
		$sql = $select->__toString();
		// echo"<pre>";print_r($sql);die('done');

		/* 
		echo"<pre>";print_r($sql);die('done');
		$db = Engine_Db_Table::getDefaultAdapter();
		$stmt = $db->query($sql);
		$data =  $stmt->fetchAll();

		return $finalDAta; */
		// Finally get activity
		return $this->fetchAll($select);
	}
}
