<?php
class authentication{
    private $authKey;

    public function __construct($authKey){
        $this->authKey = $authKey;
    }

    public function authorization(){
        if($this->authKey==1){
            return "yavxse";
        }elseif($this->authKey==2){
            if($_SERVER['REQUEST_METHOD'] != 'GET'){
                die("Access denied for user. The operation requested exceeds user privileges. ");
            }
            return "faisal";
        }else{
            die("You don’t have authorization");
        }
    }
}




?>