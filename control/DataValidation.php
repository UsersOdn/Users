<?php
namespace control;
/**
 * Created by PhpStorm.
 * User: t.ahmadian
 * Date: 9/28/2016
 * Time: 12:20 PM
 */
class DataValidation
{
    /*
     * this function gets an array and check validity of values for every keys
     */
    public function GeneralValidation($param,$msg){
        $output="true";
        foreach($param as $key=>$val)
        {
            switch ($key){
                case 'firstname':
                case 'description':
                case 'lastname':
                case 'name':
                    if($this->checkNameValidity($val,$msg)){
                    }
                    else{
                        $output="false";
                    }
                    break;
                case 'username':
                    if($this->checkUserNameValidity($val,$msg)){}
                    else{
                        $output="false";
                    }
                    break;
                case 'email':
                    if( $this->checkEmailValidity($val,$msg)){}
                    else{
                        $output="false";
                    }
                    break;
                case 'password':
                    if( $this->checkPasswordValidity($val,$msg)){}
                    else{
                        $output="false";
                    }
                    break;
                case 'gender':
                    if($this->checkGenderValidity($val,$msg)){}
                    else{
                        $output="false";
                    }
                    break;
                case 'url':
                    if($this->checkURLValidity($val,$msg)){}
                    else{
                        $output="false";
                    }
                    break;
                case 'population':
                    if($this->checkPopulationValidity($val,$msg)){}
                    else{
                        $output="false";
                    }
                    break;
                case 'mobilenumber':
                    if($this->checkMobileValidity($val,$msg)){}
                    else{
                        $output="false";
                    }
                    break;
                case 'phonenumber':
                    if($this->checkPhoneNumber1Validity($val,$msg)){}
                    else{
                        $output="false";
                    }
                    break;
                case 'otherphone':
                    if($this->checkOtherPhoneValidity($val,$msg)){}
                    else{
                        $output="false";
                    }
                    break;
                case 'areacode':
                    if($this->checkAreaCodeValidity($val,$msg)){}
                    else{
                        $output="false";
                    }
                    break;
                case 'address':
                    if($this->checkAddressValidity($val,$msg)){}
                    else{
                        $output="false";
                    }
                    break;
                case 'businessname':
                    if($this->checkBusinessNameValidity($val,$msg)){}
                    else{
                        $output="false";
                    }
                    break;
                case 'region':
                    if($this->checkRegionValidity($val,$msg)){}
                    else{
                        $output="false";
                    }
                    break;
                case 'city':
                    if($this->checkCityValidity($val,$msg)){}
                    else{
                        $output="false";
                    }
                    break;
                case 'state':
                    if($this->checkStateValidity($val,$msg)){}
                    else{
                        $output="false";
                    }
                    break;
                case 'id':
                case 'roleid':
                case 'groupid':
                case'actionid':
                    if($this->checkIdValidity($val,$msg)){}
                    else{
                        $output="false";
                    }
                    break;
                case 'category':
                    if($this->checkCategoryValidity($val,$msg)){}
                    else{
                        $output="false";
                    }
                    break;
            }
        }
        if($output=="true")
            return true;
        else
            return false;

    }

    /*
     * check password validity
     * password must be equals or biger than 8 characters
     * password is necessary always
     */
    public function checkPasswordValidity($pass,$msg){
        if(strlen(($pass))<8){
            $msg->show("your_password_is_small");
            return false;
        }
        return true;
    }

    /*
     * check gender validity
     * gender must be number, 0 or 1
     * gender is optional
     */
    public function checkGenderValidity($gender,$msg){
        if($gender==null)
            return true;
        if($gender==0 || $gender==1){
            return true;
        }
        else{
            $msg->show("your_gender_is_wrong");
            return false;
        }

    }


    /*
     * check URL validity
     * URL must be less than 1001 characters
     */
    public function checkURLValidity($url,$msg){
        if(strlen(($url))>1000){
            $msg->show("your_url_address_is_very_long");
            return false;
        }
        return true;
    }

    /*
     * check population validity
     * population must be number with at last 7 digit.
     */
    public function checkPopulationValidity($pop,$msg){
        if(!is_numeric($pop)){
            $msg->show("variable_that_present_population_is_not_a_number");
            return false;
        }
        $length = ceil(log10($pop));
        if($length>7){
            $msg->show("population_number_is_very_big");
            return false;
        }
        return true;
    }



    /*
     * check business name validity
     * business name must be combination of numbers and letters and space
     *
     */
    public function checkBusinessNameValidity($businessname,$msg){
        if(strlen($businessname)>50){
            $msg->show("your_business_name_is_very_long");
            return false;
        }
        else{
            //for($i=0;$i<50;$i++){
                //$chr=substr($businessname,$i,1);
                //if(ord($chr<48)||ord($chr>122)||((ord($chr)>57)&&(ord($chr)<65))||((ord($chr)>90)&&(ord($chr)<97))){
                 //   $msg->show("your business name must have alphabet and number characters!");
                 //   return false;
               // }
            if (!preg_match("/^[a-zA-Z0-9 ]*$/",$businessname)) {
                $msg->show("your_business_name_must_have_alphabet_and_number_characters");
                return false;
            }
        }
            return true;

    }


    /*
     * check region validity
     * region must be a number
     * region is optional
     */
    public function checkRegionValidity($region,$msg){
        if($region==null)
            return true;
        if(is_numeric($region)){
            return true;
        }
        else{
            $msg = new generate_message();
            $msg->show("region_value_must_be_number");
            return false;
        }
    }

    /*
     * check city validity
     * city must be a number
     * city is optional
     */
    public function checkCityValidity($city,$msg){
        if($city==null)
            return true;
        if(is_numeric($city)){
            return true;
        }
        else{
            $msg = new generate_message();
            $msg->show("city_value_must_be_number");
            return false;
        }
    }

    /*
     * check state validity
     * state must be a number
     * state is optional
     */
    public function checkStateValidity($state,$msg){
        if($state==null)
            return true;
        if(is_numeric($state)){
            return true;
        }
        else{
            $msg = new generate_message();
            $msg->show("state_value_must_be_number");
            return false;
        }
    }


    /*
     * check category validity
     * category must be a number
     */
    public function checkCategoryValidity($category,$msg){
        if(is_numeric($category)){
            return true;
        }
        else{
            $msg = new generate_message();
            $msg->show("category_value_must_be_number");
            return false;
        }

    }
    /*
     * check username validity
     * username must be combination of letters and space
     *
     *
     */
    public function checkUserNameValidity($username,$msg){
        //echo "check username </br>";
        if(strlen($username)>50){
            $msg->show("your_name_is_very_long");
            //echo "false </br>";
            return false;
        }
        else{
            if (!preg_match("/^[a-zA-Z ]*$/",$username)) {
                $msg->show("for_name_Only_letters_and_white_space_allowed");
                echo "false </br>";
                return false;
            }
            //echo "true </br>";
            return true;
        }

    }

    /*
     * check name validity
     */
    public function checkNameValidity($name,$msg){
        if(strlen($name)>50){
            $msg->show("your_name_is_very_long");
            return false;
        }
        else{
            if (!preg_match("/^[a-zA-Z ]*$/",$name) && !preg_match('/^[\s\x{0621}-\x{063A}\x{0640}-\x{0691}\x{0698}-\x{06D2}0-9]*$/u', $name)) {
            //if (!preg_match("/^[a-zA-Z\x{0080}-\x{024F}\s\/\-\)\(\`\.\]+$/u",$name) ) {
                $msg->show("for_name_Only_letters_and_white_space_allowed");
                return false;
            }
            return true;
        }

    }
    /*
     * check area code validity - 3digits like 021
     */
    public function checkAreaCodeValidity($phone,$msg){
        if(is_numeric(substr($phone,0,3))){
            if (strlen($phone) == 3) {
                if (substr($phone, 0, 1) != '0') {
                    $msg = new generate_message();
                    $msg->show("area_code_must_start_with_0_digit");
                    return false;
                }
                else if (substr($phone, 1, 1) == '1') {
                    $msg = new generate_message();
                    $msg->show("second_digit_in_area_code_can_not_be_1");
                    return false;
                }
            } else {
                $msg = new generate_message();
                $msg->show("digits_number_of_area_code_must_be_3");
                return false;
            }
        }
        else{
            $msg = new generate_message();
            $msg->show("area_code_must_have_just_digit_characters");
            return false;
        }
        return true;

    }
    /*
     * check email validity
     */
    public function checkEmailValidity($email,$msg){
        if($email==null)
            return true;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $msg->show("invalid_email_format");
            return false;
        }
        return true;

    }
    /*
     * check address validity
     */
    public function checkAddressValidity($address,$msg){
        if($address==null)
            return true;
        $arr1 = str_split($address);
        foreach($arr1 as $key=>$val){
            if($val=='@'||$val=='%'||$val=='_'||$val=='\''||$val=='"'||$val==';'||$val==':'){
                $msg->show("address_include_illegal_special_characters");
                return false;
            }
        }
        return true;
    }
    /*
     * check phone number validity(home phone)(only 8 characters)
     */
    public function checkPhoneNumber1Validity($phone,$msg){
        if(is_numeric(substr($phone,0,8))){
            if (strlen($phone) == 8) {
                if (substr($phone, 0, 1) == '0' || substr($phone, 0, 1) == '1') {
                    $msg = new generate_message();
                    $msg->show("phone_number_should_not_start_with_0_or_1");
                    return false;
                }
            } else {
                $msg = new generate_message();
                $msg->show("length_of_phonenumber_must_be_8");
                return false;
            }
        }
        else{
            $msg = new generate_message();
            $msg->show("phone_number_must_have_digit_characters");
            return false;
        }
        return true;

    }

    /*
    * check phone number validity(home phone)(only 8 characters)
    */
    public function checkOtherPhoneValidity($phone,$msg){
        if(is_numeric($phone)){
            if ( strlen($phone) > 11 ) {

                    $msg = new generate_message();
                    $msg->show("other_phone_number_length_is_not_valid");
                    return false;

            }
        }
        else{
            $msg = new generate_message();
            $msg->show("phone_number_must_have_digit_characters");
            return false;
        }
        return true;

    }
    /*
     * check phone number validity(home number)(special numbers)(between 3 - 8 characters) for organizations
     */
    public function checkPhoneNumber2Validity($phone,$msg){
        if(is_numeric(substr($phone,0,strlen($phone)))){
            if ((strlen($phone) >=3) && (strlen($phone) <=8 ) ) {
                if (substr($phone, 0, 1) == '0') {
                    $msg = new generate_message();
                    $msg->show("phone_number_should_not_start_with_0");
                    return false;
                }
            } else {
                $msg = new generate_message();
                $msg->show("digit_numbers_of_phonenumber_must_be_between_3_and_8");
                return false;
            }
        }
        else{
            $msg = new generate_message();
            $msg->show("phone_number_must_have_digit_characters");
            return false;
        }
        return true;

    }
    /*
     * check mobile validity
     */
    public function checkMobileValidity($phone,$msg){
        if($phone==""){
            return true;
        }
        if(is_numeric(substr($phone,1,10))){
            if (strlen($phone) == 11) {
                if (substr($phone, 0, 2) != '09') {
                    $msg = new generate_message();
                    $msg->show("prefix_of_phone_number_is_wrong");
                    return false;
                }
            } else {
                $msg = new generate_message();
                $msg->show("digits_number_of_phone_number_must_be_11");
                return false;
            }
        }
        else{
            $msg = new generate_message();
            $msg->show("phone_number_must_have_digit_characters");
            return false;
            }
            return true;
    }
    /*
     * check id validity
     */
    public function checkIdValidity($id,$msg){
        if(is_numeric($id)){
            return true;
        }
        else{
            $msg->show("id_must_be_a_number");
            return false;
        }
    }
}