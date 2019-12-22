<?php

if(!empty($_POST)){
	$sell = SellData::getById($_POST["cotization_id"]);
	$operations = OperationData::getAllProductsBySellId($sell->id);
	$op = new OperationData();
	$op->sell_id = $sell->id;
	$op->operation_type_id = 7;
	$iva_val = ConfigurationData::getByPreffix("imp-val")->val;



	$sell->p_id = $_POST["p_id"];
	$sell->d_id = $_POST["d_id"];
	$sell->iva=  $iva_val;
	$sell->total = $_POST["total"];
	$sell->discount = $_POST["discount"];
	$sell->cash = $_POST["money"];
	$sell->stock_to_id = StockData::getPrincipal()->id;

	$sell->process_cotization();
	$a = json_decode($_POST['op-q']);
	foreach ($a as $key) {
		$set_operation = new OperationData();
		$set_operation->q_approved = $key->q_ap;
		$set_operation->id = $key->id;
		$set_operation->update_q_approved();
	}

	foreach($operations as $op){
		$op->set_draft(0);
	}


	Core::alert("Cotizacion Procesada Exitosamente!");
	Core::redir("./?view=sells");

}


?>