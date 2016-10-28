<?php
namespace Model\Mapper; 
class UserSignupMonthStatMapper extends AbstractMapper
{ 

	protected $_entityTable = 'UserSignupMonthStat'; 
	protected $_entityClass = 'ModelUserSignupMonthStat'; 
	protected $_entityData = null; 
	protected function _createEntity(array $data) 
	{ 
		 $this->_entityData = array( 
			'years' => ( isset ($data['years'] ) ? $data['years'] : null)   , 
 			'months' => ( isset ($data['months'] ) ? $data['months'] : null)   , 
 			'monthName' => ( isset ($data['monthName'] ) ? $data['monthName'] : null)   , 
 			'count' => ( isset ($data['count'] ) ? $data['count'] : null)  
		 );
		 return $this->_entityData;
	}
}

?>