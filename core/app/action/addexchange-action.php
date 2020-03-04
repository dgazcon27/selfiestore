<?php

	$exchange = new ExchangeData();
	$exchange->description = $_POST['description'];
	$exchange->value = $_POST['value'];
	if (isset($_POST['id'])) {
		$exchange->id = $_POST['id'];
		$exchange->update();
	} else {
		$exchange->add();
	}
	Core::redir("./index.php?view=exchanges")

?>