<?php
namespace Model\Mapper; 
class UserSignupMonthJalaliStatMapper extends AbstractMapper
{ 

	protected $_entityTable = 'UserSignupMonthJalaliStat'; 
	protected $_entityClass = 'ModelUserSignupMonthJalaliStat'; 
	protected $_entityData = null; 
	protected function _createEntity(array $data) 
	{ 
		 $this->_entityData = array( 
			'years' => ( isset ($data['years'] ) ? $data['years'] : null)   , 
 			'monthName' => ( isset ($data['monthName'] ) ? $data['monthName'] : null)   , 
 			'month' => ( isset ($data['month'] ) ? $data['month'] : null)   , 
 			'count' => ( isset ($data['count'] ) ? $data['count'] : null)  
		 );
		 return $this->_entityData;
	}
}

?>