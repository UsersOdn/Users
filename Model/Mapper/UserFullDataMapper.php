<?php
namespace Model\Mapper; 
class UserFullDataMapper extends AbstractMapper
{ 

	protected $_entityTable = 'UserFullData'; 
	protected $_entityClass = 'ModelUserFullData'; 
	protected $_entityData = null; 
	protected function _createEntity(array $data) 
	{ 
		 $this->_entityData = array( 
			'UserId' => ( isset ($data['UserId'] ) ? $data['UserId'] : null)   , 
 			'UserName' => ( isset ($data['UserName'] ) ? $data['UserName'] : null)   , 
 			'FirstName' => ( isset ($data['FirstName'] ) ? $data['FirstName'] : null)   , 
 			'LastName' => ( isset ($data['LastName'] ) ? $data['LastName'] : null)   , 
 			'Email' => ( isset ($data['Email'] ) ? $data['Email'] : null)   , 
 			'Telephone' => ( isset ($data['Telephone'] ) ? $data['Telephone'] : null)   , 
 			'CreationDate' => ( isset ($data['CreationDate'] ) ? $data['CreationDate'] : null)   , 
 			'CreationDateJalali' => ( isset ($data['CreationDateJalali'] ) ? $data['CreationDateJalali'] : null)   , 
 			'UserProfileId' => ( isset ($data['UserProfileId'] ) ? $data['UserProfileId'] : null)   , 
 			'Gender' => ( isset ($data['Gender'] ) ? $data['Gender'] : null)   , 
 			'StateId' => ( isset ($data['StateId'] ) ? $data['StateId'] : null)   , 
 			'CityId' => ( isset ($data['CityId'] ) ? $data['CityId'] : null)   , 
 			'RegionId' => ( isset ($data['RegionId'] ) ? $data['RegionId'] : null)   , 
 			'Address' => ( isset ($data['Address'] ) ? $data['Address'] : null)   , 
 			'UserHistoryId' => ( isset ($data['UserHistoryId'] ) ? $data['UserHistoryId'] : null)   , 
 			'LastLogin' => ( isset ($data['LastLogin'] ) ? $data['LastLogin'] : null)   , 
 			'LastLoginJalali' => ( isset ($data['LastLoginJalali'] ) ? $data['LastLoginJalali'] : null)   , 
 			'IsActive' => ( isset ($data['IsActive'] ) ? $data['IsActive'] : null)   , 
 			'LostPasswordControl' => ( isset ($data['LostPasswordControl'] ) ? $data['LostPasswordControl'] : null)   , 
 			'IsDelete' => ( isset ($data['IsDelete'] ) ? $data['IsDelete'] : null)   , 
 			'StartSession' => ( isset ($data['StartSession'] ) ? $data['StartSession'] : null)   , 
 			'StartSessionJalali' => ( isset ($data['StartSessionJalali'] ) ? $data['StartSessionJalali'] : null)   , 
 			'EditPasswordDate' => ( isset ($data['EditPasswordDate'] ) ? $data['EditPasswordDate'] : null)   , 
 			'EditPasswordDateJalali' => ( isset ($data['EditPasswordDateJalali'] ) ? $data['EditPasswordDateJalali'] : null)   , 
 			'IsLogin' => ( isset ($data['IsLogin'] ) ? $data['IsLogin'] : null)   , 
 			'CreateBy' => ( isset ($data['CreateBy'] ) ? $data['CreateBy'] : null)  
		 );
		 return $this->_entityData;
	}
}

?>