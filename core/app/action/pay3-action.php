<?php

if(isset($_GET["id"])){
	$sell = SellData::getById($_GET["id"]);
	$sell->p_id=1;
	
	$fecha = 0;
	$fecha = $sell->created_at;
	$update = $this->created_at = "NOW()";
	
	
	$sell->update_p() = $update;
	Core::redir("./?view=sellscredit");
	
	
}
?>