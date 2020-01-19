<?php
if(!isset($_SESSION["cotization"])){
	$product = array("product_id"=>$_POST["product_id"],"q"=>$_POST["q"], "stock_id"=>$_POST["stock_id"]);
	$_SESSION["cotization"] = array($product);
	$cart = $_SESSION["cotization"];

	$num_succ = 0;
	$process=false;
	$errors = array();
	foreach($cart as $c){
		$q = OperationData::getQByStock($c["product_id"],$_POST['stock_id']);
		if($c["q"]<=$q){
			$num_succ++;
		}else{
			$error = array("product_id"=>$c["product_id"],"message"=>"No hay suficiente cantidad de producto en inventario.");
			$errors[count($errors)] = $error;
		}

	}
///////////////////////////////////////////////////////////////////
	if($num_succ==count($cart)){
		$process = true;
	}

	if($process==false){
		unset($_SESSION["cotization"]);
		$_SESSION["errors"] = $errors;
	}
}else {

$found = false;
$errors = [];
$cart = $_SESSION["cotization"];
$index=0;

$q = OperationData::getQByStock($_POST["product_id"],StockData::getPrincipal()->id);
$can = true;
if($_POST["q"] > $q){
	$error = array("product_id"=>$_POST["product_id"],"message"=>"No hay suficiente cantidad de producto en inventario.");
	$errors[count($errors)] = $error;
	$can=false;
}

if($can==false){
	$_SESSION["errors"] = $errors;
}

if($can==true){

foreach($cart as $c){
	if($c["product_id"]==$_POST["product_id"]){
		$found=true;
		break;
	}
	$index++;
}
if($found==true){
	$q1 = $cart[$index]["q"];
	$q2 = $_POST["q"];
	$cart[$index]["q"]=$q1+$q2;
	$_SESSION["cotization"] = $cart;
}

if($found==false){

    $nc = count($cart);
	$product = array("product_id"=>$_POST["product_id"],"q"=>$_POST["q"], "stock_id"=>$_POST["stock_id"]);
	array_push($cart, $product);
	$_SESSION["cotization"] = $cart;
}

}
}
 print "<script>window.location='index.php?view=newcotization';</script>";

?>