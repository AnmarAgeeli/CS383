<?php
class orders{
    private $table;    
    private $orderNumber; //get
    private $orderDate; 
    private $requiredDate; //get
    private $shippedDate; 
    private $status; //get
    private $comments; 
    private $customerNumber;
    private $conn;
    public $s404 = "404";
    public $m404 = "Not found";
    public $s200 = "200";
    public $m200 = "Ok";

    public function __construct($conn){
        $this->table = "orders";
        $this->orderDate = "orderDate";
        $this->orderNumber = "orderNumber";
        $this->requiredDate = "requiredDate";
        $this->shippedDate = "shippedDate";
        $this->status = "status";
        $this->comments = "comments";
        $this->customerNumber = "customerNumber";
        $this->conn = $conn;
    }

    public function create($createString,$primaryKey){
        $query = "SELECT * FROM $this->table WHERE $this->orderNumber='$primaryKey'";
        $result = $this->conn->query($query);
        if(!($result->fetch(PDO::FETCH_ASSOC))){
            $query = "INSERT INTO $this->table $createString";
            $result = $this->conn->query($query);
            $this->jsonResponse($this->s200, "A new order has been added", null); 
        }else{
            $this->jsonResponse($this->s404, "orderNumber already exist", null);
        }
    }

    public function read($parameters){
        $records=[];
        $query=  "SELECT $this->orderNumber, $this->requiredDate, $this->status 
        FROM $this->table $parameters";
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

    public function update($updateString,$orderNumber){
        $query = "SELECT * FROM $this->table WHERE $this->orderNumber='$orderNumber'";
        $result = $this->conn->query($query);

        if($result->fetch(PDO::FETCH_ASSOC)){
            $query = "UPDATE $this->table $updateString WHERE $this->orderNumber='$orderNumber'";
            $result = $this->conn->query($query);
            $this->jsonResponse($this->s200, "Order has been Updated", null);            
        }else{
            $this->jsonResponse($this->s404, "orderNumber does not exist", null);
        }
    }

    public function delete($orderNumber){
        $query = "SELECT * FROM $this->table WHERE $this->orderNumber='$orderNumber'";
        $result = $this->conn->query($query);

        if($result->fetch(PDO::FETCH_ASSOC)){
            $query = "DELETE FROM $this->table WHERE $this->orderNumber='$orderNumber'";
            $result = $this->conn->query($query);
            $this->jsonResponse($this->s200, "Order has been deleted", null);            
        }else{
            $this->jsonResponse($this->s404, "orderNumber does not exist", null);
        }
    }

    public function jsonResponse($status, $message, $data ){
        header("HTTP/1.2 ".$message);
        header('Content-Type: application/json;');
        $response['status'] = $status;
        $response['message'] = $message;
        $response['data'] = $data;
        print_r(json_encode($response));
    }

    public function getOrderDate(){
        return $this->orderDate;
    }
    public function getOrderNumber(){
        return $this->orderNumber;
    }
    public function getShippedDate(){
        return $this->shippedDate;
    }
    public function getRequiredDate(){
        return $this->requiredDate;
    }
    public function getStatus(){
        return $this->status;
    }
    public function getComments(){
        return $this->comments;
    }
    public function getCustomerNumber(){
        return $this->customerNumber;
    }
}


?>