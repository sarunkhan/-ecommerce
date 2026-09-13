<?php
require_once "config.php";
$data=json_decode(file_get_contents("php://input"),true);
$email=trim($data["email"]??""); $password=$data["password"]??"";
$stmt=$pdo->prepare("SELECT id,name,email,password_hash FROM users WHERE email=?");
$stmt->execute([$email]); $u=$stmt->fetch();
if(!$u||!password_verify($password,$u["password_hash"])){http_response_code(401);echo json_encode(["success"=>false,"message"=>"Invalid email or password"]);exit;}
echo json_encode(["success"=>true,"message"=>"Login successful","user"=>["id"=>$u["id"],"name"=>$u["name"],"email"=>$u["email"]]]);
?>