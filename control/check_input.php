<?php
namespace control;
/**
 * Created by PhpStorm.
 * User: t.ahmadian
 * Date: 10/2/2016
 * Time: 11:25 AM
 */
class check_input {

    public function checkRequest ( $req )
    {
        /*if(isset( $req ) && $req != "" && $req != null ){
            return true;
        }
        else{
            return false;
        }*/

        return ( (isset( $req ) && $req != "" && $req != null ) ? true : false );
    }

    public function checkEssential($array , $input){
        $flag=true;
        global $msg;
        foreach($array as $key=>$val){
            if($this->checkRequest($input[$key])==false){
                $essentialArray[$key]=false;
                $msg->show($input[$key] ." lost" );
                $flag=false;
            }
        }
        if($flag==false)
            return false;
        return true;
    }


}