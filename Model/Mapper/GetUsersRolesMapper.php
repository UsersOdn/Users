<?php
namespace Model\Mapper; 
class GetUsersRolesMapper extends AbstractMapper
{ 

	protected $_entityTable = 'GetUsersRoles'; 
	protected $_entityClass = 'ModelGetUsersRoles'; 
	protected $_entityData = null; 
	protected function _createEntity(array $data) 
	{ 
		 $this->_entityData = array( 
			'UserId' => ( isset ($data['UserId'] ) ? $data['UserId'] : null)   , 
 			'UserName' => ( isset ($data['UserName'] ) ? $data['UserName'] : null)   , 
 			'FirstName' => ( isset ($data['FirstName'] ) ? $data['FirstName'] : null)   , 
 			'LastName' => ( isset ($data['LastName'] ) ? $data['LastName'] : null)   , 
 			'RoleId' => ( isset ($data['RoleId'] ) ? $data['RoleId'] : null)   , 
 			'RoleName' => ( isset ($data['RoleName'] ) ? $data['RoleName'] : null)   , 
 			'Description' => ( isset ($data['Description'] ) ? $data['Description'] : null)   , 
 			'CreationDate' => ( isset ($data['CreationDate'] ) ? $data['CreationDate'] : null)   , 
 			'CreationDateJalali' => ( isset ($data['CreationDateJalali'] ) ? $data['CreationDateJalali'] : null)  
		 );
		 return $this->_entityData;
	}
}

?>