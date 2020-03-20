<?php 
	$response = array();
	$products = array();
	if (isset($_GET['q'])) {
		$prd = ProductData::getLike($_GET['q']);
		foreach ($prd as $value) {
			$stock = OperationData::getQByStock($value->id, Core::$user->stock_id);
			array_push($products, array('id' => $value->id,'name' => $value->name, 'image' => $value->image, 'price'=> number_format($value->price_out,2,".",","), 'stock' => $stock )); 

		}
	} elseif (isset($_GET['id'])) {
		$op = OperationData::getAllProductsBySellId($_GET['id']);
		
		foreach ($op as $value) {
			$prod = ProductData::getById($value->product_id);
			array_push($products, array('id' => $prod->id,'name' => $prod->name, 'image' => $prod->image, 'q' => $value->q, 'price'=> $prod->price_out, 'total' => number_format($prod->price_out*$value->q,2,".",",") )); 
		}

	} elseif (isset($_GET['product'])) {
		$prd = ProductData::getById($_GET['product']);
		$stock = OperationData::getQByStock($_GET['product'], Core::$user->stock_id);
		if ($stock > 0 && $stock >= $_GET['quantity']) {
			array_push($products, array('id' => $prd->id,'name' => $prd->name, 'q' => $_GET['quantity'], 'image' => $prd->image, 'price'=> number_format($prd->price_out,2,".",","), 'total' => number_format($prd->price_out*$_GET['quantity'],2,".",",") ));
			
		}

	}
	$response = array('products' => $products);

	echo json_encode($response);

?>