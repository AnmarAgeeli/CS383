<?php
require ('../../../../classicmodelsDbConfig.php'); 
require ('../../../../authentication.php');
require ('employees.php');

// createing objects
$authorization = apache_request_headers()["Authorization"];
$authentication = new authentication($authorization);
$dbUserName = $authentication->authorization();
$conn = new classicmodelsConfig($dbUserName);
$employee = new employees($conn->connect()); //////

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


$employeeNumber = $employee->getemployeeNumber();
$firstName = $employee->getfirstName();
$lastName = $employee->getlastName();
$extension = $employee->getextension();
$email = $employee->getemail();
$officeCode = $employee->getofficeCode();
$reportsTo = $employee->getreportsTo();
$jobTitle = $employee->getjobTitle();


//num
if(isset($requestParameters[$employeeNumber])){
  $responseParameters[$employeeNumber]=$requestParameters[$employeeNumber]; 
}else{
  $missingData.="$employeeNumber, ";
  $missingId.="$employeeNumber, ";
}
//f_name
if(isset($requestParameters[$firstName])){
  $responseParameters[$firstName]=$requestParameters[$firstName]; 
}else{
  $missingData.="$firstName, ";
}
//l_name
if(isset($requestParameters[$lastName])){
  $responseParameters[$lastName]=$requestParameters[$lastName]; 
}else{
  $missingData.="$lastName, ";
}
//extension
if(isset($requestParameters[$extension])){
  $responseParameters[$extension]=$requestParameters[$extension]; 
}else{
  $missingData.="$extension, ";
}
//email
if(isset($requestParameters[$email])){
  $responseParameters[$email]=$requestParameters[$email]; 
}else{
  $missingData.="$email, ";
}
//officeCode
if(isset($requestParameters[$officeCode])){
  $responseParameters[$officeCode]=$requestParameters[$officeCode]; 
}else{
  $optionalData.="$officeCode, ";
}
//reportsTo
if(isset($requestParameters[$reportsTo])){
  $responseParameters[$reportsTo]=$requestParameters[$reportsTo]; 
}else{
  $missingData.="$reportsTo, ";
}
//jobTitle
if(isset($requestParameters[$jobTitle])){
  $responseParameters[$jobTitle]=$requestParameters[$jobTitle]; 
}else{
  $optionalData.="$jobTitle, ";
}


//executing 
if ($requestMethod == 'GET') {
  if(!empty($_GET)){
    $where = getValues($andCondition);
    $employee->read($where);
  }else{
    $employee->read($where);
  }

}elseif($requestMethod == 'POST'){
  if(!$missingData){
    if(!$optionalData){
        $create =  setValues($responseParameters); 
        $employee->create($create,$responseParameters[$employeeNumber]);
    }else{
        echo $optionalMassege . $optionalData;
        $create =  setValues($responseParameters); 
        $employee->create($create,$responseParameters[$employeeNumber]);
    }
  }else{
    echo $missingMassege . $missingData;
  }
  
}elseif($requestMethod == 'DELETE'){
  if(isset($responseParameters[$employeeNumber])){                          
    $employee->delete($responseParameters[$employeeNumber]);
  }else{
    echo $missingMassege . $missingId;
  }

}elseif($requestMethod == 'PUT'){
  if(isset($responseParameters) && isset($responseParameters[$employeeNumber])){  
    $update = updateValues($responseParameters);    
    $employee->update($update,$responseParameters[$employeeNumber]);
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
