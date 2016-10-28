<?php
namespace Model\Mapper; 
class UserLogsMapper extends AbstractMapper
{ 

	protected $_entityTable = 'UserLogs'; 
	protected $_entityClass = 'ModelUserLogs'; 
	protected $_entityData = null; 
	protected function _createEntity(array $data) 
	{ 
		 $this->_entityData = array( 
			'id' => ( isset ($data['id'] ) ? $data['id'] : null)   , 
 			'UserId' => ( isset ($data['UserId'] ) ? $data['UserId'] : null)   , 
 			'ActionId' => ( isset ($data['ActionId'] ) ? $data['ActionId'] : null)   , 
 			'Description' => ( isset ($data['Description'] ) ? $data['Description'] : null)   , 
 			'CreationDate' => ( isset ($data['CreationDate'] ) ? $data['CreationDate'] : null)  
		 );
		 return $this->_entityData;
	}
}

?>