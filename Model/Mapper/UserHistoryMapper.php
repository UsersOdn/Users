<?php
namespace Model\Mapper; 
class UserHistoryMapper extends AbstractMapper
{ 

	protected $_entityTable = 'UserHistory'; 
	protected $_entityClass = 'ModelUserHistory'; 
	protected $_entityData = null; 
	protected function _createEntity(array $data) 
	{ 
		 $this->_entityData = array( 
			'id' => ( isset ($data['id'] ) ? $data['id'] : null)   , 
 			'UserId' => ( isset ($data['UserId'] ) ? $data['UserId'] : null)   , 
 			'LastLogin' => ( isset ($data['LastLogin'] ) ? $data['LastLogin'] : null)   , 
 			'IsActive' => ( isset ($data['IsActive'] ) ? $data['IsActive'] : null)   , 
 			'LostPasswordControl' => ( isset ($data['LostPasswordControl'] ) ? $data['LostPasswordControl'] : null)   , 
 			'IsDelete' => ( isset ($data['IsDelete'] ) ? $data['IsDelete'] : null)   , 
 			'StartSession' => ( isset ($data['StartSession'] ) ? $data['StartSession'] : null)   , 
 			'IsLogin' => ( isset ($data['IsLogin'] ) ? $data['IsLogin'] : null)   , 
 			'CreateBy' => ( isset ($data['CreateBy'] ) ? $data['CreateBy'] : null)   , 
 			'EditPasswordDate' => ( isset ($data['EditPasswordDate'] ) ? $data['EditPasswordDate'] : null)   , 
 			'CreationDate' => ( isset ($data['CreationDate'] ) ? $data['CreationDate'] : null)  
		 );
		 return $this->_entityData;
	}
}

?>