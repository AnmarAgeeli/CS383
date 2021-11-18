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
            $this->xmlResponse($this->s200, "A new user has been created", null);            
        }else{
            $this->xmlResponse($this->s404, "Id already exist", null);
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
            $this->xmlResponse($this->s404, $this->m404, null);
        }else{
            $this->xmlResponse($this->s200, $this->m200, $records);
        }
    }

    public function update($name,$id){
        $query = "SELECT * FROM $this->table WHERE $this->id=$id";
        $result = $this->conn->query($query);

        if($result->fetch(PDO::FETCH_ASSOC)){
            $query = "UPDATE $this->table SET $this->name='$name' WHERE $this->id=$id";
            $result = $this->conn->query($query);
            $this->xmlResponse($this->s200, "User has been Updated", null);            
        }else{
            $this->xmlResponse($this->s404, "Id does not exist", null);
        }
    }

    public function delete($id){
        $query = "SELECT * FROM $this->table WHERE $this->id=$id";
        $result = $this->conn->query($query);

        if($result->fetch(PDO::FETCH_ASSOC)){
            $query = "DELETE FROM $this->table WHERE $this->id=$id";
            $result = $this->conn->query($query);
            $this->xmlResponse($this->s200, "User has been deleted", null);            
        }else{
            $this->xmlResponse($this->s404, "Id does not exist", null);
        }
    }

    public function xmlResponse($status, $message, $data ){
        header("HTTP/1.2 ".$message);
        header('Content-Type: application/xml;');
        //$response['status'] = $status;
        //$response['message'] = $message;
        //$response['data'] = $data;
        

        $xml_encode = new DomDocument("1.0","UTF-8");
        $response = $xml_encode->createElement("head");
        $response->setAttribute("status", $status);
        $response->setAttribute("message", $message);
        $xml_encode->appendChild($response);
        
        if($data){
            $users= $xml_encode->createElement("users");
            for($i=0; $i<count($data); $i++){

                $user = $xml_encode->createElement("user");
                $id = $xml_encode->createElement("id",$data[$i][$this->id]);
                $name = $xml_encode->createElement("name",$data[$i][$this->name]);
                $user->appendChild($id);
                $user->appendChild($name);
                $users->appendChild($user);
            }
            $xml_encode->appendChild($users);
        }

        print_r($xml_encode->saveXML());
        //$xml_encode->appendChild($users);
        //print_r(phpinfo(8));
        //print_r(get_loaded_extensions());
    }


}


?>