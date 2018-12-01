<?php

class Adcustommodule_Api_Core extends Core_Api_Abstract
{
  
  public function isuseronline($userid)
  {
      // Get online users
    $onlineTable = Engine_Api::_()->getDbtable('online', 'user');
    
    $select = $onlineTable->select()
      ->where('user_id > ?', 0)
	  ->where('user_id = ?', $userid)
      ->where('active > ?', new Zend_Db_Expr('DATE_SUB(NOW(),INTERVAL 20 MINUTE)'))
      ->group('user_id')
      ;
	  
	//echo $select;
	 
	$getonlineuser = $onlineTable->fetchRow($select);

    if(count($getonlineuser)) {
       return true;
    }

    return false; 	
  
  }
}
