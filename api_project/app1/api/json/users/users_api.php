<?php
require ('../../../dbConfig.php'); 
require ('users.php'); 

//////// new class  faisal 
$authorization = apache_request_headers()["Authorization"];
if($authorization==1){
  $dbUserName="yavxse";
}elseif($authorization==2){
  $dbUserName="faisal";
  if($_SERVER['REQUEST_METHOD'] != 'GET'){
    die("Access denied for user  " . $dbUserName );
  }
}else{
  die("You don’t have authorization");
}
////////////////////////////////////////

//$dbUserName="yavxse";
$where = " ";
$andCondition = "and";
$orCondition = "or";
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestParameters = json_decode(file_get_contents('php://input'),true);

if(isset($requestParameters['name'])){
  $responseName=$requestParameters['name']; 
}
if(isset($requestParameters['id'])){
  $responseId=$requestParameters['id'];
}


$conn = new usersConfig($dbUserName);
$user = new users($conn->connect());


if ($requestMethod == 'GET') {
  if(!empty($_GET)){
    getValues($andCondition);
    $user->read($where);
  }else{
    $user->read($where);
  }

}elseif($requestMethod == 'POST'){
  if(isset($responseName)&&isset($responseId)){      //TDDO function
    $user->create($responseName,$responseId);
  }else{
    echo "Data is missing";
  }
  
}elseif($requestMethod == 'DELETE'){
  if(isset($responseId)){                           //TDDO function
    $user->delete($responseId);
  }else{
    echo "Id is missing";
  }

}elseif($requestMethod == 'PUT'){
  if(isset($responseName)&&isset($responseId)){       //TDDO function
    $user->update($responseName,$responseId);
  }else{
    echo "Data is missing";
  }
}












///////////////////////////////
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
