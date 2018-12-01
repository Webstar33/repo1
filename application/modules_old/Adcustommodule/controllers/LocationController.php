<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Adcustommodule
 * @version    $Id: LocationController.php 2018-17-02 $
 */

class Adcustommodule_LocationController extends Core_Controller_Action_Standard
{
  public $_viewer_id;
  public function init()
  {
  	$viewer = Engine_Api::_()->user()->getViewer();
  	$this->_viewer_id = $viewer->getIdentity();

  	if(empty($this->_viewer_id)) {
  		$this->_helper->viewRenderer->setNoRender(true);
  	}
  }

  public function editAction()
  {
  	$this->_helper->layout->disableLayout();
  	$this->view->viewer_id = $this->_viewer_id;
  }
}