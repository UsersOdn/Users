<?php
/**
 * Created by PhpStorm.
 * User: hedi
 * Date: 9/24/16
 * Time: 2:51 PM
 */
namespace Model\Access;
use control\generate_message;
use JDFDateConverter\JDFDateConverter;
use Model\Mapper as M;
use Model\DataBase as D;
use DateConverter as jdf;

// limitation for password control
define('password_count_limit' , 5 );

class User
{
    protected $dataBase = null;

    protected  $DateConvertor =  null;
    public function __construct()
    {
        date_default_timezone_set('Asia/Tehran');
        $this->dataBase = new D\DataBase();
        header('Content-type: text/plain; charset=utf-8');
        //create an instance from date converter
        $this->DateConvertor = new jdf\JDFDateConverter();
    }


    /*find a user based on a specific property like id
    *
    */
    public function get_user_by_id( $id )
    {
        //check id validity
        if( !$this->checkId( $id ) )
            return "Err_get_user_by_id_id_is_not_valid";

        //create a new Instance from UserFullDataMapper
        $MapperInstance = new M\UserFullDataMapper( $this->dataBase );

        // create an array for user id
        $condition = array( 'UserId' => $id );

        // prepare input field fro find the user by it's Id
        $condition = $this->prepareCondition( $condition );
        //find and return user
        $output = $MapperInstance->find( $condition  );
        // load state
        $State = new M\StatesMapper( $this->dataBase );
        $StateId = $output[0]['StateId'];
        $StateName = $State->findById( $StateId );
        // load city
        $City = new M\CitiesMapper( $this->dataBase );
        $CityId = $output[0]['CityId'];
        $CityName = $City->findById( $CityId );
        // load region
        $Region = new M\RegionsMapper( $this->dataBase );
        $RegionId = $output[0]['RegionId'];
        $RegionName = $Region->findById( $RegionId );

        // set all fetched data togrther
        $userarray = array();
        foreach ( $output[0] as $key=>$value )
        {
            $userarray[$key] = $value;
        }
        $userarray['StateName'] = $StateName['StateName'];
        $userarray['CityName'] = $CityName['CityName'];
        $userarray['RegionName'] = $RegionName['RegionName'];


        //var_dump( $userarray );
        return $userarray;


    }
    /*
     *
     *find a user based on a specific property like id , property passed by an array
     * Usage:  $this->getUserByProperty( array('FirstName' , 'Foo') );
     *
     */
    public function get_users( $FirstName='' , $LastName='' , $Email='', $Telephone='', $StateId='' , $CityId='' ,$RegionId='' , $Gender='', $isLogin='' , $isDelete ='' , $isActive = '' )
    {
        // condition is an empty array at first
        $condition = array();

        // set values for search if parameter is set
        ($FirstName == '' ?  : $condition['FirstName'] = $FirstName) ;
        ($LastName == '' ?  : $condition['LastName'] = $LastName) ;
        ($Email == '' ?  : $condition['Email'] = $Email) ;
        ($Telephone == '' ?  : $condition['Telephone'] = $Telephone) ;
        ($StateId == '' ?  : $condition['StateId'] = $StateId) ;
        ($CityId == '' ?  : $condition['CityId'] = $CityId) ;
        ($RegionId == '' ?  : $condition['RegionId'] = $RegionId) ;
        ($Gender == '' ?  : $condition['Gender'] = $Gender) ;
        ($isLogin == '' ?  : $condition['IsLogin'] = $isLogin) ;
        ($isDelete == '' ?  : $condition['IsDelete'] = $isDelete) ;
        ($isActive == '' ?  : $condition['IsActive'] = $isActive) ;


        //$fields='*';

        $MapperInstance = new M\UserFullDataMapper( $this->dataBase );

        // prepare condition string for where
        $condition = $this->prepareConditionAdvSearch( $condition );

        return $MapperInstance->find($condition ) ;
    }

    /*
    *
    *find a user based on a specific property like id , property passed by an array
    * Usage:  $this->getUserByProperty( array('FirstName' , 'Foo') );
    *
    */
    public function get_undeleted_users( $FirstName='' , $LastName='' , $Email='', $Telephone='', $StateId='' , $CityId='' ,$RegionId='' , $Gender='', $isLogin='' , $isDelete ='' , $isActive = '' )
    {
        // condition is an empty array at first
        $condition = array();

        // set values for search if parameter is set
        ($FirstName == '' ?  : $condition['FirstName'] = $FirstName) ;
        ($LastName == '' ?  : $condition['LastName'] = $LastName) ;
        ($Email == '' ?  : $condition['Email'] = $Email) ;
        ($Telephone == '' ?  : $condition['Telephone'] = $Telephone) ;
        ($StateId == '' ?  : $condition['StateId'] = $StateId) ;
        ($CityId == '' ?  : $condition['CityId'] = $CityId) ;
        ($RegionId == '' ?  : $condition['RegionId'] = $RegionId) ;
        ($Gender == '' ?  : $condition['Gender'] = $Gender) ;
        ($isLogin == '' ?  : $condition['IsLogin'] = $isLogin) ;
        ($isDelete == '' ?  : $condition['IsDelete'] = $isDelete) ;
        ($isActive == '' ?  : $condition['IsActive'] = $isActive) ;


        //$fields='*';

        $MapperInstance = new M\UnDeleteUserDataMapper( $this->dataBase );

        // prepare condition string for where
        $condition = $this->prepareConditionAdvSearch( $condition );

        return $MapperInstance->find($condition ) ;
    }
    /*
 *
 *find a user based on a specific property like id , property passed by an array
     *
 *
 */
    public function search_users($FirstName='' , $LastName='' , $Email='', $Telephone='', $StateId='' , $CityId=''
        , $RegionId='' , $Address , $Gender='', $isLogin='' , $isDelete ='' , $isActive = '' , $CreateBy , $startDateCreate , $endDateCreate
        , $startLastLogin , $endLastLogin , $startEditPasswordDate , $endEditPasswordDate )
    {
        // condition is an empty array at first
        $condition = array();

        // set values for search if parameter is set
        ($FirstName == '' ?  : $condition['FirstName'] = $FirstName) ;
        ($LastName == '' ?  : $condition['LastName'] = $LastName) ;
        ($Email == '' ?  : $condition['Email'] = $Email) ;
        ($Telephone == '' ?  : $condition['Telephone'] = $Telephone) ;
        ($StateId == '' ?  : $condition['StateId'] = $StateId) ;
        ($CityId == '' ?  : $condition['CityId'] = $CityId) ;
        ($RegionId == '' ?  : $condition['RegionId'] = $RegionId) ;
        ($Address == '' ?  : $condition['Address'] = $Address) ;
        ($Gender == '' ?  : $condition['Gender'] = $Gender) ;
        ($isLogin == '' ?  : $condition['isLogin'] = $isLogin) ;
        ($isDelete == '' ?  : $condition['isDelete'] = $isDelete) ;
        ($isActive == '' ?  : $condition['isActive'] = $isActive) ;
        ($CreateBy == '' ?  : $condition['CreateBy'] = $CreateBy) ;

        //TODO: this should be review

        $MapperInstance = new M\UserFullDataMapper( $this->dataBase );

        // prepare condition string for where
        $condition = $this->prepareConditionAdvSearch( $condition );

        $between = array();
        //$this->DateConvertor->get_gregorian('1395-08-07');

        //set start date
        //convert date from jalali to gregorian just for search
        $startDateCreate = ($startDateCreate == '' ? $this->DateConvertor->get_gregorian('1392-01-01') : $this->DateConvertor->get_gregorian($startDateCreate) );
        $endDateCreate = ($endDateCreate == '' ?  $this->DateConvertor->get_gregorian('1407-01-01') : $this->DateConvertor->get_gregorian( $endDateCreate ) );
        // set login date
        $startLastLogin = ($startLastLogin == '' ?  $this->DateConvertor->get_gregorian('1392-01-01'): $this->DateConvertor->get_gregorian($startLastLogin) );
        $endLastLogin = ($endLastLogin == '' ? $this->DateConvertor->get_gregorian('1407-01-01') : $this->DateConvertor->get_gregorian($endLastLogin) );
        // set edit password date
        $startEditPasswordDate = ($startEditPasswordDate == '' ?  $this->DateConvertor->get_gregorian('1392-01-01'): $this->DateConvertor->get_gregorian($startEditPasswordDate) );
        $endEditPasswordDate = ($endEditPasswordDate == '' ? $this->DateConvertor->get_gregorian('1407-01-01') : $this->DateConvertor->get_gregorian($endEditPasswordDate) );

        $between = " CreationDate between '". $startDateCreate . "' and '" . $endDateCreate ."'";
        $between .= "  and ";
        $between .= " LastLogin between '". $startLastLogin . "' and '" . $endLastLogin ."'";
        $between .= "  and ";
        $between .= " EditPasswordDate between '". $startEditPasswordDate . "' and '" . $endEditPasswordDate ."'";

        $condition .= ($condition == '' ? $between : " and ". $between );
        //echo $condition;
        return $MapperInstance->find($condition ) ;
    }






    /*
     * update user main data
     *
     */
    public function edit_user( $UserId , $FirstName = '' , $LastName = '' , $Email = '' , $Telephone=''  )
    {
        // check user id
        if( !$this->checkId( $UserId ) )
        {
            return "Err_edit_User_user_id_is_not_valid";
        }

        // if no parameter sent , return
        if( $FirstName == '' && $LastName == '' && $Email == '' && $Telephone = '' )
        {
            return "msg_edit_user_you_should_specify_at_least_one_parameter";
        }

        // create data for update
        $data = array();
        ($FirstName == '' ?  : $data['FirstName'] = $FirstName );
        ($LastName == '' ?  : $data['LastName'] = $LastName );
        ($Email == '' ?  : $data['Email'] = $Email );
        ($Telephone == '' ?  : $data['Telephone'] = $Telephone );
        // in this system telephone is username
        ($Telephone == '' ?  : $data['UserName'] = $Telephone );

        // create new instance from user mapper
        $MapperInstance = new M\UsersMapper( $this->dataBase );

        // create condition
        $where = "id=". $UserId;
        //update user data
        return $MapperInstance->update( $data , $where );
    }

    /*
     * edit password user
     *
     */
    public function edit_user_password( $UserId , $Password )
    {
        //check user id
        if( !$this->checkId( $UserId ))
            return "msg_edit_password_user_id_is_not_valid";
        // check password to be set
        if( !isset( $Password ) )
        {
            return "msg_password_edit_password_not_set";
        }
        //create new instance from user mapper
        $UserMapper = new M\UsersMapper( $this->dataBase );
        //create data array
        $data = array();
        //TODO:  edit Password
        $data['password'] =  $Password;
        //create condition
        $condition = "id=".$UserId;
        // update user password
        $ResetPassResult = $UserMapper->update( $data , $condition );

        // update date of reset password

        $historyMapper = new M\UserHistoryMapper( $this->dataBase );

        $condition = "UserId=".$UserId;

        // get current time stamp
        $data = array();
        $data['EditPasswordDate'] = date( 'Y-m-d h:i:s');

        $historyMapper->update( $data , $condition );
        // end of update reset password date

        return $ResetPassResult;



    }
    /*
     * get last edit password
     */
    public function get_last_edit_password( $UserId )
    {
        //check user id
        if( !$this->checkId( $UserId ))
            return "msg_last_edit_password_user_id_is_not_valid";
        // update date of reset password

        $historyMapper = new M\UserHistoryMapper( $this->dataBase );

        $condition = "UserId=".$UserId;

        $fields = 'EditPasswordDate';

        return $historyMapper->find( $condition , $fields );
        // end of update reset password date


    }

    /*
     *
     * insert new user to DB
     */
    public function register_user($userName , $firstName , $lastName , $password , $telephone , $Email=''  ,
                                  $stateId = '' , $cityId  = '' , $regionId = '' , $address = '' , $gender = '' , $otherPhone = array() ,$CreatedBy ='' )
    {
        $MapperInstance = new M\UsersMapper( $this->dataBase );

        $property = array( 'UserName'=>$userName , 'FirstName'=>$firstName , 'LastName'=> $lastName ,
            'Password'=> $password , 'Email'=>$Email , 'Telephone'=> $telephone , 'CreationDate'=>date('Y-m-d h:i:s'));

        $UserId = $MapperInstance->insert( $property );

        // if user registerEd to users table then do other codes
        if( $UserId > 0 )
        {

            // INSERT USER PROFILE
            $profileProperty = array();

            $profileMapper  = new M\UserProfileMapper( $this->dataBase);

            $profileProperty['CityId'] = $cityId ;
            $profileProperty['StateId'] = $stateId ;
            $profileProperty['RegionId'] = $regionId ;
            $profileProperty['Address'] = $address ;
            $profileProperty['Gender'] = $gender ;
            $profileProperty['UserId'] = $UserId;
            $profileProperty['CreationDate'] = date('Y-m-d h:i:s');

            $profileMapper->insert($profileProperty);


            // INSERT USER HISTORY

            $UserHistoryMapper = new M\UserHistoryMapper( $this->dataBase );

            $UserHistoryProperty = array();
            $UserHistoryProperty['UserId'] = $UserId;
            $UserHistoryProperty['LastLogin'] = date('Y-m-d h:i:s');
            $UserHistoryProperty['IsActive'] =  '0';
            $UserHistoryProperty['LostPasswordControl'] = '0';
            $UserHistoryProperty['IsDelete'] = '0';
            $UserHistoryProperty['StartSession'] = date('Y-m-d h:i:s');
            $UserHistoryProperty['IsLogin'] = '0';
            // if user register an account for himself,then  createBy = userid , otherwise the admin or any other user id assign to "createBy"
            // who create this user , id of account creator
            $UserHistoryProperty['CreateBy'] = ( $CreatedBy == '' ? $UserId : $CreatedBy) ;
            $UserHistoryProperty['CreationDate'] = date('Y-m-d h:i:s');
            //var_dump( $UserHistoryProperty );

            $UserHistoryMapper->insert( $UserHistoryProperty );

            // insert other phones for this User
            if( sizeof( $otherPhone ) > 0 )
            {
                //create instance of tel mapper
                $TelsMapper = new M\TelsMapper( $this->dataBase );
                //for each phone number, iterate one time more
                foreach ( $otherPhone as $key => $phoneItem )
                {
                    $data = array();
                    $data['UserId'] = $UserId;
                    $data['Telephone'] = $phoneItem;
                    $data['CreationDate'] = date('Y-m-d h:i:s');

                    $TelsMapper->insert( $data );

                }

            }
        }

        return $UserId;
    }



    /*
     *
     *  delete user by by id
     *  we don't delete any user data, ,we just disable it
     */
    public function delete_user_by_id( $id )
    {
        //check id validity
        if( !$this->checkId( $id ) )
            return "Err_delete_user_by_id_id_is_not_valid";

        //create new instance of User History
        $MapperInstance = new M\UserHistoryMapper( $this->dataBase );

        // create an array for condition
        $condition = array();

        $condition['UserId'] = $id;

        //prepare condition
        $where = $this->prepareCondition( $condition );

        // parameter that should update
        $property = array( 'IsDelete' => '1' );

        return $MapperInstance->update( $property , $where );
    }

    /*
     *  restore user ( undelete )
     */
    public function restore_user_by_id( $UserId )
    {
        // check user id
        if( !$this->checkId( $UserId ) )
        {
            return "Err_set_user_description_user_id_is_not_valid";
        }
        // create instance of mapper
        $historyMapper = new M\UserHistoryMapper( $this->dataBase );
        // prepare data and condition
        $data = array();
        $data ['IsDelete'] = '0';
        $condition = array();
        $condition['UserId'] = $UserId;
        $condition = $this->prepareCondition($condition );
        // restore user
        return $historyMapper->update( $data , $condition );
    }

    /*
         *  get user create by
         */
    public function get_user_create_by ( $UserId )
    {

        //check id validity
        if( !$this->checkId( $UserId ) )
            return "Err_get_user_create_by_user_id_is_not_valid";
        //create new instance of user history
        $MapperInstance = new M\UserHistoryMapper( $this->dataBase );

        // create an array for condition
        $condition = "UserId=".$UserId;

        $fields = 'CreateBy';

        return $MapperInstance->find( $condition  , $fields);

        //
        // $this->get_user_by_id()
    }

    /*
     * get undeleted user by creator id
     */
    public function get_users_by_creator_id( $UserId )
    {
        //check id validity
        if( !$this->checkId( $UserId ) )
            return "Err_get_user_by_creator_user_id_is_not_valid ";

        $userMapper = new M\UndeleteUserDataMapper( $this->dataBase );

        $condition = "CreateBy=".$UserId;

        return $userMapper->find( $condition );
    }


    /*
     * get deleted user by creator id
     */
    public function get_deleted_users_by_creator_id( $UserId )
    {
        //check id validity
        if( !$this->checkId( $UserId ) )
            return "Err_get_deleted_user_by_creator_user_id_is_not_valid";

        $userMapper = new M\DeleteUserDataMapper( $this->dataBase );

        $condition = "CreateBy=".$UserId;

        return $userMapper->find( $condition );
    }


    /*
     * get all deleted user
     */
    public function get_all_deleted_users( )
    {
        $userMapper = new M\DeleteUserDataMapper( $this->dataBase );

        return $userMapper->find( );
    }

    /*
     *  activate user
     */
    public function activate_user( $UserId )
    {
        //check id validity
        if( !$this->checkId( $UserId ) )
            return "Err_activate_user_id_is_not_valid";
        //create new instance of user history
        $MapperInstance = new M\UserHistoryMapper( $this->dataBase );

        // create an array for condition
        $condition = array();

        $condition['UserId'] = $UserId;

        //prepare condition
        $where = $this->prepareCondition( $condition );

        //User should be active
        $property = array( 'IsActive' => 1 );

        return $MapperInstance->update( $property , $where );
    }

    /*
     *  deactivate user, condition is array
     */
    public function deactivate_user( $UserId )
    {

        //check id validity
        if( !$this->checkId( $UserId ) )
            return "Err_deactivate_user_id_is_not_valid";

        $MapperInstance = new M\UserHistoryMapper( $this->dataBase );

        $condition['UserId'] = $UserId;

        $where = $this->prepareCondition( $condition );

        $property = array( 'IsActive' => 0 );

        return $MapperInstance->update( $property , $where );
    }


    /*
     *  get actions for a specific user
     */
    public function get_actions_by_userid( $UserId )
    {
        //check id validity
        if( !$this->checkId( $UserId ) )
            return "Err_actions_user_id_is_not_valid";

        // create new instance of user action mapper
        $UserActionsMapper = new M\UserActionsMapper( $this->dataBase );
        // set condition for find actions of this user
        $condition = "UserId=". $UserId;
        //find actions
        $ActionList = $UserActionsMapper->find( $condition );
        //return actions
        return $ActionList;
    }

    /*
     * check a user have access to an action
     */
    public function check_user_access_to_action( $actionName , $ActionList  )
    {
        //create new instance from action mapper
        $actionMapper = new M\ActionsMapper( $this->dataBase );

        //find id of this actionName
        //if( !$result = $actionMapper->find ( "ActionName='".$actionName."'" ) )
        //    return "msg_check_user_access_there_is_no_action_for_your_action_name";

        //$actionId = ($result[0] ? $result[0]['id'] : null );
        // if no id found return
        //if( $actionId == null ) return false;


        //var_dump( $actionId );
        ($ActionList[0] == null ?  : $UserActions = $ActionList[0]['Actions'] );
        // make an array of action ids to find access to action
        if( !isset( $UserActions ))
        {
            return "msg_check_user_access_user_action_not_defined";
        }
        $actions = explode(',' , $UserActions );
        //search in actions array
        foreach ( $actions as $action )
        {
            // if access exists , return true
            if( $actionName == $action )
                return true;
        }
        // there is no access to this action, return false
        return false;
    }


    /*
     * check if a username is valid in database
     */
    protected function check_username_exists( $UserName )
    {
        // create new instance of User mapper
        $UserMapper = new M\UsersMapper( $this->dataBase );

        // create condition
        $condition = array();

        $condition['UserName'] = $UserName;

        // prepare condition
        $condition = $this->prepareCondition( $condition );

        //check existence of username
        $User = $UserMapper->find( $condition );

        return ( $User ? $User[0]['id'] : 0 );
    }

    /*
     * check username and password if is valid, if is valid return id of user pass
     */
    public function check_username_password_valid( $UserName , $Password )
    {
        // create new instance of User mapper
        $UserMapper = new M\UsersMapper( $this->dataBase );

        // create condition
        $condition = array();

        $condition['UserName'] = $UserName;
        $condition['Password'] = $Password;

        // prepare condition
        $condition = $this->prepareCondition( $condition );

        //check existence of username and password
        $result = $UserMapper->find( $condition );
        //var_dump( $result );
        return  ($result ? $result[0]['id'] : 0 ) ;
    }

    /*
     *
     * check lostControlpassword , if it exceeds from limit , block the user
     */
    protected function check_user_lost_control_password ( $UserId )
    {
        //check id validity
        if( !$this->checkId( $UserId ) )
            return "Err_check_user_lost_password_id_is_not_valid";

        $UserHistoryMapper = new M\UserHistoryMapper( $this->dataBase );

        // create condition for load
        $condition = array();
        $condition['UserId'] = $UserId;
        $condition = $this->prepareCondition( $condition );

        // load user history
        $userHistory = $UserHistoryMapper->find( $condition );

        //update user history

        /*
         *increment lost password control for user
         *limitation for password count
         */

        $data = array();
        // set new passwordControl value
        $data['LostPasswordControl'] = intval( $userHistory[0]['LostPasswordControl'] ) + 1 ;
        // check if user should be block (based on limitation) block him
        $data['IsActive'] = ( $data['LostPasswordControl'] < password_count_limit ? 1 : 0 );

        // update user history
        return $UserHistoryMapper->update( $data , $condition );

    }



    /*
     * login for user
     * inputs: username and password
     */
    public function login_user( $username , $password )
    {

        // check if the username registered on system
        if( !( $UId = $this->check_username_exists( $username ) ) )
        {
            //if username not registerd return message and exit
            return "Err_login_This_Username_have_not_registered";
        }

        //check validity of username and password in the system
        if( !($UserId = intval( $this->check_username_password_valid( $username , $password ) ) )   )
        {
            // check password control
            $this->check_user_lost_control_password( $UId );

            return "Err_login_Your_username_and_password_not_correct";
        }

        // get action list for UserId
        if ( !$ActionList = $this->get_actions_by_userid( $UserId ) )
            return "msg_login_no_action_list_found_for_this_user";

        //var_dump( $ActionList );
        // check (action list) access for login
        if( !$this->check_user_access_to_action( 'login' , $ActionList ) )
        {
            // no access to login
            return "Err_login_You_have_not_access_to_login_in_system";
        }

        // create a new instance from userFullData ( VIEW )
        $UserFullDataMapper = new M\UserFullDataMapper( $this->dataBase );

        //set condition for load user full data when he login
        $condition = $this->prepareCondition( array( 'UserId' => $UserId , 'IsDelete' => '0' ) );

        // load user full data from view
        $UserFullData = $UserFullDataMapper->find( $condition ) ;


        // account is not deleted , so check if user is deactivate or not
        if( $UserFullData )
        {

            // use account blocked
            if( $UserFullData[0]['IsActive'] == 0 )
            {
                return "msg_login_your_account_has_been_blocked";
            }

            // user exists and is active, he can login
            else
            {

                //update user history for this user
                $UserHistoryMapper = new M\UserHistoryMapper( $this->dataBase );

                // crate condition for update user history
                $condition = array();
                $condition ["UserId"] = $UserId;

                // create array for user history
                $data = array();

                // reset lost password control
                $data['LostPasswordControl'] = 0;
                // set islogin to 1
                $data['isLogin'] = 1;
                //set Start session to login time
                $data['StartSession'] = date("Y-m-d h:i:s");
                //update last login to now
                $data['LastLogin'] = date("Y-m-d h:i:s");

                // prepare condition
                $condition = $this->prepareCondition( $condition );
                //update user history fields
                $update = $UserHistoryMapper->update( $data , $condition );


                //echo "OK: Your login was successful";

                // load new data from database
                $UserFullData = $UserFullDataMapper->find( $condition ) ;

                // login is successful , return data
                return $UserFullData;
            }
        }
        // account is deleted
        else
        {

            return "msg_login_your_account_has_been_deleted_before";

        }

    }

    /*
     * logout
     */
    public function logout_user($UserId )
    {
        //check user id
        if( !$this->checkId( $UserId ) )
            return "msg_logout_User_id_is_not_valid";

        //create new instance from
        $UserHistoryMapper = new M\UserHistoryMapper( $this->dataBase );

        // create data for update
        $data = array();
        $data['IsLogin'] = '0';
        // create condition
        $condition = "UserId=".$UserId;
        // update logout
        return $UserHistoryMapper->update( $data , $condition );
    }

    /*
     * get user profile
     */
    public function get_user_profile( $UserId )
    {
        // check user id
        if( !$this->checkId( $UserId ) )
            return "Err_get_user_profile_Id_is_not_valid";

        // create a mapper to userprofile
        $UserProfileMapper = new M\UserProfileMapper(  $this->dataBase );
        //find profile by id
        return $UserProfileMapper->findById( $UserId );


    }

    /*
     * get user role
     */
    public function get_user_role( $UserId )
    {
        // check user id
        if( !$this->checkId( $UserId ) )
            return "Err_get_user_role_Id_is_not_valid";

        // create a new instance from GetUsersRolesMapper
        $GetUsersRoleMapper = new M\GetUsersRolesMapper( $this->dataBase );

        // create condition for find roles
        $condition = $this->prepareCondition( array( 'UserId' => $UserId ) );

        // find and return an array from UserRoles
        return $GetUsersRoleMapper->find( $condition );
    }

    /*
     * get user groups
     */
    public function get_user_group( $UserId )
    {
        // check user id
        if( !$this->checkId( $UserId ) )
            return "Err_get_user_group_Id_is_not_valid";

        // create a new instance from GetUsersGroupsMapper
        $GetUserGroupsMapper = new M\GetUsersGroupsMapper( $this->dataBase );

        // create condition for find groups
        $condition = $this->prepareCondition( array( 'UserId' => $UserId ) );

        // find and return an array from UserGroups
        return $GetUserGroupsMapper->find( $condition );
    }


    /*
     *  set User Profile
     */
    public function  set_user_profile ( $UserId , $Gender='' , $StateId='' , $CityId='' , $RegionId='' , $Address='' )
    {
        // check user id
        if( !$this->checkId( $UserId ) )
            return "Err_set_user_profile_Id_is_not_valid";

        // create a new instance from GetUsersGroupsMapper
        $UserProfileMapper = new M\UserProfileMapper( $this->dataBase );

        // create condition for insert profile
        $data = array();
        $data['UserId'] = $UserId;
        $data['Gender'] = $Gender;
        $data['stateId'] = $StateId;
        $data['CityId'] = $CityId;
        $data['RegionId'] = $RegionId;
        $data['Address'] = $Address;
        $data['CreationDate'] = date('Y-m-d h:i:s');

        // insert data to user profile
        return $UserProfileMapper->insert( $data );
    }

    /*
     * set user roles
     */
    public function set_user_role(  $UserId , $RoleId )
    {
        // check user id
        if( !$this->checkId( $UserId ) )
            return "Err_get_user_role_user_id_is_not_valid";

        // check role id
        if( !$this->checkId( $RoleId ) )
            return "Err_get_user_role_role_id_is_not_valid";

        //create new instance of UserRoleMapper
        $UserRole = new M\UserRoleMapper( $this->dataBase );

        // prepare data into array for insert
        $data = array();
        $data['UserId'] = $UserId;
        $data['RoleId'] = $RoleId;

        return $UserRole->insert( $data );

    }



    /*
     * edit user profile
     */
    public function edit_user_profile( $UserId , $Gender='' , $StateId='' , $CityId='' , $RegionId='' , $Address='' )
    {

        if( $Gender === '' && $StateId === '' && $CityId === '' && $RegionId === '' && $Address === '')
        {
            return "You_should_specify_at_least_one_parameter.";
        }
        if( !$this->checkId( $UserId ) )
            return "Err_edit_user_profile_User_Id_is_not_valid";

        // create a new instance from GetUsersGroupsMapper
        $UserProfileMapper = new M\UserProfileMapper( $this->dataBase );

        // find user profile id
        if( !($result = $UserProfileMapper->find( 'UserId = '.$UserId ) ) )
        {
            return "Err_edit_user_profile_no_Valid_user_for_this_id";
        }

        // set profile id
        $ProfileId = $result[0]['id'];


        // create condition for update profile
        $condition = array();

        // set parameters in array just if a valid data passed to function
        ($Gender === '' ?  : $condition['Gender'] = $Gender) ;
        ($StateId === '' ?  : $condition['StateId'] = $StateId) ;
        ($CityId === '' ?  : $condition['CityId'] = $CityId) ;
        ($RegionId === '' ?  : $condition['RegionId'] = $RegionId) ;
        ($Address === '' ?  : $condition['Address'] = $Address) ;

        // update data to user profile
        return $UserProfileMapper->update( $condition , 'Id ='.$ProfileId );
    }

    /*
     * set role
     */
    public function set_role($RoleName , $RoleDescription )
    {
        // create new instance from RoleMapper
        $RoleMapper = new M\RolesMapper( $this->dataBase );

        // create an array for data that inserted to roles
        $data = array();
        // add data fro setting role
        $data['Name'] = $RoleName;
        $data['Description'] = $RoleDescription;

        // insert role to table
        return $RoleMapper->insert( $data );

    }

    /*
     * get role
     */
    public function get_role( $RoleId )
    {
        // check role id
        if( !$this->checkId( $RoleId ) )
            return "Err_get_role_role_id_is_not_valid";

        // create new instance from RoleMapper
        $RoleMapper = new M\RolesMapper( $this->dataBase );

        // find role by id
        return $RoleMapper->findById( $RoleId );

    }


    /*
     * get all role - addes 2-8-95
     */
    public function get_all_role( )
    {

        // create new instance from RoleMapper
        $RoleMapper = new M\RolesMapper( $this->dataBase );

        // find role by id
        return $RoleMapper->find( );

    }
    /*
     * edit role
     */
    public function edit_role( $RoleId , $RoleName = '' , $RoleDescription = '')
    {
        if( $RoleName === '' && $RoleDescription === '')
        {
            return "Edit_Role_You_should_specify_at_least_one_parameter";
        }
        //check role id validity
        if( !$this->checkId( $RoleId ) )
            return "Err_edit_role_Role_Id_is_not_valid";

        // create new instance from RoleMapper
        $RoleMapper = new M\RolesMapper( $this->dataBase );

        // create array of condition
        $condition = array();
        ($RoleName == '' ?  : $condition['Name'] = $RoleName) ;
        ($RoleDescription == '' ?  : $condition['Description'] = $RoleDescription) ;

        //update
        return $RoleMapper->update( $condition , 'id='.$RoleId );


    }
    /*
     * get all remaining roles
     */

    // get all remaining
    public function get_all_remaining_roles( $UserId )
    {
        $where = " Roles.id not in (select GetUsersRoles.RoleId from GetUsersRoles where UserId = $UserId) ";

        $roleMapper = new M\RolesMapper( $this->dataBase );
        // find all group groups
        return $roleMapper->find($where);
    }

    /*
    * get all remaining roles for group
    */

    // get all remaining
    public function get_all_remaining_roles_for_group( $GroupId )
    {
        $where = " Roles.id not in (select GetUsersRoles.RoleId from GetUsersRoles where UserId = $GroupId) ";

        $roleMapper = new M\RolesMapper( $this->dataBase );
        // find all group groups
        return $roleMapper->find($where);
    }

    /*
     *  set action
     */

    public function set_action( $ActionName , $ActionDescription )
    {
        // create new instance from ActionMapper
        $ActionMapper = new M\ActionsMapper( $this->dataBase );

        // create an array for data that inserted to roles
        $data = array();
        // add data fro setting Action
        $data['ActionName'] = $ActionName;
        $data['Description'] = $ActionDescription;

        // insert Action to table
        return $ActionMapper->insert( $data );
    }

    /*
     *  get action
     */

    public function get_action( $ActionId )
    {
        // check action id
        if( !$this->checkId( $ActionId ) )
            return "Err_get_action_action_id_is_not_valid";

        // create new instance from action mapper
        $ActionMapper = new M\ActionsMapper( $this->dataBase );

        // find action by id
        return $ActionMapper->findById( $ActionId );

    }

    /*
     * edit action
     */
    public function edit_action( $ActionId , $ActionName='' , $ActionDescription ='')
    {
        if( $ActionName === ''  && $ActionDescription === '' )
            return 'Edit_Action_You_should_specify_at_least_One_parameter';

        //Check action id validity
        if( !$this->checkId( $ActionId ) )
            return "Err_edit_action_action_Id_is_not_valid";

        // create new instance from RoleMapper
        $ActionMapper = new M\ActionsMapper( $this->dataBase );

        // create array of condition
        $condition = array();
        ($ActionName == '' ?  : $condition['ActionName'] = $ActionName) ;
        ($ActionDescription == '' ?  : $condition['Description'] = $ActionDescription) ;

        //update
        return $ActionMapper->update( $condition , 'id='.$ActionId );
    }


    /*
     * delete action
     */
    public function delete_action( $ActionId)
    {

        if( !$this->checkId( $ActionId ))
            return "Err_delete_action_action_id_is_not_valid";
        //create new instance of action mapper
        $ActionMapper = new M\ActionsMapper( $this->dataBase );
        //prepare condition
        $conition = 'id='.$ActionId;
        //delete action
        return $ActionMapper->delete( $conition );

    }

    /*
     * set role action
     */
    public function set_role_action( $RoleId , $ActionId )
    {
        if( $ActionId == '' )
            return "Err_set_role_action_action_id_is_null";
        if( !$this->checkId( $ActionId ))
            return "Err_set_role_action_action_id_is_not_valid";


        $RoleActionMapper = new  M\RoleActionMapper( $this->dataBase );

        // prepare data
        $data = array();
        $data['RoleId'] = $RoleId;
        $data['ActionId'] = $ActionId;
        $data['CreationDate']  = date('Y-m-d h:i:s');
        //insert role action
        return $RoleActionMapper->insert( $data );



    }




    /*
     *  get role action
     */
    public function get_role_action( $RoleId )
    {
        //if role id is not set truly , return
        if ( !$this->checkId( $RoleId ) )
            return "msg_get_role_action_role_id_is_not_valid";

        //create new instance of role action mapper
        $RoleActionMapper = new M\RoleActionFullDataMapper( $this->dataBase );
        //set condition
        $condition = "RoleId=". $RoleId;
        //find actions by roleid
        $f =  $RoleActionMapper->find( $condition );
       // print_r($f);
        return $f;
    }


    /*
    *  get group role
    */
    public function get_group_role( $GroupId )
    {
        //if role id is not set truly , return
        if ( !$this->checkId( $GroupId ) )
            return "msg_get_group_role_group_id_is_not_valid";

        //create new instance of role action mapper
        $GroupRole = new M\RoleGroupFullDataMapper( $this->dataBase );
        //set condition
        $condition = "GroupId=". $GroupId;
        //find actions by roleid
        $f =  $GroupRole->find( $condition );
        // print_r($f);
        return $f;
    }


    /*
     * set user role
     */
    public function delete_user_role( $UserId , $RoleId )
    {
        // create new instance from userRoleMapper
        $UserRoleMapper = new M\UserRoleMapper( $this->dataBase );
        //create array fro condition
        $condition = array();
        $condition['UserId'] = $UserId;
        $condition['RoleId'] = $RoleId;
        // prepare condition for delete
        $condition = $this->prepareCondition( $condition );
        // delete UserRole from UserRoles
        return $UserRoleMapper->delete( $condition );
    }


    /*
     * delete role
     */
    public function delete_role( $RoleId )
    {
        if( !$this->checkId( $RoleId ))
            return "Err_delete_role_role_id_is_not_valid";
        //create new instance of role mapper
        $RoleMapper = new M\RolesMapper( $this->dataBase );
        //prepare condition
        $conition = 'id='.$RoleId;
        //delete role
        return $RoleMapper->delete( $conition );

    }

    /*
     * set group
     */
    public function set_group( $GroupName , $GroupDescription )
    {
        // create new instance from groupMapper
        $RoleMapper = new M\GroupsMapper( $this->dataBase );

        // create an array for data that inserted to group
        $data = array();
        // add data fro setting group
        $data['GroupName'] = $GroupName;
        $data['Description'] = $GroupDescription;

        // insert group to table
        return $RoleMapper->insert( $data );

    }

    /*
     * get all groups
     */
    public function get_all_groups( )
    {

        $groupMapper = new M\GroupsMapper( $this->dataBase );

        // find all group groups
        return $groupMapper->find( );
    }

    // get all remaining
    public function get_all_remaining_groups( $UserId )
    {
        $where = " Groups.id not in (select GetUsersGroups.GroupId from GetUsersGroups where UserId = $UserId) ";

        $groupMapper = new M\GroupsMapper( $this->dataBase );
        // find all group groups
        return $groupMapper->find($where);
    }
    //get all remaining action
    public function get_all_remaining_actions($RoleId){

        $where = " Actions.id not in  (select ActionId from RoleActionFullData where RoleActionFullData.RoleId = $RoleId ) ";

        $groupMapper = new M\ActionsMapper( $this->dataBase );
        // find all group groups
        return $groupMapper->find($where);
    }
    //input is role id and output is all users information that have this role
    public function get_all_users_have_this_role($RoleId){

        $where = " UserId  in  (select UserId from UserRole where RoleId = $RoleId ) ";

        $userMapper = new M\UnDeleteUserDataMapper( $this->dataBase );
        // find all group groups
        return $userMapper->find($where);
    }

    //input is role id and output is all users information that have this group
    public function get_all_users_have_this_group($GroupId){

        $where = " UserId  in  (select UserId from UserGroup where GroupId = $GroupId ) ";

        $userMapper = new M\UnDeleteUserDataMapper( $this->dataBase );
        // find all group groups
        return $userMapper->find($where);
    }

    /*
     * get group by id
     */
    public function get_group_by_id( $GroupId = '' )
    {

        if( !$this->checkId( $GroupId ) )
            return "Err_get_group_group_id_is_not_valid";
        // create new instance from groupsMapper
        $groupMapper = new M\GroupsMapper( $this->dataBase );

        // find group
        return $groupMapper->findById( $GroupId );
    }

    /*
     * get group by name
     */
    public function get_group_by_name( $GroupName ='' )
    {

        // create new instance from groupsMapper
        $groupMapper = new M\GroupsMapper( $this->dataBase );

        //craete array of condition
        $condition = array();
        ( $GroupName == '' ? : $condition['GroupName'] = $GroupName );
        //prepare condition to find
        $condition = $this->prepareConditionAdvSearch( $condition );
        // find group names
        return $groupMapper->find( $condition );
    }


    /*
     * edit group
     */
    public function edit_group( $GroupId , $GroupName='' , $GroupDescription ='')
    {

        if( $GroupName == '' && $GroupDescription == '' )
            return "Edit_Group_You_should_specify_at_least_one_of_the_parameters";

        //Check action id validity
        if( !$this->checkId( $GroupId ) )
            return "Err_edit_group_group_Id_is_not_valid";

        // create an instance from group
        $GroupMapper = new M\GroupsMapper( $this->dataBase );

        // create array of condition
        $condition = array();
        ( $GroupName == '' ?  : $condition['GroupName'] = $GroupName  ) ;
        ( $GroupDescription == '' ?  : $condition['Description'] = $GroupDescription ) ;

        //update
        return $GroupMapper->update( $condition , 'id='.$GroupId );

    }

    /*
     * delete group
     */
    public function delete_group( $GroupId )
    {
        if( !$this->checkId( $GroupId ) )
            return "Err_delete_group_group_id_is_not_valid";
        // create instance of group mapper
        $GroupMapper = new M\GroupsMapper( $this->dataBase );
        // prepare condition
        $condition = 'id='.$GroupId;
        //delete group
        return $GroupMapper->delete( $condition );
    }

    /*
     *
     * set state
     */
    public function set_state( $StateName )
    {
        // create new instance from StateMapper
        $StateMapper = new M\StatesMapper( $this->dataBase );

        // crate array of data
        $data = array();
        $data['StateName'] = $StateName;
        $data['CreationDate'] = date('Y-m-d h:i:s');

        // insert state to DB
        return $StateMapper->insert( $data );

    }

    /*
     * get all states
     */
    public function get_all_states()
    {
        // create new instance from StateMapper
        $StateMapper = new M\StatesMapper( $this->dataBase );

        //find all
        return $StateMapper->find();

    }
    /*
     *
     * get state by id
     */
    public function get_state_By_Id( $StateId )
    {
        // create new instance from StateMapper
        $StateMapper = new M\StatesMapper( $this->dataBase );

        //find state by id
        return $StateMapper->findById( $StateId );

    }

    /*
     * delete state
     */
    public function delete_state( $id )
    {
        $StateMapper = new M\StatesMapper( $this->dataBase );

        $condition = array();
        $condition['id'] = $id;

        $condition = $this->prepareCondition( $condition );

        return $StateMapper->delete( $condition );
    }

    /*
     *
     * get state id by name
     */
    public function get_state_By_Name( $StateName )
    {
        // create new instance from StateMapper
        $StateMapper = new M\StatesMapper( $this->dataBase );

        // create array for condition
        $condition = array();
        $condition['StateName'] = $StateName;

        // prepare condition for find state
        $condition = $this->prepareConditionAdvSearch( $condition );

        //find state by name
        return $StateMapper->find($condition);
    }


    /*
     *
     * set City
     */
    public function set_city( $CityName , $StateId )
    {
        // create new instance from city Mapper
        $CityMapper = new M\CitiesMapper( $this->dataBase );

        // crate array of data
        $data = array();
        $data['CityName'] = $CityName;
        $data['StateId'] = $StateId;
        $data['CreationDate'] = date('Y-m-d h:i:s');

        // insert city to DB
        return $CityMapper->insert( $data );

    }

    /*
     *
     * get City by id
     */
    public function get_all_cities_by_state_id( $StateId )
    {
        // create new instance from city mapper
        $CityMapper = new M\CitiesMapper( $this->dataBase );

        $condition = array();
        $condition['StateId'] = $StateId;

        $condition = $this->prepareConditionAdvSearch( $condition );

        // find
        return $CityMapper->find( $condition );
    }

    /*
     *
     * get City by id
     */
    public function get_city_by_id( $CityId )
    {
        // create new instance from StateMapper
        $CityMapper = new M\CitiesMapper( $this->dataBase );

        // find
        return $CityMapper->findById( $CityId );

    }

    /*
 *
 * get City by name
 */
    public function get_city_by_name( $CityName )
    {
        // create new instance from StateMapper
        $CityMapper = new M\CitiesMapper( $this->dataBase );

        $condition = array();
        $condition['CityName'] = $CityName;

        $condition = $this->prepareConditionAdvSearch( $condition );
        // find
        return $CityMapper->find( $condition );

    }

    /*
     * edit city
     */
    public function edit_city( $CityId , $CityName = '' , $StateId = '' )
    {
        if( $StateId == '' && $CityName == '' )
            return "Err_edit_city_You_should_specify_at_least_one_parameter";

        //Check city id validity
        if( !$this->checkId( $CityId ) )
            return "Err_edit_City_City_Id_is_not_valid";

        // check stateId validity
        if( !$this->checkId( $StateId ) )
            return "Err_edit_City_Stat_Id_is_not_valid";
        // create new instance of city mapper
        $CityMapper = new M\CitiesMapper( $this->dataBase );
        // create array for data
        $data = array();
        ( $CityName =='' ?  : $data['CityName'] = $CityName );
        ( $StateId =='' ?  : $data['StateId'] = $StateId );
        //create condition for update data
        $condition = 'id='.$CityId;
        //update City
        return $CityMapper->update( $data , $condition );

    }

    /*
     * delete city
     */
    public function delete_city( $CityId )
    {
        // check stateId validity
        if( !$this->checkId( $CityId ) )
            return "Err_delete_City_city_Id_is_not_valid";

        $CityMapper = new M\CitiesMapper( $this->dataBase );
        // create condition for deleting city
        $condition = 'id='.$CityId;
        return $CityMapper->delete( $condition );
    }


    /*
     *
     * set City
     */
    public function set_region( $RegionName , $CityId )
    {
        // create new instance from Region Mapper
        $RegionMapper = new M\RegionsMapper( $this->dataBase );

        // crate array of data
        $data = array();
        $data['RegionName'] = $RegionName;
        $data['CityId'] = $CityId;
        $data['CreationDate'] = date('Y-m-d h:i:s');

        // insert Region to DB
        return $RegionMapper->insert( $data );

    }

    /*
   *
   * get City by id
   */
    public function get_all_regions_by_city_id( $CityId )
    {
        // create new instance from region mapper
        $RegionMapper = new M\RegionsMapper( $this->dataBase );

        $condition = array();
        $condition['CityId'] = $CityId;

        $condition = $this->prepareConditionAdvSearch( $condition );

        // find
        return $RegionMapper->find( $condition );
    }
    /*
     *
     * get Region by id
     */
    public function get_region_by_id( $RegionId )
    {
        if( !$this->checkId( $RegionId ) )
            return "Err_get_region_by_id_region_id_is_not_valid";
        // create new instance from Region Mapper
        $RegionMapper = new M\RegionsMapper( $this->dataBase );

        // insert state to DB
        return $RegionMapper->findById( $RegionId );

    }

    /*
 *
 * get Region by name
 */
    public function get_region_by_name( $RegionName )
    {
        // create new instance from StateMapper
        $RegionMapper = new M\RegionsMapper( $this->dataBase );

        $condition = array();
        $condition['RegionName'] = $RegionName;

        $condition = $this->prepareConditionAdvSearch( $condition );
        // insert state to DB
        return $RegionMapper->find( $condition );

    }

    /*
     * edit Region
     */
    public function edit_region( $RegionId , $RegionName = '' , $CityId = '' )
    {
        if( $CityId == '' && $RegionName == '' )
            return "Err_edit_region_You_should_specify_at_least_one_parameter";

        //Check region id validity
        if( !$this->checkId( $RegionId ) )
            return "Err_edit_region_region_Id_is_not_valid";

        // check region Id validity
        if( !$this->checkId( $CityId ) )
            return "Err_edit_region_city_Id_is_not_valid";
        // create new instance of city mapper
        $RegionMapper = new M\RegionsMapper( $this->dataBase );
        // create array for data
        $data = array();
        ( $RegionName =='' ?  : $data['RegionName'] = $RegionName );
        ( $CityId =='' ?  : $data['CityId'] = $CityId );
        //create condition for update data
        $condition = 'id='.$RegionId;
        //update City
        return $RegionMapper->update( $data , $condition );

    }

    /*
     * delete region
     */
    public function delete_region( $RegionId )
    {
        // check  REgion Id validity
        if( !$this->checkId( $RegionId ) )
            return "Err_delete_Region_region_Id_is_not_valid";

        $RegionMapper = new M\RegionsMapper( $this->dataBase );
        // create condition for deleting city
        $condition = 'id='.$RegionId;
        return $RegionMapper->delete( $condition );
    }

    /*
     * set role group
     */
    public function set_role_group( $RoleId , $GroupId )
    {
        if( !$this->checkId( $RoleId) )
            return "Err_set_role_group_role_id_is_not_valid";
        if( !$this->checkId( $GroupId ) )
            return "Err_set_role_group_group_id_is_not_valid";
        //create new instance of role group mapper
        $RoleGroupMapper = new M\RoleGroupMapper( $this->dataBase );
        // create array for data
        $data = array();
        $data['RoleId'] = $RoleId;
        $data['GroupId'] = $GroupId;
        $data['CreationDate'] = date('Y-m-d h:i:s');
        //insert to role group
        return $RoleGroupMapper->insert( $data );

    }

    /*
     * delete role group
     */
    public function delete_role_group( $RoleId , $GroupId )
    {
        if( !$this->checkId( $RoleId) )
            return "Err_delete_role_group_role_id_is_not_valid";
        if( !$this->checkId( $GroupId ) )
            return "Err_delete_role_group_group_id_is_not_valid";
        //create new instance of role group mapper
        $RoleGroupMapper = new M\RoleGroupMapper( $this->dataBase );
        // create condition
        $data = array();
        $data['RoleId'] = $RoleId;
        $data['GroupId'] = $GroupId;

        $condition = $this->prepareCondition( $data );
        //delete role group
        return $RoleGroupMapper->delete( $condition );

    }

    /*
     * set user group
     */
    public function set_user_group( $UserId , $GroupId )
    {
        if( !$this->checkId( $UserId) )
            return "Err_set_user_group_user_id_is_not_valid";
        if( !$this->checkId( $GroupId ) )
            return "Err_delete_role_group_group_id_is_not_valid";
        //create new instance of user group mapper
        $UserGroupMapper = new M\UserGroupMapper( $this->dataBase );
        // create array for data
        $data = array();
        $data['UserId'] = $UserId;
        $data['GroupId'] = $GroupId;
        $data['CreationDate'] = date('Y-m-d h:i:s');
        //insert to role group
        return $UserGroupMapper->insert( $data );

    }

    /*
     * delete user group
     */
    public function delete_user_group( $UserId , $GroupId )
    {
        if( !$this->checkId( $UserId) )
            return "Err_set_user_group_user_id_is_not_valid";
        if( !$this->checkId( $GroupId ) )
            return "Err_set_user_group_group_id_is_not_valid";
        //create new instance of user group mapper
        $UserGroupMapper = new M\UserGroupMapper( $this->dataBase );
        // create condition
        $data = array();
        $data['UserId'] = $UserId;
        $data['GroupId'] = $GroupId;

        $condition = $this->prepareCondition( $data );
        //delete user group
        return $UserGroupMapper->delete( $condition );

    }
    /*
     * delete user group
     */
    public function delete_role_action( $RoleId , $ActionId )
    {
        if( !$this->checkId( $RoleId) )
            return "Err_set_user_group_role_id_is_not_valid";
        if( !$this->checkId( $ActionId ) )
            return "Err_set_user_group_action_id_is_not_valid";
        //create new instance of user group mapper
        $RoleActionMapper = new M\RoleActionMapper( $this->dataBase );
        // create condition
        $data = array();
        $data['RoleId'] = $RoleId;
        $data['ActionId'] = $ActionId;

        $condition = $this->prepareCondition( $data );
        //delete user group
        return $RoleActionMapper->delete( $condition );

    }

    /*
     * set user description
     */
    public function set_user_description( $UserId , $Description = '' )
    {
        // check user id
        if( !$this->checkId( $UserId ) )
        {
            return "Err set user description: user id is not valid ";
        }
        // check input description not be null
        if ($Description == '' )
        {
            return "Err_set_user_description_description_not_set";
        }

        // create mapper instance
        $profileMapper  = new M\UserProfileMapper( $this->dataBase);

        // prepare condition and data
        $condition = array();
        $condition['UserId'] = $UserId;
        $condition = $this->prepareCondition( $condition );

        $data = array();
        $data['Description'] = $Description ;

        // update description

        return $profileMapper->update( $data , $condition );
    }


    /*
     * get user description
     */
    public function get_user_description( $UserId )
    {
        // check user id
        if( !$this->checkId( $UserId ) )
        {
            return "Err_get_user_description_user_id_is_not_valid";
        }


        // create mapper instance
        $profileMapper  = new M\UserProfileMapper( $this->dataBase);

        // prepare condition and data
        $condition = "UserId = ". $UserId;

        // update description
        $field = 'Description';
        return $profileMapper->find( $condition, $field );
    }


    /*
     * load menu for users sidebar
     */

    public function LoadMenu()
    {
        $MenuMapper = new M\MenuFullDataMapper( $this->dataBase );

        return $MenuMapper->find();
    }

    /*
     * load monthly statistic
     */
    public function get_monthly_user_statistic()
    {
        $UserMonthMapper = new M\UserSignupMonthStatMapper( $this->dataBase );

        return $UserMonthMapper->find();
    }
    /*
     * load monthly statistic
     */
    public function get_monthly_user_statistic_jalali()
    {
        $UserMonthMapper = new M\UserSignupMonthJalaliStatMapper( $this->dataBase );

        return $UserMonthMapper->find();
    }
    /*
     * load weekly statistic
     */
    public function get_weekly_user_statistic()
    {
        $UserWeekMapper = new M\UserSignupWeekStatMapper( $this->dataBase );

        return $UserWeekMapper->find();
    }

    /*
     * load weekly statistic
     */
    public function get_weekly_user_statistic_jalali()
    {
        $UserWeekMapper = new M\UserSignupWeekJalaliStatMapper( $this->dataBase );

        return $UserWeekMapper->find();
    }
    //______________________________ 27-7-95 from here
    /*
     * get login users statistics
     */
    public function get_logged_in_user_statistics()
    {
        $onTimeOnLine = new M\OnTimeOnlineUsersStatMapper( $this->dataBase );
        return $onTimeOnLine->find();
    }

    /*
     * get total user statistics that we have , and this month stat
     */
    public function total_user_statistics()
    {
        $totalUserStat = new M\TotalUsersStatMapper( $this->dataBase );
        return $totalUserStat->find();
    }


    //______________________________ to here 27-7-95
    /*
     * check validity of integer id to be inserted an be numeric
     */
    public function checkId( $id )
    {
        return (( is_numeric( $id ) || $id === '' )? true : false);
    }
    /*
     *
     * create string for condition from array => this use for condition phrase
     */
    public function prepareCondition( $conditionArray )
    {
        $conditionString = "";
        $i = 0;
        foreach ( $conditionArray as $key => $value )
        {
            $conditionString .= $key ."=" . "'$value'";
            if( $i < ( sizeof($conditionArray) - 1 )  )
            {
                $conditionString .=" and ";
            }
            $i++;
        }
        return $conditionString;
    }

    /*
     *
     * create string for condition from array => this use for condition phrase
     */
    public function prepareConditionAdvSearch( $conditionArray )
    {
        $conditionString = "";
        $i = 0;
        foreach ( $conditionArray as $key => $value )
        {
            $conditionString .= $key ." like " . "'%$value%'";
            if( $i < ( sizeof($conditionArray) - 1 )  )
            {
                $conditionString .=" and ";
            }
            $i++;
        }
        return $conditionString;
    }

    /*
     * test the functions
     */

    public function show()
    {
        //var_dump( $this->get_users()  );
        //var_dump( $this->get_user_by_id( 4 ));

        // $this->delete_User(array('UserId' => 2 ) );
        //$this->activate_user(array('UserId' => 2 ) );
        //var_dump( $this->activate_user( '2' ) );
        //var_dump( $this->login_user( 'aram' , '123' ) );
        //$this->insertUser(array('id'=>null , 'FirstName'=>'dede' , 'LastName'=>'sads' , 'UserName'=>'sddvdsf' , 'Password'=>'sdsdfsdv' , 'Email'=>'sferte','Telephone'=>'egfsdgf', 'CreationDate'=>date('Y-m-d h:i:s')));

        //$this->mergeArray( array("asd", "sdfsdfs") , array("234","3245234"));
        //$id =  $this->register_user( 'aramsa' , 'arsssam', 'rrrrrrr' , 'ttt' ,'4234234234' ,'345345345' , array( '0952258','08542145','0854512') );
        //var_dump( $this->register_user('adddddxxd','asdxxddas','qedddqwxxeqwe','098766','09876','qweqwe','1','2','3','add','1', array( '0952258','08542145','0854512')  ) );
        //var_dump( $this->delete_User_By_Id('4') );
        //$email = 'sd';        $this->register_user('asgfbhdad', 'asdasd' , 'asdasd' , '42525' , 'ftrgedfrth' , $email , '2');

        //var_dump( $this->login_user( 'aram' , '123' ) );
        //var_dump( $this->logout_user( 2 )) ;
        //var_dump( $this->login_user( 'aramii' , '123' ) );
        //var_dump( $this->login_user( 'aramy' , '123' ) );
        //var_dump( $this->edit_user_profile( 4, 0 ,'','','',''));
        //var_dump( $this>$this->set_user_profile('1', '', '2','3','5','address') );
        //var_dump( $this->set_user_groups( '4' , '1'));
        //var_dump( $this->set_user_roles( '4' , '1'));
        //var_dump( $this->get_user_roles( '4' ));
        //var_dump( $this->set_role( 'Role5' , 'Role5 Desc') );
        //$this->set_user_roles( '4' , '5' );
        //var_dump( $this->edit_role('4' , 'Role4' , ''));
        //var_dump( $this->get_group( 'gr' ));
        //$actid =  $this->set_action('action5' , 'action5 ');
        //var_dump($this->set_role_action('3' , '' ));
        //var_dump( $this->edit_group( '2' , 'sdfsdf', 'sdfsdfsdf' ));
        //$this->set_state('تهران');
        //$this->set_city( 'تهران' , '1');
        //var_dump( $this->get_city_by_id( '1' ) );
        //var_dump( $this->get_city_by_name('تهر'));
        //var_dump( $this->edit_city('1' , 'تهران' , '' ) );
        //var_dump( $this->delete_city( '1' ) );
        //var_dump( $this->set_role_group( '1' , '3'));
        //var_dump( $this->delete_role_group( '1' , '3'));
        // var_dump( $this->edit_user_password('2' , '097718'));

        //var_dump( $this->get_user_description('2') );
        //var_dump( $this->restore_user_by_id('1') );
        //var_dump( $this->edit_user_password('2', '987654321') );
        //var_dump( $this->get_last_edit_password('2') );
        $this->delete_user_by_id(1);
        var_dump( $this->get_all_deleted_users() );





    }


}


?>