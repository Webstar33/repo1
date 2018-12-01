<?php

class Adcustommodule_Widget_AdListPopularVideosController extends Engine_Content_Widget_Abstract
{
  public function indexAction()
  {
	  
	$this->view->styletype = $styleType = $this->_getParam('styleType', 'style1');
	 
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

    // Get paginator
    $table = Engine_Api::_()->getItemTable('video');
    $video = Engine_Api::_()->getApi('core', 'video');
    $params['search'] = 1;

    $select = $video->getItemsSelect($table->select(), $params);
    $select->where('status = ?', 1)
      ->order($popularCol . ' DESC');
    $authorisedSelect = $video->getAuthorisedSelect($select);
    $this->view->paginator = $paginator = Zend_Paginator::factory($authorisedSelect);

    // Set item count per page and current page number
    $paginator->setItemCountPerPage($this->_getParam('itemCountPerPage', 4));
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));

    // Hide if nothing to show
    if( $paginator->getTotalItemCount() <= 0 ) {
      return $this->setNoRender();
    }
  }
}