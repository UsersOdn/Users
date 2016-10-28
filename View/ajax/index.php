<?php
use \Model\DataBase as D;
use \Model\Access as A;
use \Model\Mapper as M;
use \control as C;


define('APPLICATION_PATH', realpath('../../'));


$paths = array(
    APPLICATION_PATH,
    get_include_path()
);
set_include_path(implode(PATH_SEPARATOR, $paths));

function __autoload($className)
{
    $filename = str_replace('\\', '/' , $className) . '.php';
    require_once  $filename;

}





class ajaxCall
{
    protected $act = '';
    protected $msg = '';

    protected  $ctrl = null;

    public function __construct()
    {
        // start the session if not started
        if( !isset( $_SESSION ) )
            session_start();
        $this->act = $_REQUEST['act'];
        $this->msg = new C\generate_message();
        $this->ctrl = new C\index();
    }



    //____________________________

    public function general_ajax()
    {



       switch ( $this->act )
        {
            case 'login':
                $this->login_ajax();
                break;

            case 'logout':
                $this->logout_ajax();
                break;
           case 'get_user_id':
               $this->get_user_data();
               break;

           case 'get_user_access':
               $this->get_user_access();
               break;
		   case 'show_user_profile':
               $this->show_user_profile();
               break;
           case 'get_users':
               $this->get_users();
               break;
           case 'get_user_profile':
               $this->get_user_profile();
               break;
           case 'get_all_cities_by_state_id':
               $this->get_all_cities_by_state_id();
               break;
           case 'get_all_regions_by_city_id':
               $this->get_all_regions_by_city_id();
               break;
           case 'admin_user_register':
               $this->admin_user_register();
               break;
           case 'edit_user_profile':
               $this->edit_user_profile();
               break;
           case 'edit_user':
               $this->edit_user();
               break;
			case 'load_menu':
               $this->load_menu();
               break;
           case 'get_monthly_statistic':
               $this->get_monthly_statistic();
               break;
           case 'get_all_states':
               $this->get_all_states();
               break;
           case 'get_weekly_statistic':
               $this->get_weekly_statistic();
               break;
           case 'get_monthly_statistic_jalali':
               $this->get_monthly_statistic_jalali();
               break;
           case 'get_weekly_statistic_jalali':
               $this->get_weekly_statistic_jalai();
               break;
           case 'get_user_group':
               $this->get_user_group();
               break;
           case 'get_all_groups':
               $this->get_all_groups();
               break;
           case 'set_user_group':
               $this->set_user_group();
               break;
           case 'set_user_role':
               $this->set_user_role();
               break;
           case 'delete_user':
               $this->delete_user();
               break;
           case 'admin_user_active':
               $this->admin_user_active();
               break;
           case 'get_all_remaining_groups':
               $this->get_all_remaining_groups();
               break;
           case 'get_all_remaining_roles':
               $this->get_all_remaining_roles();
               break;
           case 'delete_user_group':
               $this->delete_user_group();
               break;
           case 'get_user_role':
               $this->get_user_role();
               break;
           case 'delete_user_role':
               $this->delete_user_role();
               break;
           case 'get_role_by_id':
               $this->get_role_by_id();
               break;
           case 'get_all_roles':
               $this->get_all_roles();
               break;
           case 'delete_role':
               $this->delete_role();
               break;
           case 'set_role':
               $this->set_role();
               break;
           case 'get_group_by_id':
               $this->get_group_by_id();
               break;
           
           case 'delete_group':
               $this->delete_group();
               break;
           case 'set_group':
               $this->set_group();
               break;
           case 'get_role_action':
               $this->get_role_action();
               break;
           case 'set_role_action':
               $this->set_role_action();
               break;
           case 'delete_role_action':
               $this->delete_role_action();
               break;
           case 'get_all_remaining_actions':
               $this->get_all_remaining_actions();
               break;
           case 'get_all_remaining_users':
               $this->get_all_remaining_users();
               break;
           case 'get_all_users_have_this_role':
               $this->get_all_users_have_this_role();
               break;
           case 'get_all_users_have_this_group':
               $this->get_all_users_have_this_group();
               break;
           case 'set_group_role':
               $this->set_group_role();
               break;
           case 'delete_group_role':
               $this->delete_group_role();
               break;
           case 'get_group_role':
               $this->get_group_role();
               break;
           case 'get_all_remaining_roles_for_group':
               $this->get_all_remaining_roles_for_group();
               break;
           case 'search_users':
               $this->search_users();
               break;
            default:
                $this->msg->show('Your request is not valid');

        }
    }

    //____________________________ login with ajax
    protected function login_ajax()
    {

        if( !$this->check_captcha() )
        {
            $this->msg->show("Captcha_is_wrong" ,"Warning");
            return;
        }
        else{
            $this->ctrl->callfunction();
        }

    }
    ///______________________________________________
    protected function search_users(){
        $this->ctrl->callfunction();
    }
    ///______________________________________________
    protected function get_all_remaining_roles_for_group(){
        $this->ctrl->callfunction();
    }
    //___________________________________________set group role
    protected function set_group_role(){
        $this->ctrl->callfunction();
    }
    //___________________________________________delete group role
    protected function delete_group_role(){
        $this->ctrl->callfunction();
    }
    //___________________________________________get group role
    protected function get_group_role(){
        $this->ctrl->callfunction();
    }
    //___________________________________________get all user have this group
    protected function get_all_users_have_this_group(){
        $this->ctrl->callfunction();
    }
    //___________________________________________delete role action
    protected function delete_role_action(){
        $this->ctrl->callfunction();
    }
    //_______________________________________get all remaining actions
    protected function get_all_remaining_actions(){
        $this->ctrl->callfunction();
    }
    //_______________________________________get all remaining users
    protected function get_all_remaining_users(){
        $this->ctrl->callfunction();
    }
    //_______________________________________get all users have this role
    protected function get_all_users_have_this_role(){
        $this->ctrl->callfunction();
    }
    //_______________________________________set role action
    protected function set_role_action(){
        $this->ctrl->callfunction();
    }
    //_______________________________________get role action
    protected function get_role_action(){
        $this->ctrl->callfunction();
    }
    //_______________________________________set user role
    protected function set_user_role(){
        $this->ctrl->callfunction();
    }

    //_______________________________________get user role
    protected function get_user_role(){
        $this->ctrl->callfunction();
    }
    //_______________________________________delete user role
    protected function delete_user_role(){
        $this->ctrl->callfunction();
    }
        //_______________________________________delete_user_group
    protected function delete_user_group(){
        $this->ctrl->callfunction();
    }
    //_______________________________________admin user activate
    protected function admin_user_active(){
        $this->ctrl->callfunction();
    }
    //_______________________________________delete user
    protected function delete_user(){
        $this->ctrl->callfunction();
    }
    //_______________________________________set_user_group
    protected function set_user_group()
    {

        $this->ctrl->callfunction();
    }

    //_______________________________________get_all_remaining_roles
    protected function get_all_remaining_roles()
    {

        $this->ctrl->callfunction();
    }
    //_______________________________________get_all_remaining_groups
    protected function get_all_remaining_groups()
    {

        $this->ctrl->callfunction();
    }
    //_______________________________________get_all_groups
    protected function get_all_groups()
    {

        $this->ctrl->callfunction();
    }

    //______________________________________get_all_regions_by_city_id
    protected function get_all_regions_by_city_id()
    {

        $this->ctrl->callfunction();
    }
    //__________________________________get_all_cities_by_state_id
    protected function get_all_cities_by_state_id()
    {

        $this->ctrl->callfunction();
    }
    //____________________________________get_all_states
    protected function get_all_states()
    {

        $this->ctrl->callfunction();
    }
    //__________________________________admin user register
    protected function admin_user_register()
    {

        $this->ctrl->callfunction();
    }
    //__________________________________ edit user profile
    protected function edit_user_profile()
    {

        $this->ctrl->callfunction();
    }
    //__________________________________edit user
    protected function edit_user()
    {

        $this->ctrl->callfunction();
    }

    //______________________________ check captcha for login
    protected function check_captcha(){

        // get captha value that user inserted
        $captcha = $_REQUEST['captcha'];

        // get captcha value from session
        $captcha_code =  $_SESSION['captcha_code'];

        // compare two above values
        return ( $captcha ==  $captcha_code ? true : false );

    }

    //__________________________ logout user
    protected function logout_ajax()
    {
        //echo '{"act":"logout"}';

        //TODO: logout data

       $this->ctrl->callfunction();

    }
    //__________________________ get users
    protected function get_users()
    {
        //echo '{"act":"logout"}';

        //TODO: logout data

        $this->ctrl->callfunction();

    }
	
    //__________________________ get user profile
    protected function show_user_profile()
    {
        //echo '{"act":""}';

        //TODO: logout data

         $this->ctrl->callfunction();

    }


    //_____________________________get user profile information
    protected function get_user_profile()
    {
        //echo '{"act":""}';

        //TODO: logout data

        $this->ctrl->callfunction();

    }

    //_________________________ get user data
    protected function get_user_data()
    {

        //TODO: load user data
        echo '{"act":"logout"}';
        //$this->ctrl->callfunction();
    }
    //__________________________get user access
    protected function get_user_access()
    {

        //TODO: load user access

        $this->ctrl->callfunction();
    }

    //_________________________ set user profile
    protected function set_user_profile()
    {

        //TODO: set user profile

        $this->ctrl->callfunction();
    }

    //_________________________ reset_password
    protected function reset_password()
    {

        //TODO:reset password

        $this->ctrl->callfunction();
    }

    //_________________________ get user group
    protected function get_user_group()
    {

        //TODO: get user group

        $this->ctrl->callfunction();
    }


    //_________________________ get state
    protected function get_state()
    {

        //TODO: get state

    }
    //_________________________ get city
    protected function get_city()
    {

        //TODO: get city
    }

    //_________________________ get region
    protected function get_region()
    {

        //TODO: get region
    }


    //_________________________ register user
    protected function register_user()
    {

        //TODO: register user

        $this->ctrl->callfunction();
    }


    //_________________________ activate user
    protected function activate_user()
    {

        //TODO: activate user
    }

    //_________________________ activate user via sms
    protected function activate_user_via_sms()
    {

        //TODO: activate user via sms
    }

    //_________________________ deactivate user
    protected function deactivate_user()
    {

        //TODO: deactivate user
    }
	
	 protected function load_menu()
    {

        $this->ctrl->callfunction();
    }
    protected function get_monthly_statistic()
    {

        $this->ctrl->callfunction();
    }
    protected function get_weekly_statistic()
    {

        $this->ctrl->callfunction();
    }
    protected function get_monthly_statistic_jalali()
    {

        $this->ctrl->callfunction();
    }
    protected function get_weekly_statistic_jalai()
    {

        $this->ctrl->callfunction();
    }
    protected function get_role_by_id()
    {

        $this->ctrl->callfunction();
    }
    protected function get_all_roles()
    {

        $this->ctrl->callfunction();
    }
    protected function delete_role()
    {

        $this->ctrl->callfunction();
    }
    protected function set_role()
    {

        $this->ctrl->callfunction();
    }
    protected function get_group_by_id()
    {

        $this->ctrl->callfunction();
    }

    protected function delete_group()
    {

        $this->ctrl->callfunction();
    }
    protected function set_group()
    {

        $this->ctrl->callfunction();
    }
}


//___________________________________ create instance from ajax handler
$ajaxInstance = new ajaxCall();
$ajaxInstance->general_ajax();



?>

