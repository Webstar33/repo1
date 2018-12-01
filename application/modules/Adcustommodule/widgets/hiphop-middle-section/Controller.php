<?php

class Adcustommodule_Widget_HiphopMiddleSectionController extends Engine_Content_Widget_Abstract
{
  public function indexAction()
  {
	
    // Don't render this if not authorized
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $subject = null;
    if( Engine_Api::_()->core()->hasSubject() ) {
      // Get subject
      $subject = Engine_Api::_()->core()->getSubject();
      if( !$subject->authorization()->isAllowed($viewer, 'view') ) {
        return false;
      }
    }
    
    $this->view->subject = $subject;
	
	// Should we consider views or comments popular?
    $popularType = $this->_getParam('popularType', 'view');
	
    if( !in_array($popularType, array('view', 'comment', 'rating','creation')) ) {
      $popularType = 'view';
    }
	
    $this->view->popularType = $popularType;
    if( $popularType == 'rating' ) {
      $this->view->popularCol = $popularCol = 'rating';
    } elseif( $popularType == 'creation' ) {
      $this->view->popularCol = $popularCol = 'video_id';
	}else{
      $this->view->popularCol = $popularCol = $popularType . '_count';
    }
	
	$request = Zend_Controller_Front::getInstance()->getRequest();
	
	$this->view->feedOnly = $feedOnly = $request->getParam('feedOnly', false);
	$this->view->isOnline = $isOnline = $request->getParam('isOnline', 0);
	$this->view->mostpopular = $mostpopular = $request->getParam('mostpopular', 1);
	$this->view->new_post = $new_post = $request->getParam('new_post', 0);
	$this->view->locationParams = $locationParams = $request->getParam('locationParams', '');
	$this->view->offset = $offset = $request->getParam('offset', 0);
        
        //$limit = 6 + $offset ;
        $limit = ($offset != null) ? 3 : 30;
       //echo 'feedOnly:'.$feedOnly.'-isOnline:'.$isOnline.'-mostpopular:'.$mostpopular.'-new_post:'.$new_post.'-locationParams:'.$locationParams.'-offset:'.$offset; die('ll');
	
	$attachmenttable = Engine_Api::_()->getDbtable('attachments', 'activity');
        $attachName = $attachmenttable->info('name');
     
	
	$actiontable = Engine_Api::_()->getDbtable('actions', 'activity');
	$actionName = $actiontable->info('name');
		
		if($mostpopular && !$locationParams){
			$ids = Engine_Api::_()->adcustommodule()->getAllPapular();
		} else if($locationParams && $mostpopular) {
			$locationParams = (array)@json_decode($locationParams);
			if(!empty($locationParams['latitude']) && !empty($locationParams['longitude'])){
				$radius = 10; //in miles
				$latitude = $locationParams['latitude'];
				$longitude = $locationParams['longitude'];
				$flage = Engine_Api::_()->getApi('settings', 'core')->getSetting('seaocore.proximity.search.kilometer', 0);
				if (!empty($flage)) {
				  $radius = $radius * (0.621371192);
				}
				$ids = Engine_Api::_()->adcustommodule()->getMostPoplularFeedByTheLocation($radius, $latitude, $longitude);

               // error_log("ids = ".$ids,3,'/var/www/html/error.log');
			}
		} else if($new_post && !$locationParams) {
			
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
                        $newids = $actions;
                        $customActionTable = Engine_Api::_()->getDbtable('customActions', 'activity');
                        
                        if( isset($newids) && count($newids) > 0 ){
			
			$wcselect =  $actiontable->select()
                            ->setIntegrityCheck(false)
                            ->from($actionName,NULL)
                            ->joinLeft($attachName, "$actionName.action_id = $attachName.action_id")
                            ->where($actionName.'.action_id IN('.join(',', $newids).')')
                            ->where($attachName.'.type = ?', 'video')
                            ->where($actionName.'.category_id = ?', '27')
                            ->group($attachName.'.id');

                            $resultsnew = $attachmenttable->fetchAll($wcselect);
                       
                        }
                        
                        $videosarrayNew = array();
	
                        if(isset($resultsnew)){
                            foreach($resultsnew as $rec) {
                                $videosarrayNew[] = $rec->id;
                            }
                        }
                        
                        $table = Engine_Api::_()->getItemTable('video');
                        $video = Engine_Api::_()->getApi('core', 'video');
                        $params['search'] = 1;

                        $this->view->ispopularvideoexist = 0;
                        $this->view->isrecentvideoexist = 0;

                        $totalvideoNew = 0;
                        $nextoffsetNew = 0;
                        
                        if(count($videosarrayNew) > 0) {

                        $totalselect = $video->getItemsSelect($table->select(), $params);
                        
                                $totalselect->where('status = ?', 1)
                                ->where('view_count =? ',0)
                                ->where('comment_count =?',0)
                                ->where('like_count =?',0)
                                ->where('video_id IN (?)',$videosarrayNew)
                                 ->order('video_id DESC');
                                $total = $video->getAuthorisedSelect($totalselect);
                                
                                $this->view->totalvideo = $totalvideoNew = count($total);
                                

                                $select = $video->getItemsSelect($table->select(), $params);
                                $select->where('status = ?', 1)
                                ->where('view_count =? ',0)
                                ->where('comment_count =?',0)
                                ->where('like_count =?',0)
                                ->where('video_id IN (?)',$videosarrayNew)
                                ->order('video_id DESC')
                                ->limit($limit , $offset);
                                

                                        
                                $authorisedSelect = $video->getAuthorisedSelect($select);
                                
                                $this->view->paginator = $authorisedSelect;
                                $this->view->nextoffset = $nextoffsetNew = $offset + $limit;
                               
                                $this->view->ispopularvideoexist = 1;
                        }

                        if($totalvideoNew >= $nextoffsetNew){
                            $this->view->isloadmorecreate = 1;
                        }else{
                            $this->view->isloadmorecreate = 0;
                        }
                        
                        
                        

			
		} else if($locationParams && $new_post) {
			$locationParams = (array)@json_decode($locationParams);
			if(!empty($locationParams['latitude']) && !empty($locationParams['longitude'])){
				$radius = 10; //in miles
				$latitude = $locationParams['latitude'];
				$longitude = $locationParams['longitude'];
				$flage = Engine_Api::_()->getApi('settings', 'core')->getSetting('seaocore.proximity.search.kilometer', 0);
				if (!empty($flage)) {
				  $radius = $radius * (0.621371192);
				}
				$ids = Engine_Api::_()->adcustommodule()->getNewWithLocation($radius, $latitude, $longitude);
			}
			
		} else if($isOnline) {
			
		} else if($locationParams && $isOnline) {
			
		}
                
        if($mostpopular == 1){
                        $customActionTable = Engine_Api::_()->getDbtable('customActions', 'activity');
              
                        if( isset($ids) && count($ids) > 0 ){

                                $wcselect =  $actiontable->select()
                                                ->setIntegrityCheck(false)
                                                ->from($actionName,NULL)
                                                ->joinLeft($attachName, "$actionName.action_id = $attachName.action_id")
                                                ->where($actionName.'.action_id IN('.join(',', $ids).')')
                                                ->where($attachName.'.type = ?', 'video')
                                                ->where($actionName.'.category_id = ?', '27')
                                                ->group($attachName.'.id');

                                $results = $attachmenttable->fetchAll($wcselect);

                        } 
                        
                
                $videosarray = array();
                 //error_log("query1 = ".$wcselect,3,'/var/www/html/error.log');
               // error_log("result 1  = ".json_encode($results),3,'/var/www/html/error.log');

                if(isset($results)){

                    foreach($results as $rec) {
                       // error_log("result 2  = ".json_encode($rec),3,'/var/www/html/error.log');
                        $videosarray[] = $rec->id;
                    }
                }
                 

                //print_r($videosarray);die('ooo');

                $table = Engine_Api::_()->getItemTable('video');
                $video = Engine_Api::_()->getApi('core', 'video');
                $params['search'] = 1;

                $this->view->ispopularvideoexist = 0;
                $this->view->isrecentvideoexist = 0;

                $totalvideo = 0;
                $nextoffset = 0;

                if(count($videosarray) > 0) {

                $totalselect = $video->getItemsSelect($table->select(), $params);
                        $totalselect->where('status = ?', 1)
                        ->where('video_id IN (?)',$videosarray)
                        ->where('view_count > 0')
                        ->orwhere('comment_count > 0')
                        ->orwhere('like_count > 0')
                         ->order($popularCol . ' DESC');
                        $total = $video->getAuthorisedSelect($totalselect);

                        $this->view->totalvideo = $totalvideo = count($total);

                        $select = $video->getItemsSelect($table->select(), $params);
                        $select->where('status = ?', 1)
                        ->where('video_id IN (?)',$videosarray)
                        ->where('view_count > 0 OR comment_count > 0 OR like_count > 0')
                        //->orwhere('comment_count > 0')
                        //->orwhere('like_count > 0')
                         ->order($popularCol . ' DESC')->limit($limit , $offset);


                                 //$sql = $select->__toString();
                        //echo $sql; die;
                        // file_put_contents('/var/www/html/error.log', '');
                        // error_log("query = ".$select,3,'/var/www/html/error.log');
                        $authorisedSelect = $video->getAuthorisedSelect($select);
                        $this->view->paginator = $authorisedSelect;
                        $this->view->nextoffset = $nextoffset = $offset + $limit;
                        //$this->view->paginator = $paginator = Zend_Paginator::factory($authorisedSelect);
        //
        //	      //Set item count per page and current page number
        //		$paginator->setItemCountPerPage($this->_getParam('itemCountPerPage', 9));
        //		$paginator->setCurrentPageNumber($this->_getParam('page', 1));
                        $this->view->ispopularvideoexist = 1;
                        //error_log("video queryyyy = ".$select,3,'/var/www/html/error.log');
                }

                if($totalvideo >= $nextoffset){
                    $this->view->isloadmorecreate = 1;
                }else{
                    $this->view->isloadmorecreate = 0;
                }
               }
		 
		
	
  }
}
