<?php
namespace Model\Mapper; 
class UserSignupWeekJalaliStatMapper extends AbstractMapper
{ 

	protected $_entityTable = 'UserSignupWeekJalaliStat'; 
	protected $_entityClass = 'ModelUserSignupWeekJalaliStat'; 
	protected $_entityData = null; 
	protected function _createEntity(array $data) 
	{ 
		 $this->_entityData = array( 
			'years' => ( isset ($data['years'] ) ? $data['years'] : null)   , 
 			'months' => ( isset ($data['months'] ) ? $data['months'] : null)   , 
 			'monthName' => ( isset ($data['monthName'] ) ? $data['monthName'] : null)   , 
 			'weeks' => ( isset ($data['weeks'] ) ? $data['weeks'] : null)   , 
 			'count' => ( isset ($data['count'] ) ? $data['count'] : null)  
		 );
		 return $this->_entityData;
	}
}

?>