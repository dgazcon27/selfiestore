<?php

if(count($_POST)>0){
	$user = new PersonData();
	$user->no = $_POST["no"];
	$user->ci = $_POST["no"];
	$user->name = $_POST["name"];
	$user->lastname = $_POST["lastname"];
	$user->address1 = $_POST["address1"];
	$user->phone1 = $_POST["phone1"];
	$user->is_active_access = 0;
	$user->has_credit = isset($_POST["has_credit"])?1:0;
	$user->add_solo_client();

print "<script>window.location='index.php?view=clients';</script>";


header('Location: ./?view=sell');


}


?>