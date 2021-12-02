<?php
class customers{
    private $table;
    private $customerNumber;
    private $customerName; //get
    private $contactLastName; 
    private $contactFirstName; 
    private $phone; //get
    private $addressLine1;
    private $addressLine2;
    private $city; //get
    private $state; 
    private $postalCode;
    private $country; //get
    private $salesRepEmployeeNumber;
    private $creditLimit; //get
    private $conn;
    public $s404 = "404";
    public $m404 = "Not found";
    public $s200 = "200";
    public $m200 = "Ok";

    public function __construct($conn){
        $this->table = "customers";
        $this->customerName = "customerName";
        $this->customerNumber = "customerNumber";
        $this->contactLastName = "contactLastName";
        $this->contactFirstName = "contactFirstName";
        $this->phone = "phone";
        $this->addressLine1 = "addressLine1";
        $this->addressLine2 = "addressLine2";
        $this->city = "city";
        $this->state = "state"; 
        $this->postalCode = "postalCode";
        $this->country = "country";
        $this->salesRepEmployeeNumber = "salesRepEmployeeNumber";
        $this->creditLimit = "creditLimit"; 
        $this->conn = $conn;
    }

    public function create($createString,$primaryKey){
        $query = "SELECT * FROM $this->table WHERE $this->customerNumber='$primaryKey'";
        $result = $this->conn->query($query);
        if(!($result->fetch(PDO::FETCH_ASSOC))){
            
            $query = "INSERT INTO $this->table $createString";
            $result = $this->conn->query($query);
            $this->jsonResponse($this->s200, "A new customer has been added", null); 
        }else{
            $this->jsonResponse($this->s404, "customerNumber already exist", null);
        }
    }

    public function read($parameters){
        $records=[];
        $query=  "SELECT $this->customerName, $this->phone, $this->city, 
        $this->country, $this->creditLimit FROM $this->table $parameters";
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

    public function update($updateString,$customerNumber){
        $query = "SELECT * FROM $this->table WHERE $this->customerNumber='$customerNumber'";
        $result = $this->conn->query($query);

        if($result->fetch(PDO::FETCH_ASSOC)){
            $query = "UPDATE $this->table $updateString WHERE $this->customerNumber='$customerNumber'";
            $result = $this->conn->query($query);
            $this->jsonResponse($this->s200, "Customer has been Updated", null);            
        }else{
            $this->jsonResponse($this->s404, "customerNumber does not exist", null);
        }
    }

    public function delete($customerNumber){
        $query = "SELECT * FROM $this->table WHERE $this->customerNumber='$customerNumber'";
        $result = $this->conn->query($query);

        if($result->fetch(PDO::FETCH_ASSOC)){
            $query = "DELETE FROM $this->table WHERE $this->customerNumber='$customerNumber'";
            $result = $this->conn->query($query);
            $this->jsonResponse($this->s200, "Customer has been deleted", null);            
        }else{
            $this->jsonResponse($this->s404, "customerNumber does not exist", null);
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

    public function getCustomerName(){
        return $this->customerName;
    }
    public function getCustomerNumber(){
        return $this->customerNumber;
    }
    public function getContactFirstName(){
        return $this->contactFirstName;
    }
    public function getContactLastName(){
        return $this->contactLastName;
    }
    public function getPhone(){
        return $this->phone;
    }
    public function getAddressLine1(){
        return $this->addressLine1;
    }
    public function getAddressLine2(){
        return $this->addressLine2;
    }
    public function getCity(){
        return $this->city;
    }
    public function getState(){
        return $this->state;
    }
    public function getPostalCode(){
        return $this->postalCode;
    }
    public function getCountry(){
        return $this->country;
    }
    public function getSalesRepEmployeeNumber(){
        return $this->salesRepEmployeeNumber;
    }
    public function getCreditLimit(){
        return $this->creditLimit;
    }
}


?>