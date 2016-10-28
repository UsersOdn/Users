<?php
namespace Model\Mapper; 
class RoleGroupFullDataMapper extends AbstractMapper
{ 

	protected $_entityTable = 'RoleGroupFullData'; 
	protected $_entityClass = 'ModelRoleGroupFullData'; 
	protected $_entityData = null; 
	protected function _createEntity(array $data) 
	{ 
		 $this->_entityData = array( 
			'GroupId' => ( isset ($data['GroupId'] ) ? $data['GroupId'] : null)   , 
 			'RoleId' => ( isset ($data['RoleId'] ) ? $data['RoleId'] : null)   , 
 			'RoleName' => ( isset ($data['RoleName'] ) ? $data['RoleName'] : null)   , 
 			'Description' => ( isset ($data['Description'] ) ? $data['Description'] : null)  
		 );
		 return $this->_entityData;
	}
}

?>