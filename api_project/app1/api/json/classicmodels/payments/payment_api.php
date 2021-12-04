<?php
require ('../../../../classicmodelsDbConfig.php'); 
require ('../../../../authentication.php');
require ('payments.php');

// createing objects
$authorization = apache_request_headers()["Authorization"];
$authentication = new authentication($authorization);
$dbUserName = $authentication->authorization();
$conn = new classicmodelsConfig($dbUserName);
$payment = new payments($conn->connect()); //////

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


$checkNumber = $payment->getCheckNumber();
$customerNumber = $payment->getCustomerNumber();
$paymentDate = $payment->getPaymentDate();
$amount = $payment->getAmount();

//num
if(isset($requestParameters[$checkNumber])){
  $responseParameters[$checkNumber]=$requestParameters[$checkNumber]; 
}else{
  $missingData.="$checkNumber, ";
  $missingId.="$checkNumber, ";
}
//customer_num
if(isset($requestParameters[$customerNumber])){
  $responseParameters[$customerNumber]=$requestParameters[$customerNumber]; 
}else{
  $missingData.="$customerNumber, ";
}
//payments
if(isset($requestParameters[$paymentDate])){
  $responseParameters[$paymentDate]=$requestParameters[$paymentDate]; 
}else{
  $missingData.="$paymentDate, ";
}
//amount
if(isset($requestParameters[$amount])){
  $responseParameters[$amount]=$requestParameters[$amount]; 
}else{
  $missingData.="$amount, ";
}

//executing 
if ($requestMethod == 'GET') {
  if(!empty($_GET)){
    $where = getValues($andCondition);
    $payment->read($where);
  }else{
    $payment->read($where);
  }

}elseif($requestMethod == 'POST'){
  if(!$missingData){
    if(!$optionalData){
        $create =  setValues($responseParameters); 
        $payment->create($create,$responseParameters[$checkNumber]);
    }else{
        echo $optionalMassege . $optionalData;
        $create =  setValues($responseParameters); 
        $payment->create($create,$responseParameters[$checkNumber]);
    }
  }else{
    echo $missingMassege . $missingData;
  }
  
}elseif($requestMethod == 'DELETE'){
  if(isset($responseParameters[$checkNumber])){                          
    $payment->delete($responseParameters[$checkNumber]);
  }else{
    echo $missingMassege . $missingId;
  }

}elseif($requestMethod == 'PUT'){
  if(isset($responseParameters) && isset($responseParameters[$checkNumber])){  
    $update = updateValues($responseParameters);    
    $payment->update($update,$responseParameters[$checkNumber]);
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
