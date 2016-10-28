<?php
namespace Model\Mapper; 
class MenuMapper extends AbstractMapper
{ 

	protected $_entityTable = 'Menu'; 
	protected $_entityClass = 'ModelMenu'; 
	protected $_entityData = null; 
	protected function _createEntity(array $data) 
	{ 
		 $this->_entityData = array( 
			'id' => ( isset ($data['id'] ) ? $data['id'] : null)   , 
 			'Name' => ( isset ($data['Name'] ) ? $data['Name'] : null)   , 
 			'Url' => ( isset ($data['Url'] ) ? $data['Url'] : null)   , 
 			'Level' => ( isset ($data['Level'] ) ? $data['Level'] : null)   , 
 			'Parent' => ( isset ($data['Parent'] ) ? $data['Parent'] : null)   , 
 			'ActionId' => ( isset ($data['ActionId'] ) ? $data['ActionId'] : null)   , 
 			'CreationDate' => ( isset ($data['CreationDate'] ) ? $data['CreationDate'] : null)  
		 );
		 return $this->_entityData;
	}
}

?>