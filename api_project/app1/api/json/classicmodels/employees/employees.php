<?php
class employees{
    private $table;    
    private $officesTable;
    private $employeeNumber;
    private $lastName; //get
    private $firstName; //get
    private $extension; 
    private $email; 
    private $officeCode; //get
    private $reportsTo;
    private $jobTitle; //get
    private $offies;
    private $city;
    private $country;
    private $phone;
    private $conn;
    public $s404 = "404";
    public $m404 = "Not found";
    public $s200 = "200";
    public $m200 = "Ok";

    public function __construct($conn){
        $this->table = "employees";
        $this->officesTable = "offices";
        $this->lastName = "lastName";
        $this->employeeNumber = "employeeNumber";
        $this->firstName = "firstName";
        $this->extension = "extension";
        $this->email = "email";
        $this->officeCode = "officeCode";
        $this->reportsTo = "reportsTo";
        $this->jobTitle = "jobTitle";
        $this->state = "state"; 
        $this->postalCode = "postalCode";
        $this->country = "country";
        $this->salesRepEmployeeNumber = "salesRepEmployeeNumber";
        $this->creditLimit = "creditLimit"; 
        $this->city = "city";
        $this->phone = "phone";
        $this->country = "country";
        $this->conn = $conn;
    }

    public function create($createString,$primaryKey){
        $query = "SELECT * FROM $this->table WHERE $this->employeeNumber='$primaryKey'";
        $result = $this->conn->query($query);
        if(!($result->fetch(PDO::FETCH_ASSOC))){
            $query = "INSERT INTO $this->table $createString";
            $result = $this->conn->query($query);
            $this->jsonResponse($this->s200, "A new employee has been added", null); 
        }else{
            $this->jsonResponse($this->s404, "employeeNumber already exist", null);
        }
    }

    public function read($parameters){
        $records=[];
        $query=  "SELECT $this->firstName, $this->lastName, $this->table.$this->officeCode, 
        $this->jobTitle, $this->city, $this->phone, $this->country 
        FROM $this->table
        INNER JOIN $this->officesTable
        ON $this->table.$this->officeCode = $this->officesTable.$this->officeCode
        $parameters";

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

    public function update($updateString,$employeeNumber){
        $query = "SELECT * FROM $this->table WHERE $this->employeeNumber='$employeeNumber'";
        $result = $this->conn->query($query);

        if($result->fetch(PDO::FETCH_ASSOC)){
            $query = "UPDATE $this->table $updateString WHERE $this->employeeNumber='$employeeNumber'";
            $result = $this->conn->query($query);
            $this->jsonResponse($this->s200, "Employee has been Updated", null);            
        }else{
            $this->jsonResponse($this->s404, "EmployeeNumber does not exist", null);
        }
    }

    public function delete($employeeNumber){
        $query = "SELECT * FROM $this->table WHERE $this->employeeNumber='$employeeNumber'";
        $result = $this->conn->query($query);

        if($result->fetch(PDO::FETCH_ASSOC)){
            $query = "DELETE FROM $this->table WHERE $this->employeeNumber='$employeeNumber'";
            $result = $this->conn->query($query);
            $this->jsonResponse($this->s200, "Employee has been deleted", null);            
        }else{
            $this->jsonResponse($this->s404, "EmployeeNumber does not exist", null);
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

    public function getLastName(){
        return $this->lastName;
    }
    public function getEmployeeNumber(){
        return $this->employeeNumber;
    }
    public function getExtension(){
        return $this->extension;
    }
    public function getFirstName(){
        return $this->firstName;
    }
    public function getEmail(){
        return $this->email;
    }
    public function getOfficeCode(){
        return $this->officeCode;
    }
    public function getReportsTo(){
        return $this->reportsTo;
    }
    public function getJobTitle(){
        return $this->jobTitle;
    }
}


?>