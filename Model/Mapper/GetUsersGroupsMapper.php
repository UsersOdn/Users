<?php
namespace Model\Mapper; 
class GetUsersGroupsMapper extends AbstractMapper
{ 

	protected $_entityTable = 'GetUsersGroups'; 
	protected $_entityClass = 'ModelGetUsersGroups'; 
	protected $_entityData = null; 
	protected function _createEntity(array $data) 
	{ 
		 $this->_entityData = array( 
			'UserId' => ( isset ($data['UserId'] ) ? $data['UserId'] : null)   , 
 			'UserName' => ( isset ($data['UserName'] ) ? $data['UserName'] : null)   , 
 			'FirstName' => ( isset ($data['FirstName'] ) ? $data['FirstName'] : null)   , 
 			'LastName' => ( isset ($data['LastName'] ) ? $data['LastName'] : null)   , 
 			'GroupId' => ( isset ($data['GroupId'] ) ? $data['GroupId'] : null)   , 
 			'GroupName' => ( isset ($data['GroupName'] ) ? $data['GroupName'] : null)   , 
 			'Description' => ( isset ($data['Description'] ) ? $data['Description'] : null)   , 
 			'CreationDate' => ( isset ($data['CreationDate'] ) ? $data['CreationDate'] : null)   , 
 			'CreationDateJalali' => ( isset ($data['CreationDateJalali'] ) ? $data['CreationDateJalali'] : null)  
		 );
		 return $this->_entityData;
	}
}

?>