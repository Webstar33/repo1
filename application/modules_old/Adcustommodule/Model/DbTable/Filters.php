<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Group
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Groups.php 10049 2013-06-06 22:24:49Z shaun $
 * @author     John
 */

/**
 * @category   Application_Extensions
 * @package    Group
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Adcustommodule_Model_DbTable_Filters extends Core_Model_Item_DbTable_Abstract
{
    protected $_rowClass = 'Adcustommodule_Model_Filter';

    public function getByUserId($user_id = ''){
		$select = $this->select()
        ->where('user_id = ?', $user_id);
        
		return $this->fetchRow($select);
	}
}
