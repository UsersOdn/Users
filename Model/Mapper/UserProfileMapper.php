<?php
namespace Model\Mapper; 
class UserProfileMapper extends AbstractMapper
{ 

	protected $_entityTable = 'UserProfile'; 
	protected $_entityClass = 'ModelUserProfile'; 
	protected $_entityData = null; 
	protected function _createEntity(array $data) 
	{ 
		 $this->_entityData = array( 
			'id' => ( isset ($data['id'] ) ? $data['id'] : null)   , 
 			'UserId' => ( isset ($data['UserId'] ) ? $data['UserId'] : null)   , 
 			'Gender' => ( isset ($data['Gender'] ) ? $data['Gender'] : null)   , 
 			'StateId' => ( isset ($data['StateId'] ) ? $data['StateId'] : null)   , 
 			'CityId' => ( isset ($data['CityId'] ) ? $data['CityId'] : null)   , 
 			'RegionId' => ( isset ($data['RegionId'] ) ? $data['RegionId'] : null)   , 
 			'Address' => ( isset ($data['Address'] ) ? $data['Address'] : null)   , 
 			'Description' => ( isset ($data['Description'] ) ? $data['Description'] : null)   , 
 			'CreationDate' => ( isset ($data['CreationDate'] ) ? $data['CreationDate'] : null)  
		 );
		 return $this->_entityData;
	}
}

?>