<?php
namespace Model\Mapper; 
class TotalUsersStatMapper extends AbstractMapper
{ 

	protected $_entityTable = 'TotalUsersStat'; 
	protected $_entityClass = 'ModelTotalUsersStat'; 
	protected $_entityData = null; 
	protected function _createEntity(array $data) 
	{ 
		 $this->_entityData = array( 
			'Year' => ( isset ($data['Year'] ) ? $data['Year'] : null)   , 
 			'MonthName' => ( isset ($data['MonthName'] ) ? $data['MonthName'] : null)   , 
 			'Month' => ( isset ($data['Month'] ) ? $data['Month'] : null)   , 
 			'ThisMountCount' => ( isset ($data['ThisMountCount'] ) ? $data['ThisMountCount'] : null)   , 
 			'Previous' => ( isset ($data['Previous'] ) ? $data['Previous'] : null)   , 
 			'Total' => ( isset ($data['Total'] ) ? $data['Total'] : null)  
		 );
		 return $this->_entityData;
	}
}

?>