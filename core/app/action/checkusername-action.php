<?php 
	if (isset($_GET['username'])) {
		$user = UserData::checkUsername($_GET['username']);
		if (count($user) > 0) {
			echo "true";
		} else {
			echo "false";
		}
	}
?>