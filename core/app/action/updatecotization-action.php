<?php

if(isset($_SESSION["cotization"])){
	$cart = $_SESSION["cotization"];
	if(count($cart)>0){
		$num_succ = 0;
		$process=true;
		$q_update = json_decode($_POST['q_update']);
		$thissell = SellData::getById($_POST['id_sell']);

		if($process==true){
			$operation = new OperationData();
			$operation->delete_by_sell_id($_POST['id_sell']);
			$total_pay = 0;
			
			// Delete operation

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
				$op->operation_type_id = 2;
				$op->stock_id = StockData::getPrincipal()->id;
				$op->sell_id= $_POST['id_sell'];
				$op->q= $quantity;
				$op->is_draft = $thissell->is_draft;


				$add = $op->add_cotization();
				$total_pay += $product->price_out*$quantity;
			}

			$sell = new SellData();
			$sell->total = $total_pay;
			$sell->update_cotization($_POST['id_sell']); 		
			unset($_SESSION["cotization"]);
			setcookie("selled","selled");
			print "<script>window.location='index.php?view=cotizations';</script>";
		}
	}
}



?>