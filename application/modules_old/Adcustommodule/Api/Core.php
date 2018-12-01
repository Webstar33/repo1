<?php

class Adcustommodule_Api_Core extends Core_Api_Abstract
{
	private $channelTable = 'engine4_activity_subchannel';
	public function isuseronline($userid)
	{
		  // Get online users
		$onlineTable = Engine_Api::_()->getDbtable('online', 'user');
		
		$select = $onlineTable->select()
		  ->where('user_id > ?', 0)
		  ->where('user_id = ?', $userid)
		  //->where('active > ?', new Zend_Db_Expr('DATE_SUB(NOW(),INTERVAL 20 MINUTE)'))
		  ->group('user_id')
		  ;
		  
		//echo $select;
		 
		$getonlineuser = $onlineTable->fetchRow($select);

		if(count($getonlineuser)) {
		   return true;
		}

		return false; 	
	  
	}
	
	public function checkIfCHannelExist($data){
		$defaultDB = Engine_Db_Table::getDefaultAdapter();
		$select = $defaultDB->select()
					 ->from($this->channelTable)
					 ->where('user_id = ?', $data['user_id'])
					 ->where('category_id = ?', $data['category_id'])
					 ->where('subcategory_id = ?', $data['subcategory_id'])
					 ->where('popular = ?', $data['popular'])
					 ->where('most_popular = ?', $data['most_popular'])
					 ->where('location_params = ?', (!empty($data['location_params']))?$data['location_params']:'')
					 ;

		$results = $defaultDB->fetchAll($select);
        return (count($results) > 0)?$results:false;
		
	}
	
	public function updateExistingChannel($data, $existingData){
		$defaultDB = Engine_Db_Table::getDefaultAdapter();
		$updateData = $data;
		$defaultDB->update($this->channelTable,$updateData, 'id = '.$existingData['id']);
		return true;
	}
	
	public function addNewChannel($data){
		$defaultDB = Engine_Db_Table::getDefaultAdapter();
		try{
			$defaultDB->insert($this->channelTable, $data);
			return true;
		}catch(Exception $e){
			return false;
		}
		return false;
	}
	
	public function fetchChannelNotifications(){
		$defaultDB = Engine_Db_Table::getDefaultAdapter();
		$select = $defaultDB->select()
			->from($this->channelTable)
			->order('id ASC')
			;

		$results = $defaultDB->fetchAll($select);
		return $results;
	}
	
	public function getChannelById($channel_id){
		$defaultDB = Engine_Db_Table::getDefaultAdapter();
		$select = $defaultDB->select()
			->from($this->channelTable)
			->where('id = ?',$channel_id)
			;

		$results = $defaultDB->fetchRow($select);
		return $results;
	} 
	 
	
	
}
