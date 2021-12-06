<?php
require ('../../../../classicmodelsDbConfig.php'); 
require ('../../../../authentication.php');
require ('../../../../queryCreator.php');
require ('product_lines.php');

// createing objects
$authorization = apache_request_headers()["Authorization"];
$authentication = new authentication($authorization);
$dbUserName = $authentication->authorization();
$conn = new classicmodelsConfig($dbUserName);
$product = new product_lines($conn->connect());
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
    $where = $query->getValues($andCondition);
    $product->read($where);
  }else{
    $product->read($where);
  }

}elseif($requestMethod == 'POST'){
  if(!$missingData){
    if(!$optionalData){
        $create =  $query->setValues($responseParameters); 
        $product->create($create,$responseParameters[$productLine]);
    }else{
        echo $optionalMassege . $optionalData;
        $create =  $query->setValues($responseParameters); 
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
    $update = $query->updateValues($responseParameters);    
    $product->update($update,$responseParameters[$productLine]);
  }else{
    echo $missingMassege . $missingId;
  }
}

 ?>
