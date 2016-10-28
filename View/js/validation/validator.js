/**
 * Created by hedi on 10/5/16.
 */

/*
 general validation : get a value and a Type , then check that
 */
function general_validation( boxid ,checkid , labelid , value , type ) {
    var mes="";
    switch (type )
    {
        //call check username
        case 'username':
            check_username( boxid ,checkid , labelid , value );
            break;
        //call check phone
        case 'phone':
            check_phone( boxid ,checkid , labelid , value );
            break;


        //call check password
        case 'password':
            check_password(boxid , checkid , labelid , value );
            break;

        //call check email
        case 'email':
            check_email(boxid ,checkid , labelid , value);
            break;

        //call check mobile number
        case 'mobile':
            check_mobile(boxid ,checkid , labelid , value );
            break;


        //call check area code
        case 'areaCode':
            check_areacode( boxid ,checkid , labelid , value );
            break;


        //call check home phone number
        case 'homePhone':
            check_home_phone(boxid ,checkid , labelid , value );
            break;


        //call check organization phone numbers
        case 'organizationPhone':
            check_organization_phone(boxid , checkid , labelid , value );
            break;

        //call check name
        case 'name':
            check_name(boxid , checkid , labelid , value );
            break;

        //call check address
        case 'address':
            check_address(boxid , checkid , labelid , value );
            break;

        //call check url
        case 'url':
            check_url( boxid ,checkid , labelid , value );
            break;


        //call check check_business_name
        case 'businessName':
            check_business_name( boxid ,checkid , labelid , value );
            break;

        //default
        default:

    }

}


/*
 check username function
 */

function check_username(boxid , checkid , labelid , value )
{
    if(value.length>50){
        document.getElementById(labelid).innerHTML="username must be less than 50 characters";
        document.getElementById(checkid).value= "false";
        setWarningFillForm( 'username_max_length_50' , 'fa' , labelid );
        document.getElementById(boxid).style.color="#FF0000";

    }
    else{
        //document.getElementById(labelid).innerHTML="OK";
        document.getElementById(checkid).value= "true";
        document.getElementById(boxid).style.color="#000000";


    }

}

/*
 check password
 */
function check_password(boxid , checkid , labelid , value )
{
    if(value != null){
        //check password length
        if((value.length < 8)){
            document.getElementById(labelid).innerHTML= '<span style="color:#FF0000"> password length should be more than 8 chars </span>';
            document.getElementById(checkid).value= "false";
            setWarningFillForm( 'password_min_length_8' , 'fa' , labelid );
            document.getElementById(boxid).style.color="#FF0000";

        }
        else if(value.length>20){
            document.getElementById(labelid).innerHTML= '<span style="color:#FF0000"> password length should be less than 20 chars </span>';
            document.getElementById(checkid).value= "false";
            setWarningFillForm( 'password_max_length_20' , 'fa' , labelid );
            document.getElementById(boxid).style.color="#FF0000";
        }
        else{
            document.getElementById(labelid).innerHTML="";
            document.getElementById(checkid).value= "true";
            document.getElementById(boxid).style.color="#000000";


        }
    }
    else{
        document.getElementById(checkid).value= "false";
        document.getElementById(labelid).innerHTML='<span style="color:#FF0000">please fill this textbox</span>';
        setWarningFillForm('please_fill_this_textbox' , 'fa' , labelid );
        document.getElementById(boxid).style.color="#FF0000";

    }

}
/*
 check password and repeat pasword
 */
function check_repeat_password( boxid ,checkid , labelid , password , repeat )
{
    if(password!=null){
        if(password === repeat){
            document.getElementById(labelid).innerHTML = "";
            document.getElementById(checkid).value= "true";
            document.getElementById(boxid).style.color="#000000";


        }
        else{
            document.getElementById(labelid).innerHTML='<span style="color:#FF0000"> Your repeat password not match the password </span>';
            document.getElementById(checkid).value= "false";
            setWarningFillForm('your_repeat_password_not_match_the_password' , 'fa' , labelid );
            document.getElementById(boxid).style.color="#FF0000";
        }
    }
    else{
        document.getElementById(checkid).value= "false";
        document.getElementById(labelid).innerHTML='<span style="color:#FF0000">please fill this textbox</span>';
        setWarningFillForm('please_fill_this_textbox' , 'fa' , labelid );
        document.getElementById(boxid).style.color="#FF0000";

    }
    //alert( password + "" + repeat);

}



/*
 check email
 */
function check_email( boxid ,checkid , labelid , value )
{
    // output message
    var message = "";

    // email pattern
    var pattern = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    // check email pattern is true or not
    var patternTest =  pattern.test( value );
    if( !patternTest ){
        message = "email_format_incorrect";
        document.getElementById(checkid).value= "false";
        document.getElementById(boxid).style.color="#FF0000";


    }

    // split email , splitter is @
    var splitValue = value.split("@");

    //is email has 2 part then
    if( splitValue.length == 2 )
    {
        // split email to username and host
        var username = splitValue[0];

        var host = splitValue[1];

        if( username.length > 60 )
        {
            message = "email_username_part_max_length";
            document.getElementById(checkid).value= "false";
            document.getElementById(boxid).style.color="#FF0000";

        }
        else if( host.length > 60 )
        {
            message = "email_username_part_max_length";
            document.getElementById(checkid).value= "false";
            document.getElementById(boxid).style.color="#FF0000";


        }
        else
        {
            document.getElementById(checkid).value= "true";
            message = "";
            document.getElementById(boxid).style.color="#000000";

        }


    }
    document.getElementById(labelid).innerHTML ='<span style="color:#FF0000">'+message+'</span>';
    setWarningFillForm(message , 'fa' , labelid );

}


/*
 check mobile number
 */
function check_mobile(boxid ,checkid , labelid , value )
{
    if( value.length != 0 ){
        var message = "";

        // check length of mobile number
        if( value.length != 11 ){
            message = "mobile_number_length";
            document.getElementById(checkid).value= "false";
            document.getElementById(boxid).style.color="#FF0000";

        }

        // check mobile number be at 09--------- format
        else if( value.charAt(0) != '0' || value.charAt(1) != '9' ){
            message = "mobile_format_incorrect";
            document.getElementById(checkid).value= "false";
            document.getElementById(boxid).style.color="#FF0000";


        }

        // check mobile number just be numeral
        else if( !value.match(/^[0-9]*$/) ){
            message = "input_can_be_just_numeric";
            document.getElementById(checkid).value= "false";
            document.getElementById(boxid).style.color="#FF0000";


        }

        else{
            document.getElementById(checkid).value= "true";
            message = "";
            document.getElementById(boxid).style.color="#000000";

        }

        document.getElementById(labelid).innerHTML ='<span style="color:#FF0000">'+message+'</span>';
        setWarningFillForm( message , 'fa' , labelid );
    }
    else{
        document.getElementById(checkid).value= "false";
        document.getElementById(labelid).innerHTML='<span style="color:#FF0000">please fill this textbox</span>';
        setWarningFillForm('please_fill_this_textbox' , 'fa' , labelid );
        document.getElementById(boxid).style.color="#FF0000";

    }

}
/*
 check mobile number
 */
function check_phone(boxid ,checkid , labelid , value )
{

    var message = "";

    if(value!=null){
        if(!value.match(/^[0-9]*$/ )){
            message = "input_can_be_just_numeric";
            document.getElementById(checkid).value= "false";
            document.getElementById(boxid).style.color="#FF0000";

        }

        else if( value.length > 11 ){
            message = "phone_length_max_11";
            document.getElementById(checkid).value= "false";
            document.getElementById(boxid).style.color="#FF0000";



        }
        else{
            document.getElementById(checkid).value= "true";
            message = "";
            document.getElementById(boxid).style.color="#000000";

        }

    }


    else{
        document.getElementById(checkid).value= "true";
        message = "";
        document.getElementById(boxid).style.color="#000000";

    }

    document.getElementById(labelid).innerHTML ='<span style="color:#FF0000">'+message+'</span>';
    setWarningFillForm( message , 'fa' , labelid );

}




/*
 check area code ( area pre code phone number )
 */
function check_areacode( boxid ,checkid , labelid , value )
{

    var message = "";

    // check length of area code number
    if( value.length != 3 ){
        message = "area_code_length_3";
        document.getElementById(checkid).value= "false";
        document.getElementById(boxid).style.color="#FF0000";

    }


    // check area code number be at 0-- format
    else if( value.charAt(0) != '0' || value.charAt(1) == '9' || value.charAt(2) == '0' ){
        message = "area_code_format_incorrect";
        document.getElementById(checkid).value= "false";
        document.getElementById(boxid).style.color="#FF0000";

    }

    // check area code number just be numeral
    else if( !value.match(/^[0-9]*$/) ){
        message = "input_can_be_just_numeric";
        document.getElementById(checkid).value= "false";
        document.getElementById(boxid).style.color="#FF0000";

    }

    else{
        document.getElementById(checkid).value= "true";
        message = "";
        document.getElementById(boxid).style.color="#000000";

    }

    document.getElementById(labelid).innerHTML='<span style="color:#FF0000">'+message+'</span>';
    setWarningFillForm( message , 'fa' , labelid );

}


/*
 check home phone
 */
function check_home_phone(boxid , checkid , labelid , value )
{

    var message = "";

    // check length of home phone number
    if( value.length != 8 ){
        message = "home_phone_length_max_8";
        document.getElementById(checkid).value= "false";
        document.getElementById(boxid).style.color="#FF0000";

    }

    // check home number cant be be at 0------- format
    else if( value.charAt(0) == '0'  ){
        message = "home_phone_format_incorrect";
        document.getElementById(checkid).value= "false";
        document.getElementById(boxid).style.color="#FF0000";

    }

    // check home number just be numeral
    else if( !value.match(/^[0-9]*$/) ){
        document.getElementById(checkid).value= "false";
        message = "input_can_be_just_numeric";
        document.getElementById(boxid).style.color="#FF0000";

    }

    else{
        document.getElementById(checkid).value= "true";
        message = "";
        document.getElementById(boxid).style.color="#000000";

    }

    document.getElementById(labelid).innerHTML='<span style="color:#FF0000">'+message+'</span>';
    setWarningFillForm( message , 'fa' , labelid );
}


/*
 check organization phone
 */
function check_organization_phone( boxid ,checkid , labelid , value )
{

    var message = "";

    // check length of organization phone number
    if( value.length < 4 || value.length > 11 ){
        document.getElementById(checkid).value= "false";
        message = "orgphone_phone_length_3_to_11";
        document.getElementById(boxid).style.color="#FF0000";
    }

    // check organization number cant be be at 0------- format
    else if( value.charAt(0) == '0'  ){
        document.getElementById(checkid).value= "false";
        message = "orgphone_phone_format_incorrect";
        document.getElementById(boxid).style.color="#FF0000";
    }

    // check organization number just be numeral
    else if( !value.match(/^[0-9]*$/) ){
        document.getElementById(checkid).value= "false";
        message = "input_can_be_just_numeric";
        document.getElementById(boxid).style.color="#FF0000";

    }

    else{
        document.getElementById(checkid).value= "true";
        document.getElementById(boxid).style.color="#000000";
        message = "";
    }
    document.getElementById(labelid).innerHTML='<span style="color:#FF0000">'+message+'</span>';
    setWarningFillForm( message , 'fa' , labelid );
}


/*
 check name
 */
function check_name( boxid ,checkid , labelid , value )
{
    if( value.length != 0 ){
        var message = "";

        if( value.length > 50 ){

            document.getElementById(checkid).value= "false";
            message = "name_max_length_50";
            document.getElementById(boxid).style.color="#FF0000";
        }
        else if( (value.match(/^[a-zA-Z ]*$/)) ) {//&& !(value.match(/^[\u0600-\u06EF ]*$/)) ) {

            document.getElementById(checkid).value= "true";
            message = "";
            document.getElementById(boxid).style.color="#000";
        }
        else if( (value.match(/^[\u0600-\u06EF ]*$/)) ) {//&& !(value.match(/^[\u0600-\u06EF ]*$/)) ) {

            document.getElementById(checkid).value= "true";
            message = "";
            document.getElementById(boxid).style.color="#000";
        }// \u0600-\u06FF is for persian chars

        else if ( (value.match(/^[a-zA-Z\u0600-\u06EF  ]*$/)) ) {
            document.getElementById(checkid).value= "false";
            message = "input_cant_be_mix_of_some_lang";
            document.getElementById(boxid).style.color="#f00";
        }
        else if ( (value.match(/^[a-zA-Z0-9\u0600-\u06FF  ]*$/)) ) {
            document.getElementById(checkid).value= "false";
            message = "input_cant_have_digits";
            document.getElementById(boxid).style.color="#f00";
        }
        else{

            document.getElementById(checkid).value= "true";
            message = "";
            document.getElementById(boxid).style.color="#000000";
        }

        document.getElementById(labelid).innerHTML='<span style="color:#FF0000">'+message+'</span>';
        setWarningFillForm( message , 'fa' , labelid );
    }
    else{
        //console.log('null first name' + checkid);
        document.getElementById(checkid).value= "false";
        document.getElementById(labelid).innerHTML='<span style="color:#FF0000">please fill this textbox</span>';
        setWarningFillForm('please_fill_this_textbox' , 'fa' , labelid );
        document.getElementById(boxid).style.color="#FF0000";

    }

}


/*
 check address
 */
function check_address( boxid ,checkid , labelid , value )
{
    var message = "";

    // check length of organization phone number
    if(  value.length > 1000 ){
        //document.getElementById("buttonid").disabled= true;
        document.getElementById(checkid).value= "false";
        message = "address_max_length_1000";
        document.getElementById(boxid).style.color="#FF0000";
    }
    // input chars with
    else if( value.match(/--/g) || value.match(/--;/g) || value.match(/-;/g)  ){
        document.getElementById(checkid).value= "false";
        message = "input_contain_unallowd_chars";
        document.getElementById(boxid).style.color="#FF0000";
    }
    else{
        document.getElementById(checkid).value= "true";
        document.getElementById(boxid).style.color="#000000";
        message = "";
    }


    document.getElementById(labelid).innerHTML='<span style="color:#FF0000">'+message+'</span>';
    setWarningFillForm( message , 'fa' , labelid );
}

/*
 check business name
 */
function  check_business_name( boxid ,checkid , labelid , value )
{
    if(value.length  <= 50){
        document.getElementById(labelid).innerHTML="";
        document.getElementById(checkid).value= "true";
        document.getElementById(boxid).style.color="#000000";
    }
    else{
        document.getElementById(labelid).innerHTML='<span style="color:#FF0000">Your business name should be less that 50 chars</span>';
        setWarningFillForm('business_name_max_length_50' , 'fa' , labelid );
        document.getElementById(boxid).style.color="#FF0000";
        document.getElementById(checkid).value= "false";
    }
}

/*
 check url
 */
function  check_url( boxid ,checkid , labelid , value )
{

    var urlregex = /^((https?|ftp|):\/\/|)([a-zA-Z0-9.-]+(:[a-zA-Z0-9.&%$-]+)*@)*((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9][0-9]?)(\.(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9])){3}|([a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]+\.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(:[0-9]+)*(\/($|[a-zA-Z0-9.,?'\\+&%$#=~_-]+))*$/;
    if(urlregex.test(value)){
        document.getElementById(labelid).innerHTML="";
        document.getElementById(checkid).value= "true";
        document.getElementById(boxid).style.color="#000000";

    }
    else{
        document.getElementById(labelid).innerHTML='<span style="color:#FF0000">URL has no correct type</span>';
        document.getElementById(boxid).style.color="#FF0000";
        setWarningFillForm('url_format_incorrect' , 'fa' , labelid );
        document.getElementById(checkid).value= "false";
    }

    //return this.optional(value) || /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(value);
    //return ( value.length  >= 50 ? "OK" : "Your business name should be less that 50 chars");
}

/*
 check date format - 4-8-95
 */
function check_date( date ) {
    // regular expression to match required date format
    var allowBlank = true;
    var minYear = 1300;
    var maxYear = (new Date()).getFullYear();

    var errorMsg = "";

    // regular expression to match required date format
    re = /^(\d{1,2})-(\d{1,2})-(\d{4})$/;

    if(date != '') {
        if(regs = date.match(re)) {
            if(regs[1] < 1 || regs[1] > 31) {
                errorMsg = "Invalid_value_for_day";// + regs[1];
            } else if(regs[2] < 1 || regs[2] > 12) {
                errorMsg = "Invalid_value_for_month;// " + regs[2];
            } else if(regs[3] < minYear || regs[3] > maxYear) {
                errorMsg = "Invalid_value_for_year";//: " + regs[3] + " - must be between " + minYear + " and " + maxYear;
            }
        } else {
            errorMsg = "Invalid_date_format";//: " + field;
        }
    } else if(!allowBlank) {
        errorMsg = "Empty_date_not_allowed";
    }

    if(errorMsg != "") {
        //alert(errorMsg);
        //prepare_message(errorMsg , 'fa' , 'wrn');
        setWarningFillForm( errorMsg , 'fa' , labelid );
        //field.focus();
        return false;
    }

    return true;
}