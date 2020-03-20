<?php 
	if (isset($_POST)) {
		$sell = new SellData();
		$sell->person_id = isset($_POST['client_id']) ? $_POST['client_id'] : 'NULL';
		$sell->f_id = isset($_POST['f_id']) ? $_POST['f_id'] : 0;
		$sell->refe = isset($_POST['refe']) ? $_POST['refe'] : 0;
		$sell->id = $_POST['id'];
		$response = $sell->changeSell();
		Core::redir("./index.php?view=sells");
	}
?>