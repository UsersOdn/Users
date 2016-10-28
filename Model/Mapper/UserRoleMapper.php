<?php
namespace Model\Mapper; 
class UserRoleMapper extends AbstractMapper
{ 

	protected $_entityTable = 'UserRole'; 
	protected $_entityClass = 'ModelUserRole'; 
	protected $_entityData = null; 
	protected function _createEntity(array $data) 
	{ 
		 $this->_entityData = array( 
			'UserId' => ( isset ($data['UserId'] ) ? $data['UserId'] : null)   , 
 			'RoleId' => ( isset ($data['RoleId'] ) ? $data['RoleId'] : null)   , 
 			'CreationDate' => ( isset ($data['CreationDate'] ) ? $data['CreationDate'] : null)  
		 );
		 return $this->_entityData;
	}
}

?>