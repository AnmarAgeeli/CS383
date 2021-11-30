<?php
class products{
    private $table;
    private $productName; //get
    private $productCode; //get
    private $productLine; //get
    private $productScale;
    private $productVendor;
    private $productDescription;
    private $quantityInStock; //get
    private $buyPrice; //get
    private $MSRP; 
    private $conn;
    public $s404 = "404";
    public $m404 = "Not found";
    public $s200 = "200";
    public $m200 = "Ok";

    public function __construct($conn){
        $this->table = "products";
        $this->productName = "productName";
        $this->productCode = "productCode";
        $this->productLine = "productLine";
        $this->productScale = "productScale";
        $this->productVendor = "productVendor";
        $this->productDescription = "productDescription";
        $this->quantityInStock = "quantityInStock";
        $this->buyPrice = "buyPrice";
        $this->MSRP = "MSRP"; 
        $this->conn = $conn;
    }

    public function create($createString,$primaryKey){
        $query = "SELECT * FROM $this->table WHERE $this->productCode='$primaryKey'";
        $result = $this->conn->query($query);
        if(!($result->fetch(PDO::FETCH_ASSOC))){
            $query = "INSERT INTO $this->table $createString";
            $result = $this->conn->query($query);
            $this->jsonResponse($this->s200, "A new product has been added", null);            
        }else{
            $this->jsonResponse($this->s404, "productCode already exist", null);
        }
    }

    public function read($parameters){
        $records=[];
        $query=  "SELECT $this->productName, $this->productCode, $this->productLine, 
        $this->quantityInStock, $this->buyPrice FROM $this->table $parameters";
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

    public function update($updateString,$productCode){
        $query = "SELECT * FROM $this->table WHERE $this->productCode='$productCode'";
        $result = $this->conn->query($query);

        if($result->fetch(PDO::FETCH_ASSOC)){
            $query = "UPDATE $this->table $updateString WHERE $this->productCode='$productCode'";
            $result = $this->conn->query($query);
            $this->jsonResponse($this->s200, "Product has been Updated", null);            
        }else{
            $this->jsonResponse($this->s404, "productCode does not exist", null);
        }
    }

    public function delete($productCode){
        $query = "SELECT * FROM $this->table WHERE $this->productCode='$productCode'";
        $result = $this->conn->query($query);

        if($result->fetch(PDO::FETCH_ASSOC)){
            $query = "DELETE FROM $this->table WHERE $this->productCode='$productCode'";
            $result = $this->conn->query($query);
            $this->jsonResponse($this->s200, "Product has been deleted", null);            
        }else{
            $this->jsonResponse($this->s404, "productCode does not exist", null);
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

    public function getProductName(){
        return $this->productName;
    }
    public function getProductCode(){
        return $this->productCode;
    }
    public function getProductScale(){
        return $this->productScale;
    }
    public function getProductLine(){
        return $this->productLine;
    }
    public function getProductVendor(){
        return $this->productVendor;
    }
    public function getProductDescription(){
        return $this->productDescription;
    }
    public function getQuantityInStock(){
        return $this->quantityInStock;
    }
    public function getBuyPrice(){
        return $this->buyPrice;
    }
    public function getMsrp(){
        return $this->MSRP;
    }

}


?>