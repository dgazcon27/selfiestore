<?php

if(count($_POST)>0){
	$user = PersonData::getById($_POST["user_id"]);
	$user->no = $_POST["no"];
	$user->name = $_POST["name"];
	$user->address1 = $_POST["address1"];
	$user->phone1 = $_POST["phone1"];
	$user->credit_limit = isset($_POST["credit_limit"]) ? $_POST["credit_limit"] : 0;

	$user->is_active_access = isset($_POST["is_active_access"])?1:0;
	$user->has_credit = isset($_POST["has_credit"])?1:0;

	$user->update_solo_client();


print "<script>window.location='index.php?view=clients';</script>";


}


?>