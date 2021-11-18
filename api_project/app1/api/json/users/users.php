<?php
class users{
    private $table;
    private $name;
    private $id;
    private $conn;
    public $s404 = "404";
    public $m404 = "Not found";
    public $s200 = "200";
    public $m200 = "Ok";

    public function __construct($conn){
        $this->table = "users";
        $this->name = "name";
        $this->id = "id";
        $this->conn = $conn;
    }

    public function create($name,$id){
        $query = "SELECT * FROM $this->table WHERE $this->id=$id";
        $result = $this->conn->query($query);
        
        if(!($result->fetch(PDO::FETCH_ASSOC))){
            $query = "INSERT INTO $this->table ($this->id,$this->name) VALUES ('$id','$name')";
            $result = $this->conn->query($query);
            $this->jsonResponse($this->s200, "A new user has been created", null);            
        }else{
            $this->jsonResponse($this->s404, "Id already exist", null);
        }
    }

    public function read($parameters){
        $records=[];
        $query=  "SELECT * FROM $this->table $parameters";
        $result = $this->conn->query($query);
        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            $records[]=$row;
        }
        if(!$records){
            $this->jsonResponse($this->s404, $this->m404, null);
        }else{
            $this->jsonResponse($this->s200, $this->m200, $records);
        }
    }

    public function update($name,$id){
        $query = "SELECT * FROM $this->table WHERE $this->id=$id";
        $result = $this->conn->query($query);

        if($result->fetch(PDO::FETCH_ASSOC)){
            $query = "UPDATE $this->table SET $this->name='$name' WHERE $this->id=$id";
            $result = $this->conn->query($query);
            $this->jsonResponse($this->s200, "User has been Updated", null);            
        }else{
            $this->jsonResponse($this->s404, "Id does not exist", null);
        }
    }

    public function delete($id){
        $query = "SELECT * FROM $this->table WHERE $this->id=$id";
        $result = $this->conn->query($query);

        if($result->fetch(PDO::FETCH_ASSOC)){
            $query = "DELETE FROM $this->table WHERE $this->id=$id";
            $result = $this->conn->query($query);
            $this->jsonResponse($this->s200, "User has been deleted", null);            
        }else{
            $this->jsonResponse($this->s404, "Id does not exist", null);
        }
    }

    public function jsonResponse($status, $message, $data ){
        header("HTTP/1.2 ".$message);
        header('Content-Type: application/json;');
        $response['status'] = $status;
        $response['message'] = $message;
        $response['data'] = $data;
        print_r(json_encode($response));
        //json_encode($response);
    }


}


?>