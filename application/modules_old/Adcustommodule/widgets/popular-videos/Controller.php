<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Video
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Controller.php 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */

/**
 * @category   Application_Extensions
 * @package    Video
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Adcustommodule_Widget_PopularVideosController extends Engine_Content_Widget_Abstract
{
  public function indexAction()
  {
    

    // Get likes
    $itemTable = Engine_Api::_()->getItemTable('video');
    $likesTable = Engine_Api::_()->getDbtable('likes', 'core');
    $likesTableName = $likesTable->info('name');
    $params['search'] = 1;
    $video = Engine_Api::_()->getApi('core', 'video');

    $select = $video->getItemsSelect($itemTable->select(), $params);
    $select->distinct(true)
      ->from($itemTable)
      //->order(new Zend_Db_Expr('COUNT(like_id)'))
      ;
	  
    $authorisedSelect = $video->getAuthorisedSelect($select);

    // Get paginator
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