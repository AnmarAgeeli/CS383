<?php
require ('../../../../classicmodelsDbConfig.php'); 
require ('../../../../authentication.php');
require ('../../../../queryCreator.php');
require ('payments.php');

// createing objects
$authorization = apache_request_headers()["Authorization"];
$authentication = new authentication($authorization);
$dbUserName = $authentication->authorization();
$conn = new classicmodelsConfig($dbUserName);
$payment = new payments($conn->connect()); 
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
    $where = $query->getValues($andCondition);
    $payment->read($where);
  }else{
    $payment->read($where);
  }

}elseif($requestMethod == 'POST'){
  if(!$missingData){
    if(!$optionalData){
        $create =  $query->setValues($responseParameters); 
        $payment->create($create,$responseParameters[$checkNumber]);
    }else{
        echo $optionalMassege . $optionalData;
        $create =  $query->setValues($responseParameters); 
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
    $update = $query->updateValues($responseParameters);    
    $payment->update($update,$responseParameters[$checkNumber]);
  }else{
    echo $missingMassege . $missingId;
  }
}


 ?>
