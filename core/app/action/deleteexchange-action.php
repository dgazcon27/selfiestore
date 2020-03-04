<?php 
	$exchange = new ExchangeData();
	if (isset($_GET['id'])) {
		$exchange->delById($_GET['id']);
	}
	Core::redir("./index.php?view=exchanges")
?>