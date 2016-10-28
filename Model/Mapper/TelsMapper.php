<?php
namespace Model\Mapper; 
class TelsMapper extends AbstractMapper
{ 

	protected $_entityTable = 'Tels'; 
	protected $_entityClass = 'ModelTels'; 
	protected $_entityData = null; 
	protected function _createEntity(array $data) 
	{ 
		 $this->_entityData = array( 
			'id' => ( isset ($data['id'] ) ? $data['id'] : null)   , 
 			'UserId' => ( isset ($data['UserId'] ) ? $data['UserId'] : null)   , 
 			'Telephone' => ( isset ($data['Telephone'] ) ? $data['Telephone'] : null)   , 
 			'CreationDate' => ( isset ($data['CreationDate'] ) ? $data['CreationDate'] : null)  
		 );
		 return $this->_entityData;
	}
}

?>