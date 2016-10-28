<?php
namespace Model\Mapper; 
class AllUsersLoginHistoryStatMapper extends AbstractMapper
{ 

	protected $_entityTable = 'AllUsersLoginHistoryStat'; 
	protected $_entityClass = 'ModelAllUsersLoginHistoryStat'; 
	protected $_entityData = null; 
	protected function _createEntity(array $data) 
	{ 
		 $this->_entityData = array( 
			'Year' => ( isset ($data['Year'] ) ? $data['Year'] : null)   , 
 			'MonthName' => ( isset ($data['MonthName'] ) ? $data['MonthName'] : null)   , 
 			'Month' => ( isset ($data['Month'] ) ? $data['Month'] : null)   , 
 			'count' => ( isset ($data['count'] ) ? $data['count'] : null)  
		 );
		 return $this->_entityData;
	}
}

?>