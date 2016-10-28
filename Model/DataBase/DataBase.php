<?php

namespace Model\DataBase;
use Model\Config;
use PDO;


class DataBase implements DatabaseAdapterInterface
{
    protected  $config = null;

    protected $_dbInstance = null;

    protected $_result = null ;

    public function __construct() {


    }

    /**
     * Fetch a single row from the current result set (as an associative array)
     */
    public function fetch()
    {

        if ($this->_result !== null) {
            if (($row = $this->_result->fetch(PDO::FETCH_ASSOC))=== false ) {
                $this->freeResult();

            }
            return $row;
        }
        return false;
    }


    /**
     * Free up the current result set
     */
    public function freeResult()
    {
        if ($this->_result === null) {
            return false;
        }
        //mysqli_free_result($this->_result);
        $this->_result->closeCursor();
        return true;
    }


    // create connection to database
    public function connect()
    {
        try {

            $this->config = (new Config\Config())->getConfig(); // load config file in config class

            // create pdo connection to DB by using config
            $this->_dbInstance = new \PDO('mysql:host=' . $this->config['ServerName'] . ';dbname=' . $this->config['DBName'], $this->config['UserName'], $this->config['Password'],array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

            // set attributes for this  connection
            $this->_dbInstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(\PDOException $e)
        {
            echo $e->getMessage();
        }
    }


    /**
     * Execute the specified query
     */
    public function query( $query )
    {
        if (!is_string($query) || empty($query)) {
            throw new \InvalidArgumentException('The specified query is not valid.');
        }
        // lazy connect to MySQL
        $this->connect();
        try
        {
            // prepare the query
            //echo "<br/>".$query."<br />";
            $this->_dbInstance->prepare("SET NAMES utf8");
            $this->_result = $this->_dbInstance->prepare($query);

            //execute query
            $this->_result->execute();


        }catch(\PDOException $e)
        {
            return "-1";
        }

        //$this->disconnect();
        //var_dump( $this->_result );
        return $this->_result;
    }

    /**
     * Perform a SELECT statement
     */
    public function select($table, $where = '', $fields = '*', $order = '', $limit = null, $offset = null)
    {
        $query = 'SELECT ' . $fields . ' FROM ' . $table
            . (($where) ? ' WHERE ' . $where : '')
            . (($limit) ? ' LIMIT ' . $limit : '')
            . (($offset && $limit) ? ' OFFSET ' . $offset : '')
            . (($order) ? ' ORDER BY ' . $order : '');
        $this->query($query);
        //echo "<br/>".$query."<br />";
        return $this->countRows();
    }

    /**
     * Perform an INSERT statement
     */
    public function insert($table, array $data)
    {
        $fields = implode(',', array_keys($data));
        $values = implode(',', array_map(array($this, 'quoteValue'), array_values($data)));
        $query = 'INSERT INTO ' . $table . ' (' . $fields . ') ' . ' VALUES (' . $values . ')';
        //echo $query;
        $a = $this->query($query);
        return ($a == "-1" ? "-1" : $this->getInsertId()) ;
    }

    /**
     * Perform an UPDATE statement
     */
    public function update($table, array $data, $where = '')
    {
        $set = array();
        foreach ($data as $field => $value) {
            $set[] = $field . '=' . $this->quoteValue($value);
            //echo $value;
        }
        $set = implode(',', $set);
        $query = 'UPDATE ' . $table . ' SET ' . $set
            . (($where) ? ' WHERE ' . $where : '');
        //echo "<br />".$query;
        $this->query($query);
        return $this->getAffectedRows();
    }

    /**
     * Perform a DELETE statement
     */
    public function delete($table, $where = '')
    {
        $query = 'DELETE FROM ' . $table
            . (($where) ? ' WHERE ' . $where : '');
        //echo $query;
        $this->query($query);
        return  $this->getAffectedRows();
    }


    /**
     * Get the number of rows returned by the current result set
     */
    public function countRows()
    {
        return $this->_result !== null ? $this->_result->rowCount() : 0;
    }

    /**
     * Get the number of affected rows
     */
    public function getAffectedRows()
    {
        return $this->_result !== null
            ? $this->_result->rowCount(): 0;
    }

    /**
     * Escape the specified value
     */
    public function quoteValue($value)
    {
        $this->connect();
        if ($value === null) {
            $value = 'NULL';
        }
        else if (is_string($value)) {
            //$value = "'" . mysqli_real_escape_string($this->_link, $value) . "'";
            $value = "'". $value ."'";
        }

        return $value;
    }


    /**
     * Get the insertion ID
     */
    public function getInsertId()
    {
        return $this->_result !== null
            ? $this->_dbInstance->lastInsertId() : 'msg:data not inserted';
    }


    public function disconnect()
    {
        if ($this->_dbInstance === null) {
            return false;
        }

        $this->_dbInstance = null;
       return true;
    }

    public function test()
    {
        //echo $this->select('Users')."<br />";
        //echo $this->delete('Users' , 'id = 2')."<br />";
        //echo $this->update('Users' , array('Email'=>'asdaad') , "Email = 'asdad'");
        //echo $this->insert('Users' , array( "id"=>Null , "UserName"=>"sdfdsxcvxfgwf" , "Password" =>"sdfsfd" , "FirstName"=> "sdfsdf" , "LastName"=>"rtytry" , "Email" => "asdafrsg" , "Telephone"=>"124145445", "CreationDate"=>date("Y-m-d h:i:s")));


    }


}

?>