<?php

class Adcustommodule_Widget_ContestMiddleSectionController extends Engine_Content_Widget_Abstract
{
  public function indexAction()
  {
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
	
    $type = 'home';

    $this->view->allCategories = Engine_Api::_()->getDbtable('categories', 'activity')->getAllCategories(0,$type);

  }
}
