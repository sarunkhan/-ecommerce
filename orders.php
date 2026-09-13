<?php
require_once "config.php";
$data=json_decode(file_get_contents("php://input"),true);
$user_id=(int)($data["user_id"]??0); $items=$data["items"]??[];
if(!$user_id||!is_array($items)||!count($items)){http_response_code(400);echo json_encode(["success"=>false,"message"=>"user_id and items are required"]);exit;}
try{
  $pdo->beginTransaction(); $total=0; $rows=[];
  $p=$pdo->prepare("SELECT price,stock FROM products WHERE id=?");
  foreach($items as $item){
    $id=(int)($item["product_id"]??0); $qty=(int)($item["quantity"]??0);
    $p->execute([$id]); $product=$p->fetch();
    if(!$product||$qty<1||$qty>$product["stock"]) throw new Exception("Invalid product or stock");
    $price=(float)$product["price"]; $total += $price*$qty; $rows[]=[$id,$qty,$price];
  }
  $o=$pdo->prepare("INSERT INTO orders(user_id,total_amount) VALUES(?,?) RETURNING id");
  $o->execute([$user_id,$total]); $order_id=$o->fetchColumn();
  $oi=$pdo->prepare("INSERT INTO order_items(order_id,product_id,quantity,price) VALUES(?,?,?,?)");
  foreach($rows as $r)$oi->execute([$order_id,$r[0],$r[1],$r[2]]);
  $pdo->commit(); echo json_encode(["success"=>true,"order_id"=>$order_id,"total_amount"=>$total]);
}catch(Throwable $e){if($pdo->inTransaction())$pdo->rollBack();http_response_code(400);echo json_encode(["success"=>false,"message"=>$e->getMessage()]);}
?>