<?php 
	if (isset($_GET['id']) && isset($_GET['status'])) {
		SellData::setStatusSell($_GET['id'],$_GET['status']);
		if (isset($_GET['from'])) {
			Core::redir("./index.php?view=sells");
		} else {
			Core::redir("./index.php?view=orders-approved");
		}
	}
?>