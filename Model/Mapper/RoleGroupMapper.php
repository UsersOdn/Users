<?php
namespace Model\Mapper; 
class RoleGroupMapper extends AbstractMapper
{ 

	protected $_entityTable = 'RoleGroup'; 
	protected $_entityClass = 'ModelRoleGroup'; 
	protected $_entityData = null; 
	protected function _createEntity(array $data) 
	{ 
		 $this->_entityData = array( 
			'RoleId' => ( isset ($data['RoleId'] ) ? $data['RoleId'] : null)   , 
 			'GroupId' => ( isset ($data['GroupId'] ) ? $data['GroupId'] : null)   , 
 			'CreationDate' => ( isset ($data['CreationDate'] ) ? $data['CreationDate'] : null)  
		 );
		 return $this->_entityData;
	}
}

?>