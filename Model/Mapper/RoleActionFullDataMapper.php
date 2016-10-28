<?php
namespace Model\Mapper; 
class RoleActionFullDataMapper extends AbstractMapper
{ 

	protected $_entityTable = 'RoleActionFullData'; 
	protected $_entityClass = 'ModelRoleActionFullData'; 
	protected $_entityData = null; 
	protected function _createEntity(array $data) 
	{ 
		 $this->_entityData = array( 
			'RoleId' => ( isset ($data['RoleId'] ) ? $data['RoleId'] : null)   , 
 			'ActionId' => ( isset ($data['ActionId'] ) ? $data['ActionId'] : null)   , 
 			'ActionName' => ( isset ($data['ActionName'] ) ? $data['ActionName'] : null)   , 
 			'Description' => ( isset ($data['Description'] ) ? $data['Description'] : null)  
		 );
		 return $this->_entityData;
	}
}

?>