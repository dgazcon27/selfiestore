<?php 
	$operations = array();

	if($_GET["client"]=="" && $_GET["user"]==""){
		$operations = SellData::getAllByDateOp($_GET["start"],$_GET["end"],2);
	}
	else if($_GET["client"]=="" && $_GET["user"]!=""){
		$operations = SellData::getAllByDateOpByUserId($_GET["user"],$_GET["start"],$_GET["end"],2);
	}
	else if($_GET["client"]!="" && $_GET["user"]==""){
		$operations = SellData::getAllByDateBCOp($_GET["client"],$_GET["start"],$_GET["end"],2);
	}else{
		$operations = SellData::getAllByDateBCOpByUserId($_GET["user"],$_GET["client"],$_GET["start"],$_GET["end"],2);
	}

	// Total gastos
	$spend_total = 0;
	if ($_GET['start'] != "" && $_GET['end'] != "") {
		$spend = SpendData::getGroupByDateOpReport($_GET['start'], $_GET['end']);
	} else {
		$start = date("d-m-Y h:i:s",time());
		$end = date("d-m-Y",strtotime($start."+ 7 days")); 
		$spend = SpendData::getGroupByDateOpReport($start, $end);
	}
	if (count($spend) >0) {
		foreach ($spend as $value) {
			$spend_total += $value->price;
		}
	}

	$total = 0;
	$total_invested = 0;

	$total_credit = 0;
	$total_credit_invested = 0;
	$total_credit_closed = 0;
	$total_payments = 0;

	$total_win = 0;
	$credit_array = array();

	$response = array();
	$clients = array();
	if (count($operations)) {
		foreach ($operations as $key) {
			
			// operaciones de contado
			if ($key->p_id == 1) {
				$total += ($key->total-$key->discount);
				$total_invested += $key->invoice_code;
				# code...
			}
			if ($key->p_id == 4 && $key->payments > 0) {
				$total_credit += ($key->total-$key->discount);
				$total_payments += (float)$key->cash+(float)$key->payments;
				// $total_credit_invested += (float)$key->invoice_code;
				$person_total = 0;
				$person_paid = 0;
				$person_invested = 0;
				if (isset($key->person_id) && !isset($clients[$key->person_id])) {
					$person = SellData::getCreditsByClientId($key->person_id);
					if (count($person) > 0) {
						foreach ($person as $bill) {
							$x = $bill->total-$bill->discount;
							$s = $bill->cash+$bill->payments;
							if ($x-$s == 0) {
								// $person_total += $bill->total-$bill->discount;
								$total_credit_closed += $bill->cash+$bill->payments;
								$total_credit_invested += $bill->invoice_code;
							}
						}

					}
					$clients[$key->person_id] = $key->person_id;
				}
			}
		}
	}
	// $total_credit_invested = 0;
	// $total_credit_closed = 0;
	// if (count($credit_array) > 0) {
	// 	foreach ($credit_array as $key) {
	// 		$total_credit_invested += $key['invested'];
	// 		$total_credit_closed += $key['closed'];
	// 	}
	// }
	$gain = ($total+$total_credit_closed)-($total_invested+$total_credit_invested+$spend_total);
	$response = array(

		'selled' => number_format($total,2,'.',','),
		'payments' => number_format($total_payments,2,'.',','),
		'closed_credit' => number_format($total_credit_closed,2,'.',',') ,
		'global_total' => number_format($total+$total_credit_closed,2,'.',','),
		'invested' => number_format($total_invested+$total_credit_invested,2,'.',',') ,
		'spend' => number_format($spend_total,2,'.',','),
		'gain'=> number_format($gain,2,'.',','),
		'ceo' => number_format($gain*0.70,2,'.',','),
		'markenting' => number_format($gain*0.05,2,'.',','),
		'manager' => number_format($gain*0.25,2,'.',',') ,
		'to_get' =>number_format($total_credit-$total_payments,2,'.',','),

	);
	echo json_encode($response);

?>