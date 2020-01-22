<?php 
	if (isset($_GET['id']) && isset($_GET['status'])) {
		SellData::setStatusSell($_GET['id'],$_GET['status']);
		Core::redir("./index.php?view=orders-approved");
	}
?>