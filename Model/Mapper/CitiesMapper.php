<?php
namespace Model\Mapper; 
class CitiesMapper extends AbstractMapper
{ 

	protected $_entityTable = 'Cities'; 
	protected $_entityClass = 'ModelCities'; 
	protected $_entityData = null; 
	protected function _createEntity(array $data) 
	{ 
		 $this->_entityData = array( 
			'id' => ( isset ($data['id'] ) ? $data['id'] : null)   , 
 			'CityName' => ( isset ($data['CityName'] ) ? $data['CityName'] : null)   , 
 			'StateId' => ( isset ($data['StateId'] ) ? $data['StateId'] : null)   , 
 			'CreationDate' => ( isset ($data['CreationDate'] ) ? $data['CreationDate'] : null)  
		 );
		 return $this->_entityData;
	}
}

?>