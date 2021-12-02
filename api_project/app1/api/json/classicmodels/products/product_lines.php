<?php
class product_lines{
    private $table;
    private $priductsTable;
    private $productLine; //get
    private $textDescription; //get
    private $htmlDescription; 
    private $image; 
    private $conn;
    public $s404 = "404";
    public $m404 = "Not found";
    public $s200 = "200";
    public $m200 = "Ok";

    public function __construct($conn){
        $this->table = "productlines";
        $this->productTable = "products";
        $this->productsTable = "products";
        $this->productLine = "productLine";
        $this->textDescription = "textDescription";
        $this->htmlDescription = "htmlDescription";
        $this->image = "image";
        $this->conn = $conn;
    }

    public function create($createString,$primaryKey){
        $query = "SELECT * FROM $this->table WHERE $this->productLine='$primaryKey'";
        $result = $this->conn->query($query);
        if(!($result->fetch(PDO::FETCH_ASSOC))){
            $query = "INSERT INTO $this->table $createString";
            $result = $this->conn->query($query);
            $this->jsonResponse($this->s200, "A new productLine has been added", null);        
        }else{
            $this->jsonResponse($this->s404, "productLine already exist", null);
        }
    }

    public function read($parameters){
        $records=[];
        $query=  "SELECT $this->productLine, $this->textDescription FROM $this->table $parameters";
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

    public function update($updateString,$productLine){
        $query = "SELECT * FROM $this->table WHERE $this->productLine='$productLine'";
        $result = $this->conn->query($query);

        if($result->fetch(PDO::FETCH_ASSOC)){
            $query = "UPDATE $this->table $updateString WHERE $this->productLine='$productLine'";
            $result = $this->conn->query($query);
            $this->jsonResponse($this->s200, "Productline has been Updated", null);            
        }else{
            $this->jsonResponse($this->s404, "productLine does not exist", null);
        }
    }

    public function delete($productLine){
        $query = "SELECT * FROM $this->table WHERE $this->productLine='$productLine'";
        $result = $this->conn->query($query);

        if($result->fetch(PDO::FETCH_ASSOC)){
            $query = "SELECT * FROM $this->productTable WHERE $this->productLine='$productLine'";
            $result = $this->conn->query($query);
            if(!$result->fetch(PDO::FETCH_ASSOC)){
                $query = "DELETE FROM $this->table WHERE $this->productLine='$productLine'";
                $result = $this->conn->query($query);
                $this->jsonResponse($this->s200, "ProductLine has been deleted", null); 
            }else{
                $this->jsonResponse($this->s404, "ProductLine Cannot be deleted", null); 
            }           
        }else{
            $this->jsonResponse($this->s404, "productLine does not exist", null);
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

    public function getProductLine(){
        return $this->productLine;
    }
    public function getTextDescription(){
        return $this->textDescription;
    }
    public function getHtmlDescription(){
        return $this->htmlDescription;
    }
    public function getImage(){
        return $this->image;
    }


}


?>