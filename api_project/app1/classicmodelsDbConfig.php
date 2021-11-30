<?php
class classicmodelsConfig{
    private $username;
    private $password;
    private $host;
    private $dbname;

    public function __construct($username){
        $this->username = $username;
        $this->password = '';
        $this->dbname = "classicmodels";
        $this->host = 'localhost';
    }
    public function connect(){
        return new PDO("mysql:host=".$this->host.";dbname=".$this->dbname,$this->username,$this->password);
    }
}
 ?>