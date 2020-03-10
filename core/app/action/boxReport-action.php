<?php  

	header('Content-Type: text/html; charset=UTF-8');
	include "fpdf/fpdf.php";
	if (isset($_GET["id"])) {

		// GET SELLS

		$sells = SellData::getByBoxId($_GET["id"]);
		
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

		$pdf->setY($core->getCurrentCellPosition()-5);
		$pdf->SetTextColor(255);
		$pdf->setFillColor(0);
		$pdf->Cell(0,15, "CAJA #".$_GET["id"],1,0,'C',1);
		$pdf->SetTextColor(0);
		$pdf->setFillColor(255);
		$pdf->setY($core->getNextSpaceCell(10));
		$pdf->SetFont('Arial','',14); 
		$pdf->Cell(0,15, "REPORTE DE CAJA ".date("d/m/Y",strtotime($sells[0]->created_at)), 1,0,'C',0);

		// INICIO TITULO OPERACIONES DE CONTADO
		$pdf->setY($core->getNextSpaceCell(15));
		$pdf->SetFont('Arial','B',19); 
		$pdf->setFillColor(180,198,232);
		$pdf->Cell(0,20, "VENTAS DEL DIA", 1,0,'C',1);

		// TITULOS TOTAL DE INVERSIONES Y GANANCIAS
		$pdf->SetFont('Arial','B',10); 
		$pdf->SetTextColor(255);
		$pdf->setFillColor(0);
		$pdf->setY($core->getNextSpaceCell(20));
		$pdf->Cell(48,7, "FACTURA", 1,0,'C',1);
		$pdf->setX(58.5);
		$pdf->Cell(48,7, "METODO DE PAGO", 1,0,'C',1);
		$pdf->setX(107);
		$pdf->Cell(48,7, "REFERENCIA", 1,0,'C',1);
		$pdf->setX(155.5);
		$pdf->Cell(50.5,7, "TOTAL", 1,0,'C',1);
		$pdf->SetTextColor(0);

		$pdf->setY($core->getNextSpaceCell());
		$pdf->setX(10);
		$pdf->SetFont('Arial','',10); 

		$total_sells = 0;
		$total_efe = 0;
		$total_zelle = 0;
		$total_dual = 0;
		$total_pt = 0;
		$total_trans = 0;

		if (count($sells) > 0) {
			foreach ($sells as $sell) {
				$variable = "";
			  	if($sell->f_id == 1){
	        		$variable = "EFECTIVO";
	        		$total_efe += $sell->total-$sell->discount;
		        }elseif($sell->f_id == 2){
					$variable = "TRANSFERENCIA";
					$total_trans += $sell->total-$sell->discount;
				}
		        elseif($sell->f_id == 3){
					$variable = "ZELLE";
					$total_zelle += $sell->total-$sell->discount;
				}
		    	elseif($sell->f_id == 4){
		        	$variable = "DUAL";
		        	$total_efe += $sell->efe;
		        	$total_pt += $sell->pun;
		        	$total_trans += $sell->tra;	
		        	$total_zelle += $sell->zel;
				}
		    	elseif($sell->f_id == 5){
		        	$variable = "PUNTO DE VENTA";
		        	$total_pt += $sell->pun;
				} else {
					$variable = "N/A";
				}

				$total_sells += $sell->total-$sell->discount;
				$refe = isset($sell->refe) ? $sell->refe : "N/A";


				$pdf->Cell(48,7, "#".$sell->ref_id, 1,0,'C',0);
				$pdf->setX(58.5);
				$pdf->Cell(48,7, $variable, 1,0,'C',0);
				$pdf->setX(107);
				$pdf->Cell(48,7, $refe, 1,0,'C',0);
				$pdf->setX(155.5);
				$pdf->Cell(50.5,7, number_format($sell->total-$sell->discount,2,'.',',')." $", 1,0,'C',0);
				if ($core->getCurrentCellPosition() >= 236) {
					$pdf->AddPage();
					$core->setCurrentCellPosition(7);					
				}
				$pdf->setY($core->getNextSpaceCell());


			}
		}

		if ($core->getCurrentCellPosition() >= 236) {
			$pdf->AddPage();
			$core->setCurrentCellPosition(7);					
		}
		$pdf->SetFont('Arial','B',10); 
		$pdf->SetTextColor(255);
		$pdf->setX(10);
		$pdf->setY($core->getNextSpaceCell(7));
		$pdf->Cell(97,7, "TOTAL EN EFECTIVO", 1,0,'C',1);
		$pdf->setX(107.5);
		$pdf->Cell(98.5,7, "TOTAL EN TRANSFERENCIA", 1,0,'C',1);
		$pdf->SetTextColor(0);
		$pdf->setY($core->getNextSpaceCell());
		$pdf->setX(10);
		$pdf->Cell(97,7, number_format($total_efe,2,'.',',')." $" , 1,0,'C',0);
		$pdf->setX(107.5);
		$pdf->Cell(98.5,7, number_format($total_trans,2,'.',',')." $", 1,0,'C',0);
		$pdf->setY($core->getNextSpaceCell());
		$pdf->SetTextColor(255);
		$pdf->setX(10);
		$pdf->Cell(97,7, "TOTAL EN ZELLE", 1,0,'C',1);
		$pdf->setX(107.5);
		$pdf->Cell(98.5,7, "TOTAL EN PUNTO DE VENTA", 1,0,'C',1);
		$pdf->SetTextColor(0);
		$pdf->setY($core->getNextSpaceCell());
		$pdf->setX(10);
		$pdf->Cell(97,7, number_format($total_zelle,2,'.',',')." $" , 1,0,'C',0);
		$pdf->setX(107.5);
		$pdf->Cell(98.5,7, number_format($total_pt,2,'.',',')." $", 1,0,'C',0);

		$pdf->SetFont('Arial','B',13); 
		$pdf->setY($core->getNextSpaceCell());
		$pdf->Cell(0,21, '', 1,0,'C',0);
		$pdf->setY($core->getNextSpaceCell());
		$pdf->setX(107.5);
		$pdf->Cell(98,7, "TOTAL EN VENTAS", 0,0,'C',0);
		$pdf->setY($core->getNextSpaceCell());
		$pdf->setX(107.5);
		$pdf->SetTextColor(255);
		$pdf->Cell(98.5,7, number_format($total_sells,2,'.',',')." $", 1,0,'C',1);

		if ($core->getCurrentCellPosition() >= 236) {
			$pdf->AddPage();
			$core->setCurrentCellPosition(7);					
		}
		$pdf->setY($core->getNextSpaceCell());

		// INICIO TITULO OPERACIONES DE CONTADO
		$pdf->SetTextColor(0);
		$pdf->setY($core->getNextSpaceCell(5));
		$pdf->SetFont('Arial','B',19); 
		$pdf->setFillColor(180,198,232);
		$pdf->Cell(0,20, "ABONOS DEL DIA", 1,0,'C',1);
		$pdf->setY($core->getNextSpaceCell(20));
		$pdf->SetFont('Arial','B',10); 
		$pdf->SetTextColor(255);
		$pdf->setFillColor(0);

		$pdf->Cell(48,7, "FACTURA", 1,0,'C',1);
		$pdf->setX(58.5);
		$pdf->Cell(48,7, "CLIENTE", 1,0,'C',1);
		$pdf->setX(107);
		$pdf->Cell(48,7, "MONTO", 1,0,'C',1);
		$pdf->setX(155.5);
		$pdf->Cell(50.5,7, "FECHA", 1,0,'C',1);
		$pdf->SetTextColor(0);

		$total_payments = 0;
		$payments = PaymentData::getBoxedPayments($_GET["id"]);

		$pdf->setX(10);
		$pdf->setY($core->getNextSpaceCell());
		$pdf->SetFont('Arial','',10); 

		if (count($payments) > 0) {
			foreach ($payments as $pay) {
				$sell = SellData::getById($pay->sell_id);
				$person = PersonData::getById($pay->person_id);
				$paym = $pay->val;
				if ($paym < 0) {
					$paym = $paym*-1;
				}

				$pdf->Cell(48,7, "#".$sell->ref_id, 1,0,'C',0);
				$pdf->setX(58.5);
				$pdf->Cell(48,7,$person->name." ".$person->lastname, 1,0,'C',0);
				$pdf->setX(107);
				$pdf->Cell(48,7, number_format($paym,2,'.',',')." $", 1,0,'C',0);
				$pdf->setX(155.5);
				$pdf->Cell(50.5,7, date("d/m/Y",strtotime($pay->created_at)), 1,0,'C',0);
				if ($core->getCurrentCellPosition() >= 236) {
					$pdf->AddPage();
					$core->setCurrentCellPosition(7);					
				}
				$pdf->setY($core->getNextSpaceCell());
				$total_payments += $paym;
			}
		}

		if ($core->getCurrentCellPosition() >= 240) {
			$pdf->AddPage();
			$core->setCurrentCellPosition(7);
		}
		$pdf->Cell(0,21, '', 1,0,'C',0);
		$pdf->SetFont('Arial','B',13); 

		$pdf->setY($core->getNextSpaceCell());
		$pdf->setX(107.5);
		$pdf->Cell(98,7, "TOTAL EN ABONOS", 0,0,'C',0);
		$pdf->setY($core->getNextSpaceCell());
		$pdf->setX(107.5);
		$pdf->SetTextColor(255);
		$pdf->Cell(98.5,7, number_format($total_payments,2,'.',',')." $", 1,0,'C',1);

		if ($core->getCurrentCellPosition() >= 236) {
			$pdf->AddPage();
			$core->setCurrentCellPosition(7);					
		}
		$pdf->setY($core->getNextSpaceCell());

		// INICIO TITULO OPERACIONES DE CONTADO
		$pdf->SetTextColor(0);
		$pdf->setY($core->getNextSpaceCell(5));
		$pdf->SetFont('Arial','B',19); 
		$pdf->setFillColor(180,198,232);
		$pdf->Cell(0,20, "GASTOS DEL DIA", 1,0,'C',1);
		$pdf->setY($core->getNextSpaceCell(20));
		$pdf->SetFont('Arial','B',10); 
		$pdf->SetTextColor(255);
		$pdf->setFillColor(0);

		$pdf->Cell(48,7, "FACTURA", 1,0,'C',1);
		$pdf->setX(58.5);
		$pdf->Cell(48,7, "CONCEPTO", 1,0,'C',1);
		$pdf->setX(107);
		$pdf->Cell(48,7, "MONTO", 1,0,'C',1);
		$pdf->setX(155.5);
		$pdf->Cell(50.5,7, "FECHA", 1,0,'C',1);
		$pdf->SetTextColor(0);

		$spends = SpendData::getBoxedSpend($_GET["id"]);
		$spend_total = 0;
		$pdf->setX(10);
		$pdf->setY($core->getNextSpaceCell());
		$pdf->SetFont('Arial','',10); 
		foreach ($spends as $spend) {
			$pdf->Cell(48,7, "#".$spend->id, 1,0,'C',0);
			$pdf->setX(58.5);
			$pdf->Cell(48,7,$spend->name, 1,0,'C',0);
			$pdf->setX(107);
			$pdf->Cell(48,7, number_format($spend->price,2,'.',',')." $", 1,0,'C',0);
			$pdf->setX(155.5);
			$pdf->Cell(50.5,7, date("d/m/Y",strtotime($spend->created_at)), 1,0,'C',0);
			$spend_total += $spend->price;
			if ($core->getCurrentCellPosition() >= 236) {
			$pdf->AddPage();
				$core->setCurrentCellPosition(7);					
			}
			$pdf->setY($core->getNextSpaceCell());


		}


		if ($core->getCurrentCellPosition() >= 236) {
			$pdf->AddPage();
			$core->setCurrentCellPosition(7);
		}
		$pdf->Cell(0,21, '', 1,0,'C',0);
		$pdf->SetFont('Arial','B',13); 
		
		$pdf->setY($core->getNextSpaceCell());
		$pdf->setX(107.5);
		$pdf->Cell(98,7, "TOTAL EN GASTOS", 0,0,'C',0);
		$pdf->setY($core->getNextSpaceCell());
		$pdf->setX(107.5);
		$pdf->SetTextColor(255);
		$pdf->Cell(98.5,7, number_format($spend_total,2,'.',',')." $", 1,0,'C',1);
		
		$pdf->SetTextColor(0);

		$pdf->SetFont('Arial','B',17);
		$pdf->setX(10);

		if ($core->getCurrentCellPosition()+45 >= 240) {
			$pdf->AddPage();
			$core->setCurrentCellPosition(7);
			$pdf->setY($core->getNextSpaceCell());
		}else {
			$pdf->setY($core->getNextSpaceCell(10));

		}
		$pdf->Cell(0,45, '', 1,0,'C',0);


		$pdf->setX(10);
		$pdf->setY($core->getNextSpaceCell());
		$pdf->Cell(98,7, "TOTAL", 0,0,'C',0);
		
		$pdf->SetTextColor(255);
		$pdf->Cell(98.5,7, number_format($total_sells+$total_payments-$spend_total,2,'.',',')." $", 1,0,'C',1);
		$pdf->setY($core->getNextSpaceCell(20));
		$pdf->SetTextColor(0);

		$pdf->Cell(0,7, "_________________      _________________      _________________", 0,0,'C',0);
		$pdf->SetFont('Arial','B',12); 
		$pdf->setY($core->getNextSpaceCell());
		$pdf->setX(33);
		$pdf->Cell(0,7, "ENTREGA                                       RECIBE                                          AUDITA", 0,0,'L',0);

		$pdf->Output();
	}

?>