<?php


//$url = 'http://localhost/CS383/api_project/app1/api/json/users/users_api.php?id='.$_GET['id'];
$url = 'http://localhost/CS383/api_project/app1/api/json/users/users_api.php';
$client  = curl_init($url);
curl_setopt($client, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($client);

$jResponse = json_decode($response);
echo '<pre>';
print_r($jResponse->data);


 ?>
