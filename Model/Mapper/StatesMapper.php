<?php
namespace Model\Mapper; 
class StatesMapper extends AbstractMapper
{ 

	protected $_entityTable = 'States'; 
	protected $_entityClass = 'ModelStates'; 
	protected $_entityData = null; 
	protected function _createEntity(array $data) 
	{ 
		 $this->_entityData = array( 
			'id' => ( isset ($data['id'] ) ? $data['id'] : null)   , 
 			'StateName' => ( isset ($data['StateName'] ) ? $data['StateName'] : null)   , 
 			'CreationDate' => ( isset ($data['CreationDate'] ) ? $data['CreationDate'] : null)  
		 );
		 return $this->_entityData;
	}
}

?>