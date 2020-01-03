<?php 
	if (isset($_GET['id'])) {
		$sell = SellData::updateOrderToSell($_GET['id']);
		echo "success";
	}

?>