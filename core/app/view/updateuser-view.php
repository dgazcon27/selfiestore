<?php
if(count($_POST)>0){
	$user = UserData::getById($_POST["user_id"]);
	$user->stock_id = isset($_POST["stock_id"]) ? $_POST["stock_id"] : 1;
	$user->comision = isset($_POST["comision"]) && $_POST["comision"]!="" ? $_POST["comision"]:"NULL";

	if(isset($_FILES["image"])){
		$image = new Upload($_FILES["image"]);
		if($image->uploaded){
			$image->Process("storage/profiles/");
			if($image->processed){
				$user->image = $image->file_dst_name;
			}
		}
	}
	$user->name = $_POST["name"];
	$user->lastname = $_POST["lastname"];
	$user->email = $_POST["email"];
	$user->status = isset($_POST["status"])?1:0;
	$user->update();
	if (isset($_POST['kind']) && ($_POST['kind'] == 8 || $_POST['kind'] == 4)) {
		$person = new PersonData();
		$person->id = PersonData::getByUserId($_POST["user_id"])->id;
		$person->phone1 = $_POST["phone1"];
		$person->ci = $_POST['ci'];
		$person->name = $_POST['name'];
		$person->email1 = $user->email;
		$person->lastname = $_POST['lastname'];
		$person->rif = $_POST['rif'];
		$person->company = $_POST['company_name'];
		$person->phone2 = $_POST['company_phone'];
		$person->address2 = $_POST['company_address'];
		$person->update_client();
	}
	if($_POST["password"]!=""){
		$user->password = sha1(md5($_POST["password"]));
		$user->update_passwd();
	}

	print "<script>alert('Usuario actualizado exitosamente');</script>";
	print "<script>window.location='index.php?view=users';</script>";
}


?>