<?php
require_once "config.php";
try {
    $stmt=$pdo->query("SELECT id,name,description,price,image,category,stock FROM products ORDER BY id DESC");
    echo json_encode(["success"=>true,"products"=>$stmt->fetchAll()]);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(["success"=>false,"message"=>"Unable to load products"]);
}
?>