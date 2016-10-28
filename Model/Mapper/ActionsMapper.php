<?php
namespace Model\Mapper; 
class ActionsMapper extends AbstractMapper
{ 

	protected $_entityTable = 'Actions'; 
	protected $_entityClass = 'ModelActions'; 
	protected $_entityData = null; 
	protected function _createEntity(array $data) 
	{ 
		 $this->_entityData = array( 
			'id' => ( isset ($data['id'] ) ? $data['id'] : null)   , 
 			'ActionName' => ( isset ($data['ActionName'] ) ? $data['ActionName'] : null)   , 
 			'Description' => ( isset ($data['Description'] ) ? $data['Description'] : null)  
		 );
		 return $this->_entityData;
	}
}

?>