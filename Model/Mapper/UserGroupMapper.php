<?php
namespace Model\Mapper; 
class UserGroupMapper extends AbstractMapper
{ 

	protected $_entityTable = 'UserGroup'; 
	protected $_entityClass = 'ModelUserGroup'; 
	protected $_entityData = null; 
	protected function _createEntity(array $data) 
	{ 
		 $this->_entityData = array( 
			'UserId' => ( isset ($data['UserId'] ) ? $data['UserId'] : null)   , 
 			'GroupId' => ( isset ($data['GroupId'] ) ? $data['GroupId'] : null)   , 
 			'CreationDate' => ( isset ($data['CreationDate'] ) ? $data['CreationDate'] : null)  
		 );
		 return $this->_entityData;
	}
}

?>