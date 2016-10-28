<?php
namespace Model\Mapper; 
class UsersMapper extends AbstractMapper
{ 

	protected $_entityTable = 'Users'; 
	protected $_entityClass = 'ModelUsers'; 
	protected $_entityData = null; 
	protected function _createEntity(array $data) 
	{ 
		 $this->_entityData = array( 
			'id' => ( isset ($data['id'] ) ? $data['id'] : null)   , 
 			'UserName' => ( isset ($data['UserName'] ) ? $data['UserName'] : null)   , 
 			'Password' => ( isset ($data['Password'] ) ? $data['Password'] : null)   , 
 			'FirstName' => ( isset ($data['FirstName'] ) ? $data['FirstName'] : null)   , 
 			'LastName' => ( isset ($data['LastName'] ) ? $data['LastName'] : null)   , 
 			'Email' => ( isset ($data['Email'] ) ? $data['Email'] : null)   , 
 			'Telephone' => ( isset ($data['Telephone'] ) ? $data['Telephone'] : null)   , 
 			'CreationDate' => ( isset ($data['CreationDate'] ) ? $data['CreationDate'] : null)  
		 );
		 return $this->_entityData;
	}
}

?>