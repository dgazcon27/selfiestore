<?php


if(isset($_SESSION["cotization"])){
	$cart = $_SESSION["cotization"];
	if(count($cart)>0){
		$num_succ = 0;
		$process=true;
		$q_update = json_decode($_POST['q_update']);
		

		if($process==true){
			$sell = new SellData();
			$sell->total = $_POST['total'];
			$sell->cash = $_POST['total'];
			$sell->update_cotization($_POST['id_sell']);

			// Delete operation
			$operation = new OperationData();
			$operation->delete_by_sell_id($_POST['id_sell']);

			foreach($cart as  $c){
				$quantity = $c["q"];
				$while = false;
				$i = 0;
				while (!$while && $i < count($q_update)) {
				    if ($q_update[$i]->product_id == $c["product_id"]) {
						$quantity = $q_update[$i]->q;
						$while = true;
					}
					$i++;
				}
				$operation_type="salida-pendiente"; 
				$product = ProductData::getById($c["product_id"]);
				$op = new OperationData();
				$op->product_id = $c["product_id"];
				$op->price_in = $product->price_in;
				$op->price_out = $product->price_out;
				$op->operation_type_id = OperationTypeData::getByName($operation_type)->id;
				$op->stock_id = StockData::getPrincipal()->id;
				$op->sell_id= $_POST['id_sell'];
				$op->q= $quantity;


				$add = $op->add_cotization();			 		

			}
			unset($_SESSION["cotization"]);
			setcookie("selled","selled");
			print "<script>window.location='index.php?view=cotizations';</script>";
		}
	}
}



?>