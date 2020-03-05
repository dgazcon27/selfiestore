<?php 
	
	header('Content-Type: text/html; charset=UTF-8');

	// include "core/controller/Core.php";
	// include "core/controller/Database.php";
	// include "core/controller/Executor.php";
	// include "core/controller/Model.php";
	// include "core/app/model/UserData.php";
	// include "core/app/model/SellData.php";
	// include "core/app/model/OperationData.php";
	// include "core/app/model/ProductData.php";
	// include "core/app/model/PersonData.php";
	// include "core/app/model/StockData.php";
	// include "core/app/model/ConfigurationData.php";
	include "fpdf/fpdf.php";
	
	// include 'barcode.php';

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

	// VARIABLES DE CONTADO
	$count_sells = 0;
	$total_efectivo_contado = 0;
	$total_pt_contado = 0;
	$total_trans_contado = 0;
	$total_zelle_contado = 0;
	$tota_descuentos_contado = 0;
	///////////////////////////////


	// VARIABLES DE CREDITOS
	$total_credit = 0;
	$total_credit_invested = 0;
	$total_credit_closed = 0;
	$total_payments = 0;
	$total_count_credits = 0;
	$total_credits_active = 0;
	$total_discount_credit = 0;

	$total_efectivo_credit = 0;
	$total_pt_credit = 0;
	$total_trans_credit = 0;
	$total_zelle_credit = 0;

	$credit_efe_close = 0;
	$credit_pt_close = 0;
	$credit_tra_close = 0;
	$credit_zel_close = 0;
	////////////////////////////////

	$total = 0;
	$total_invested = 0;


	$total_ganado_contado = 0;
	$credit_array = array();

	$response = array();
	$clients = array();
	if (count($operations)) {
		foreach ($operations as $key) {
			
			// operaciones de contado
			if ($key->p_id == 1) {
				$total += ($key->total-$key->discount);
				$tota_descuentos_contado += $key->discount;
				$total_invested += $key->invoice_code;
				$count_sells += 1;
				if ($key->f_id == 1) {
					$total_efectivo_contado += $key->total-$key->discount;
				} elseif ($key->f_id == 2) {
					$total_trans_contado += $key->total-$key->discount;
				} elseif ($key->f_id == 3) {
					$total_zelle_contado += $key->total-$key->discount;
				} elseif($key->f_id == 4){
					$total_efectivo_contado += $key->efe;
		        	$total_pt_contado += $key->pun;
		        	$total_trans_contado += $key->tra;	
		        	$total_zelle_contado += $key->zel;
				} elseif ($key->f_id == 5) {
					$total_pt_contado += $key->total-$key->discount;
				}
				# code...
			}

			if ($key->p_id == 4 && $key->payments > 0) {
				$total_count_credits += 1;
				$total_payments += (float)$key->cash+(float)$key->payments;
				$total_efectivo_credit += (float)$key->cash+(float)$key->payments;
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
							}
						}
					}
					$clients[$key->person_id] = $key->person_id;
				}
			}



		}
	}
	$total_ganado_contado = $total-$total_invested;

	// GET CREDITS DETAILS
	$list_of_credits = [];
	$users = PersonData::getClientsWithCredit();
	if (count($users) > 0) {
		foreach ($users as $user) {
			$sells = SellData::getCreditsByClientId($user->id);
			if (count($sells) > 0) {
				$sell_client = [];
				foreach ($sells as $sell) {
					$date_created = new DateTime($sell->created_at); 
					$date_start = new DateTime($_GET['start']); 
					$date_end = new DateTime($_GET['end']); 
					if ($date_created >= $date_start && $date_created <= $date_end) {
						$total_credits_active += 1;
						array_push($sell_client, $sell);
					}
				}
				array_push($list_of_credits, array("person"=>$user, "credits"=>$sell_client));
			}
		}
	}

	// BEGIN GENERATE PDF
	
	$core = new Core();
	$image = ConfigurationData::getByPreffix('report_image')->val;
	if (isset($image) && $image != "") {
		$image = "storage/configuration/".$image;
	} else {
		$image = "img/factura1.png";
	}
	$core->setCurrentCellPosition($core->base_cell_report_global);
	$core->setSpaceCell(7);


	$pdf = new FPDF($orientation='P',$unit='mm', $size='Letter');
	$pdf->AddPage();
	$pdf->setX(90);
	$pdf->Cell(0,0,($pdf->Image($image,null,null,45,35)),0,1,"C");
	$pdf->SetFont('Arial','B',19); 

	$pdf->setY($core->getCurrentCellPosition());
	$pdf->SetTextColor(255);
	$pdf->setFillColor(0);
	$pdf->Cell(0,15, "REPORTE GENERAL",1,0,'C',1);
	$pdf->setY($core->getNextSpaceCell(15));
	$pdf->Cell(0,15, "",1,0,'C',0);
	$pdf->SetTextColor(0);

	// INICIO TITULO OPERACIONES DE CONTADO
	$pdf->setY($core->getNextSpaceCell(10));
	$pdf->SetFont('Arial','B',19); 
	$pdf->setFillColor(180,198,232);
	$pdf->Cell(0,20, "OPERACIONES DE CONTADO", 1,0,'C',1);


	// CELDA VENTAS PROCESADAS
	$pdf->SetFont('Arial','B',10); 
	$pdf->setY($core->getNextSpaceCell(20));
	$pdf->Cell(100,7, "CANTIDAD DE VENTAS PROCESADAS", 1,0,'C',0);
	$pdf->setX(110);
	$pdf->Cell(96,7, $count_sells, 1,0,'C',0);

	// ESPACIO VACIO
	$pdf->setY($core->getNextSpaceCell());
	$pdf->Cell(0,7, '', 1,0,'C',0);

	// TITULOS TOTAL DE INVERSIONES Y GANANCIAS
	$pdf->SetTextColor(255);
	$pdf->setFillColor(0);
	$pdf->setY($core->getNextSpaceCell());
	$pdf->Cell(64.5,7, "TOTAL, INVERSION EN VENTAS", 1,0,'C',1);
	$pdf->setX(75);
	$pdf->Cell(65.5,7, "TOTAL, GANACIA EN VENTAS", 1,0,'C',1);
	$pdf->setX(141);
	$pdf->Cell(65,7, "TOTAL, DESCUENTOS EN VENTAS", 1,0,'C',1);

	// TOTALES INVERSIONES Y GANANCIAS
	$pdf->SetTextColor(0);
	$pdf->setY($core->getNextSpaceCell());
	$pdf->Cell(64.5,7,number_format($total_invested,2,'.',',')." $" , 1,0,'C',0);
	$pdf->setX(75);
	$pdf->Cell(65.5,7, number_format($total_ganado_contado,2,'.',',')." $", 1,0,'C',0);
	$pdf->setX(141);
	$pdf->Cell(65,7, number_format($tota_descuentos_contado,2,'.',',')." $", 1,0,'C',0);

	// CELDA DE ESPACIO
	$pdf->setY($core->getNextSpaceCell());
	$pdf->Cell(0,7, '', 1,0,'C',0);

	// TITULOS TIPOS DE VENTAS
	$pdf->SetTextColor(255);
	$pdf->setFillColor(0);
	$pdf->setY($core->getNextSpaceCell());
	$pdf->Cell(49.5,7, "TOTAL, EFECTIVO", 1,0,'C',1);
	$pdf->setX(60);
	$pdf->Cell(49.5,7, "TOTAL, PUNTO DE VENTA", 1,0,'C',1);
	$pdf->setX(110);
	$pdf->Cell(49.5,7, "TOTAL, TRANSFERENCIA", 1,0,'C',1);
	$pdf->setX(160);
	$pdf->Cell(46,7, "TOTAL, ZELLE", 1,0,'C',1);
	$pdf->setFillColor(255);
	$pdf->SetTextColor(0);

	// TOTALES TIPOS DE VENTA
	$pdf->setY($core->getNextSpaceCell());
	$pdf->Cell(49.5,7, number_format($total_efectivo_contado,2,'.',',')." $" , 1,0,'C',1);
	$pdf->setX(60);
	$pdf->Cell(49.5,7, number_format($total_pt_contado,2,'.',',')." $" , 1,0,'C',1);
	$pdf->setX(110);
	$pdf->Cell(49.5,7, number_format($total_trans_contado,2,'.',',')." $" , 1,0,'C',1);
	$pdf->setX(160);
	$pdf->Cell(46,7, number_format($total_zelle_contado,2,'.',',')." $" , 1,0,'C',1);

	//CELDA TOTAL
	$pdf->setY($core->getNextSpaceCell());
	$pdf->Cell(0,21, "", 1,0,'C',0);

	// TITULO TOTAL
	$pdf->SetFont('Arial','',14); 
	$pdf->setY($core->getNextSpaceCell());
	$pdf->setX(110);
	$pdf->Cell(100,7, "TOTAL", 0,0,'C',0);

	// VALOR TOTAL DE VENTAS DE CONTADO	
	$pdf->setY($core->getNextSpaceCell());
	$pdf->SetFont('Arial','B',13); 
	$pdf->setFillColor(0);
	$pdf->SetTextColor(255);
	$pdf->setX(110);
	$pdf->Cell(96,7, number_format($total,2,'.',',')." $", 1,0,'C',1);
	$pdf->setFillColor(255);
	$pdf->SetTextColor(0);

	// INICIO TITULO OPERACIONES DE CREDITO
	$pdf->setY($core->getNextSpaceCell(14));
	$pdf->SetFont('Arial','B',19); 
	$pdf->setFillColor(180,198,232);
	$pdf->Cell(0,20, "OPERACIONES DE CREDITO", 1,0,'C',1);

	//TITULO ABONOS
	$pdf->setY($core->getNextSpaceCell(14));
	$pdf->SetFont('Arial','B',12); 
	$pdf->setFillColor(0);
	$pdf->SetTextColor(255);
	$pdf->Cell(0,7, "ABONOS", 1,0,'C',1);
	$pdf->setFillColor(255);
	$pdf->SetTextColor(0);

	// TITULO CANTIDAD DE ABONOS PROCESADOS
	$pdf->SetFont('Arial','B',10); 
	$pdf->setY($core->getNextSpaceCell());
	$pdf->Cell(100,7, "CANTIDAD DE ABONOS PROCESADOS", 1,0,'C',0);
	$pdf->setX(110);
	$pdf->Cell(96,7, $total_count_credits, 1,0,'C',0);

	// TITULOS TIPOS DE ABONOS
	$pdf->SetTextColor(255);
	$pdf->setFillColor(0);
	$pdf->setY($core->getNextSpaceCell());
	$pdf->Cell(49.5,7, "TOTAL, EFECTIVO", 1,0,'C',1);
	$pdf->setX(60);
	$pdf->Cell(49.5,7, "TOTAL, PUNTO DE VENTA", 1,0,'C',1);
	$pdf->setX(110);
	$pdf->Cell(49.5,7, "TOTAL, TRANSFERENCIA", 1,0,'C',1);
	$pdf->setX(160);
	$pdf->Cell(46,7, "TOTAL, ZELLE", 1,0,'C',1);
	$pdf->setFillColor(255);
	$pdf->SetTextColor(0);

	// TOTALES TIPOS DE VENTA
	$pdf->setY($core->getNextSpaceCell());
	$pdf->Cell(49.5,7, number_format($total_efectivo_credit,2,'.',',')." $" , 1,0,'C',1);
	$pdf->setX(60);
	$pdf->Cell(49.5,7, number_format($total_pt_credit,2,'.',',')." $" , 1,0,'C',1);
	$pdf->setX(110);
	$pdf->Cell(49.5,7, number_format($total_trans_credit,2,'.',',')." $" , 1,0,'C',1);
	$pdf->setX(160);
	$pdf->Cell(46,7, number_format($total_zelle_credit,2,'.',',')." $" , 1,0,'C',1);

	//CELDA TOTAL ABONOS
	$pdf->setY($core->getNextSpaceCell()); 
	$pdf->Cell(0,21, "", 1,0,'C',0);

	// TITULO TOTAL ABONOS
	$pdf->SetFont('Arial','',14); 
	$pdf->setY($core->getNextSpaceCell());
	$pdf->setX(110);
	$pdf->Cell(100,7, "TOTAL", 0,0,'C',0);

	// VALOR TOTAL DE ABONOS
	$pdf->setY($core->getNextSpaceCell());
	$pdf->SetFont('Arial','B',13); 
	$pdf->setFillColor(0);
	$pdf->SetTextColor(255);
	$pdf->setX(110);
	$pdf->Cell(96,7, number_format($total_payments,2,'.',',')." $", 1,0,'C',1);
	$pdf->setFillColor(255);
	$pdf->SetTextColor(0);
	// SEGUNDA PAGINA
	$pdf->AddPage();
	$core->setCurrentCellPosition(7);

	//TITULO ABONOS
	$pdf->setY($core->getNextSpaceCell());
	$pdf->SetFont('Arial','B',14); 
	$pdf->setFillColor(0);
	$pdf->SetTextColor(255);
	$pdf->Cell(0,7, "BALANCE DE CREDITOS", 1,0,'C',1);
	$pdf->setFillColor(255);
	$pdf->SetTextColor(0);

	// CREDITOS ACTIVOS
	$pdf->SetFont('Arial','B',10); 
	$pdf->setY($core->getNextSpaceCell());
	$pdf->Cell(100,7, "CANTIDAD DE CREDITOS ACTIVOS", 1,0,'C',0);
	$pdf->setX(110);
	$pdf->Cell(96,7, $total_credits_active, 1,0,'C',0);

	// CELDA DE ESPACIO
	$pdf->setY($core->getNextSpaceCell());
	$pdf->Cell(0,21, '', 1,0,'C',0);

	// TITULOS TIPOS DE ABONOS
	$pdf->setY($core->getNextSpaceCell(14));
	$pdf->SetTextColor(255);
	$pdf->setFillColor(0);
	$pdf->setY($core->getNextSpaceCell());
	$pdf->Cell(49.5,8, "NOMBRE", 1,0,'C',1);
	$pdf->setX(60);
	$pdf->Cell(49.5,8, "TELEFONO", 1,0,'C',1);
	$pdf->setX(110);
	$pdf->Cell(49.5,8, "CREDITOS ACTIVOS", 1,0,'C',1);
	$pdf->setX(160);
	$pdf->Cell(46,8, "LIMITE CREDITICIO", 1,0,'C',1);
	$pdf->setFillColor(255);
	$pdf->SetTextColor(0);

	// LIST OF CREDITS
	$pdf->SetFont('Arial','B',9); 
	$core->setSpaceCell(5);
	$pdf->setY($core->getNextSpaceCell(8));

	if (count($list_of_credits) > 0) {
		foreach ($list_of_credits as $list) {
			if (count($list['credits'])>0) {
				$pdf->SetTextColor(0);
				$pdf->SetFont('Arial','',9); 
				$pdf->setFillColor(255, 192, 0);
				$pdf->Cell(50,5, $list['person']->name." ".$list['person']->lastname, 1,0,'C',1);
				$pdf->setX(60);
				$pdf->Cell(50,5, $list['person']->phone1, 1,0,'C',1);
				$pdf->setX(110);
				$pdf->Cell(50,5, count($list['credits']), 1,0,'C',1);
				$pdf->setX(160);
				$pdf->Cell(46,5, $list['person']->credit_limit, 1,0,'C',1);

				if ($core->getCurrentCellPosition() >= 236) {
					$pdf->AddPage();
					$core->setCurrentCellPosition(7);					
				}

				$pdf->setY($core->getNextSpaceCell());
				$pdf->SetFont('Arial','B',8); 
				$pdf->setFillColor(231, 230, 230);
				$pdf->Cell(33,5, "# DE CREDITO", 1,0,'C',1);
				$pdf->setX(43);
				$pdf->Cell(30,5, "TOTAL", 1,0,'C',1);
				$pdf->setX(73);
				$pdf->Cell(37,5, "ABONADO", 1,0,'C',1);
				$pdf->setX(110);
				$pdf->Cell(33,5, "DEBE", 1,0,'C',1);
				$pdf->setX(143);
				$pdf->Cell(33,5, "FECHA DE CREDITO", 1,0,'C',1);
				$pdf->setX(176);
				$pdf->Cell(30,5, "ESTADO", 1,0,'C',1);
				if ($core->getCurrentCellPosition() >= 236) {
					$pdf->AddPage();
					$core->setCurrentCellPosition(7);					
				}
				$pdf->setY($core->getNextSpaceCell());
				$total_deb = 0;
				$payments_maked = 0;
				foreach ($list['credits'] as $credits) {
					$deb = $credits->total-$credits->discount-($credits->cash+$credits->payments);
					$date_deb= date("d/m/Y", strtotime($credits->created_at));
					$total_deb += $credits->total-$credits->discount;
					// $pdf->SetTextColor(237,125,49);
					$state = "PENDIENTE";
					$payments_maked += $credits->cash+$credits->payments;
					if ($deb == 0) {
						$total_discount_credit += $credits->discount;
						$total_credit += $credits->total-$credits->discount;
						$total_credit_invested += $credits->invoice_code;
						$credit_efe_close += $credits->total-$credits->discount;
						$state = "COMPLETO";
					}

					$pdf->SetTextColor(0);
					$pdf->Cell(33,5,"#".$credits->ref_id, 1,0,'C',0);
					$pdf->setX(43);
					$pdf->Cell(30,5,number_format($credits->total-$credits->discount,2,'.',',')." $" , 1,0,'C',0);
					$pdf->setX(73);
					$pdf->Cell(37,5,number_format($credits->cash+$credits->payments,2,'.',',')." $" , 1,0,'C',0);
					$pdf->setX(110);
					$pdf->Cell(33,5, number_format($deb,2,'.',',')." $", 1,0,'C',0);
					$pdf->setX(143);
					$pdf->Cell(33,5, $date_deb, 1,0,'C',0);
					$pdf->setX(176);
					$pdf->SetTextColor(237,125,49);
					$state = "PENDIENTE";
					if ($deb == 0) {
						$pdf->SetTextColor(0,176,80);
						$state = "COMPLETO";
					}
					$pdf->Cell(30,5, $state, 1,0,'C',0);
					if ($core->getCurrentCellPosition() >= 236) {
						$pdf->AddPage();
						$core->setCurrentCellPosition(7);					
					}
					$pdf->setY($core->getNextSpaceCell());
				}
				$pdf->SetTextColor(255);
				$pdf->setFillColor(0);
				$pdf->setX(110);
				$pdf->Cell(96,5, "SALDO PENDIENTE: ".number_format($total_deb-$payments_maked,2,'.',',')." $", 1,0,'C',1);
				$pdf->setFillColor(255);
				$pdf->SetTextColor(0);
				$pdf->setY($core->getNextSpaceCell());
				

			}
		}
	}
	// CELDA DE ESPACIO
	$core->setSpaceCell(7);
	$pdf->Cell(0,7, '', 1,0,'C',0);
	$pdf->SetFont('Arial','B',10); 
	// TITULOS TOTAL DE INVERSIONES Y GANANCIAS CREDITOS DETALLADOS
	$pdf->SetTextColor(255);
	$pdf->setFillColor(0);
	$pdf->setY($core->getNextSpaceCell());
	$pdf->Cell(64.5,7, "TOTAL, INVERSION EN CREDITOS", 1,0,'C',1);
	$pdf->setX(75);
	$pdf->Cell(65.5,7, "TOTAL, GANACIA EN CREDITOS", 1,0,'C',1);
	$pdf->setX(141);
	$pdf->Cell(65,7, "TOTAL, DESCUENTOS EN CREDITOS", 1,0,'C',1);
	$pdf->setFillColor(255);
	$pdf->SetTextColor(0);

	
	// TOTALES DE INVERSIONES Y CREDITOS DETALLADOS
	$pdf->setY($core->getNextSpaceCell());	
	$pdf->Cell(64.5,7, number_format($total_credit_invested,2,'.',',')." $", 1,0,'C',0);
	$pdf->setX(75);
	$pdf->Cell(65.5,7, number_format($total_credit-$total_credit_invested,2,'.',',')." $", 1,0,'C',0);
	$pdf->setX(141);
	$pdf->Cell(65,7, number_format($total_discount_credit,2,'.',',')." $", 1,0,'C',0);

	if ($core->getCurrentCellPosition() >= 236) {
		$pdf->AddPage();
		$core->setCurrentCellPosition(7);					
	}
	// TITULOS TIPOS DE CREDITOS
	$pdf->SetTextColor(255);
	$pdf->setFillColor(0);
	$pdf->setY($core->getNextSpaceCell());
	$pdf->Cell(49.5,7, "TOTAL, EFECTIVO", 1,0,'C',1);
	$pdf->setX(60);
	$pdf->Cell(49.5,7, "TOTAL, PUNTO DE VENTA", 1,0,'C',1);
	$pdf->setX(110);
	$pdf->Cell(49.5,7, "TOTAL, TRANSFERENCIA", 1,0,'C',1);
	$pdf->setX(160);
	$pdf->Cell(46,7, "TOTAL, ZELLE", 1,0,'C',1);
	$pdf->SetTextColor(0);

	// TOTALES TIPOS DE VENTA
	$pdf->setY($core->getNextSpaceCell());
	$pdf->Cell(49.5,7, number_format($credit_efe_close,2,'.',',')." $" , 1,0,'C',0);
	$pdf->setX(60);
	$pdf->Cell(49.5,7, number_format($credit_pt_close,2,'.',',')." $" , 1,0,'C',0);
	$pdf->setX(110);
	$pdf->Cell(49.5,7, number_format($credit_tra_close,2,'.',',')." $" , 1,0,'C',0);
	$pdf->setX(160);
	$pdf->Cell(46,7, number_format($credit_zel_close,2,'.',',')." $" , 1,0,'C',0);

	if ($core->getCurrentCellPosition() >= 236) {
		$pdf->AddPage();
		$core->setCurrentCellPosition(7);					
	}

	//CELDA TOTAL
	$pdf->setY($core->getNextSpaceCell());
	$pdf->Cell(0,21, "", 1,0,'C',0);

	// TITULO TOTAL
	$pdf->SetFont('Arial','',14); 
	$pdf->setY($core->getNextSpaceCell());
	$pdf->setX(110);
	$pdf->Cell(100,7, "TOTAL", 0,0,'C',0);

	// VALOR TOTAL DE VENTAS DE CONTADO	
	$pdf->setY($core->getNextSpaceCell());
	$pdf->SetFont('Arial','B',13); 
	$pdf->setFillColor(0);
	$pdf->SetTextColor(255);
	$pdf->setX(110);
	$pdf->Cell(96,7, number_format($credit_efe_close,2,'.',',')." $", 1,0,'C',1);
	$pdf->setFillColor(255);
	$pdf->SetTextColor(0);

	if ($core->getCurrentCellPosition() >= 236) {
		$pdf->AddPage();
		$core->setCurrentCellPosition(7);					
	}

	$products = array();
	foreach ($operations as $op) {
		if ($op->operation_type_id == 2) {
			
		}
	}
	// BALANCE DE PRODUCTOS
	// INICIO TITULO OPERACIONES DE CREDITO
	$pdf->setY($core->getNextSpaceCell(14));
	$pdf->SetFont('Arial','B',19); 
	$pdf->setFillColor(180,198,232);
	$pdf->Cell(0,20, "BALANCE DE PRODUCTOS", 1,0,'C',1);

	$products = array();
	foreach ($operations as $op) {
		if ($op->operation_type_id == 2) {
			
		}
	}


	$pdf->Output();


?>