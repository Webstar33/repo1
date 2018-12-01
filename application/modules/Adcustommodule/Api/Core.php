<?php

class Adcustommodule_Api_Core extends Core_Api_Abstract
{
	private $channelTable = 'engine4_activity_subchannel';
	public function isuseronline($userid)
	{
		  // Get online users
		$onlineTable = Engine_Api::_()->getDbtable('online', 'user');
		
		$select = $onlineTable->select()
		  ->where('user_id > ?', 0)
		  ->where('user_id = ?', $userid)
		  //->where('active > ?', new Zend_Db_Expr('DATE_SUB(NOW(),INTERVAL 20 MINUTE)'))
		  ->group('user_id')
		  ;
		  
		//echo $select;
		 
		$getonlineuser = $onlineTable->fetchRow($select);

		if(count($getonlineuser)) {
		   return true;
		}

		return false; 	
	  
	}
	
	public function checkIfCHannelExist($data){
		$defaultDB = Engine_Db_Table::getDefaultAdapter();
		$select = $defaultDB->select()
					 ->from($this->channelTable)
					 ->where('user_id = ?', $data['user_id'])
					 ->where('category_id = ?', $data['category_id'])
					 ->where('subcategory_id = ?', $data['subcategory_id'])
					 ->where('popular = ?', $data['popular'])
					 ->where('most_popular = ?', $data['most_popular'])
					 ->where('location_params = ?', (!empty($data['location_params']))?$data['location_params']:'')
					 ;

		$results = $defaultDB->fetchAll($select);
        return (count($results) > 0)?$results:false;
		
	}
	
	public function updateExistingChannel($data, $existingData){
		$defaultDB = Engine_Db_Table::getDefaultAdapter();
		$updateData = $data;
		$defaultDB->update($this->channelTable,$updateData, 'id = '.$existingData['id']);
		return true;
	}
	
	public function addNewChannel($data){
		$defaultDB = Engine_Db_Table::getDefaultAdapter();
		try{
			$defaultDB->insert($this->channelTable, $data);
			return true;
		}catch(Exception $e){
			return false;
		}
		return false;
	}
	
	public function fetchChannelNotifications(){
		$defaultDB = Engine_Db_Table::getDefaultAdapter();
		$select = $defaultDB->select()
			->from($this->channelTable)
			->order('id ASC')
			;

		$results = $defaultDB->fetchAll($select);
		return $results;
	}
	
	public function getChannelById($channel_id){
		$defaultDB = Engine_Db_Table::getDefaultAdapter();
		$select = $defaultDB->select()
			->from($this->channelTable)
			->where('id = ?',$channel_id)
			;

		$results = $defaultDB->fetchRow($select);
		return $results;
	} 
	 
	public function getAllPapularByTheLocation($radius, $latitude, $longitude){
		// echo $radius.'<br>'.$latitude.'<br>'.$longitude; die;
		$db = Engine_Db_Table::getDefaultAdapter();
		$query = "SELECT SUM(`ppscount`) AS `Total`,`action_id` FROM (
			SELECT `engine4_activity_actions`.`action_id`,count(`engine4_activity_comments`.`comment_id`) as ppscount
			FROM `engine4_activity_actions` INNER JOIN `engine4_activity_comments` ON (engine4_activity_comments.resource_id = engine4_activity_actions.action_id) AND engine4_activity_comments.poster_id IN (SELECT user_id
			FROM `engine4_users` WHERE ((degrees(acos(sin(radians($latitude)) * sin(radians(engine4_users.latitude)) + cos(radians($latitude)) * cos(radians(engine4_users.latitude)) * cos(radians($longitude - engine4_users.longitude)))) * 69.172 <= $radius)) )
			WHERE engine4_activity_actions.attachment_count = 0 					
			GROUP BY engine4_activity_actions.action_id 
			UNION ALL

			SELECT `engine4_activity_actions`.`action_id`,count(`engine4_activity_likes`.`like_id`) as ppscount
			FROM `engine4_activity_actions` INNER JOIN `engine4_activity_likes` ON (engine4_activity_likes.resource_id = engine4_activity_actions.action_id) AND engine4_activity_likes.poster_id IN (SELECT user_id
			FROM `engine4_users` WHERE ((degrees(acos(sin(radians($latitude)) * sin(radians(engine4_users.latitude)) + cos(radians($latitude)) * cos(radians(engine4_users.latitude)) * cos(radians($longitude - engine4_users.longitude)))) * 69.172 <= $radius)))
			WHERE engine4_activity_actions.attachment_count = 0 
			GROUP BY engine4_activity_actions.action_id
			UNION ALL

			SELECT `engine4_activity_actions`.`action_id`, count(`engine4_core_comments`.`comment_id`) as ppscount
			FROM `engine4_activity_actions` 
			INNER JOIN `engine4_activity_attachments` ON (engine4_activity_attachments.action_id = engine4_activity_actions.action_id)
			INNER JOIN `engine4_core_comments` ON (engine4_core_comments.resource_id = engine4_activity_attachments.id) AND engine4_core_comments.poster_id IN (SELECT user_id
			FROM `engine4_users` WHERE ((degrees(acos(sin(radians($latitude)) * sin(radians(engine4_users.latitude)) + cos(radians($latitude)) * cos(radians(engine4_users.latitude)) * cos(radians($longitude - engine4_users.longitude)))) * 69.172 <= $radius)))
			WHERE engine4_activity_actions.attachment_count = 1 and engine4_activity_actions.object_type = 'user'
			GROUP BY engine4_activity_actions.action_id	
			UNION ALL

			SELECT `engine4_activity_actions`.`action_id`, count(`engine4_core_likes`.`like_id`) as ppscount
			FROM `engine4_activity_actions` 
			INNER JOIN `engine4_activity_attachments` ON (engine4_activity_attachments.action_id = engine4_activity_actions.action_id)
			INNER JOIN `engine4_core_likes` ON (engine4_core_likes.resource_id = engine4_activity_attachments.id) AND engine4_core_likes.poster_id IN (SELECT user_id
			FROM `engine4_users` WHERE ((degrees(acos(sin(radians($latitude)) * sin(radians(engine4_users.latitude)) + cos(radians($latitude)) * cos(radians(engine4_users.latitude)) * cos(radians($longitude - engine4_users.longitude)))) * 69.172 <= $radius)))
			WHERE engine4_activity_actions.attachment_count = 1 and engine4_activity_actions.object_type = 'user'
			GROUP BY engine4_activity_actions.action_id
			) AS `union` GROUP BY action_id order by Total DESC";
			
			$build_query = $db->query($query);
			
			$results = $build_query->fetchAll();
			if(!empty($results)) {
				$actions = array();
				foreach( $results as $result ){
					$actions[] = $result['action_id'];
				}
			}
		return $actions;
	}
	
	public function getAllPapular(){
		$db = Engine_Db_Table::getDefaultAdapter();
		$query = "SELECT SUM(`ppscount`) AS `Total`,`action_id` FROM (
					SELECT `engine4_activity_actions`.`action_id`, count(`engine4_activity_comments`.`comment_id`) as ppscount
					FROM `engine4_activity_actions` LEFT JOIN `engine4_activity_comments` ON (engine4_activity_comments.resource_id = engine4_activity_actions.action_id)
					WHERE engine4_activity_actions.attachment_count = 0 					
					GROUP BY engine4_activity_actions.action_id 
					UNION ALL
					SELECT `engine4_activity_actions`.`action_id`, count(`engine4_activity_likes`.`like_id`) as ppscount
					FROM `engine4_activity_actions` LEFT JOIN `engine4_activity_likes` ON (engine4_activity_likes.resource_id = engine4_activity_actions.action_id)
					WHERE engine4_activity_actions.attachment_count = 0 
					GROUP BY engine4_activity_actions.action_id
					UNION ALL
					SELECT `engine4_activity_actions`.`action_id`, count(`engine4_core_comments`.`comment_id`) as ppscount
					FROM `engine4_activity_actions` 
					LEFT JOIN `engine4_activity_attachments` ON (engine4_activity_attachments.action_id = engine4_activity_actions.action_id)
					LEFT JOIN `engine4_core_comments` ON (engine4_core_comments.resource_id = engine4_activity_attachments.id)
					WHERE engine4_activity_actions.attachment_count = 1 and engine4_activity_actions.object_type = 'user'
					GROUP BY engine4_activity_actions.action_id	
					UNION ALL
					SELECT `engine4_activity_actions`.`action_id`, count(`engine4_core_likes`.`like_id`) as ppscount
					FROM `engine4_activity_actions` 
					LEFT JOIN `engine4_activity_attachments` ON (engine4_activity_attachments.action_id = engine4_activity_actions.action_id)
					LEFT JOIN `engine4_core_likes` ON (engine4_core_likes.resource_id = engine4_activity_attachments.id)
					WHERE engine4_activity_actions.attachment_count = 1 and engine4_activity_actions.object_type = 'user'
					GROUP BY engine4_activity_actions.action_id
				) AS `union` GROUP BY action_id order by Total DESC";
					
			$build_query = $db->query($query);
			
			$results = $build_query->fetchAll();
			if(!empty($results)) {
				$actions = array();
				foreach( $results as $result ){
					$actions[] = $result['action_id'];
				}
			}
		return $actions;
	}
	
	function getNewWithLocation($radius, $latitude, $longitude){
			$db = Engine_Db_Table::getDefaultAdapter();
			$query = "SELECT `engine4_activity_actions`.`action_id`
					FROM `engine4_activity_actions` INNER JOIN `engine4_users` ON (engine4_users.user_id = engine4_activity_actions.subject_id) 
					WHERE ((degrees(acos(sin(radians($latitude)) * sin(radians(engine4_users.latitude)) + cos(radians($latitude)) * cos(radians(engine4_users.latitude)) * cos(radians($longitude - engine4_users.longitude)))) * 69.172 <= $radius))
					ORDER BY action_id DESC";
			
			$build_query = $db->query($query);
			
			$results = $build_query->fetchAll();
			if(!empty($results)) {
				$actions = array();
				foreach( $results as $result ){
					$actions[] = $result['action_id'];
				}
			}
		return $actions;
	}
	
	public function getMostPoplularFeed($date=''){
		$db = Engine_Db_Table::getDefaultAdapter();
		$query = "SELECT SUM(`ppscount`) AS `Total`,`action_id` FROM (
					SELECT `engine4_activity_actions`.`action_id`, count(`engine4_activity_comments`.`comment_id`) as ppscount
					FROM `engine4_activity_actions` LEFT JOIN `engine4_activity_comments` ON (engine4_activity_comments.resource_id = engine4_activity_actions.action_id)
					WHERE engine4_activity_actions.attachment_count = 0 AND engine4_activity_comments.creation_date >= '$date'					
					GROUP BY engine4_activity_actions.action_id 
					UNION ALL
					SELECT `engine4_activity_actions`.`action_id`, count(`engine4_activity_likes`.`like_id`) as ppscount
					FROM `engine4_activity_actions` LEFT JOIN `engine4_activity_likes` ON (engine4_activity_likes.resource_id = engine4_activity_actions.action_id)
					WHERE engine4_activity_actions.attachment_count = 0 AND engine4_activity_likes.creation_date >= '$date'
					GROUP BY engine4_activity_actions.action_id
					UNION ALL
					SELECT `engine4_activity_actions`.`action_id`, count(`engine4_core_comments`.`comment_id`) as ppscount
					FROM `engine4_activity_actions` 
					LEFT JOIN `engine4_activity_attachments` ON (engine4_activity_attachments.action_id = engine4_activity_actions.action_id)
					LEFT JOIN `engine4_core_comments` ON (engine4_core_comments.resource_id = engine4_activity_attachments.id)
					WHERE engine4_activity_actions.attachment_count = 1 AND engine4_activity_actions.object_type = 'user' AND engine4_core_comments.creation_date >= '$date'
					GROUP BY engine4_activity_actions.action_id	
					UNION ALL
					SELECT `engine4_activity_actions`.`action_id`, count(`engine4_core_likes`.`like_id`) as ppscount
					FROM `engine4_activity_actions` 
					LEFT JOIN `engine4_activity_attachments` ON (engine4_activity_attachments.action_id = engine4_activity_actions.action_id)
					LEFT JOIN `engine4_core_likes` ON (engine4_core_likes.resource_id = engine4_activity_attachments.id)
					WHERE engine4_activity_actions.attachment_count = 1 AND engine4_activity_actions.object_type = 'user' AND engine4_core_likes.creation_date >= '$date'
					GROUP BY engine4_activity_actions.action_id
				) AS `union` GROUP BY action_id order by Total DESC";
				
			$build_query = $db->query($query);
			
			$results = $build_query->fetchAll();
			$actions = array();
			if(!empty($results)) {
				foreach( $results as $result ){
					$actions[] = $result['action_id'];
				}
			}
			// echo '<pre>'; print_R($actions); die;
		return $actions;
	}
	 
	public function recordFilterHistory(){
		$filterTable = Engine_Api::_()->getDbtable('filters', 'adcustommodule');
		$viewer = Engine_Api::_()->user()->getViewer();
		$record = $filterTable->getByUserId($viewer->getIdentity());
		$lastFiveMin = date('Y-m-d H:i:s', strtotime('-5 minutes'));
		$actionIds = $this->getMostPoplularFeed($lastFiveMin);
		if( count($record) > 0 ) {
			$actionIds = $this->getMostPoplularFeed($row->last_filter_date_time);
			$row = $record;
			if( count($actionIds) > 0 ) {
				$row->last_filter_date_time = date('Y-m-d H:i:s', strtotime('-5 minutes'));
				$row->save();
			}
		} else {
			if( count($actionIds) > 0 ) {
				$row = $filterTable->createRow();
				$row->user_id = $viewer->getIdentity();
				$row->first_filter_date_time = $viewer->creation_date;
				$row->creation_date = date('Y-m-d H:i:s');
				$row->save();
			}
		}
		
		return $actionIds;
	}
	
	public function getMostPoplularFeedByTheLocation($radius, $latitude, $longitude){
		$db = Engine_Db_Table::getDefaultAdapter();
		$query = "SELECT SUM(`ppscount`) AS `Total`,`action_id` FROM (
			SELECT `engine4_activity_actions`.`action_id`,count(`engine4_activity_comments`.`comment_id`) as ppscount
			FROM `engine4_activity_actions` INNER JOIN `engine4_activity_comments` ON (engine4_activity_comments.resource_id = engine4_activity_actions.action_id) AND engine4_activity_comments.poster_id IN (SELECT user_id
			FROM `engine4_users` WHERE ((degrees(acos(sin(radians($latitude)) * sin(radians(engine4_users.latitude)) + cos(radians($latitude)) * cos(radians(engine4_users.latitude)) * cos(radians($longitude - engine4_users.longitude)))) * 69.172 <= $radius)) )
			WHERE engine4_activity_actions.attachment_count = 0 AND engine4_activity_comments.creation_date >= DATE_SUB(NOW(), INTERVAL 5 MINUTE) 					
			GROUP BY engine4_activity_actions.action_id 
			UNION ALL

			SELECT `engine4_activity_actions`.`action_id`,count(`engine4_activity_likes`.`like_id`) as ppscount
			FROM `engine4_activity_actions` INNER JOIN `engine4_activity_likes` ON (engine4_activity_likes.resource_id = engine4_activity_actions.action_id) AND engine4_activity_likes.poster_id IN (SELECT user_id
			FROM `engine4_users` WHERE ((degrees(acos(sin(radians($latitude)) * sin(radians(engine4_users.latitude)) + cos(radians($latitude)) * cos(radians(engine4_users.latitude)) * cos(radians($longitude - engine4_users.longitude)))) * 69.172 <= $radius)))
			WHERE engine4_activity_actions.attachment_count = 0 AND engine4_activity_likes.creation_date >= DATE_SUB(NOW(), INTERVAL 5 MINUTE) 
			GROUP BY engine4_activity_actions.action_id
			UNION ALL

			SELECT `engine4_activity_actions`.`action_id`, count(`engine4_core_comments`.`comment_id`) as ppscount
			FROM `engine4_activity_actions` 
			INNER JOIN `engine4_activity_attachments` ON (engine4_activity_attachments.action_id = engine4_activity_actions.action_id)
			INNER JOIN `engine4_core_comments` ON (engine4_core_comments.resource_id = engine4_activity_attachments.id) AND engine4_core_comments.poster_id IN (SELECT user_id
			FROM `engine4_users` WHERE ((degrees(acos(sin(radians($latitude)) * sin(radians(engine4_users.latitude)) + cos(radians($latitude)) * cos(radians(engine4_users.latitude)) * cos(radians($longitude - engine4_users.longitude)))) * 69.172 <= $radius)))
			WHERE engine4_activity_actions.attachment_count = 1 AND engine4_activity_actions.object_type = 'user' AND engine4_core_comments.creation_date >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)
			GROUP BY engine4_activity_actions.action_id	
			UNION ALL

			SELECT `engine4_activity_actions`.`action_id`, count(`engine4_core_likes`.`like_id`) as ppscount
			FROM `engine4_activity_actions` 
			INNER JOIN `engine4_activity_attachments` ON (engine4_activity_attachments.action_id = engine4_activity_actions.action_id)
			INNER JOIN `engine4_core_likes` ON (engine4_core_likes.resource_id = engine4_activity_attachments.id) AND engine4_core_likes.poster_id IN (SELECT user_id
			FROM `engine4_users` WHERE ((degrees(acos(sin(radians($latitude)) * sin(radians(engine4_users.latitude)) + cos(radians($latitude)) * cos(radians(engine4_users.latitude)) * cos(radians($longitude - engine4_users.longitude)))) * 69.172 <= $radius)))
			WHERE engine4_activity_actions.attachment_count = 1 AND engine4_activity_actions.object_type = 'user' AND engine4_core_likes.creation_date >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)
			GROUP BY engine4_activity_actions.action_id
			) AS `union` GROUP BY action_id order by Total DESC";
			
			$build_query = $db->query($query);
			
			$results = $build_query->fetchAll();
			if(!empty($results)) {
				$actions = array();
				foreach( $results as $result ){
					$actions[] = $result['action_id'];
				}
			}
		return $actions;
	}
	
}
