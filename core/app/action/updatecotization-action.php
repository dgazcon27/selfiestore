<?php
// var_dump($_SESSION["cotization"]);
// die;
if(isset($_SESSION["cotization"])){
	$cart = $_SESSION["cotization"];
	if(count($cart)>0){
		$num_succ = 0;
		$process=true;
		

		if($process==true){
			$sell = new SellData();
			$sell->total = $_POST['total'];
			$sell->cash = $_POST['total'];
			$sell->update_cotization($_POST['id_sell']);

			// Delete operation
			$operation = new OperationData();
			$operation->delete_by_sell_id($_POST['id_sell']);

			foreach($cart as  $c){

				$operation_type="salida-pendiente"; 
				$product = ProductData::getById($c["product_id"]);
				$op = new OperationData();
				$op->product_id = $c["product_id"] ;
				$op->price_in = $product->price_in;
				$op->price_out = $product->price_out;
				$op->operation_type_id = OperationTypeData::getByName($operation_type)->id;
				$op->stock_id = StockData::getPrincipal()->id;
				$op->sell_id= $_POST['id_sell'];
				$op->q= $c["q"];


				$add = $op->add_cotization();			 		

			}
			unset($_SESSION["cotization"]);
			setcookie("selled","selled");
			print "<script>window.location='index.php?view=cotizations';</script>";
		}
	}
}



?>