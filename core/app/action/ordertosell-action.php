<?php 
	if (isset($_GET['id'])) {
		$cotization = SellData::getById($_GET['id']);
		$user_cotization = SellData::getSellUser($cotization->user_id);
		if ($user_cotization->kind == 8) {
			$sell = SellData::updateOrderToSellToSucursal($_GET['id']);
			$op = OperationData::updateOrderToSellToSucursal($_GET['id']);
			
		} else {
			$sell = SellData::updateOrderToSell($_GET['id']);
			$op = OperationData::updateOrderToSell($_GET['id']);
		}
		echo "success";
	}

?>