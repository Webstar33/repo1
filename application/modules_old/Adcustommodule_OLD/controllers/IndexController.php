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
}
