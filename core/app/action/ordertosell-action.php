<?php 
	if (isset($_POST['sell_id'])) {
		$cotization = SellData::getById($_POST['sell_id']);
		$user_cotization = SellData::getSellUser($cotization->user_id);
		$sell = new SellData();
		$sell->id = $_POST['sell_id'];
		$sell->p_id = $_POST['p_id'];
		$sell->f_id = $_POST['f_id'];
		$sell->refe = $_POST['refe'];
		$sell->efe = $_POST['efe'];
		$sell->pun = $_POST['pun'];
		$sell->tra = $_POST['tra'];
		$sell->zel = $_POST['zel'];
		$sell->total = $_POST['total'];
		$sell->discount = $_POST['discount'];
		$sell->total = $_POST['total'];
		$sell->money = $_POST['money'];
		if ($user_cotization->kind == 8) {

			$sell->updateOrderToSellToSucursal();
			$op = OperationData::updateOrderToSellToSucursal($_POST['sell_id']);
			
		} else {
			$sell->updateOrderToSell();
			$op = OperationData::updateOrderToSell($_POST['sell_id']);
		}
	}
	print "<script>window.location='index.php?view=orders-approved';</script>";

?>