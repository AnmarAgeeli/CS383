<?php
require ('../../../../classicmodelsDbConfig.php'); 
require ('../../../../authentication.php');
require ('product_lines.php');

// createing objects
$authorization = apache_request_headers()["Authorization"];
$authentication = new authentication($authorization);
$dbUserName = $authentication->authorization();
$conn = new classicmodelsConfig($dbUserName);
$product = new product_lines($conn->connect());

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
$productLine = $product->getProductLine(); 
$textDescription = $product->getTextDescription();
$htmlDescription = $product->getHtmlDescription(); 
$image = $product->getImage(); 


//line
if(isset($requestParameters[$productLine])){
  $responseParameters[$productLine]=$requestParameters[$productLine]; 
}else{
  $missingData.="$productLine, ";
  $missingId.="$productLine, ";
}
//text
if(isset($requestParameters[$textDescription])){
  $responseParameters[$textDescription]=$requestParameters[$textDescription]; 
}else{
  $missingData.="$textDescription, ";
}
//Html
if(isset($requestParameters[$htmlDescription])){
    $responseParameters[$htmlDescription]=$requestParameters[$htmlDescription]; 
  }else{
    $optionalData.="$htmlDescription, ";
  }
//image
if(isset($requestParameters[$image])){
  $responseParameters[$image]=$requestParameters[$image]; 
}else{
  $optionalData.="$image, ";
}


//executing 
if ($requestMethod == 'GET') {
  if(!empty($_GET)){
    $where = getValues($andCondition);
    $product->read($where);
  }else{
    $product->read($where);
  }

}elseif($requestMethod == 'POST'){
  if(!$missingData){
    if(!$optionalData){
        $create =  setValues($responseParameters); 
        $product->create($create,$responseParameters[$productLine]);
    }else{
        echo $optionalMassege . $optionalData;
        $create =  setValues($responseParameters); 
        $product->create($create,$responseParameters[$productLine]);
    }
  }else{
    echo $missingMassege . $missingData;
  }
  
}elseif($requestMethod == 'DELETE'){
  if(isset($responseParameters[$productLine])){                          
    $product->delete($responseParameters[$productLine]);
  }else{
    echo $missingMassege . $missingId;
  }

}elseif($requestMethod == 'PUT'){
  if(isset($responseParameters) && isset($responseParameters[$productLine])){  
    $update = updateValues($responseParameters);    
    $product->update($update,$responseParameters[$productLine]);
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
