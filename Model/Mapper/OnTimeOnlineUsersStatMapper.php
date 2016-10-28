<?php
namespace Model\Mapper; 
class OnTimeOnlineUsersStatMapper extends AbstractMapper
{ 

	protected $_entityTable = 'OnTimeOnlineUsersStat'; 
	protected $_entityClass = 'ModelOnTimeOnlineUsersStat'; 
	protected $_entityData = null; 
	protected function _createEntity(array $data) 
	{ 
		 $this->_entityData = array( 
			'pdate( now())' => ( isset ($data['pdate( now())'] ) ? $data['pdate( now())'] : null)   , 
 			'count(UserId)' => ( isset ($data['count(UserId)'] ) ? $data['count(UserId)'] : null)  
		 );
		 return $this->_entityData;
	}
}

?>