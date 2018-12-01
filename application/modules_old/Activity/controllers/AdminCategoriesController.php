<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Activity
 * @author     Stars Developer
 */

class Activity_AdminCategoriesController extends Core_Controller_Action_Admin
{
    public function indexAction(){
		
        $this->view->id = $parent_id = $this->getParam("id",0);
        $this->view->table = $table = Engine_Api::_()->getDbtable('categories', 'activity');
        $select = $table->select()->where("parent_id = ?",$parent_id)->where("cat_type = ?",'home')->order("order ASC");
        $this->view->categories = $table->fetchAll($select);
        
        $categoryTable = Engine_Api::_()->getDbtable('categories', 'activity');
        $this->view->category = $categoryTable->find($parent_id)->current();
		
		/*$wcselect = $table->select()->where("cat_type = ?",'photo')->order("order ASC");
        $wccategories = $table->fetchAll($wcselect);
		
		echo "<pre>"; 
		foreach($wccategories as $cat) {
			$cat->parent_id = 0;
			$cat->save();
		}*/
		
    }
	
	public function musicAction(){
		
        $this->view->id = $parent_id = $this->getParam("id",0);
        $this->view->table = $table = Engine_Api::_()->getDbtable('categories', 'activity');
        $select = $table->select()->where("parent_id = ?",$parent_id)->where("cat_type = ?",'music')->order("order ASC");
        $this->view->categories = $table->fetchAll($select);
        
        $categoryTable = Engine_Api::_()->getDbtable('categories', 'activity');
        $this->view->category = $categoryTable->find($parent_id)->current();
    }
	
	public function photoAction(){
		
        $this->view->id = $parent_id = $this->getParam("id",0);
        $this->view->table = $table = Engine_Api::_()->getDbtable('categories', 'activity');
        $select = $table->select()->where("parent_id = ?",$parent_id)->where("cat_type = ?",'photo')->order("order ASC");
        $this->view->categories = $table->fetchAll($select);
        
        $categoryTable = Engine_Api::_()->getDbtable('categories', 'activity');
        $this->view->category = $categoryTable->find($parent_id)->current();
    }

	public function sportsAction(){
        $this->view->id = $parent_id = $this->getParam("id",0);
        $this->view->table = $table = Engine_Api::_()->getDbtable('categories', 'activity');
        $select = $table->select()->where("parent_id = ?",$parent_id)->where("cat_type = ?",'sport')->order("order ASC");
        $this->view->categories = $table->fetchAll($select);
        
        $categoryTable = Engine_Api::_()->getDbtable('categories', 'activity');
        $this->view->category = $categoryTable->find($parent_id)->current();
    }
	
	public function womenAction(){
        $this->view->id = $parent_id = $this->getParam("id",0);
        $this->view->table = $table = Engine_Api::_()->getDbtable('categories', 'activity');
        $select = $table->select()->where("parent_id = ?",$parent_id)->where("cat_type = ?",'women')->order("order ASC");
        $this->view->categories = $table->fetchAll($select);
        
        $categoryTable = Engine_Api::_()->getDbtable('categories', 'activity');
        $this->view->category = $categoryTable->find($parent_id)->current();
    }
	
	public function kidsAction(){
        $this->view->id = $parent_id = $this->getParam("id",0);
        $this->view->table = $table = Engine_Api::_()->getDbtable('categories', 'activity');
        $select = $table->select()->where("parent_id = ?",$parent_id)->where("cat_type = ?",'kids')->order("order ASC");
        $this->view->categories = $table->fetchAll($select);
        
        $categoryTable = Engine_Api::_()->getDbtable('categories', 'activity');
        $this->view->category = $categoryTable->find($parent_id)->current();
    }
	
	public function schoolAction(){
        $this->view->id = $parent_id = $this->getParam("id",0);
        $this->view->table = $table = Engine_Api::_()->getDbtable('categories', 'activity');
        $select = $table->select()->where("parent_id = ?",$parent_id)->where("cat_type = ?",'school')->order("order ASC");
        $this->view->categories = $table->fetchAll($select);
        
        $categoryTable = Engine_Api::_()->getDbtable('categories', 'activity');
        $this->view->category = $categoryTable->find($parent_id)->current();
    }
	
	
	public function gamingAction(){
        $this->view->id = $parent_id = $this->getParam("id",0);
        $this->view->table = $table = Engine_Api::_()->getDbtable('categories', 'activity');
        $select = $table->select()->where("parent_id = ?",$parent_id)->where("cat_type = ?",'game')->order("order ASC");
        $this->view->categories = $table->fetchAll($select);
        
        $categoryTable = Engine_Api::_()->getDbtable('categories', 'activity');
        $this->view->category = $categoryTable->find($parent_id)->current();
    }
	
	public function moviesAction(){
        $this->view->id = $parent_id = $this->getParam("id",0);
        $this->view->table = $table = Engine_Api::_()->getDbtable('categories', 'activity');
        $select = $table->select()->where("parent_id = ?",$parent_id)->where("cat_type = ?",'movies')->order("order ASC");
        $this->view->categories = $table->fetchAll($select);
        
        $categoryTable = Engine_Api::_()->getDbtable('categories', 'activity');
        $this->view->category = $categoryTable->find($parent_id)->current();
    }
	
	public function fashionAction(){
        $this->view->id = $parent_id = $this->getParam("id",0);
        $this->view->table = $table = Engine_Api::_()->getDbtable('categories', 'activity');
        $select = $table->select()->where("parent_id = ?",$parent_id)->where("cat_type = ?",'fashion')->order("order ASC");
        $this->view->categories = $table->fetchAll($select);
        
        $categoryTable = Engine_Api::_()->getDbtable('categories', 'activity');
        $this->view->category = $categoryTable->find($parent_id)->current();
    }
	
	public function spiritualAction(){
        $this->view->id = $parent_id = $this->getParam("id",0);
        $this->view->table = $table = Engine_Api::_()->getDbtable('categories', 'activity');
        $select = $table->select()->where("parent_id = ?",$parent_id)->where("cat_type = ?",'spiritual')->order("order ASC");
        $this->view->categories = $table->fetchAll($select);
        
        $categoryTable = Engine_Api::_()->getDbtable('categories', 'activity');
        $this->view->category = $categoryTable->find($parent_id)->current();
    }
	
	public function sciandtechAction(){
        $this->view->id = $parent_id = $this->getParam("id",0);
        $this->view->table = $table = Engine_Api::_()->getDbtable('categories', 'activity');
        $select = $table->select()->where("parent_id = ?",$parent_id)->where("cat_type = ?",'sci_tech')->order("order ASC");
        $this->view->categories = $table->fetchAll($select);
        
        $categoryTable = Engine_Api::_()->getDbtable('categories', 'activity');
        $this->view->category = $categoryTable->find($parent_id)->current();
    }
	
	public function adultsAction(){
        $this->view->id = $parent_id = $this->getParam("id",0);
        $this->view->table = $table = Engine_Api::_()->getDbtable('categories', 'activity');
        $select = $table->select()->where("parent_id = ?",$parent_id)->where("cat_type = ?",'adults')->order("order ASC");
        $this->view->categories = $table->fetchAll($select);
        
        $categoryTable = Engine_Api::_()->getDbtable('categories', 'activity');
        $this->view->category = $categoryTable->find($parent_id)->current();
    }
	
	public function loveAction(){
        $this->view->id = $parent_id = $this->getParam("id",0);
        $this->view->table = $table = Engine_Api::_()->getDbtable('categories', 'activity');
        $select = $table->select()->where("parent_id = ?",$parent_id)->where("cat_type = ?",'love')->order("order ASC");
        $this->view->categories = $table->fetchAll($select);
        
        $categoryTable = Engine_Api::_()->getDbtable('categories', 'activity');
        $this->view->category = $categoryTable->find($parent_id)->current();
    }
	
	public function foodAction(){
        $this->view->id = $parent_id = $this->getParam("id",0);
        $this->view->table = $table = Engine_Api::_()->getDbtable('categories', 'activity');
        $select = $table->select()->where("parent_id = ?",$parent_id)->where("cat_type = ?",'food')->order("order ASC");
        $this->view->categories = $table->fetchAll($select);
        
        $categoryTable = Engine_Api::_()->getDbtable('categories', 'activity');
        $this->view->category = $categoryTable->find($parent_id)->current();
    }
	
	public function peopleAction(){
        $this->view->id = $parent_id = $this->getParam("id",0);
        $this->view->table = $table = Engine_Api::_()->getDbtable('categories', 'activity');
        $select = $table->select()->where("parent_id = ?",$parent_id)->where("cat_type = ?",'people')->order("order ASC");
        $this->view->categories = $table->fetchAll($select);
        
        $categoryTable = Engine_Api::_()->getDbtable('categories', 'activity');
        $this->view->category = $categoryTable->find($parent_id)->current();
    }
	
	public function healthAction(){
        $this->view->id = $parent_id = $this->getParam("id",0);
        $this->view->table = $table = Engine_Api::_()->getDbtable('categories', 'activity');
        $select = $table->select()->where("parent_id = ?",$parent_id)->where("cat_type = ?",'health')->order("order ASC");
        $this->view->categories = $table->fetchAll($select);
        
        $categoryTable = Engine_Api::_()->getDbtable('categories', 'activity');
        $this->view->category = $categoryTable->find($parent_id)->current();
    }
	
	public function artsAction(){
        $this->view->id = $parent_id = $this->getParam("id",0);
        $this->view->table = $table = Engine_Api::_()->getDbtable('categories', 'activity');
        $select = $table->select()->where("parent_id = ?",$parent_id)->where("cat_type = ?",'arts')->order("order ASC");
        $this->view->categories = $table->fetchAll($select);
        
        $categoryTable = Engine_Api::_()->getDbtable('categories', 'activity');
        $this->view->category = $categoryTable->find($parent_id)->current();
    }
	
	public function hotAction(){
        $this->view->id = $parent_id = $this->getParam("id",0);
        $this->view->table = $table = Engine_Api::_()->getDbtable('categories', 'activity');
        $select = $table->select()->where("parent_id = ?",$parent_id)->where("cat_type = ?",'hot')->order("order ASC");
        $this->view->categories = $table->fetchAll($select);
        
        $categoryTable = Engine_Api::_()->getDbtable('categories', 'activity');
        $this->view->category = $categoryTable->find($parent_id)->current();
    }
	
    public function addAction()
    {
      // In smoothbox
      $this->_helper->layout->setLayout('admin-simple');

      // Generate and assign form
      $form = $this->view->form = new Activity_Form_Admin_Category_Add();
      $form->setAction($this->getFrontController()->getRouter()->assemble(array()));

      // Check post
      if( $this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()) ) {
        // we will add the category
        $values = $form->getValues();

        $db = Engine_Db_Table::getDefaultAdapter();
        $db->beginTransaction();

        try {
          // add category to the database
          // Transaction
          $table = Engine_Api::_()->getDbtable('categories', 'activity');

          // insert the category into the database
          $row = $table->createRow();
          $row->title = $values["label"];
		  $row->cat_type = $values["cat_type"];
          $row->parent_id = $this->getParam("id",0);
          $row->save();

          $db->commit();
        } catch( Exception $e ) {
          $db->rollBack();
          throw $e;
        }

        return $this->_forward('success', 'utility', 'core', array(
            'smoothboxClose' => 1000,
            'parentRefresh'=> 10,
            'messages' => array('Your changes have been saved successfully.')
        ));
      }
    }

    public function deleteAction()
    {
      // In smoothbox
      $this->_helper->layout->setLayout('admin-simple');
      $id = $this->_getParam('id');
      $this->view->group_id=$id;

      $categoryTable = Engine_Api::_()->getDbtable('categories', 'activity');
      $category = $categoryTable->find($id)->current();

      // Check post
      if( $this->getRequest()->isPost() ) {
        $db = $categoryTable->getAdapter();
        $db->beginTransaction();

        try {
            
            $category->delete();

            $db->commit();
        } catch( Exception $e ) {
          $db->rollBack();
          throw $e;
        }
        return $this->_forward('success', 'utility', 'core', array(
            'smoothboxClose' => 1000,
            'parentRefresh'=> 10,
            'messages' => array('Your changes have been saved successfully.')
        ));
      }
      
    }

    public function editAction()
    {
      // In smoothbox
      $this->_helper->layout->setLayout('admin-simple');
      $form = $this->view->form = new Activity_Form_Admin_Category_Add();
      $form->setAction($this->getFrontController()->getRouter()->assemble(array()));

      // Must have an id
      if( !($id = $this->_getParam('id')) ) {
        die('No identifier specified');
      }

      $categoryTable = Engine_Api::_()->getDbtable('categories', 'activity');
      $category = $categoryTable->find($id)->current();
      $form->setField($category);

      // Check post
      if( $this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()) ) {
        // Ok, we're good to add field
        $values = $form->getValues();

        $db = Engine_Db_Table::getDefaultAdapter();
        $db->beginTransaction();

        try {
          $category->title = $values["label"];
		  $category->cat_type = $values["cat_type"];
          $category->save();

          $db->commit();
        } catch( Exception $e ) {
          $db->rollBack();
          throw $e;
        }

        return $this->_forward('success', 'utility', 'core', array(
            'smoothboxClose' => 1000,
            'parentRefresh'=> 10,
            'messages' => array('Your changes have been saved successfully.')
        ));
      }
      
    }
    public function orderAction(){
        $data = $this->getRequest()->getPost();
        $categoryTable = Engine_Api::_()->getDbtable('categories', 'activity');
        $this->view->status = false;
        if(empty($data['categories']) || !is_array($data['categories'])){
            return;
        }
        foreach($data['categories'] as $id => $order){
            if(empty($id)){
                continue;
            }
            $categoryTable->update(array('order' => (int)$order),array('category_id = ?' => $id));
        }
        $this->view->status = true;
    }
}
