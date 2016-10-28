<?php
namespace Model\Mapper; 
class RoleActionMapper extends AbstractMapper
{ 

	protected $_entityTable = 'RoleAction'; 
	protected $_entityClass = 'ModelRoleAction'; 
	protected $_entityData = null; 
	protected function _createEntity(array $data) 
	{ 
		 $this->_entityData = array( 
			'RoleId' => ( isset ($data['RoleId'] ) ? $data['RoleId'] : null)   , 
 			'ActionId' => ( isset ($data['ActionId'] ) ? $data['ActionId'] : null)   , 
 			'CreationDate' => ( isset ($data['CreationDate'] ) ? $data['CreationDate'] : null)  
		 );
		 return $this->_entityData;
	}
}

?>