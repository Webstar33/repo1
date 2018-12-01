<?php

class Adcustommodule_Widget_HotMiddleSectionController extends Engine_Content_Widget_Abstract
{
  public function indexAction()
  {
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
	
    $type = 'hot';

    $this->view->allCategories = Engine_Api::_()->getDbtable('categories', 'activity')->getAllCategories(0,$type);

  }
}
