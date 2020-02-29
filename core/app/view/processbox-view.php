<?php
$sells = SellData::getSellsUnBoxed();

$box = new BoxData();
$box->stock_id = StockData::getPrincipal()->id;
if(count($sells) > 0){
	$b = $box->add();
	foreach($sells as $sell){
		$sell->box_id = $b[1];
		$sell->update_box();
	}
}

$spends = SpendData::getAllUnBoxed();

if (count($spends) > 0) {
	if (!isset($b[1])) {
		$b = $box->add();
	}
	foreach ($spends as $spend) {
		$spend->box_id = $b[1];
		$spend->update_box();
	}
}

$payments = PaymentData::getAllUnBoxed();
if (count($payments) > 0) {
	if (!isset($b[1])) {
		$b = $box->add();
	}
	foreach ($payments as $pay) {
		$pay->box_id = $b[1];
		$pay->update_box();
	}
}

Core::redir("./index.php?view=b&id=".$b[1]);
?>