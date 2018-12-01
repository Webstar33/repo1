<?php

/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Activity
 * @author     Stars Developer
 */
class Activity_Model_DbTable_Categories extends Engine_Db_Table {

    public function getSubCategoriesCount($id) {
        $select = $this->select()->where('parent_id = ?', (int) $id)->order("order ASC");
        $categories = $this->fetchAll($select);
        return count($categories);
    }

    public function getCategoryOptions($parent_id = 0) {
        $select = $this->select()->where('parent_id = ?', $parent_id)->order("order ASC");
        $categories = $this->fetchAll($select);
        $categoryOptions = array('' => 'Select Category');
        if (count($categories) <= 0) {
            return $categoryOptions;
        }
        foreach ($categories as $category) {
            $categoryOptions[$category->category_id] = $category->title;
        }
        return $categoryOptions;
    }

    public function getAllCategories($parent_id = 0, $type = 'home') {

        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        $select = $this->select()->where('parent_id = ?', $parent_id)->where('cat_type = ?', $type)->order("order ASC"); // Modified by AD 
        $categories = $this->fetchAll($select);

        if (count($categories) <= 0 || empty($categories)) {

            return null;
        }
        /*         * ** 
          Custom Code for allowing user defined categories order
          Modified on 12-feb-2018 by PJ
         * * */
        $user_id = Engine_Api::_()->user()->getViewer()->getIdentity();
        $userCategoryTable = Engine_Api::_()->getDbtable('category', 'user');
        $select = $userCategoryTable->select()->where("user_id = ?", $user_id);
        $userCategoriesOrder = $userCategoryTable->fetchAll($select)->current();
        if (!empty($userCategoriesOrder)) {

            $userCategoryOrdersArray = unserialize($userCategoriesOrder->category_data);
            $newData = array();
            foreach ($categories as $category) {
                $tempData = array(
                    'category_id' => $category->category_id,
                    'title' => $category->title,
                    'parent_id' => $category->parent_id,
                    'order' => $category->order,
                );
                if (array_key_exists($category->category_id, $userCategoryOrdersArray)) {
                    $tempData['order'] = $userCategoryOrdersArray[$category->category_id];
                }
                $newdata[] = $tempData;
            }
            usort($newdata, function($a, $b) {

                return $a['order'] - $b['order'];
            });
            $categories = $newdata;
        }
        /*         * ** 
          Custom Code Ends here
         * * */

        $allCategories = array();
        foreach ($categories as $category) {
            $category_id = (is_array($category)) ? $category['category_id'] : $category->category_id;
            $subCategories = $this->getAllCategories($category_id);


            $allCategories[] = array(
                'id' => (is_array($category)) ? $category['category_id'] : $category->category_id,
                'title' => (is_array($category)) ? $category['title'] : $category->title,
                'sub_categories' => $subCategories
            );
        }

        return $allCategories;
    }

    public function getAllCategoriesandsubcategory() {

        $parent_id = 0;
        $type = 'home';
        

        $select = $this->select()->where('parent_id = ?', $parent_id)->where('cat_type = ?', $type)->order("order ASC"); // Modified by AD 
        $categories = $this->fetchAll($select);

        if (count($categories) <= 0 || empty($categories)) {

            return null;
        }
        /*         * ** 
          Custom Code for allowing user defined categories order
          Modified on 12-feb-2018 by PJ
         * * */
        $user_id = Engine_Api::_()->user()->getViewer()->getIdentity();
        $userCategoryTable = Engine_Api::_()->getDbtable('category', 'user');
        $select = $userCategoryTable->select()->where("user_id = ?", $user_id);
        $userCategoriesOrder = $userCategoryTable->fetchAll($select)->current();
        if (!empty($userCategoriesOrder)) {

            $userCategoryOrdersArray = unserialize($userCategoriesOrder->category_data);
            $newData = array();
            foreach ($categories as $category) {
                $tempData = array(
                    'category_id' => $category->category_id,
                    'title' => $category->title,
                    'parent_id' => $category->parent_id,
                    'order' => $category->order,
                );
                if (array_key_exists($category->category_id, $userCategoryOrdersArray)) {
                    $tempData['order'] = $userCategoryOrdersArray[$category->category_id];
                }
                $newdata[] = $tempData;
            }
            usort($newdata, function($a, $b) {

                return $a['order'] - $b['order'];
            });
            $categories = $newdata;
        }
        /**** 
          Custom Code Ends here
         ****/

        $allCategories = array();
        foreach ($categories as $category) {
            $category_id = (is_array($category)) ? $category['category_id'] : $category->category_id;
            $subCategories = $this->getSubCategories($category_id);
            $allCategories[] = array(
                'id' => (is_array($category)) ? $category['category_id'] : $category->category_id,
                'title' => (is_array($category)) ? $category['title'] : $category->title,
                'sub_categories' => $subCategories
            );
        }

        return $allCategories;
    }

    public function getSubCategories($parent_id) {


        $sql = $this->select()->where('category_id  = ?', $parent_id); // Modified by AD 

        $cat = $this->fetchRow($sql);


        $type = strtolower($cat->title);


        $parent_id = '0';

        $select = $this->select()->where('parent_id = ?', $parent_id)->where('cat_type = ?', $type)->order("order ASC"); // Modified by AD 
        
        $categories = $this->fetchAll($select);

        if (count($categories) <= 0 || empty($categories)) {

            return null;
        }
        /*         * ** 
          Custom Code for allowing user defined categories order
          Modified on 12-feb-2018 by PJ
         * * */
        $user_id = Engine_Api::_()->user()->getViewer()->getIdentity();
        $userCategoryTable = Engine_Api::_()->getDbtable('category', 'user');
        $select = $userCategoryTable->select()->where("user_id = ?", $user_id);
        $userCategoriesOrder = $userCategoryTable->fetchAll($select)->current();
        if (!empty($userCategoriesOrder)) {

            $userCategoryOrdersArray = unserialize($userCategoriesOrder->category_data);
            $newData = array();
            foreach ($categories as $category) {
                $tempData = array(
                    'category_id' => $category->category_id,
                    'title' => $category->title,
                    'parent_id' => $category->parent_id,
                    'order' => $category->order,
                );
                if (array_key_exists($category->category_id, $userCategoryOrdersArray)) {
                    $tempData['order'] = $userCategoryOrdersArray[$category->category_id];
                }
                $newdata[] = $tempData;
            }
            usort($newdata, function($a, $b) {

                return $a['order'] - $b['order'];
            });
            $categories = $newdata;
        }
        /*         * ** 
          Custom Code Ends here
         * * */
        return $categories;
       
    }

}
