<?php

if(count($_POST)>0){
	$user = PersonData::getById($_POST["user_id"]);
	$user->no = $_POST["no"];
	$user->name = $_POST["name"];
	$user->address1 = $_POST["address1"];
	$user->phone1 = $_POST["phone1"];
	$user->specialties = $_POST["specialties"];
	$user->update_provider();


print "<script>window.location='index.php?view=providers';</script>";


}


?>