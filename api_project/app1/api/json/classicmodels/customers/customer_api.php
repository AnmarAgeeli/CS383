<?php
require ('../../../../classicmodelsDbConfig.php'); 
require ('../../../../authentication.php');
require ('customers.php');

// createing objects
$authorization = apache_request_headers()["Authorization"];
$authentication = new authentication($authorization);
$dbUserName = $authentication->authorization();
$conn = new classicmodelsConfig($dbUserName);
$customer = new customers($conn->connect()); //////

// declaring variables
$where = '';
$missingMassege = "The following data are missing: ";
$optionalMasseege = "(Optional) The following data are missing: ";
$optionalData = "";
$missingData = "";
$missingId = "";
$andCondition = "and";
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestParameters = json_decode(file_get_contents('php://input'),true);
$responseParameters = array();

$customerName = $customer->getCustomerName();
$customerNumber = $customer->getCustomerNumber();
$contactFirstName = $customer->getContactFirstName();
$contactLastName = $customer->getContactLastName();
$phone = $customer->getPhone();
$addressLine1 = $customer->getAddressLine1();
$addressLine2 = $customer->getAddressLine2();
$city = $customer->getCity();
$state = $customer->getState();
$postalCode = $customer->getPostalCode();
$country = $customer->getCountry();
$salesRepEmployeeNumber = $customer->getSalesRepEmployeeNumber();
$creditLimit = $customer->getCreditLimit();

//num
if(isset($requestParameters[$customerNumber])){
  $responseParameters[$customerNumber]=$requestParameters[$customerNumber]; 
}else{
  $missingData.="$customerNumber, ";
  $missingId.="$customerNumber, ";
}
//name
if(isset($requestParameters[$customerName])){
  $responseParameters[$customerName]=$requestParameters[$customerName]; 
}else{
  $missingData.="$customerName, ";
}
//f_name
if(isset($requestParameters[$contactFirstName])){
  $responseParameters[$contactFirstName]=$requestParameters[$contactFirstName]; 
}else{
  $missingData.="$contactFirstName, ";
}
//l_name
if(isset($requestParameters[$contactLastName])){
  $responseParameters[$contactLastName]=$requestParameters[$contactLastName]; 
}else{
  $missingData.="$contactLastName, ";
}
//phone
if(isset($requestParameters[$phone])){
  $responseParameters[$phone]=$requestParameters[$phone]; 
}else{
  $missingData.="$phone, ";
}
//addressLine1
if(isset($requestParameters[$addressLine1])){
  $responseParameters[$addressLine1]=$requestParameters[$addressLine1]; 
}else{
  $missingData.="$addressLine1, ";
}
//addressLine2
if(isset($requestParameters[$addressLine2])){
  $responseParameters[$addressLine2]=$requestParameters[$addressLine2]; 
}else{
  $optionalData.="$addressLine2, ";
}
//city
if(isset($requestParameters[$city])){
  $responseParameters[$city]=$requestParameters[$city]; 
}else{
  $missingData.="$city, ";
}
//state
if(isset($requestParameters[$state])){
  $responseParameters[$state]=$requestParameters[$state]; 
}else{
  $optionalData.="$state, ";
}
//postalCode
if(isset($requestParameters[$postalCode])){
  $responseParameters[$postalCode]=$requestParameters[$postalCode]; 
}else{
  $optionalData.="$postalCode, ";
}
//country
if(isset($requestParameters[$country])){
  $responseParameters[$country]=$requestParameters[$country]; 
}else{
  $missingData.="$country, ";
}
//salesRepEmployeeNumber
if(isset($requestParameters[$salesRepEmployeeNumber])){
  $responseParameters[$salesRepEmployeeNumber]=$requestParameters[$salesRepEmployeeNumber]; 
}else{
  $missingData.="$salesRepEmployeeNumber, ";
}
//creditLimit
if(isset($requestParameters[$creditLimit])){
  $responseParameters[$creditLimit]=$requestParameters[$creditLimit]; 
}else{
  $optionalData.="$creditLimit, ";
}


//executing 
if ($requestMethod == 'GET') {
  if(!empty($_GET)){
    $where = getValues($andCondition);
    $customer->read($where);
  }else{
    $customer->read($where);
  }

}elseif($requestMethod == 'POST'){
  if(!$missingData){
    if(!$optionalData){
        $create =  setValues($responseParameters); 
        $customer->create($create,$responseParameters[$customerNumber]);
    }else{
        echo $optionalMassege . $optionalData;
        $create =  setValues($responseParameters); 
        $customer->create($create,$responseParameters[$customerNumber]);
    }
  }else{
    echo $missingMassege . $missingData;
  }
  
}elseif($requestMethod == 'DELETE'){
  if(isset($responseParameters[$customerNumber])){                          
    $customer->delete($responseParameters[$customerNumber]);
  }else{
    echo $missingMassege . $missingId;
  }

}elseif($requestMethod == 'PUT'){
  if(isset($responseParameters) && isset($responseParameters[$customerNumber])){  
    $update = updateValues($responseParameters);    
    $customer->update($update,$responseParameters[$customerNumber]);
  }else{
    echo $missingMassege . $missingId;
  }
}



//functions
function getValues($condition){
  $resourcesLength = count($_GET);
  $where = "WHERE ";
  if($condition == "and"){
    foreach($_GET as $key => $value){
      if($resourcesLength > 1){
        $where .= "$key = $value AND ";
        $resourcesLength--;
      }else{
        return $where .= "$key = $value";
     }
    }
  }
}

function setValues($responseParameters){
  $resourcesLength = count($responseParameters);
  $columns = "(";
  $values = "VALUES (";
  foreach($responseParameters as $key => $value){
    if($resourcesLength > 1){
      $columns .= "$key,";
      $values .= "'$value',";
      $resourcesLength--;
    }else{
      $columns .= "$key) ";
      $values .= "'$value')";
      return $columns . $values;
    }
  }
}

function updateValues($responseParameters){
  $resourcesLength = count($responseParameters);
  $set = "SET ";
  foreach($responseParameters as $key => $value){
    if($resourcesLength > 1){
      $set .= "$key = '$value',";
      $resourcesLength--;
    }else{
      $set .= "$key = '$value' ";
      return $set ;    
    }
  }
}
 ?>
