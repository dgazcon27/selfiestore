<?php 
	if (isset($_POST['id'])) {
		$sell = new SellData();
		$sell->id = $_POST['id'];
		$sell->comment = $_POST['comment'];
		$sell->updateImei();
		Core::alert("IMEI ACTUALIZADO EXITOSAMENTE");
		Core::redir("./index.php?view=orders-approved");
	}
?>