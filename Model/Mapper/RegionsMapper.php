<?php
namespace Model\Mapper; 
class RegionsMapper extends AbstractMapper
{ 

	protected $_entityTable = 'Regions'; 
	protected $_entityClass = 'ModelRegions'; 
	protected $_entityData = null; 
	protected function _createEntity(array $data) 
	{ 
		 $this->_entityData = array( 
			'id' => ( isset ($data['id'] ) ? $data['id'] : null)   , 
 			'RegionName' => ( isset ($data['RegionName'] ) ? $data['RegionName'] : null)   , 
 			'CityId' => ( isset ($data['CityId'] ) ? $data['CityId'] : null)   , 
 			'CreationDate' => ( isset ($data['CreationDate'] ) ? $data['CreationDate'] : null)  
		 );
		 return $this->_entityData;
	}
}

?>