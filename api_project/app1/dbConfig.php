<?php
class usersConfig{
    private $username;
    private $password;
    private $host;
    private $dbname;

    public function __construct($username){
        $this->username = $username;
        $this->password = '';
        $this->dbname = "users";
        $this->host = 'localhost';
    }
    public function connect(){
        return new PDO("mysql:host=".$this->host.";dbname=".$this->dbname,$this->username,$this->password);
    }


//if(!$conn) die ("DB ERROR:: cannot connect to the database ...");
}
 ?>