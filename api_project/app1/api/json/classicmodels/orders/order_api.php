<?php
require ('../../../../classicmodelsDbConfig.php'); 
require ('../../../../authentication.php');
require ('../../../../queryCreator.php');
require ('orders.php');

// createing objects
$authorization = apache_request_headers()["Authorization"];
$authentication = new authentication($authorization);
$dbUserName = $authentication->authorization();
$conn = new classicmodelsConfig($dbUserName);
$order = new orders($conn->connect()); 
$query = new queryCreator();

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


$orderNumber = $order->getOrderNumber();
$orderDate = $order->getOrderDate();
$requiredDate = $order->getRequiredDate();
$shippedDate = $order->getShippedDate();
$status = $order->getStatus();
$comments = $order->getComments();
$customerNumber = $order->getCustomerNumber();

//num
if(isset($requestParameters[$orderNumber])){
  $responseParameters[$orderNumber]=$requestParameters[$orderNumber]; 
}else{
  $missingData.="$orderNumber, ";
  $missingId.="$orderNumber, ";
}
//f_name
if(isset($requestParameters[$orderDate])){
  $responseParameters[$orderDate]=$requestParameters[$orderDate]; 
}else{
  $missingData.="$orderDate, ";
}
//l_name
if(isset($requestParameters[$requiredDate])){
  $responseParameters[$requiredDate]=$requestParameters[$requiredDate]; 
}else{
  $missingData.="$requiredDate, ";
}
//shippedDate
if(isset($requestParameters[$shippedDate])){
  $responseParameters[$shippedDate]=$requestParameters[$shippedDate]; 
}else{
  $missingData.="$shippedDate, ";
}
//status
if(isset($requestParameters[$status])){
  $responseParameters[$status]=$requestParameters[$status]; 
}else{
  $missingData.="$status, ";
}
//comments
if(isset($requestParameters[$comments])){
  $responseParameters[$comments]=$requestParameters[$comments]; 
}else{
  $optionalData.="$comments, ";
}
//customerNumber
if(isset($requestParameters[$customerNumber])){
  $responseParameters[$customerNumber]=$requestParameters[$customerNumber]; 
}else{
  $missingData.="$customerNumber, ";
}

//executing 
if ($requestMethod == 'GET') {
  if(!empty($_GET)){
    $where = $query->getValues($andCondition);
    $order->read($where);
  }else{
    $order->read($where);
  }

}elseif($requestMethod == 'POST'){
  if(!$missingData){
    if(!$optionalData){
        $create =  $query->setValues($responseParameters); 
        $order->create($create,$responseParameters[$orderNumber]);
    }else{
        echo $optionalMassege . $optionalData;
        $create =  $query->setValues($responseParameters); 
        $order->create($create,$responseParameters[$orderNumber]);
    }
  }else{
    echo $missingMassege . $missingData;
  }
  
}elseif($requestMethod == 'DELETE'){
  if(isset($responseParameters[$orderNumber])){                          
    $order->delete($responseParameters[$orderNumber]);
  }else{
    echo $missingMassege . $missingId;
  }

}elseif($requestMethod == 'PUT'){
  if(isset($responseParameters) && isset($responseParameters[$orderNumber])){  
    $update = $query->updateValues($responseParameters);    
    $order->update($update,$responseParameters[$orderNumber]);
  }else{
    echo $missingMassege . $missingId;
  }
}

 ?>
