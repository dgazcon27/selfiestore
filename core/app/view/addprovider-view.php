<?php

if(count($_POST)>0)
{
	$user = new PersonData();
	$user->no = $_POST["no"];
	$user->name = $_POST["name"];
	$user->address1 = $_POST["address1"];
	$user->phone1 = $_POST["phone1"];
	$user->add_provider();

print "<script>window.location='index.php?view=providers';</script>";


}


?>