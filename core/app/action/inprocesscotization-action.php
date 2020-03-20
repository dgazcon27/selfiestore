<?php
	if (isset($_GET['id'])) {
		$sell = SellData::getById($_GET['id']);
		if (isset($sell->person_id)) {
			$person = PersonData::getById($sell->person_id);
			if ($person->kind == 8) {
				$operations = OperationData::getAllProductsBySellId($sell->id);
				$total = 0;

				foreach($operations as $operation){
				    $product  = $operation->getProduct();
				    $total+=$operation->q*$product->price_out;
				}

				$op = new OperationData();
				$op->sell_id = $sell->id;
				$op->operation_type_id = 7;
				$iva_val = ConfigurationData::getByPreffix("imp-val")->val;

				$x = new XXData();
				$xx = $x->add();
				$sell->ref_id = $xx[1];
				$sell->d_id = 5;
				$sell->f_id = 1;
				$sell->person_id = $sell->person_id;
				$sell->iva=  $iva_val;
				$sell->total = $total;
				$sell->stock_to_id = StockData::getPrincipal()->id;
				$sell->receive_by = $_SESSION['user_id'];
				$sell->process_cotization();
				foreach($operations as $op){
					$op->set_draft(0);
				}
			}

		} else {
			$sell = SellData::inProcessCotization($_GET['id']);
			OperationData::confirmCotization($_GET['id']);
		}
	}
?>