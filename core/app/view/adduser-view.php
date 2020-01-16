<?php
if(count($_POST)>0){
	$is_admin=0;
	if(isset($_POST["is_admin"])){$is_admin=1;}
	$user = new UserData();

	$user->kind = $_POST["kind"];
	$user->stock_id = isset($_POST["stock_id"])?$_POST["stock_id"]:"NULL";
	$user->comision = isset($_POST["comision"])&&$_POST["comision"]!=""?$_POST["comision"]:"NULL";

	$user->image="";
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
	$user->username = $_POST["username"];
	$user->email = $_POST["email"];
	$user->is_admin=$is_admin;
	$user->password = sha1(md5($_POST["password"]));
	$u = $user->add();

	$person = new PersonData();
	$person->no = "";

	// CLIENTE DATA
	$person->ci = $_POST['ci'];
	$person->name = $_POST['name'];
	$person->lastname = $_POST['lastname'];
	$person->phone1 = $_POST["phone1"];
	$person->email1 = isset($_POST["email"]) ? $_POST["email"]: "NULL";

	// COMPANY DATA
	$person->rif = isset($_POST['rif']) ? $_POST['rif'] : "NULL";
	$person->company = isset($_POST['company_name']) ? $_POST['company_name'] : "NULL";
	$person->phone2 = isset($_POST['company_phone']) ? $_POST['company_phone'] : "NULL";
	$person->address2 = isset($_POST['company_address']) ? $_POST['company_address'] : "NULL";

	$person->is_active_access = 0;
	$person->kind = $user->kind;
	$person->credit_limit = 0;
	$person->has_credit = 0;
	$person->created_at = "NOW()";
	$person->user_id = $u[1];
	$person->add_client();

	Core::alert("Usuario Agregado Exitosamente!");
	print "<script>window.location='index.php?view=users';</script>";
}


?>