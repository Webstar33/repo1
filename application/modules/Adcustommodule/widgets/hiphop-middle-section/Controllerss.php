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
		
		if($mostpopular){
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
			}
		} else if($new_post) {
			
			$table = Engine_Api::_()->getItemTable('video');
			$video = Engine_Api::_()->getApi('core', 'video');
			
			
			$totalselect = $table->select()
			->where('view_count =?',0)
			->where('status = ?', 1);
			$total = $table->fetchAll($totalselect);
			
			$this->view->totalvideo = $totalvideo = count($total);
			
			
			$select = $table->select()
			->where('view_count =?',0)
			->where('status = ?', 1)
			->order('video_id' . ' DESC')->limit($limit , $offset);
			
			//$select = $table->select()
			
			
			
			
			
			//$authorisedSelect = $video->getAuthorisedSelect($select);
			$this->view->paginator = $authorisedSelect = Zend_Paginator::factory($select);
			
			$this->view->nextoffset = $nextoffset = $offset + $limit;
		
	        $this->view->ispopularvideoexist = 1;
			
			if($totalvideo >= $nextoffset){
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
                
               
		 
		$customActionTable = Engine_Api::_()->getDbtable('customActions', 'activity');
              
		if( isset($ids) && count($ids) > 0 ){
			
			$wcselect =  $actiontable->select()
					->setIntegrityCheck(false)
					->from($actionName,NULL)
					->joinLeft($attachName, "$actionName.action_id = $attachName.action_id")
					->where($actionName.'.action_id IN('.join(',', $ids).')')
					->where($attachName.'.type = ?', 'video')
					->where($actionName.'.subcategory_id = ?', '4')
					->group($attachName.'.id');
			
			$results = $attachmenttable->fetchAll($wcselect);
                       
		} else {
			$wcselect = $actiontable->select()
					->setIntegrityCheck(false)
					->from($actionName,NULL)
					->joinLeft($attachName, "$actionName.action_id = $attachName.action_id")
					->where($attachName.'.type = ?', 'video')
					->where($actionName.'.subcategory_id = ?', '4')
					->group('id');
			$results = $attachmenttable->fetchAll($wcselect);
		}
	
		
	$videosarray = array();
	
        if(isset($results)){
            foreach($results as $rec) {
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
		 ->order($popularCol . ' DESC');
		$total = $video->getAuthorisedSelect($totalselect);
		
		$this->view->totalvideo = $totalvideo = count($total);
		
		$select = $video->getItemsSelect($table->select(), $params);
		$select->where('status = ?', 1)
		->where('video_id IN (?)',$videosarray)
		 ->order('video_id' . ' DESC')->limit($limit , $offset);
				

			 //$sql = $select->__toString();
                //echo $sql; die;
		$authorisedSelect = $video->getAuthorisedSelect($select);
                $this->view->paginator = $authorisedSelect;
                $this->view->nextoffset = $nextoffset = $offset + $limit;
		//$this->view->paginator = $paginator = Zend_Paginator::factory($authorisedSelect);
//
//	      //Set item count per page and current page number
//		$paginator->setItemCountPerPage($this->_getParam('itemCountPerPage', 9));
//		$paginator->setCurrentPageNumber($this->_getParam('page', 1));
	        $this->view->ispopularvideoexist = 1;
	}
        
        if($totalvideo >= $nextoffset){
            $this->view->isloadmorecreate = 1;
        }else{
            $this->view->isloadmorecreate = 0;
        }
	
  }
}
