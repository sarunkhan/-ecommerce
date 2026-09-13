<?php
require_once "config.php";
$data=json_decode(file_get_contents("php://input"),true);
$name=trim($data["name"]??""); $email=trim($data["email"]??""); $password=$data["password"]??"";
if(!$name||!$email||!$password){http_response_code(400);echo json_encode(["success"=>false,"message"=>"All fields are required"]);exit;}
try{
  $stmt=$pdo->prepare("INSERT INTO users(name,email,password_hash) VALUES(?,?,?)");
  $stmt->execute([$name,$email,password_hash($password,PASSWORD_DEFAULT)]);
  echo json_encode(["success"=>true,"message"=>"Registration successful"]);
}catch(PDOException $e){http_response_code(400);echo json_encode(["success"=>false,"message"=>"Email already registered"]);}
?>