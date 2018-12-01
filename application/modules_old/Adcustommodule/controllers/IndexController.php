<?php

class Adcustommodule_IndexController extends Core_Controller_Action_Standard
{
  public function indexAction()
  {
    $this->view->someVar = 'someVal';
	$viewer = Engine_Api::_()->user()->getViewer();
	
	$isuseronline = Engine_Api::_()->adcustommodule()->isuseronline($viewer->getIdentity());
	
	if($isuseronline) {
		  echo "online";
	}else {
		  echo "not online";
	}
	
  }
  
	public function subscribeChannelAction(){
		// Don't render this if not authorized
		$viewer = Engine_Api::_()->user()->getViewer();
		$subject = null;
		if( Engine_Api::_()->core()->hasSubject() ) {
		  // Get subject
		  $subject = Engine_Api::_()->core()->getSubject();
		  if( !$subject->authorization()->isAllowed($viewer, 'view') ) {
			die('not allowed');
		  }
		}

		$user_id = $viewer->getIdentity();
		if(empty($user_id)){
			die('not allowed');
		}
		
		$request = Zend_Controller_Front::getInstance()->getRequest();
		$actionTable = Engine_Api::_()->getDbtable('customActions', 'activity');
		
		// $this->view->length           = $length = $request->getParam('limit', Engine_Api::_()->getApi('settings', 'core')->getSetting('activity.length', 15));
		$this->view->length           = $length = 15;
		
		// if( $length > 50 ) {
			// $this->view->length           = $length = 50;
		// }
		// Get config options for activity
		$config = array(
			'action_id' => (int) $request->getParam('action_id'),
			'max_id'    => (int) $request->getParam('maxid'),
			'min_id'    => (int) $request->getParam('minid'),     
			'limit'     => (int) $length,
			'category_id' => (int) $request->getParam("category_id"),
			'subcategory_id' => (int) $request->getParam("subcategory_id"),
			'popular' => $request->getParam("popular",1),
			'mostpopular' => $request->getParam("mostpopular",0),
			'locationParams' => $request->getParam("locationParams",0)
		);
		$actions = $actionTable->getCustomActivityIdForNotification($viewer, $config);
		$action_ids = [];
		foreach($actions as $action){
			$action_ids[] = $action->action_id;
		}
		
		// Get config options for Storing data in Subchannel Table
		$configData = array(
			'user_id' => (int) $user_id,
			'category_id' => (int) $request->getParam("category_id"),
			'subcategory_id' => (int) $request->getParam("subcategory_id"),
			'popular' => $request->getParam("popular",1),
			'most_popular' => $request->getParam("mostpopular",0),
			'location_params' => $request->getParam("locationParams",0),
			'location_label' => $request->getParam("locationLabel",'Worldwide'),
			'type_label' => $request->getParam("typelabel",'Most Popular'),
			'action_id' => json_encode($action_ids),  
		);
		
		$alreadyExist = Engine_Api::_()->adcustommodule()->checkIfCHannelExist($configData);

		if(!$alreadyExist){
			$status = Engine_Api::_()->adcustommodule()->addNewChannel($configData);
		}else{
			$status = Engine_Api::_()->adcustommodule()->updateExistingChannel($configData, $alreadyExist[0]);
		}
		
		if($status){
			die('success');
		}else{
			die('failed');
		}
	}
	
	public function checkChannelNotificationAction(){
		$notificationChannels = Engine_Api::_()->adcustommodule()->fetchChannelNotifications();
		if(!empty($notificationChannels)){
			// $length = Engine_Api::_()->getApi('settings', 'core')->getSetting('activity.length', 15);
			$length = 15;
			// if( $length > 50 ) {
				// $length = 50;
			// }
			$notificationAPi = Engine_Api::_()->getDbtable('notifications', 'activity');
			
			foreach($notificationChannels as $channel){
				$existing_icons_ids = $channel['action_id'];
				
				$user_id = $channel['user_id'];
				$user = Engine_Api::_()->user()->getUser($user_id);
				// Get config options for activity
				$config = array(
					'action_id' => null,
					'max_id'    => 0,
					'min_id'    => 0,     
					'limit'     => $length,
					'category_id' => (int) $channel['category_id'],
					'subcategory_id' => (int) $channel['subcategory_id'],
					'popular' => $channel['popular'],
					'mostpopular' => $channel['most_popular'],
					'locationParams' => $channel['location_params'],
				);

				$actionTable = Engine_Api::_()->getDbtable('customActions', 'activity');
				$actions = $actionTable->getCustomActivityIdForNotification($user, $config);
				$action_ids = [];
				$firstAction = null;
				foreach($actions as $action){
					$action_ids[] = $action->action_id;
					$firstAction = $action;
				}
				$newActions = json_encode($action_ids);
				
				if( $newActions != $existing_icons_ids ){
					$newData = $channel;
					$newData['action_id'] = $newActions;
					try{
                        Engine_Api::_()->adcustommodule()->updateExistingChannel($newData, $channel);
						$notificationAPi->addNotification($user, $user, $firstAction, 'acm_feedupdated', array(
                            'channel_id' => $channel['id'],
                            'location_label' => $channel['location_label'],
                            'typelabel' => $channel['type_label'],
                        ));
					}catch(Exception $e){
						Engine_Api::_()->adcustommodule()->updateExistingChannel($newData, $channel);
					}
                }
				// }else{
					// echo "No New POsts", "<br/>";
				// }
			}
		}
		die('tada');
	}
	
	public function getChannelInformationAction(){
		// Don't render this if not authorized
		$viewer = Engine_Api::_()->user()->getViewer();
		$subject = null;
		if( Engine_Api::_()->core()->hasSubject() ) {
		  // Get subject
		  $subject = Engine_Api::_()->core()->getSubject();
		  if( !$subject->authorization()->isAllowed($viewer, 'view') ) {
			die('not allowed');
		  }
		}

		$user_id = $viewer->getIdentity();
		if(empty($user_id)){
			die('not allowed');
		}
		
		$request = Zend_Controller_Front::getInstance()->getRequest();
		$actionTable = Engine_Api::_()->getDbtable('customActions', 'activity');
		
		$channelId =(int) $request->getParam("channel_id",0);
		if(empty($channelId)){
			die('not allowed');
		}

		$channelData = Engine_Api::_()->adcustommodule()->getChannelById($channelId);
		echo json_encode($channelData);
		die;
	}
	
	public function tournamentAction(){

		// Render
		$this->_helper->content
		//->setNoRender()
		->setEnabled();
	
	}
	
	public function landingAction(){
		// Render
		$this->_helper->content
		//->setNoRender()
		->setEnabled();
	}

}
