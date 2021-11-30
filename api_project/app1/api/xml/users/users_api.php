<?php
require ('../../../usersDbConfig.php'); 
require ('../../../authentication.php');
require ('users.php'); 

// createing objects
$authorization = apache_request_headers()["Authorization"];
$authentication = new authentication($authorization);
$dbUserName = $authentication->authorization();
$conn = new usersConfig($dbUserName);
$user = new users($conn->connect());

// declaring variables
$where = '';
$missingData = "Data is missing";
$missingId = "Id is missing";
$andCondition = "and";
$requestMethod = $_SERVER['REQUEST_METHOD'];

if($requestMethod != "GET"){
  $requestParameters = new SimpleXMLElement(file_get_contents('php://input'));
  if(isset($requestParameters->name)){
  $responseName=$requestParameters->name; 
  }
  if(isset($requestParameters->id)){
  $responseId=$requestParameters->id;
  }
}

//executing 
if ($requestMethod == 'GET') {
  if(!empty($_GET)){
    getValues($andCondition);
    $user->read($where);
  }else{
    $user->read($where);
  }

}elseif($requestMethod == 'POST'){
  if(isset($responseName)&&isset($responseId)){      
    $user->create($responseName,$responseId);
  }else{
    echo $missingData;
  }
  
}elseif($requestMethod == 'DELETE'){
  if(isset($responseId)){                           
    $user->delete($responseId);
  }else{
    echo $missingId;
  }

}elseif($requestMethod == 'PUT'){
  if(isset($responseName)&&isset($responseId)){       
    $user->update($responseName,$responseId);
  }else{
    echo $missingData;
  }
}

//functions 
function getValues($condition){
  global $where;
  $resourcesLength = count($_GET);
  $where = "WHERE ";
  if($condition == "and"){
    foreach($_GET as $key => $value){
      if($resourcesLength > 1){
        $where .= "$key = $value AND ";
        $resourcesLength--;
      }else{
        $where .= "$key = $value";
     }
    }
  }
}

/*
  $query = " SELECT * FROM users $where ";

  $result = mysqli_query($conn,$query);

  $users_array = array();

  while($row = mysqli_fetch_row($result))
    $users_array[] = $row;

    //echo '<pre>';
    //print_r($users_array);
    if(!$users_array){
      jasonResponse('404', 'No Data found', null);
    }else{
      jasonResponse('200', 'OK', $users_array);
    }


    function jasonResponse($status, $message, $data ){
      header("HTTP/1.2 ".$message);
      header('Content-Type: application/json;');
      $response['status'] = $status;
      $response['message'] = $message;
      $response['data'] = $data;


      print_r(json_encode($response));

    }
*/
 ?>
