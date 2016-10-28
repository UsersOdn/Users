<?php

namespace Model\Config;

Class Config
{

    private $config;

    public function __construct( )
    {
        // config is an array
        $this->config = array( "ServerName" => "localhost" , "Password" => "" , "UserName" => "root" , "Port" =>"3360" , "DBName" => "OdnoosUsers");
	//$this->config = array( "ServerName" => "localhost" , "Password" => "ZZzz12345" , "UserName" => "odnoospo_odnoos" , "Port" =>"3360" , "DBName" => "odnoospo_OdnoosUsers");


    }

    public function getConfig()
    {
        // return config array
        return $this->config;
    }

}
