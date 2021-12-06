<?php
require ('../../../../classicmodelsDbConfig.php'); 
require ('../../../../authentication.php');
require ('../../../../queryCreator.php');
require ('products.php');

// createing objects
$authorization = apache_request_headers()["Authorization"];
$authentication = new authentication($authorization);
$dbUserName = $authentication->authorization();
$conn = new classicmodelsConfig($dbUserName);
$product = new products($conn->connect());
$query = new queryCreator();

// declaring variables
$where = '';
$missingMassege = "The following data are missing: ";
$missingData = "";
$missingId = "";
$andCondition = "and";
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestParameters = json_decode(file_get_contents('php://input'),true);
$responseParameters = array();
$productName = $product->getProductName(); 
$productCode = $product->getProductCode(); 
$productLine = $product->getProductLine(); 
$productScale = $product->getProductScale();
$productVendor = $product->getProductVendor();
$productDescription = $product->getProductDescription();
$quantityInStock = $product->getQuantityInStock(); 
$buyPrice = $product->getBuyPrice(); 
$MSRP = $product->getMsrp(); 


//code
if(isset($requestParameters[$productCode])){
  $responseParameters[$productCode]=$requestParameters[$productCode]; 
}else{
  $missingData.="$productCode, ";
  $missingId = "$productCode";
}
//Name
if(isset($requestParameters[$productName])){
  $responseParameters[$productName]=$requestParameters[$productName]; 
}else{
  $missingData.="$productName, ";
}
//line
if(isset($requestParameters[$productLine])){
  $responseParameters[$productLine]=$requestParameters[$productLine]; 
}else{
  $missingData.="$productLine, ";
}
//scale
if(isset($requestParameters[$productScale])){
  $responseParameters[$productScale]=$requestParameters[$productScale]; 
}else{
  $missingData.="$productScale, ";
}
//vendor
if(isset($requestParameters[$productVendor])){
  $responseParameters[$productVendor]=$requestParameters[$productVendor]; 
}else{
  $missingData.="$productVendor, ";
}
//Description
if(isset($requestParameters[$productDescription])){
  $responseParameters[$productDescription]=$requestParameters[$productDescription]; 
}else{
  $missingData.="$productDescription, ";
}
//quantity
if(isset($requestParameters[$quantityInStock])){
  $responseParameters[$quantityInStock]=$requestParameters[$quantityInStock]; 
}else{
  $missingData.="$quantityInStock, ";
}
//price
if(isset($requestParameters[$buyPrice])){
  $responseParameters[$buyPrice]=$requestParameters[$buyPrice]; 
}else{
  $missingData.="$buyPrice, ";
}
//MSRP
if(isset($requestParameters[$MSRP])){
  $responseParameters[$MSRP]=$requestParameters[$MSRP]; 
}else{
  $missingData.="$MSRP, ";
}


//executing 
if ($requestMethod == 'GET') {
  if(!empty($_GET)){
    $where = $query->getValues($andCondition);
    $product->read($where);
  }else{
    $product->read($where);
  }

}elseif($requestMethod == 'POST'){
  if(!$missingData){ 
    $create =  $query->setValues($responseParameters); 
    $product->create($create, $responseParameters[$productCode],$responseParameters[$productLine]);
  }else{
    echo $missingMassege . $missingData;
  }
  
}elseif($requestMethod == 'DELETE'){
  if(isset($responseParameters[$productCode])){                          
    $product->delete($responseParameters[$productCode]);
  }else{
    echo $missingMassege . $missingId;
  }

}elseif($requestMethod == 'PUT'){
  if(isset($responseParameters) && isset($responseParameters[$productCode])){  
    $update = $query->updateValues($responseParameters);    
    $product->update($update,$responseParameters[$productCode]);
  }else{
    echo $missingMassege . $missingId;
  }
}



 ?>
