<?php

$sell = SellData::getById($_GET["id"]);
$sell->del();
if (isset($_GET['from']) && $_GET['from'] == orders) {
	Core::redir("./index.php?view=orderscancel");
} else {
	Core::redir("./index.php?view=cotizationscancel");
}

?>