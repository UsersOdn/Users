<?php
/**
 * Created by PhpStorm.
 * User: t.ahmadian
 * Date: 9/28/2016
 * Time: 11:03 AM
 */
namespace control;
//_______________include file
use \Model\DataBase as D;
use \Model\Access as A;
use \Model\Mapper as M;
use \control as C;
$paths = array(
    APPLICATION_PATH,
    get_include_path()
);
set_include_path(implode(PATH_SEPARATOR, $paths));
function __autoload($className)
{
    $filename = str_replace('\\', '/' , $className) . '.php';
    require_once $filename;
}
class index
{
    function callfunction()
    {
//_____________init message
        $msg = new C\generate_message();
        $valid = new C\DataValidation();
        $check = new C\check_input();
//______________check_action
        if (isset($_REQUEST['act']) && $_REQUEST['act'] != "" && $_REQUEST['act'] != null) {
            /*
             * *************************************************************************************************
             for this function must use from an array to check GeneralValidation output**************************
            ****************************************************************************************************
            */
            switch ($_REQUEST['act']) {
                /*
                 * if act is login
                */
                case 'login' :
                    $essentialArray = array("username" => true, "password" => true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {
                        $validParam = array("mobilenumber" => $_REQUEST['username'], "password" => $_REQUEST['password']);//inputs that should be check for data validity
                        if ($valid->GeneralValidation($validParam, $msg)) {
                            $access = new A\User();
                            /*
                             * call appropriate access level function(login_user)
                             */
                            $response = $access->login_user($validParam["mobilenumber"], $validParam["password"]);
                            //print_r($response);
                            $sent_param = array();//array that should be sent to view layer
                            if (is_array($response)) {
                                foreach ($response as $key => $val) {
                                    $sent_param = array($key => $val);
                                }
                                //echo "**********************************************************";
                                // print_r($sent_param);
                                /*
                                 * set session for user and return allowed actions
                                 */
                                if (!isset($_SESSION)) {
                                    session_start();
                                }
                                $_SESSION['actions'] = $access->get_actions_by_userid($sent_param[0]['UserId']);
                                $msg->get_data("login");//user information that must be returned
                                $_SESSION['user'] = $sent_param[0];
                                //var_dump($_SESSION['actions']);
                            } else {
                                $msg->show($response);
                            }
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;
                /*
                 * if act is logout
                */
                case 'logout' :

                    if (!isset($_SESSION['user'])) {
                        //session_start();
                        $msg->show("essential_parameters_not_set");
                        return;
                    }
                    //$essentialArray = array("id" => true);//array that show which one of the parameters is essential
                    //if ($check->checkEssential($essentialArray, $_REQUEST)) {
                    $validParam = array("id" => $_SESSION['user']['UserId']);//inputs that should be check for data validity
                    if ($valid->GeneralValidation($validParam, $msg)) {

                        $access = new A\User();
                        /*
                         * call appropriate access level function(logout_user)
                         */
                        if ($allowed = $access->check_user_access_to_action("logout", $_SESSION['actions'])) {
                            $response = $access->logout_user($validParam["id"]);
                            if ($response == 1) {
                                unset($_SESSION['actions']);
                                unset($_SESSION['user']);
                                session_destroy();
                                $msg->get_data("You_are_logged_out_successfully");
                            }
                        } else {
                            $msg->show("you_are_not_allowed_for_this_action");
                        }
                    }
                  //  else {
                    //    $msg->show("essential_parameters_not_set");
                   //}
                    break;
                case 'get_user_access' :
                    if (!isset($_SESSION)) {
                        session_start();
                    }
                    if( !isset($_SESSION['user']) )
                    {
                        $pm = array("act"=>"data","data"=>'null');
                        echo json_encode($pm);
                        return;
                    }

                    $pm = array("act"=>"data","data"=>$_SESSION['actions']);

                    echo json_encode($pm);
                    //$msg->get_data($_SESSION['actions']);
                    break;
                /*
                * if act is set user description
               */
                case 'set_user_description' :
                    $essentialArray = array("id" => true,"description"=>true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {
                        $validParam = array("id" => $_REQUEST['id'],"description"=>$_REQUEST['description']);//inputs that should be check for data validity
                        if ($valid->GeneralValidation($validParam, $msg)) {
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            $access = new A\User();
                            /*
                             * check user have access to this action
                             */
                            if ($allowed = $access->check_user_access_to_action("set_user_description", $_SESSION['actions'])) {
                                // user have access, then set the description
                                $response = $access->set_user_description($validParam["id"],$validParam["description"]);
                                if( $response == 1 ){
                                    $msg->show("set_description_was_successful");
                                }
                                else{
                                    $msg->show("set_description_was_unsuccessful");
                                }
                            }
                            else{
                                $msg->show("you_are_not_allowed_for_this_action");
                            }
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;
                /*
                 * if act is get last edit password
                */
                case 'get_last_edit_password' :
                    $essentialArray = array("id" => true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {
                        $validParam = array("id" => $_REQUEST['id']);//inputs that should be check for data validity
                        if ($valid->GeneralValidation($validParam, $msg)) {
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            $access = new A\User();
                            /*
                             * call appropriate access level function(logout_user)
                             */
                            if ($allowed = $access->check_user_access_to_action("get_last_edit_password", $_SESSION['actions'])) {
                                $response = $access->get_last_edit_password($validParam["id"]);
                                if(is_array($response)){
                                    $msg->get_data($response[0]['EditPasswordDate']);
                                }
                                else{
                                    $msg->show("password_retrieve_was_unsuccessful");
                                }
                            }
                            else{
                                $msg->show("you_are_not_allowed_for_this_action");
                            }
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;
                /*
                 * if act is get creation date
                */
                case 'get_creation_date' :
                    $essentialArray = array("id" => true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {
                        $validParam = array("id" => $_REQUEST['id']);//inputs that should be check for data validity
                        if ($valid->GeneralValidation($validParam, $msg)) {
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            $access = new A\User();
                            /*
                             * call appropriate access level function(logout_user)
                             */
                            if ($allowed = $access->check_user_access_to_action("get_creation_date", $_SESSION['actions'])) {
                                if(isset($_SESSION['user'])){
                                    $msg->get_data($_SESSION['user']['CreationDate']);
                                }
                            }
                            else{
                                $msg->show("you_are_not_allowed_for_this_action");
                            }
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;
                /*
                 * if act is get user created by
                */
                case 'get_user_create_by' :
                    $essentialArray = array("id" => true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {
                        $validParam = array("id" => $_REQUEST['id']);//inputs that should be check for data validity
                        if ($valid->GeneralValidation($validParam, $msg)) {
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            $access = new A\User();
                            /*
                             * call appropriate access level function(logout_user)
                             */
                            if ($allowed = $access->check_user_access_to_action("get_user_created_by", $_SESSION['actions'])) {
                                 $response = $access->get_user_create_by($validParam["id"]);
                                if(is_array($response)){
                                    $msg->get_data( $response[0]['CreateBy']);
                                }
                                else{
                                    $msg->show("creator_retrieve_was_unsuccessful");
                                }
                                //TODO: processing for get_user_created_by function in database layer
                            }
                            else{
                                $msg->show("you_are_not_allowed_for_this_action");
                            }
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;
                /*
                * if act is restore user
               */
                case 'restore_user' :
                    $essentialArray = array("id" => true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {
                        $validParam = array("id" => $_REQUEST['id']);//inputs that should be check for data validity
                        if ($valid->GeneralValidation($validParam, $msg)) {
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            $access = new A\User();
                            /*
                             * call appropriate access level function(logout_user)
                             */
                            if ($allowed = $access->check_user_access_to_action("restore_user", $_SESSION['actions'])) {
                                 $response = $access->restore_user_by_id($validParam["id"]);
                                if($response==1){
                                    $msg->show("restore_user_was_successful");
                                }
                                else{
                                    $msg->show("restore_user_was_unsuccessful");
                                }
                            }
                            else{
                                $msg->show("you_are_not_allowed_for_this_action");
                            }
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;
                /*
                * if act is get_user_profile
               */
                case 'get_user_profile' :
                    if( !isset( $_SESSION['user']) )
                    {
                        return;
                    }
                    $essentialArray = array("id" => true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {
                        $validParam = array("id" => $_REQUEST['id']);//inputs that should be check for data validity
                        if ($valid->GeneralValidation($validParam, $msg)) {
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            $access = new A\User();
                            /*
                             * check user is allowed
                             */
                            if ($allowed = $access->check_user_access_to_action("get_user_profile", $_SESSION['actions'])) {
                                //call appropriate access level function(get_user_profile)
                                $response = $access->get_user_by_id($validParam["id"]);
                                if(is_array($response)){

                                    // set what fields need to pass to view
                                    $needData =
                                        array('UserName', 'UserId' , 'FirstName' , 'LastName' , 'Email' , 'Telephone' , 'Gender' , 'Address'
                                         , 'StateName' , 'StateId' , 'CityName', 'CityId' , 'RegionName' , 'RegionId' , 'Description' , 'CreationDateJalali' ,
                                            'IsActive' , 'IsDelete'  ,'IsLogin' );




                                    $passdata = $this->set_pass_data( $response , $needData );
                                    if( $allowed = $access->check_user_access_to_action("edit_username", $_SESSION['actions']) )
                                    {
                                        //this user can edit mobile number ( username )
                                        //then add it t list
                                        $passdata['editmobile'] = 1;
                                    }
                                    else
                                    {
                                        $passdata['editmobile'] = 0;
                                    }
                                    //var_dump( $_SESSION['actions'] );
                                    //$msg->get_data($response);//user profile information that must be returned
                                    $msg->get_data($passdata);//user profile information that must be returned
                                }
                                else{
                                    $msg->show($response);
                                }
                            } else {
                                $msg->show("you_are_not_allowed_for_this_action");
                            }
                        } else {
                            $msg->show("all_of_input_parameters_are_not_valid");
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;
                /*
                 * for profile information in main page without need to user id because user is logged in
                 * this user is admin
                 */
                case 'show_user_profile' :
                    if (!isset($_SESSION)) {
                        session_start();
                    }
                    if( !isset($_SESSION['user']) )
                    {
                        $pm = array("act"=>"data","data"=>'null');
                        echo json_encode($pm);
                        return;
                    }
                   $msg->get_data($_SESSION['user']);
                    break;
                /*
                * if act is set_user_profile
               */
                case 'set_user_profile' :
                    $essentialArray = array("id" => true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {
                        /*
                         * Initialize optional parameters
                         */
                        $_REQUEST["email"] = (isset($_REQUEST["email"]) ? $_REQUEST["email"] : "");
                        $_REQUEST["state"] = (isset($_REQUEST["state"]) ? $_REQUEST["state"] : "");
                        $_REQUEST["city"] = (isset($_REQUEST["city"]) ? $_REQUEST["city"] : "");
                        $_REQUEST["region"] = (isset($_REQUEST["region"]) ? $_REQUEST["region"] : "");
                        $_REQUEST["address"] = (isset($_REQUEST["address"]) ? $_REQUEST["address"] : "");
                        $_REQUEST["gender"] = (isset($_REQUEST["gender"]) ? $_REQUEST["gender"] : "");
                        $validParam = array("id" => $_REQUEST['id'], "gender" => $_REQUEST['gender'], "state" => $_REQUEST['state'], "city" => $_REQUEST['city'], "reigon" => $_REQUEST['region'], "address" => $_REQUEST['address']);//inputs that should be check for data validity
                        if ($valid->GeneralValidation($validParam, $msg)) {
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            $access = new A\User();
                            /*
                           * check user is allowed
                           */
                            if ($allowed = $access->check_user_access_to_action("set_user_profile", $_SESSION['actions'])) {
                                //call appropriate access level function(set_user_profile)
                                if ($response = $access->set_user_profile($validParam["id"], $validParam["gender"], $validParam["state"], $validParam["city"], $validParam["reigon"], $validParam["address"])) {
                                    $msg->show("set user profile information was successful.");
                                } else {
                                    $msg->show("set user profile information was unsuccessful!");
                                }
                            } else {
                                $msg->show("you_are_not_allowed_for_this_action");
                            }
                        } else {
                            $msg->show("all of input parameters are not valid!");
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;
                /*
              * if act is set_user_group
             */
                case 'set_user_group' :
                    $essentialArray = array("id" => true, "groupId"=>true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {

                        $validParam = array("id" => $_REQUEST['id'], "groupId" => $_REQUEST['groupId'] );//inputs that should be check for data validity
                        if ($valid->GeneralValidation($validParam, $msg)) {
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            $access = new A\User();
                            /*
                           * check user is allowed
                           */
                            if ($allowed = $access->check_user_access_to_action("set_user_group", $_SESSION['actions'])) {
                                //call appropriate access level function(set_user_profile)
                                $response = $access->set_user_group($validParam["id"], $validParam["groupId"] );
                                if ($response>=0) {
                                    $msg->get_data($response);
                                } else {
                                    $msg->show("set user group was unsuccessful!");
                                }
                            } else {
                                $msg->show("you_are_not_allowed_for_this_action");
                            }
                        } else {
                            $msg->show("all of input parameters are not valid!");
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;
                    /*
             * if act is set_user_role
            */
                case 'set_user_role' :
                    $essentialArray = array("id" => true, "roleId"=>true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {

                        $validParam = array("id" => $_REQUEST['id'], "roleId" => $_REQUEST['roleId'] );//inputs that should be check for data validity
                        if ($valid->GeneralValidation($validParam, $msg)) {
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            $access = new A\User();
                            /*
                           * check user is allowed
                           */
                            if ($allowed = $access->check_user_access_to_action("set_user_role", $_SESSION['actions'])) {
                                //call appropriate access level function(set_user_profile)
                                $response = $access->set_user_role($validParam["id"], $validParam["roleId"] );
                                if ($response>=0) {
                                    $msg->get_data($response);
                                } else {
                                    $msg->show("set user role was unsuccessful!");
                                }
                            } else {
                                $msg->show("you_are_not_allowed_for_this_action");
                            }
                        } else {
                            $msg->show("all of input parameters are not valid!");
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;
                /*
                * if act is edit_user_profile
               */
                case 'edit_user_profile' : // edit aram 2-8-95
                    $essentialArray = array("id" => true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {
                        /*
                         * Initialize optional parameters
                         */

                        $access = new A\User();

                        $_REQUEST["mobilenumber"] = (isset($_REQUEST["mobilenumber"]) ? $_REQUEST["mobilenumber"] : "");
                        if( $_REQUEST["mobilenumber"] != "" )
                        {
                            //check access to edit username
                            $allowed = $access->check_user_access_to_action("edit_user_profile", $_SESSION['actions']);
                            if( !$allowed )
                            {
                                $msg->show("you_are_not_allowed_for_this_action");
                                return;
                            }
                        }

                        //flag to check update operation
                        $profileedited = false;
                        $useredited = false;

                        $_REQUEST["email"] = (isset($_REQUEST["email"]) ? $_REQUEST["email"] : "");
                        $_REQUEST["state"] = (isset($_REQUEST["state"]) ? $_REQUEST["state"] : "");
                        $_REQUEST["city"] = (isset($_REQUEST["city"]) ? $_REQUEST["city"] : "");
                        $_REQUEST["region"] = (isset($_REQUEST["region"]) ? $_REQUEST["region"] : "");
                        $_REQUEST["address"] = (isset($_REQUEST["address"]) ? $_REQUEST["address"] : "");
                        $_REQUEST["gender"] = (isset($_REQUEST["gender"]) ? $_REQUEST["gender"] : "");

                        $validParam = array("id" => $_REQUEST['id'], "gender" => $_REQUEST['gender'], "state" => $_REQUEST['state'],
                            "city" => $_REQUEST['city'], "reigon" => $_REQUEST['region'], "address" => $_REQUEST['address']);//inputs that should be check for data validity
                        if ($valid->GeneralValidation($validParam, $msg)) {
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            /*
                           * check user is allowed
                           */
                            if ($allowed = $access->check_user_access_to_action("edit_user_profile", $_SESSION['actions'])) {
                                //call appropriate access level function(set_user_profile)
                                if ($response = $access->edit_user_profile($validParam["id"], $validParam["gender"], $validParam["state"], $validParam["city"], $validParam["reigon"], $validParam["address"])) {
                                    //$msg->show("edit_user_profile_information_was_successful");
                                    $profileedited = true;

                                } else {
                                    //$msg->show("edit_user_profile_information_was_unsuccessful");
                                    //return;
                                    $profileedited = false;
                                }
                            } else {
                                $msg->show("you_are_not_allowed_for_this_action");
                                return;
                            }
                        } else {
                            $msg->show("all of input parameters are not valid!");
                            return;
                        }


                        $_REQUEST["firstname"] = (isset($_REQUEST["firstname"]) ? $_REQUEST["firstname"] : "");
                        $_REQUEST["lastname"] = (isset($_REQUEST["lastname"]) ? $_REQUEST["lastname"] : "");
                        $_REQUEST["email"] = (isset($_REQUEST["email"]) ? $_REQUEST["email"] : "");
                        $validParam = array("id" => $_REQUEST['id'], "firstname" => $_REQUEST['firstname'], "lastname" => $_REQUEST['lastname'], "email" => $_REQUEST['email'], "mobilenumber" => $_REQUEST['mobilenumber']);//inputs that should be check for data validity
                        if ($valid->GeneralValidation($validParam, $msg)) {
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            $access = new A\User();
                            /*
                          * check user is allowed
                          */
                            if ($allowed = $access->check_user_access_to_action("edit_user", $_SESSION['actions'])) {
                                //call appropriate access level function(deactivate_user)
                                if ($access->edit_user($validParam["id"], $validParam["firstname"], $validParam["lastname"], $validParam["email"], $validParam["mobilenumber"])) {
                                    //$msg->show("edit_user_essential_information_was_successful");
                                    $useredited = true;

                                } else {
                                    //$msg->show("edit_user_essential_information_was_unsuccessful");
                                    $useredited = false;
                                }
                            } else {
                                $msg->show("you_are_not_allowed_for_this_action");
                            }
                        } else {
                            $msg->show("all of input parameters are not valid!");
                        }

                        // show message
                        if( $profileedited && $useredited )
                        {
                            $msg->show("edit_user_essential_information_was_successful");

                        }
                        else if( $profileedited || $useredited )
                        {
                            $msg->show("edit_user_essential_information_was_successful");

                        }
                        else
                        {
                            $msg->show("edit_user_profile_information_was_unsuccessful");

                        }


                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;
                /*
                * if act is get_user_by_id
               */
                case 'get_user_by_id' :
                    $essentialArray = array("id" => true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {
                        $validParam = array("id" => $_REQUEST['id']);//inputs that should be check for data validity
                        if ($valid->GeneralValidation($validParam, $msg)) {
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            $access = new A\User();
                            /*
                           * check user is allowed
                           */
                            if ($allowed = $access->check_user_access_to_action("get_user_by_id", $_SESSION['actions'])) {
                                //call appropriate access level function(get_user_by_id)
                                $response = $access->get_user_by_id($validParam["id"]);
                                if(is_array($response)){
                                    $msg->get_data($response);//user profile information that must be returned
                                }
                                else{
                                    $msg->show($response);
                                }
                            } else {
                                $msg->show("you_are_not_allowed_for_this_action");
                            }
                        } else {
                            $msg->show("all of input parameters are not valid!");
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;
                /*
                * if act is get_user_role
               */
                case 'get_user_role' :
                    $essentialArray = array("id" => true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {
                        $validParam = array("id" => $_REQUEST['id']);//inputs that should be check for data validity
                        if ($valid->GeneralValidation($validParam, $msg)) {
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            $access = new A\User();
                            /*
                           * check user is allowed
                           */
                            if ($allowed = $access->check_user_access_to_action("get_user_role", $_SESSION['actions'])) {
                                //call appropriate access level function(get_user_role)
                                $response = $access->get_user_role($validParam["id"]);
                                if(is_array($response)){
                                    //print_r($response);
                                    $sent_param = array();//array that should be sent to view layer other parameters has been sifted
                                    foreach ($response as $key => $val) {
                                        $sent_param[] = array("RoleId"=>$val['RoleId'],"RoleName" => $val['RoleName']);
                                    }
                                    // echo "********************************************************** </br>";
                                    //print_r($sent_param);
                                    $msg->get_data($sent_param);//user profile information that must be returned
                                }
                                else{
                                    $msg->show($response);
                                }
                            } else {
                                $msg->show("you_are_not_allowed_for_this_action");
                            }
                        } else {
                            $msg->show("all of input parameters are not valid!");
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;
                 /*
                * if act is get_user_groups
               */
                case 'get_user_group' :
                    $essentialArray = array("id" => true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {
                        $validParam = array("id" => $_REQUEST['id']);//inputs that should be check for data validity
                        if ($valid->GeneralValidation($validParam, $msg)) {
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            $access = new A\User();
                            /*
                           * check user is allowed
                           */
                            if ($allowed = $access->check_user_access_to_action("get_user_group", $_SESSION['actions'])) {
                                //call appropriate access level function(get_user_group)
                                $response = $access->get_user_group($validParam["id"]);
                                if(is_array($response)){
                                    //print_r($response);
                                    $sent_param = array();//array that should be sent to view layer other parameters has been sifted
                                    foreach ($response as $key => $val) {
                                        $sent_param[] = array("GroupId"=>$val['GroupId'],"GroupName" => $val['GroupName']);
                                    }
                                    // echo "********************************************************** </br>";
                                    //print_r($sent_param);
                                    $msg->get_data($sent_param);//user profile information that must be returned
                                }
                                else{
                                    $msg->show($response);
                                }

                            } else {
                                $msg->show("you_are_not_allowed_for_this_action");
                            }
                        } else {
                            $msg->show("all of input parameters are not valid!");
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;
                /*
               * if act is get_role_action
              */
                case 'get_role_action' :
                    $essentialArray = array("id" => true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {
                        $validParam = array("id" => $_REQUEST['id']);//inputs that should be check for data validity
                        if ($valid->GeneralValidation($validParam, $msg)) {
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            $access = new A\User();
                            /*
                           * check user is allowed
                           */
                            if ($allowed = $access->check_user_access_to_action("get_role_action", $_SESSION['actions'])) {
                                //call appropriate access level function(get_role_action)
                                $response = $access->get_role_action($validParam["id"]);
                                if(is_array($response)){
                                    //print_r($response);
                                    $sent_param = array();//array that should be sent to view layer other parameters has been sifted
                                    foreach ($response as $key => $val) {
                                        $sent_param[] = array("id"=>$val['ActionId'],"ActionName" => $val['ActionName'],"Description" => $val['Description']);
                                    }
                                    // echo "********************************************************** </br>";
                                    //print_r($sent_param);
                                    $msg->get_data($sent_param);//user profile information that must be returned
                                }
                                else{
                                    $msg->show($response);
                                }

                            } else {
                                $msg->show("you_are_not_allowed_for_this_action");
                            }
                        } else {
                            $msg->show("all of input parameters are not valid!");
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;

                /*
             * if act is get_group_role
            */
                case 'get_group_role' :
                    $essentialArray = array("id" => true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {
                        $validParam = array("id" => $_REQUEST['id']);//inputs that should be check for data validity
                        if ($valid->GeneralValidation($validParam, $msg)) {
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            $access = new A\User();
                            /*
                           * check user is allowed
                           */
                            if ($allowed = $access->check_user_access_to_action("get_group_role", $_SESSION['actions'])) {
                                //call appropriate access level function(get_group_role)
                                $response = $access->get_group_role($validParam["id"]);
                                if(is_array($response)){
                                    //print_r($response);
                                    $sent_param = array();//array that should be sent to view layer other parameters has been sifted
                                    foreach ($response as $key => $val) {
                                        $sent_param[] = array("id"=>$val['RoleId'],"RoleName" => $val['RoleName'],"Description" => $val['Description']);
                                    }
                                    // echo "********************************************************** </br>";
                                    //print_r($sent_param);
                                    $msg->get_data($sent_param);//user profile information that must be returned
                                }
                                else{
                                    $msg->show($response);
                                }

                            } else {
                                $msg->show("you_are_not_allowed_for_this_action");
                            }
                        } else {
                            $msg->show("all of input parameters are not valid!");
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;
                /*
                * if act is site_user_register
               */
                case 'site_user_register' ://username is mobilenumber
                    $essentialArray = array("firstname" => true, "lastname" => true, "password" => true, "mobilenumber" => true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {
                        /*
                         * initializing optional parameters
                         */
                        $_REQUEST["email"] = (isset($_REQUEST["email"]) ? $_REQUEST["email"] : "");
                        $validParam = array("firstname" => $_REQUEST['firstname'], "lastname" => $_REQUEST['lastname'], "password" => $_REQUEST['password'], "mobilenumber" => $_REQUEST['mobilenumber'], "email" => $_REQUEST['email']);//inputs that should be check for data validity
                        if ($valid->GeneralValidation($validParam, $msg)) {
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            $access = new A\User();
                            /*
                          * check user is allowed
                          */
                            if ($allowed = $access->check_user_access_to_action("site_user_register", $_SESSION['actions'])) {
                                //call appropriate access level function(register_user)
                                $userId = $access->register_user($validParam["mobilenumber"], $validParam["firstname"], $validParam["lastname"], $validParam["password"], $validParam["mobilenumber"], $validParam["email"]);
                                if ($userId) {
                                    $msg->show("user registration was successful.");
                                } else {
                                    $msg->show("user registration was unsuccessful!");
                                }
                            } else {
                                $msg->show("you_are_not_allowed_for_this_action");
                            }
                        } else {
                            $msg->show("all of input parameters are not valid!");
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;
                /*
                * if act is get all states
               */
                case 'get_all_states' ://username is mobilenumber
                if (!isset($_SESSION)) {
                    session_start();
                }
                $access = new A\User();
                $response = $access->get_all_states();
                if ($response) {
                    // var_dump($response);
                    $msg->get_data($response);
                } else {
                    $msg->show("states load failed");
                }
                break;

                /*
              * if act is get all remaining groups except that he/she has
             */
                case 'get_all_remaining_groups' :


                    $essentialArray = array("id" => true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {
                        $validParam = array("id" => $_REQUEST['id']);//inputs that should be check for data validity
                        if ($valid->GeneralValidation($validParam, $msg)) {
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            $access = new A\User();
                            /*
                           * check user is allowed
                           */
                            if ($allowed = $access->check_user_access_to_action("get_all_remaining_groups", $_SESSION['actions'])) {
                                //call appropriate access level function(get_user_group)
                                $response = $access->get_all_remaining_groups($validParam["id"]);
                                if ($response) {
                                    // print_r($response);
                                    $msg->get_data($response);
                                } else {
                                    $msg->show("groups load failed");
                                }

                            } else {
                                $msg->show("you_are_not_allowed_for_this_action");
                            }
                        } else {
                            $msg->show("all of input parameters are not valid!");
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;
                /*
              * if act is get all remaining users except they that have this role
             */
                case 'get_all_remaining_users' :


                    $essentialArray = array("id" => true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {
                        $validParam = array("id" => $_REQUEST['id']);//inputs that should be check for data validity
                        if ($valid->GeneralValidation($validParam, $msg)) {
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            $access = new A\User();
                            /*
                           * check user is allowed
                           */
                            if ($allowed = $access->check_user_access_to_action("get_all_remaining_users", $_SESSION['actions'])) {
                                //call appropriate access level function(get_user_group)
                                $response = $access->get_all_remaining_users($validParam["id"]);
                                if ($response) {
                                    // print_r($response);
                                    $msg->get_data($response);
                                } else {
                                    $msg->show("users load failed");
                                }

                            } else {
                                $msg->show("you_are_not_allowed_for_this_action");
                            }
                        } else {
                            $msg->show("all of input parameters are not valid!");
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;

                /*
              * if act is get all remaining users except they that have this role
             */
                case 'get_all_users_have_this_role' :


                    $essentialArray = array("id" => true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {
                        $validParam = array("id" => $_REQUEST['id']);//inputs that should be check for data validity
                        if ($valid->GeneralValidation($validParam, $msg)) {
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            $access = new A\User();
                            /*
                           * check user is allowed
                           */
                            if ($allowed = $access->check_user_access_to_action("get_all_users_have_this_role", $_SESSION['actions'])) {
                                //call appropriate access level function(get_user_group)
                                $response = $access->get_all_users_have_this_role($validParam["id"]);
                                if ($response) {
                                    $sent_param = array();//array that should be sent to view layer other parameters has been sifted
                                    foreach ($response as $key => $val) {
                                        $sent_param[] = array("UserId"=>$val['UserId'],"FirstName" => $val['FirstName'], "LastName" => $val['LastName'],"mobileNumber"=>$val['Telephone'], "Email" => $val['Email']);
                                    }
                                    // echo "********************************************************** </br>";
                                   // print_r($response);
                                    $msg->get_data($sent_param);//user profile information that must be returned
                                } else {
                                    $msg->show("no user have this role");
                                }

                            } else {
                                $msg->show("you_are_not_allowed_for_this_action");
                            }
                        } else {
                            $msg->show("all of input parameters are not valid!");
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;

                /*
           * if act is get all remaining users except they that have this group
          */
                case 'get_all_users_have_this_group' :


                    $essentialArray = array("id" => true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {
                        $validParam = array("id" => $_REQUEST['id']);//inputs that should be check for data validity
                        if ($valid->GeneralValidation($validParam, $msg)) {
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            $access = new A\User();
                            /*
                           * check user is allowed
                           */
                            if ($allowed = $access->check_user_access_to_action("get_all_users_have_this_group", $_SESSION['actions'])) {
                                //call appropriate access level function(get_all_users_have_this_group)
                                $response = $access->get_all_users_have_this_group($validParam["id"]);
                                if ($response) {
                                    $sent_param = array();//array that should be sent to view layer other parameters has been sifted
                                    foreach ($response as $key => $val) {
                                        $sent_param[] = array("UserId"=>$val['UserId'],"FirstName" => $val['FirstName'], "LastName" => $val['LastName'],"mobileNumber"=>$val['Telephone'], "Email" => $val['Email']);
                                    }
                                    // echo "********************************************************** </br>";
                                    // print_r($response);
                                    $msg->get_data($sent_param);//user profile information that must be returned
                                } else {
                                    $msg->show("no user have this group");
                                }

                            } else {
                                $msg->show("you_are_not_allowed_for_this_action");
                            }
                        } else {
                            $msg->show("all of input parameters are not valid!");
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;
                /*
              * if act is get all remaining action except that role has
             */
                case 'get_all_remaining_actions' :


                    $essentialArray = array("id" => true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {
                        $validParam = array("id" => $_REQUEST['id']);//inputs that should be check for data validity
                        if ($valid->GeneralValidation($validParam, $msg)) {
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            $access = new A\User();
                            /*
                           * check user is allowed
                           */
                            if ($allowed = $access->check_user_access_to_action("get_all_remaining_actions", $_SESSION['actions'])) {
                                //call appropriate access level function(get_user_group)
                                $response = $access->get_all_remaining_actions($validParam["id"]);
                                if ($response) {

                                    $msg->get_data($response);
                                } else {
                                    $msg->show("actions load failed");
                                }

                            } else {
                                $msg->show("you_are_not_allowed_for_this_action");
                            }
                        } else {
                            $msg->show("all of input parameters are not valid!");
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;

                /*
              * if act is get all remaining groups except that he/she has
             */
                case 'get_all_remaining_roles' :


                    $essentialArray = array("id" => true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {
                        $validParam = array("id" => $_REQUEST['id']);//inputs that should be check for data validity
                        if ($valid->GeneralValidation($validParam, $msg)) {
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            $access = new A\User();
                            /*
                           * check user is allowed
                           */
                            if ($allowed = $access->check_user_access_to_action("get_all_remaining_roles", $_SESSION['actions'])) {
                                //call appropriate access level function(get_user_group)
                                $response = $access->get_all_remaining_roles($validParam["id"]);
                                if ($response) {
                                    $msg->get_data($response);
                                } else {
                                    $msg->show("roles load failed");
                                }

                            } else {
                                $msg->show("you_are_not_allowed_for_this_action");
                            }
                        } else {
                            $msg->show("all of input parameters are not valid!");
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;
                    /*
                   * if act is get all remaining roles  except its that belong to this group
                  */
                case 'get_all_remaining_roles_for_group' :


                    $essentialArray = array("id" => true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {
                        $validParam = array("id" => $_REQUEST['id']);//inputs that should be check for data validity
                        if ($valid->GeneralValidation($validParam, $msg)) {
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            $access = new A\User();
                            /*
                           * check user is allowed
                           */
                            if ($allowed = $access->check_user_access_to_action("get_all_remaining_roles_for_group", $_SESSION['actions'])) {
                                //call appropriate access level function(get_user_group)
                                $response = $access->get_all_remaining_roles_for_group($validParam["id"]);
                                if ($response) {
                                    $msg->get_data($response);
                                } else {
                                    $msg->show("roles load failed");
                                }

                            } else {
                                $msg->show("you_are_not_allowed_for_this_action");
                            }
                        } else {
                            $msg->show("all of input parameters are not valid!");
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;

                case 'get_all_cities_by_state_id':
                    $essentialArray = array("id" => true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {
                        /*
                         * initializing optinal parameters
                         */
                        $validParam = array("id" => $_REQUEST['id']);//inputs that should be check for data validity
                        if ($valid->GeneralValidation($validParam, $msg)) {
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            //call appropriate access level function(register_user)
                            $access = new A\User();
                            /*
                          * check user is allowed
                          */
                                //call appropriate access level function(register_user)
                                $response = $access->get_all_cities_by_state_id($validParam["id"]);
                            //var_dump($response);
                                if ($response) {
                                    $msg->get_data($response);
                                } else {
                                    $msg->show("load cities failed");
                                }

                        } else {
                            $msg->show("all of input parameters are not valid!");
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;
                case 'get_all_regions_by_city_id':
                    $essentialArray = array("id" => true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {
                        /*
                         * initializing optinal parameters
                         */
                        $validParam = array("id" => $_REQUEST['id']);//inputs that should be check for data validity
                        if ($valid->GeneralValidation($validParam, $msg)) {
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            //call appropriate access level function(register_user)
                            $access = new A\User();
                            /*
                          * check user is allowed
                          */
                            //call appropriate access level function(register_user)
                            $response = $access->get_all_regions_by_city_id($validParam["id"]);
                           // var_dump($response);
                            if ($response) {
                                $msg->get_data($response);
                            } else {
                                $msg->show("load regions failed");
                            }

                        } else {
                            $msg->show("all of input parameters are not valid!");
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;
                /*
                * if act is admin_user_register
               */
                case 'admin_user_register' ://username is mobilenumber

                    // check session login user
                    if( !isset($_SESSION['user'] ))
                    {
                        $msg->show('You should logged in to create account' );
                        return;
                    }

                    $essentialArray = array("firstname" => true, "lastname" => true, "password" => true, "mobilenumber" => true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {
                        /*
                         * initializing optinal parameters
                         */
                        $_REQUEST["email"] = (isset($_REQUEST["email"]) ? $_REQUEST["email"] : "");
                        $_REQUEST["state"] = (isset($_REQUEST["state"]) ? $_REQUEST["state"] : "");
                        $_REQUEST["city"] = (isset($_REQUEST["city"]) ? $_REQUEST["city"] : "");
                        $_REQUEST["region"] = (isset($_REQUEST["region"]) ? $_REQUEST["region"] : "");
                        $_REQUEST["address"] = (isset($_REQUEST["address"]) ? $_REQUEST["address"] : "");
                        $_REQUEST["gender"] = (isset($_REQUEST["gender"]) ? $_REQUEST["gender"] : "");
                        $_REQUEST["otherphone"] = (isset($_REQUEST["otherphone"]) ? $_REQUEST["otherphone"] : "");
                       // echo "gdfg".$_REQUEST["otherphone"];

                        // valid param check validity of the parameters
                        $validParam = array("firstname" => $_REQUEST['firstname'], "lastname" => $_REQUEST['lastname'],
                            "password" => $_REQUEST['password'], "mobilenumber" => $_REQUEST['mobilenumber'], "email" => $_REQUEST['email'],
                            "gender" => $_REQUEST['gender'], "address" => $_REQUEST['address'], "region" => $_REQUEST['region'], "city" => $_REQUEST['city'],
                            "state" => $_REQUEST['state']);//inputs that should be check for data validity

                        //_____________________
                        // check validity for other phone numbers
                        $otherphoneParam = array();

                        $otherphoneValidity = true;

                        if( $_REQUEST['otherphone'] != '' ) {
                            for ($i = 0; $i < sizeof($_REQUEST['otherphone']); $i++) {
                                //echo substr($_REQUEST['otherphone'][$i], 1 , 1 ). " ";
                                /*if (substr($_REQUEST['otherphone'][$i], 1, 1) == '9') {

                                    $otherphoneParam['mobilenumber'] = $_REQUEST['otherphone'][$i];
                                    $otherphoneValidity = $valid->GeneralValidation($otherphoneParam, $msg);
                                    if (!$otherphoneValidity) {
                                        return;
                                    }

                                } else if (substr($_REQUEST['otherphone'][$i], 1, 1) < '9' && substr($_REQUEST['otherphone'][$i], 1, 1) >= '1') {

                                    //check area code
                                    $areacode = substr($_REQUEST['otherphone'][$i], 0, 3);

                                    $otherphoneParam['areacode'] = $areacode;

                                    $otherphoneValidity = $valid->GeneralValidation($otherphoneParam, $msg);
                                    if (!$otherphoneValidity) {
                                        return;
                                    }

                                    $otherphoneParam = array();
                                    //check phone number
                                    $phonenumber = substr($_REQUEST['otherphone'][$i], 3, 8);
                                    $otherphoneParam['phonenumber'] = $phonenumber;

                                    $otherphoneValidity = $valid->GeneralValidation($otherphoneParam, $msg);
                                    if (!$otherphoneValidity) {
                                        return;
                                    }

                                }*/

                                $otherphoneParam['otherphone'] = $_REQUEST['otherphone'][$i];
                                $otherphoneValidity = $valid->GeneralValidation($otherphoneParam, $msg);
                                if (!$otherphoneValidity) {
                                    return;
                                }
                            }
                        }
                        else{
                            $_REQUEST['otherphone'] = null;
                        }
                           // echo "hhH";
                        //var_dump( $otherphoneParam );

                        //return ;
                        if ($valid->GeneralValidation($validParam, $msg) && $otherphoneValidity) {
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            //call appropriate access level function(register_user)
                            $access = new A\User();
                            /*
                          * check user is allowed
                          */
                            if ($allowed = $access->check_user_access_to_action("admin_user_register", $_SESSION['actions'])) {
                                //call appropriate access level function(register_user)
                                $userId = $access->register_user($validParam["mobilenumber"], $validParam["firstname"],
                                    $validParam["lastname"], $validParam["password"], $validParam["mobilenumber"], $validParam["email"],
                                    $validParam["state"], $validParam["city"], $validParam["region"], $validParam["address"], $validParam["gender"] , $_REQUEST['otherphone']  );
                                if ($userId) {
                                    $msg->show("user_registration_was_successful");
                                } else {
                                    $msg->show("user registration was unsuccessful!");
                                }
                            } else {
                                $msg->show("you_are_not_allowed_for_this_action");
                            }
                        } else {
                            $msg->show("all of input parameters are not valid!");
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;
                /*
                * if act is get_users
               */
                case 'get_users' ://no input is necessary
                    //call appropriate access level function(get_users)
                    if (!isset($_SESSION)) {
                        session_start();
                    }
                    else if( isset($_SESSION['user']) ){

                        $access = new A\User();

                        /*
                        * check user is allowed
                        */
                        if ($allowed = $access->check_user_access_to_action("get_users", $_SESSION['actions'])) {
                            //call appropriate access level function(register_user)
                            $response = $access->get_undeleted_users();
                            //print_r($response );
                            if(is_array($response)){
                                //print_r($response);
                                $sent_param = array();//array that should be sent to view layer other parameters has been sifted
                                foreach ($response as $key => $val) {
                                    $sent_param[] = array("UserId"=>$val['UserId'],"FirstName" => $val['FirstName'], "LastName" => $val['LastName'],"mobileNumber"=>$val['Telephone'], "Email" => $val['Email']);
                                }
                                // echo "********************************************************** </br>";
                                //print_r($sent_param);
                                $msg->get_data($sent_param);//user profile information that must be returned
                            }
                            else{
                                $msg->show($response);
                            }
                        } else {
                            $msg->show("you_are_not_allowed_for_this_action");
                        }
                    }
                    else
                    {
                        $pm = array("act"=>"message","text"=>'you_are_not_logged_in');
                        echo json_encode($pm);
                        return;
                    }

                    break;
                case 'search_users' :
                    //TODO: parameters not complete!
                    $validParam = array("firstname" => $_REQUEST['firstname'], "lastname" => $_REQUEST['lastname'],
                        "email" => $_REQUEST['email'], "gender" => $_REQUEST['gender'],
                        "state" => $_REQUEST['StateId'] , "city" => $_REQUEST['CityId'] ,"region" => $_REQUEST['RegionId'] ,
                        "address" => $_REQUEST['Address'] ,"islogin" => $_REQUEST['isLogin'] , "isdelete" => $_REQUEST['isDelete'] ,
                        "isActive" => $_REQUEST['isActive'] , "creator" => $_REQUEST['CreateBy'] , "startCreateDate" => $_REQUEST['startCreateDate'] ,
                        "endCreateDate" => $_REQUEST['endCreateDate'] ,"startLoginDate" => $_REQUEST['startLoginDate'] ,
                        "endLoginDate" => $_REQUEST['endLoginDate'] , "startEditPasswordDate" => $_REQUEST['startEditPasswordDate'] ,
                        "endEditPasswordDate" => $_REQUEST['endEditPasswordDate'] );//inputs that should be check for data validity
                   // $validParam = array();
                    if( $_REQUEST['mobilenumber'] != '' )
                    {
                        ($validParam['otherphone'] = $_REQUEST['mobilenumber']);
                    }
                    //print_r( $validParam );
                    if ($valid->GeneralValidation($validParam, $msg)) {
                        //call appropriate access level function(search_user)
                        //TODO: search_user code
                        if (!isset($_SESSION)) {
                            session_start();
                        }
                        $access = new A\User();
                        /*
                        * check user is allowed
                        */
                        if ($allowed = $access->check_user_access_to_action("search_users", $_SESSION['actions'])) {
                            //call appropriate access level function(activate_user)
                            $response = $access->search_users($validParam['firstname'] , $validParam['lastname'] , $validParam['email'] ,
                                ( isset( $validParam['otherphone'] ) ? $validParam['otherphone'] : '' ) , $validParam['state'] , $validParam['city'] , $validParam['region'] ,
                                $validParam['address'] , $validParam['gender'] , $validParam['islogin'] , $validParam['isdelete'],
                                $validParam['isActive'] , $validParam['creator'] , $validParam['startCreateDate'] , $validParam['endCreateDate'],
                                $validParam['startLoginDate'] , $validParam['endLoginDate'] , $validParam['startEditPasswordDate'],
                                $validParam['endEditPasswordDate']);
                            if( $response ){

                                $sent_param = array();//array that should be sent to view layer other parameters has been sifted
                                foreach ($response as $key => $val) {
                                    $sent_param[] = array("UserId"=>$val['UserId'],"FirstName" => $val['FirstName'], "LastName" => $val['LastName'],"mobileNumber"=>$val['Telephone'], "Email" => $val['Email']);
                                }
                                // echo "********************************************************** </br>";
                                //print_r($sent_param);
                                $msg->get_data($sent_param);//user profile information that must be returned
                            }
                            else{
                                $msg->show("The user is already active!");
                            }
                        } else {
                            $msg->show("you_are_not_allowed_for_this_action");
                        }

                    }
                    break;

                /*
                * if act is user_active1 (user activation)
               */
                case 'user_active' :
                    $essentialArray = array("id" => true, "activecode" => true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {
                        $validParam = array("id" => $_REQUEST['id'], "activecode" => $_REQUEST['activecode']);//inputs that should be check for data validity
                        if ($valid->GeneralValidation($validParam, $msg)) {
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            $access = new A\User();
                            /*
                            * check user is allowed
                            */
                            if ($allowed = $access->check_user_access_to_action("admin_user_active", $_SESSION['actions'])) {
                                //call appropriate access level function(activate_user)
                                //TODO: please correct bellow code
                                /*if($access->activate_user2($validParam["id"],($validParam["activecode"])){
                                    $msg->show("user activation was successful.");
                                }
                                else{
                                    $msg->show("The user is already active!");
                                }*/
                            } else {
                                $msg->show("you_are_not_allowed_for_this_action");
                            }
                        } else {
                            $msg->show("all of input parameters are not valid!");
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;
                /*
                * if act is user_active2 (adminstrator activation)
               */
                case 'admin_user_active' :
                    $essentialArray = array("id" => true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {
                        $validParam = array("id" => $_REQUEST['id']);//inputs that should be check for data validity
                        if ($valid->GeneralValidation($validParam, $msg)) {
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            $access = new A\User();
                            /*
                            * check user is allowed
                            */
                            if ($allowed = $access->check_user_access_to_action("admin_user_active", $_SESSION['actions'])) {
                                //call appropriate access level function(activate_user)
                                $response = $access->activate_user($validParam["id"]);
                                if ($response) {
                                    $msg->show("user activation was successful.");
                                } else {
                                    $msg->show("The user is already active!");
                                }
                            } else {
                                $msg->show("you_are_not_allowed_for_this_action");
                            }
                        } else {
                            $msg->show("all of input parameters are not valid!");
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;
                /*
                * if act is user_deactive
               */
                case 'user_deactive' :
                    $essentialArray = array("id" => true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {
                        $validParam = array("id" => $_REQUEST['id']);//inputs that should be check for data validity
                        if ($valid->GeneralValidation($validParam, $msg)) {
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            //call appropriate access level function(deactivate_user)
                            $access = new A\User();
                            /*
                            * check user is allowed
                            */
                            if ($allowed = $access->check_user_access_to_action("user_deactive", $_SESSION['actions'])) {
                                //call appropriate access level function(deactivate_user)
                                if ($access->deactivate_user($validParam["id"]) == 1) {
                                    $msg->show("user deactivation was successful.");
                                } else {
                                    $msg->show("user deactivation was unsuccessful!");
                                }
                            } else {
                                $msg->show("you_are_not_allowed_for_this_action");
                            }
                        } else {
                            $msg->show("all of input parameters are not valid!");
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;
                /*
                * if act is delete_user
               */
                case 'delete_user' :
                    $essentialArray = array("id" => true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {
                        $validParam = array("id" => $_REQUEST['id']);//inputs that should be check for data validity
                        if ($valid->GeneralValidation($validParam, $msg)) {
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            //call appropriate access level function(delete_user_by_id)
                            $access = new A\User();
                            /*
                           * check user is allowed
                           */
                            if ($allowed = $access->check_user_access_to_action("delete_user", $_SESSION['actions'])) {
                                //call appropriate access level function(deactivate_user)
                                $response=$access->delete_User_By_Id($validParam["id"]);
                                if ($response) {
                                    $msg->get_data($response);
                                } else {
                                    $msg->show("user is not exists!");
                                }
                            } else {
                                $msg->show("you_are_not_allowed_for_this_action");
                            }
                        } else {
                            $msg->show("all of input parameters are not valid!");
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;
                /*
               * if act is delete_user_group
              */
                case 'delete_user_group' :
                $essentialArray = array("id" => true, "groupid" => true);//array that show which one of the parameters is essential
                if ($check->checkEssential($essentialArray, $_REQUEST)) {
                    $validParam = array("id" => $_REQUEST['id'], "groupid" => $_REQUEST['groupid']);//inputs that should be check for data validity
                    if ($valid->GeneralValidation($validParam, $msg)) {
                        if (!isset($_SESSION)) {
                            session_start();
                        }
                        //call appropriate access level function(delete_user_by_id)
                        $access = new A\User();
                        /*
                       * check user is allowed
                       */
                        if ($allowed = $access->check_user_access_to_action("delete_user_group", $_SESSION['actions'])) {
                            //call appropriate access level function(deactivate_user)
                            $response = $access->delete_user_group($validParam["id"] , $validParam["groupid"] );
                            if ( $response==1){
                                $msg->get_data($response);
                            } else {
                                $msg->show("user is not exists!");
                            }
                        } else {
                            $msg->show("you_are_not_allowed_for_this_action");
                        }
                    } else {
                        $msg->show("all of input parameters are not valid!");
                    }
                } else {
                    $msg->show("essential_parameters_not_set");
                }
                break;
                /*
              * if act is delete_role_action
             */
                case 'delete_role_action' :
                    $essentialArray = array("id" => true,"actionid"=>true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {
                        $validParam = array("id" => $_REQUEST['id'],"actionid"=>$_REQUEST['actionid']);//inputs that should be check for data validity
                        if ($valid->GeneralValidation($validParam, $msg)) {
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            //call appropriate access level function(delete_user_by_id)
                            $access = new A\User();
                            /*
                           * check user is allowed
                           */
                            if ($allowed = $access->check_user_access_to_action("delete_role_action", $_SESSION['actions'])) {
                                //call appropriate access level function(deactivate_user)
                                $response = $access->delete_role_action($validParam["id"] ,$validParam["actionid"]  );
                                if ( $response==1){
                                    $msg->get_data($response);
                                } else {
                                    $msg->show("user is not exists!");
                                }
                            } else {
                                $msg->show("you_are_not_allowed_for_this_action");
                            }
                        } else {
                            $msg->show("all of input parameters are not valid!");
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;
                /*
             * if act is delete_group_role
            */
                case 'delete_group_role' :
                    $essentialArray = array("groupid" => true,"roleid"=>true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {
                        $validParam = array("groupid" => $_REQUEST['groupid'],"roleid"=>$_REQUEST['roleid']);//inputs that should be check for data validity
                        if ($valid->GeneralValidation($validParam, $msg)) {
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            //call appropriate access level function(delete_user_by_id)
                            $access = new A\User();
                            /*
                           * check user is allowed
                           */
                            if ($allowed = $access->check_user_access_to_action("delete_group_role", $_SESSION['actions'])) {
                                //call appropriate access level function(deactivate_user)
                                $response = $access->delete_role_group($validParam["roleid"] ,$validParam["groupid"]  );
                                if ( $response==1){
                                    $msg->get_data($response);
                                } else {
                                    $msg->show("user is not exists!");
                                }
                            } else {
                                $msg->show("you_are_not_allowed_for_this_action");
                            }
                        } else {
                            $msg->show("all of input parameters are not valid!");
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;
                case 'delete_user_role' :
                    $essentialArray = array("id" => true,"roleid"=>true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {
                        $validParam = array("id" => $_REQUEST['id'],"roleid" => $_REQUEST['roleid']);//inputs that should be check for data validity
                        if ($valid->GeneralValidation($validParam, $msg)) {
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            //call appropriate access level function(delete_user_by_id)
                            $access = new A\User();
                            /*
                           * check user is allowed
                           */
                            if ($allowed = $access->check_user_access_to_action("delete_user_group", $_SESSION['actions'])) {
                                //call appropriate access level function(deactivate_user)
                                $response = $access->delete_user_role($validParam["id"] , $validParam["roleid"] );
                                if ( $response==1){
                                    $msg->get_data($response);
                                } else {
                                    $msg->show("user is not exists!");
                                }
                            } else {
                                $msg->show("you_are_not_allowed_for_this_action");
                            }
                        } else {
                            $msg->show("all of input parameters are not valid!");
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;
                case 'edit_user' :

                    $essentialArray = array("id" => true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {
                        /*
                         * initializing optinal parameters
                         */
                        $_REQUEST["firstname"] = (isset($_REQUEST["firstname"]) ? $_REQUEST["firstname"] : "");
                        $_REQUEST["lastname"] = (isset($_REQUEST["lastname"]) ? $_REQUEST["lastname"] : "");
                        $_REQUEST["email"] = (isset($_REQUEST["email"]) ? $_REQUEST["email"] : "");
                        $_REQUEST["mobilenumber"] = (isset($_REQUEST["mobilenumber"]) ? $_REQUEST["mobilenumber"] : "");
                        $validParam = array("id" => $_REQUEST['id'], "firstname" => $_REQUEST['firstname'], "lastname" => $_REQUEST['lastname'], "email" => $_REQUEST['email'], "mobilenumber" => $_REQUEST['mobilenumber']);//inputs that should be check for data validity
                        if ($valid->GeneralValidation($validParam, $msg)) {
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            $access = new A\User();
                            /*
                          * check user is allowed
                          */
                            if ($allowed = $access->check_user_access_to_action("edit_user", $_SESSION['actions'])) {
                                //call appropriate access level function(deactivate_user)
                                if ($access->edit_user($validParam["id"], $validParam["firstname"], $validParam["lastname"], $validParam["email"], $validParam["mobilenumber"])) {
                                    $msg->show("edit_user_essential_information_was_successful");

                                } else {
                                    $msg->show("edit_user_essential_information_was_unsuccessful");
                                }
                            } else {
                                $msg->show("you_are_not_allowed_for_this_action");
                            }
                        } else {
                            $msg->show("all of input parameters are not valid!");
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;
                case 'edit_password' :
                    $essentialArray = array("id" => true, "password" => true, "confirmPassword" => true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {
                        if ($_REQUEST["password"] == $_REQUEST["confirmPassword"]) {
                            $validParam = array("id" => $_REQUEST['id'], "password" => $_REQUEST['password']);//inputs that should be check for data validity
                            if ($valid->GeneralValidation($validParam, $msg)) {
                                if (!isset($_SESSION)) {
                                    session_start();
                                }
                                $access = new A\User();
                                /*
                              * check user is allowed
                              */
                                if ($allowed = $access->check_user_access_to_action("edit_password", $_SESSION['actions'])) {
                                    //call appropriate access level function(edit_user_password)
                                    if ($access->edit_user_password($validParam["id"], $validParam["password"])) {
                                        $msg->show("edit password was successful.");
                                    } else {
                                        $msg->show("edit password was unsuccessful!");
                                    }
                                } else {
                                    $msg->show("you_are_not_allowed_for_this_action");
                                }
                            } else {
                                $msg->show("all of input parameters are not valid!");
                            }
                        } else {
                            $msg->show("password and confirm password must be equal!");
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;
                
                case 'edit_password' :
                    $essentialArray = array("id" => true, "password" => true, "confirmPassword" => true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {
                        if ($_REQUEST["password"] == $_REQUEST["confirmPassword"]) {
                            $validParam = array("id" => $_REQUEST['id'], "password" => $_REQUEST['password']);//inputs that should be check for data validity
                            if ($valid->GeneralValidation($validParam, $msg)) {
                                if (!isset($_SESSION)) {
                                    session_start();
                                }
                                $access = new A\User();
                                /*
                              * check user is allowed
                              */
                                if ($allowed = $access->check_user_access_to_action("edit_password", $_SESSION['actions'])) {
                                    //call appropriate access level function(edit_user_password)
                                    if ($access->edit_user_password($validParam["id"], $validParam["password"])) {
                                        $msg->show("edit password was successful.");
                                    } else {
                                        $msg->show("edit password was unsuccessful!");
                                    }
                                } else {
                                    $msg->show("you_are_not_allowed_for_this_action");
                                }
                            } else {
                                $msg->show("all of input parameters are not valid!");
                            }
                        } else {
                            $msg->show("password and confirm password must be equal!");
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;
                case 'load_menu':
                    if (!isset($_SESSION)) {
                        session_start();
                    }
                    if( !isset($_SESSION['user']) )
                    {
                        $pm = array("act"=>"data","data"=>'null');
                        echo json_encode($pm);
                        return;
                    }

                    $access = new A\User();
                    $response = $access->LoadMenu();


                    //$msg->get_data( $response );
                    // list of menu that user can see
                    $UserMenu = array();

                    // list of Menu ids that this user has not access to it (forbidden menu id list )
                    $forbiddenMenuId = array();


                    if( !isset($_SESSION['actions']) )
                    {
                        return ;
                    }

                    $actions = $_SESSION['actions'][0]['Actions'];

                    $actionList = explode(',' , $actions );
                    //var_dump( $actionList );

                    foreach ( $response as $MenuItem )
                    {
                        // check parent of this menu is forbid
                        if( in_array( $MenuItem['Parent'] , $forbiddenMenuId ) )
                        {
                            //add this id to forbid list
                            array_push( $forbiddenMenuId , $MenuItem['id'] );
                            continue;
                        }
                        // check if user have permission for this menu
                        if( !in_array( $MenuItem['ActionName'] , $actionList ) )
                        {
                            //user dose not have this access, add id for this menu to forbid list
                            array_push( $forbiddenMenuId , $MenuItem['id'] );
                            continue;
                        }
                        // add menu item data to UserMenu
                        array_push( $UserMenu , $MenuItem );


                    }

                    //$msg->get_data( $UserMenu );
                    // data that should show in view
                    $needData = array('Level', 'Parent'  , 'Name' , 'Url');
                    // array of menu that should pass to view
                    $passData = array();

                    // for each menu run set pass data to filter that menu for passing to view
                    foreach ( $UserMenu as $UserMenuItem ) {
                        array_push( $passData , $this->set_pass_data( $UserMenuItem , $needData ) );

                    }


                    $msg->get_data( $UserMenu );



                    break;

                case 'get_monthly_statistic':

                    $access = new A\User();
                    $response = $access->get_monthly_user_statistic();


                    $msg->get_data( $response );
                    break;

                case 'get_weekly_statistic':
                    $access = new A\User();
                    $response = $access->get_weekly_user_statistic();


                    $msg->get_data( $response );
                    break;
                case 'get_monthly_statistic_jalali':

                    $access = new A\User();
                    $response = $access->get_monthly_user_statistic_jalali();


                    $msg->get_data( $response );
                    break;

                case 'get_weekly_statistic_jalali':
                    $access = new A\User();
                    $response = $access->get_weekly_user_statistic_jalali();


                    $msg->get_data( $response );
                    break;

                /*
                 * case roles
                 */
                case 'get_all_roles':
                    //call appropriate access level function(get all roles)
                    if (!isset($_SESSION)) {
                        session_start();
                    }
                    else if( isset($_SESSION['user']) ){

                        $access = new A\User();
                        /*
                        * check user is allowed
                        */
                        if ($allowed = $access->check_user_access_to_action("get_all_roles", $_SESSION['actions'])) {
                            //call appropriate access level function(register_user)
                            $response = $access->get_all_role();
                            if(is_array($response)){
                                //print_r($response);
                                $sent_param = array();//array that should be sent to view layer other parameters has been sifted
                                foreach ($response as $key => $val) {
                                    $sent_param[] = array("RoleId"=>$val['id'],"RoleName" => $val['Name'], "Description" => $val['Description']);
                                }
                                // echo "********************************************************** </br>";
                                //print_r($sent_param);
                                $msg->get_data($sent_param);//user profile information that must be returned
                            }
                            else{
                                $msg->show($response);
                            }
                        } else {
                            $msg->show("you_are_not_allowed_for_this_action");
                        }
                    }

                    break;

                // get role by id
                case 'get_role_by_id':
                    //call appropriate access level function(get role by id)
                    if (!isset($_SESSION)) {
                        session_start();
                    }
                    else if( isset($_SESSION['user']) ){

                        $essentialArray = array("id" => true);//array that show which one of the parameters is essential
                        if ($check->checkEssential($essentialArray, $_REQUEST)) {
                            $validParam = array("id" => $_REQUEST['id']);//inputs that should be check for data validity
                            if ($valid->GeneralValidation($validParam, $msg)) {

                                $access = new A\User();
                                /*
                               * check user is allowed
                               */
                                if ($allowed = $access->check_user_access_to_action("get_role_by_id", $_SESSION['actions'])) {
                                    //call appropriate access level function(get_role_by_id)
                                    $response = $access->get_role($validParam["id"]);
                                    if(is_array($response)){
                                        $msg->get_data($response);//user profile information that must be returned
                                    }
                                    else{
                                        $msg->show($response);
                                    }
                                } else {
                                    $msg->show("you_are_not_allowed_for_this_action");
                                }
                            } else {
                                $msg->show("all of input parameters are not valid!");
                            }
                        } else {
                            $msg->show("essential_parameters_not_set");
                        }

                    }
                    break;

                case 'delete_role':
                    if (!isset($_SESSION)) {
                        session_start();
                    }
                    else if( isset($_SESSION['user']) ) {

                        $essentialArray = array("id" => true);//array that show which one of the parameters is essential
                        if ($check->checkEssential($essentialArray, $_REQUEST)) {
                            $validParam = array("id" => $_REQUEST['id']);//inputs that should be check for data validity
                            if ($valid->GeneralValidation($validParam, $msg)) {

                                //call appropriate access level function(delete_role_by_id)
                                $access = new A\User();
                                /*
                               * check user is allowed
                               */
                                if ($allowed = $access->check_user_access_to_action("delete_role", $_SESSION['actions'])) {
                                    //call appropriate access level function(deactivate_user)
                                    $response = $access->delete_role($validParam["id"]);
                                    //echo"iddddddddddd=";
                                    //print_r($validParam["id"]);
                                    //print_r( $response );
                                   // $msg->get_data($response);
                                    //return;
                                    if ($response) {
                                        $msg->get_data($response);
                                    } else {
                                        $msg->show("role does not exists!");
                                    }
                                } else {
                                    $msg->show("you_are_not_allowed_for_this_action");
                                }
                            } else {
                                $msg->show("all of input parameters are not valid!");
                            }
                        } else {
                            $msg->show("essential_parameters_not_set");
                        }
                    }

                    break;

                case 'set_role':
                    if (!isset($_SESSION)) {
                        session_start();
                    }
                    else if( isset($_SESSION['user']) ) {

                        $essentialArray = array("name" => true);//array that show which one of the parameters is essential
                        if ($check->checkEssential($essentialArray, $_REQUEST)) {
                            $validParam = array( "name" => $_REQUEST['name'] , "description" => $_REQUEST['description']);//inputs that should be check for data validity
                            if ($valid->GeneralValidation($validParam, $msg)) {

                                $access = new A\User();
                                /*
                                 * check user have access to this action
                                 */
                                if ($allowed = $access->check_user_access_to_action("set_role", $_SESSION['actions'])) {
                                    // user have access, then set the description
                                    $response = $access->set_role( $validParam["name"], $validParam["description"]);

                                    if ($response) {
                                        $msg->show("set_role_was_successful");
                                    } else {
                                        $msg->show("set_role_was_unsuccessful");
                                    }
                                } else {
                                    $msg->show("you_are_not_allowed_for_this_action");
                                }
                            }
                        } else {
                            $msg->show("essential_parameters_not_set");
                        }
                    }

                    break;
                /*
           * if act is set_user_role
          */
                case 'set_role_action' :
                    $essentialArray = array("roleid" => true, "actionid"=>true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {

                        $validParam = array("roleid" => $_REQUEST['roleid'], "actionid" => $_REQUEST['actionid'] );//inputs that should be check for data validity
                        if ($valid->GeneralValidation($validParam, $msg)) {
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            $access = new A\User();
                            /*
                           * check user is allowed
                           */
                            if ($allowed = $access->check_user_access_to_action("set_role_action", $_SESSION['actions'])) {
                                //call appropriate access level function(set_user_profile)
                                $response = $access->set_role_action($validParam["roleid"], $validParam["actionid"] );
                                if ($response>=0) {
                                    $msg->get_data($response);
                                } else {
                                    $msg->show("set user role was unsuccessful!");
                                }
                            } else {
                                $msg->show("you_are_not_allowed_for_this_action");
                            }
                        } else {
                            $msg->show("all of input parameters are not valid!");
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;
                /*
         * if act is set_group_role
        */
                case 'set_group_role' :
                    $essentialArray = array("groupid" => true, "roleid"=>true);//array that show which one of the parameters is essential
                    if ($check->checkEssential($essentialArray, $_REQUEST)) {

                        $validParam = array("groupid" => $_REQUEST['groupid'], "roleid" => $_REQUEST['roleid'] );//inputs that should be check for data validity
                        if ($valid->GeneralValidation($validParam, $msg)) {
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            $access = new A\User();
                            /*
                           * check user is allowed
                           */
                            if ($allowed = $access->check_user_access_to_action("set_group_role", $_SESSION['actions'])) {
                                //call appropriate access level function(set_user_profile)
                                $response = $access->set_role_group($validParam["roleid"], $validParam["groupid"] );
                                if ($response>=0) {
                                    $msg->get_data($response);
                                } else {
                                    $msg->show("set user role was unsuccessful!");
                                }
                            } else {
                                $msg->show("you_are_not_allowed_for_this_action");
                            }
                        } else {
                            $msg->show("all of input parameters are not valid!");
                        }
                    } else {
                        $msg->show("essential_parameters_not_set");
                    }
                    break;

                case 'set_group':
                    if (!isset($_SESSION)) {
                        session_start();
                    }
                    else if( isset($_SESSION['user']) ) {

                        $essentialArray = array("name" => true);//array that show which one of the parameters is essential
                        if ($check->checkEssential($essentialArray, $_REQUEST)) {
                            $validParam = array( "name" => $_REQUEST['name'] , "description" => $_REQUEST['description']);//inputs that should be check for data validity
                            if ($valid->GeneralValidation($validParam, $msg)) {

                                $access = new A\User();
                                /*
                                 * check user have access to this action
                                 */
                                if ($allowed = $access->check_user_access_to_action("set_group", $_SESSION['actions'])) {
                                    // user have access, then set the description
                                    $response = $access->set_group( $validParam["name"], $validParam["description"]);

                                    if ($response) {
                                        $msg->show("set_group_was_successful");
                                    } else {
                                        $msg->show("set_group_was_unsuccessful");
                                    }
                                } else {
                                    $msg->show("you_are_not_allowed_for_this_action");
                                }
                            }
                        } else {
                            $msg->show("essential_parameters_not_set");
                        }
                    }

                    break;


                /*
                * case roles
                */
                case 'get_all_groups':
                    //call appropriate access level function(get all roles)
                    if (!isset($_SESSION)) {
                        session_start();
                    }
                    else if( isset($_SESSION['user']) ){

                        $access = new A\User();
                        /*
                        * check user is allowed
                        */
                        if ($allowed = $access->check_user_access_to_action("get_all_groups", $_SESSION['actions'])) {
                            //call appropriate access level function(register_user)
                            $response = $access->get_all_groups();
                            if(is_array($response)){
                               // echo"@@@@@@@@@@@@@@@@@@@@@@@@@@";
                               // print_r($response);
                                $sent_param = array();//array that should be sent to view layer other parameters has been sifted
                                foreach ($response as $key => $val) {
                                    $sent_param[] = array("GroupId"=>$val['id'],"GroupName" => $val['GroupName'], "Description" => $val['Description']);
                                }
                                // echo "********************************************************** </br>";
                                //print_r($sent_param);
                                $msg->get_data($sent_param);//user profile information that must be returned
                            }
                            else{
                                $msg->show($response);
                            }
                        } else {
                            $msg->show("you_are_not_allowed_for_this_action");
                        }
                    }

                    break;

                // get role by id
                case 'get_group_by_id':
                    //call appropriate access level function(get role by id)
                    if (!isset($_SESSION)) {
                        session_start();
                    }
                    else if( isset($_SESSION['user']) ){

                        $essentialArray = array("id" => true);//array that show which one of the parameters is essential
                        if ($check->checkEssential($essentialArray, $_REQUEST)) {
                            $validParam = array("id" => $_REQUEST['id']);//inputs that should be check for data validity
                            if ($valid->GeneralValidation($validParam, $msg)) {

                                $access = new A\User();
                                /*
                               * check user is allowed
                               */
                                if ($allowed = $access->check_user_access_to_action("get_group_by_id", $_SESSION['actions'])) {
                                    //call appropriate access level function(get_role_by_id)
                                    $response = $access->get_group_by_id($validParam["id"]);
                                    if(is_array($response)){
                                        $msg->get_data($response);//user profile information that must be returned
                                    }
                                    else{
                                        $msg->show($response);
                                    }
                                } else {
                                    $msg->show("you_are_not_allowed_for_this_action");
                                }
                            } else {
                                $msg->show("all of input parameters are not valid!");
                            }
                        } else {
                            $msg->show("essential_parameters_not_set");
                        }

                    }
                    break;

                case 'delete_group':
                    if (!isset($_SESSION)) {
                        session_start();
                    }
                    else if( isset($_SESSION['user']) ) {

                        $essentialArray = array("id" => true);//array that show which one of the parameters is essential
                        if ($check->checkEssential($essentialArray, $_REQUEST)) {
                            $validParam = array("id" => $_REQUEST['id']);//inputs that should be check for data validity
                            if ($valid->GeneralValidation($validParam, $msg)) {

                                //call appropriate access level function(delete_role_by_id)
                                $access = new A\User();
                                /*
                               * check user is allowed
                               */
                                if ($allowed = $access->check_user_access_to_action("delete_group", $_SESSION['actions'])) {
                                    //call appropriate access level function(deactivate_user)
                                    $response = $access->delete_group($validParam["id"]);
                                    if ($response) {
                                        $msg->get_data($response);
                                    } else {
                                        $msg->show("group does not exists!");
                                    }
                                } else {
                                    $msg->show("you_are_not_allowed_for_this_action");
                                }
                            } else {
                                $msg->show("all of input parameters are not valid!");
                            }
                        } else {
                            $msg->show("essential_parameters_not_set");
                        }
                    }

                    break;
                /*
                * if act is not defined
               */
                default:
                    $msg->show("invalid data");
            }
        } else {
            $msg->show("Erorr 404 <br> action not set");
        }
    }

    /*
     * select just the data that need, and return it
     */

    protected  function set_pass_data( $orginalData , $needData )
    {
        $returnValues = array();


        $i = 0;
        foreach ( $orginalData as $key => $item) {

           //echo $key ."  " .$needData[$i];
            if (in_array($key, $needData))
            {

                $returnValues[$key] = $item;
            }

        }
        return $returnValues;

    }
}