<?php

class Adcustommodule_Widget_CustomMenuMainController extends Engine_Content_Widget_Abstract
{
  public function indexAction()
  {
    $this->view->navigation = $navigation = Engine_Api::_()
      ->getApi('menus', 'core')
      ->getNavigation('core_main');

    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $requireCheck = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.general.browse', 1);
    if( !$requireCheck && !$viewer->getIdentity() ) {
      $navigation->removePage($navigation->findOneBy('route', 'user_general'));
    }
    $this->view->menuType = $this->_getParam('menuType', 'horizontal');
    $this->view->menuFromTheme = $this->_getParam('menuFromTheme', false);
  }
}
