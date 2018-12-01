<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Activity
 * @author     Stars Developer
 */
class Activity_Form_Admin_Category_Add extends Engine_Form
{
  protected $_field;

  public function init()
  {
    $this->setMethod('post');

    /*
    $type = new Zend_Form_Element_Hidden('type');
    $type->setValue('heading');
    */

    $label = new Zend_Form_Element_Text('label');
    $label->setLabel('Category Name')
      ->addValidator('NotEmpty')
      ->setRequired(true)
      ->setAttrib('class', 'text');

	$cattype = new Zend_Form_Element_Select('cat_type');
    $cattype->setLabel('Category Type')
      ->setMultioptions(array(
				'home' => "Home Page",
				'music' => 'Music Page',
				'photo' => 'Photo Page',
				'sport' => 'Sports Page',
				'women' => 'Women Page',
				'kids'  => 'Kids Page',
				'school' => 'School Page',
				'game'	=>'Game Page',
				'movies' => 'Movies Page',
				'fashion' => 'Fashion Page',
				'spiritual' => 'Spiritual Page',
				'sci_tech' => 'Sci & Tech Page',
				'adults'  => '18 & Over Page',
				'love' => 'Love Page',
				'food' => 'Food Page',
				'people' => 'People Page',
				'health' => 'Health Page',
				'arts' => 'Arts Page',
				'hot' => 'Hot Page',
				))
      ->setAttrib('class', 'select');  
    
    $id = new Zend_Form_Element_Hidden('id');


    $this->addElements(array(
      //$type,
      $label,
	  $cattype,
      $id
    ));
    // Buttons
    $this->addElement('Button', 'submit', array(
      'label' => 'Save Category',
      'type' => 'submit',
      'ignore' => true,
      'decorators' => array('ViewHelper')
    ));

    $this->addElement('Cancel', 'cancel', array(
      'label' => 'cancel',
      'link' => true,
      'prependText' => ' or ',
      'href' => '',
      'onClick'=> 'javascript:parent.Smoothbox.close();',
      'decorators' => array(
        'ViewHelper'
      )
    ));
    $this->addDisplayGroup(array('submit', 'cancel'), 'buttons');
    $button_group = $this->getDisplayGroup('buttons');

   // $this->addDisplayGroup(array('submit', 'cancel'), 'buttons');
  }

  public function setField($category)
  {
    $this->_field = $category;

    // Set up elements
    //$this->removeElement('type');
    $this->label->setValue($category->title);
	$this->cat_type->setValue($category->cat_type);
    $this->id->setValue($category->category_id);
    $this->submit->setLabel('Edit Category');

    // @todo add the rest of the parameters
  }
}