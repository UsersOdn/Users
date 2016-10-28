<?php
namespace Model\Mapper; 
class GroupsMapper extends AbstractMapper
{ 

	protected $_entityTable = 'Groups'; 
	protected $_entityClass = 'ModelGroups'; 
	protected $_entityData = null; 
	protected function _createEntity(array $data) 
	{ 
		 $this->_entityData = array( 
			'id' => ( isset ($data['id'] ) ? $data['id'] : null)   , 
 			'GroupName' => ( isset ($data['GroupName'] ) ? $data['GroupName'] : null)   , 
 			'Description' => ( isset ($data['Description'] ) ? $data['Description'] : null)   , 
 			'CreationDate' => ( isset ($data['CreationDate'] ) ? $data['CreationDate'] : null)  
		 );
		 return $this->_entityData;
	}
}

?>