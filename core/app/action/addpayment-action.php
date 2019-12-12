<?php

if(count($_POST)>0){

 	$sell = SellData::getById($_POST["sellid"]);
 	if(count($sell)>0)
    {

		$payment2 = new PaymentData();
	 	$payment2->val = -1*$_POST["val"];
	 	$payment2->person_id = $_POST["client_id"];
	 	$payment2->sell_id = $_POST["sellid"];
	 	$payment2->add_payment();
    	//Inicio actualizar abonos en la venta
		
    	$payments = $sell->payments + $_POST["val"];
		SellData::updateSellPaymentById($_POST["sellid"],$payments);
		//Fin actualizar abonos en la venta
		//Inicio actualizar estatus de la venta a credito
		if(($sell->total - $_POST["val"] - $sell->discount - $sell->payments) == 0){
			$sell->p_id=1;
			$sell->update_p();
		}
		$sell->created_at = date('Y-m-d H:i:s');
		$sell->update_date();
	}
	//Fin actualizar estatus de la venta a credito
	print "<script>window.location='index.php?view=credit';</script>";
}


?>