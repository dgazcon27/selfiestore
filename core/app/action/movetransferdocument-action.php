<?php 
	if (isset($_GET['id'])) {
		$sell = SellData::getById($_GET['id']);
		$proc = OperationData::getAllProductsBySellId($sell->id);
		$products = [];
		for ($i=0; $i<count($proc);$i++) {
			$name_p = ProductData::getById($proc[$i]->product_id);
			$unidad = "N/A";
			if (isset($name_p->unit) && $name_p->unit != 0) {
				$unidad = $name_p->unit;
			}
			array_push($products, array("name"=>$name_p->name, "barcode"=>$name_p->barcode,"unit"=>$unidad,"q"=>$proc[$i]->q));
		}

		$person = PersonData::getById($sell->person_id);
		$seller = null;
		if (isset($_GET['seller'])) {
			if (isset($sell->receive_by)) {
				$seller = UserData::getById($sell->receive_by);
			}
		}
		$response = array('sell' => $sell, 'products' => $products, 'person' => $person, 'seller' => $seller);
		echo json_encode($response);
	}
?>