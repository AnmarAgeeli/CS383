<?php
class payments{
    private $table;    
    private $checkNumber;
    private $customerNumber; //get
    private $paymentDate; //get
    private $amount; 
    private $conn;
    public $s404 = "404";
    public $m404 = "Not found";
    public $s200 = "200";
    public $m200 = "Ok";

    public function __construct($conn){
        $this->table = "payments";
        $this->customerNumber = "customerNumber";
        $this->checkNumber = "checkNumber";
        $this->paymentDate = "paymentDate";
        $this->amount = "amount";
        $this->conn = $conn;
    }

    public function create($createString,$primaryKey){
        $query = "SELECT * FROM $this->table WHERE $this->checkNumber='$primaryKey'";
        $result = $this->conn->query($query);
        if(!($result->fetch(PDO::FETCH_ASSOC))){
            $query = "INSERT INTO $this->table $createString";
            $result = $this->conn->query($query);
            $this->jsonResponse($this->s200, "A new payment has been added", null); 
        }else{
            $this->jsonResponse($this->s404, "checkNumber already exist", null);
        }
    }

    public function read($parameters){
        $records=[];
        $query=  "SELECT $this->checkNumber, $this->customerNumber, $this->paymentDate, $this->amount
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

    public function update($updateString,$checkNumber){
        $query = "SELECT * FROM $this->table WHERE $this->checkNumber='$checkNumber'";
        $result = $this->conn->query($query);

        if($result->fetch(PDO::FETCH_ASSOC)){
            $query = "UPDATE $this->table $updateString WHERE $this->checkNumber='$checkNumber'";
            $result = $this->conn->query($query);
            $this->jsonResponse($this->s200, "Payment has been Updated", null);            
        }else{
            $this->jsonResponse($this->s404, "checkNumber does not exist", null);
        }
    }

    public function delete($checkNumber){
        $query = "SELECT * FROM $this->table WHERE $this->checkNumber='$checkNumber'";
        $result = $this->conn->query($query);

        if($result->fetch(PDO::FETCH_ASSOC)){
            $query = "DELETE FROM $this->table WHERE $this->checkNumber='$checkNumber'";
            $result = $this->conn->query($query);
            $this->jsonResponse($this->s200, "Payment has been deleted", null);            
        }else{
            $this->jsonResponse($this->s404, "checkNumber does not exist", null);
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

    public function getcustomerNumber(){
        return $this->customerNumber;
    }
    public function getcheckNumber(){
        return $this->checkNumber;
    }
    public function getamount(){
        return $this->amount;
    }
    public function getpaymentDate(){
        return $this->paymentDate;
    }

}


?>