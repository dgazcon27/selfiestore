<?php

// define('LBROOT',getcwd()); // LegoBox Root ... the server root
// include("core/controller/Database.php");

if(!isset($_SESSION["user_id"])) {
$user = $_POST['username'];
$pass = sha1(md5($_POST['password']));

$base = new Database();
$con = $base->connect();
$sql = "select * from user where (email= \"".$user."\" or username= \"".$user."\") and password= \"".$pass."\" and status=1";
//print $sql;
$query = $con->query($sql);
$found = false;
$userid = null;
$kind = null;
if ($query->num_rows > 0) {
	$r = $query->fetch_array();
	$found = true;
	$userid = $r['id'];
	$kind = $r['kind'];
} 

if($found==true) {
	print "Cargando ... $user";
	$_SESSION['user_id']=$userid;
	if ($kind == 1) {
		$_SESSION['is_admin'] = true;
		print "<script>window.location='index.php?view=home';</script>";
	} elseif ($kind == 4 || $kind == 8) {
		# code...
		$_SESSION['is_client'] = true;
		$user_data = PersonData::getByUserId($userid);
		$_SESSION['client_id'] = $user_data->id;
		print "<script>window.location='index.php?view=cotizations';</script>";
	} elseif ($kind == 2) {
		print "<script>window.location='index.php?view=orders-approved';</script>";
	} elseif ($kind == 5) {
		print "<script>window.location='index.php?view=cotizations';</script>";
		
	} else {
		print "<script>window.location='index.php?view=home';</script>";
	}
}else {
	print "<script>window.location='index.php?view=login';</script>";
}

}else{
	print "<script>window.location='index.php?view=home';</script>";
	
}
?>