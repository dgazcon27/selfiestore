<?php

$sell = SellData::getById($_GET["id"]);
$operations = OperationData::getAllProductsBySellId($_GET["id"]);

foreach ($operations as $op) {
	$op->cancel();
}

$sell->cancel();
if (isset($_GET['from']) && $_GET['from'] == 'orders') {
	Core::redir("./index.php?view=orders-approved");
} else {
	Core::redir("./index.php?view=cotizations");

}

?>