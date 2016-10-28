<?php
namespace Model\Mapper; 
class UserSignupWeekStatMapper extends AbstractMapper
{ 

	protected $_entityTable = 'UserSignupWeekStat'; 
	protected $_entityClass = 'ModelUserSignupWeekStat'; 
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