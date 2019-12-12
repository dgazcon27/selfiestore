<?php

if(isset($_GET["id"])){
$sell = SellData::getById($_GET["id"]);
$sell->p_id=1;

	$update = $this->created_at = "NOW()";
		
	
$sell->update_p() = $update;
Core::redir("./?view=bycob");
}
?>