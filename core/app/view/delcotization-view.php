<?php

$sell = SellData::getById($_GET["id"]);
$sell->del();
Core::redir("./index.php?view=cotizationscancel");

?>