<?php 
	header('Content-Type: text/html; charset=UTF-8');
	include "../fpdf/fpdf.php";
	include "../core/controller/Core.php";
	include "../core/controller/Database.php";
	include "../core/controller/Executor.php";
	include "../core/controller/Model.php";
	include "../core/app/model/UserData.php";
	include "../core/app/model/SellData.php";
	include "../core/app/model/OperationData.php";
	include "../core/app/model/ProductData.php";
	include "../core/app/model/PersonData.php";
	include "../core/app/model/ConfigurationData.php";

	function printTitleWhite($pdf, $core, $title, $width = 0, $height = 0, $border = 0, $fill = 0, $positionX = 10){
		$pdf->setY($core->getCurrentCellPosition());
		$pdf->SetTextColor(255);
		$pdf->setFillColor(0);
		$pdf->setX($positionX);
		$pdf->Cell($width,$height, $title,$border,0,'C',$border);
		$pdf->SetTextColor(0);
		$pdf->setFillColor(255);
	}

	function printText($pdf, $core, $title, $width = 0, $height = 0, $border = 0, $fill = 0, $positionX = 10, $textColor, $fillColor, $aling = 'C'){
		$pdf->SetTextColor($textColor);
		$pdf->setFillColor($fillColor);
		$pdf->setX($positionX);
		$pdf->Cell($width,$height, $title,$border,0,$aling,$border);
		$pdf->SetTextColor($fillColor);
		$pdf->setFillColor($textColor);

	}

	function printTitleBlack($pdf, $core, $title, $width = 0, $height = 0, $border = 0, $fill = 0, $positionX = 10){
		$pdf->setY($core->getCurrentCellPosition());
		$pdf->SetTextColor(0);
		$pdf->setFillColor(255);
		$pdf->setX($positionX);
		$pdf->Cell($width,$height,$title,$border,0,'C',$fill);
	}

	function checkPositionBreak($pdf, $core){
		if ($core->getCurrentCellPosition() >= 241) {
			$pdf->AddPage();
			$core->setCurrentCellPosition(7);					
		}
	}
	
	$total_products = 0;
	if (isset($_GET['id'])) {
		$sell = SellData::getById($_GET['id']);
		$proc = OperationData::getAllProductsBySellId($sell->id);
		$products = [];
		if (count($proc) > 0) {
			for ($i=0; $i<count($proc);$i++) {
				$name_p = ProductData::getById($proc[$i]->product_id);
				$unidad = "N/A";
				if (isset($name_p->unit) && $name_p->unit != 0) {
					$unidad = $name_p->unit;
				}
				array_push($products, array("name"=>$name_p->name, "barcode"=>$name_p->barcode,"unit"=>$unidad,"q"=>$proc[$i]->q));
				$total_products += $proc[$i]->q;
			}
		}

		$person = PersonData::getById($sell->person_id);
		$person_info = array();
		if (isset($person)) {
			if (isset($person->name) && isset($person->lastname)) {
				$person_info['name'] = $person->name." ".$person->lastname;
			}
			$person_info['phone1'] = isset($person->phone1) ? $person->phone1 : "N/A";
			$person_info['phone2'] = isset($person->phone2) ? $person->phone2 : "N/A";
			$person_info['rif'] = isset($person->rif) ? $person->rif: "N/A";
			$person_info['company'] = isset($person->company) ? $person->company : "N/A";
			$person_info['address1'] = isset($person->address1) ? $person->address1 : "N/A";

		}
		$seller = null;
		$seller_info = array();
		$seller = UserData::getById($sell->receive_by);
		if (isset($seller)) {
			if (isset($seller->name) && isset($seller->lastname)) {
				$seller_info['name'] = $seller->name." ".$seller->lastname;
			}
		}

		$core = new Core();
		$image = ConfigurationData::getByPreffix('report_image')->val;
		if (isset($image) && $image != "") {
			$image = "../storage/configuration/".$image;
		} else {
			$image = "../img/factura1.png";
		}
		$core->setCurrentCellPosition($core->base_cell_report_global);
		$core->setSpaceCell(7);


		$pdf = new FPDF($orientation='P',$unit='mm', $size='Letter');
		$pdf->AddPage();
		$pdf->setX(90);
		$pdf->Cell(0,0,($pdf->Image($image,null,null,45,35)),0,1,"C");
		$pdf->SetFont('Arial','B',22);

		printText($pdf, $core, utf8_decode("GUÍA DE TRASLADO"),0,15,1,1,10,255,0);

		$pdf->SetFont('Arial','B',18);
		$pdf->setY($core->getNextSpaceCell(12));
		$pdf->setX(10);
		$pdf->Cell(0,0, "PEDIDO",0,0,'C',0);
		$pdf->setY($core->getNextSpaceCell(7));
		$pdf->setX(10);
		$pdf->Cell(0,0, "#".$sell->ref_id,0,0,'C',0);
		// $pdf->SetTextColor(0);

		$pdf->setY($core->getNextSpaceCell());
		$pdf->SetFont('Arial','B',11);
		printTitleBlack($pdf, $core, "ATENDIDO POR",65.1,6, 1,0);
		printTitleBlack($pdf, $core, "CLIENTE",65.1,6, 1, 0, 75);
		printTitleBlack($pdf, $core, utf8_decode("TELÉFONO DE CLIENTE"),66,6, 1,0,140);

		$core->setSpaceCell(6);

		$pdf->SetFont('Arial','',9);
		$pdf->setY($core->getNextSpaceCell());
		printTitleBlack($pdf, $core, utf8_decode($seller_info['name']),65.1,6, 1,0);
		printTitleBlack($pdf, $core, utf8_decode($person_info['name']),65.1,6, 1, 0, 75);
		printTitleBlack($pdf, $core, utf8_decode($person_info['phone1']),66,6, 1,0,140);

		$pdf->setY($core->getNextSpaceCell());
		$pdf->SetFont('Arial','B',11);
		printTitleBlack($pdf, $core, "RIF",65.1,6, 1,0);
		printTitleBlack($pdf, $core, utf8_decode("EMPRESA"),65.1,6, 1, 0, 75);
		printTitleBlack($pdf, $core, utf8_decode("TELÉFONO DE EMPRESA"),66,6, 1,0,140);

		$pdf->SetFont('Arial','',9);
		$pdf->setY($core->getNextSpaceCell());
		printTitleBlack($pdf, $core, $person_info['rif'],65.1,6, 1,0);
		printTitleBlack($pdf, $core, $person_info['company'],65.1,6, 1, 0, 75);
		printTitleBlack($pdf, $core, $person_info['phone2'],66,6, 1,0,140);

		$pdf->SetFont('Arial','B',11);
		$pdf->setY($core->getNextSpaceCell());
		$pdf->Cell(0,30, "",1,0,'C',0);
		$pdf->setY($core->getNextSpaceCell());
		$pdf->Cell(0,0, utf8_decode("DIRECCIÓN DE ENTREGA"),0,0,'C',0);

		$pdf->SetFont('Arial','',9);
		$pdf->setY($core->getNextSpaceCell());
		$pdf->Cell(0,6, $person_info['address1'],1,0,'C',0);

		$pdf->SetFont('Arial','B',9);
		$pdf->setY($core->getNextSpaceCell(18));
		printTitleWhite($pdf, $core, utf8_decode("CÓDIGO DE BARRA"),35,6,1,1);
		printTitleWhite($pdf, $core, "NOMBRE DEL PRODUCTO",117.5,6,1,1,45.5);
		printTitleWhite($pdf, $core, "PESO",20,6,1,1,163.5);
		printTitleWhite($pdf, $core, "CANTIDAD",22,6,1,1,184);

		$pdf->SetFont('Arial','',9);
		foreach ($products as $product) {
			$pdf->setY($core->getNextSpaceCell());
			printTitleBlack($pdf, $core, $product['barcode'],35,6,1,1);
			printTitleBlack($pdf, $core, substr(utf8_decode($product['name']), 0,63),118.5,6,1,1,45);
			printTitleBlack($pdf, $core, $product['unit'],20.5,6,1,1,163.5);
			printTitleBlack($pdf, $core, $product['q'],22,6,1,1,184);
			checkPositionBreak($pdf, $core);
		}

		$pdf->setX(10);
		$pdf->setY($core->getNextSpaceCell());
		$pdf->Cell(0,25, "",1,0,'C',1);
		$pdf->SetFont('Arial','B',12);
		$pdf->setY($core->getNextSpaceCell(6));
		printTitleBlack($pdf, $core, "FECHA",98,10,0,0);
		printTitleBlack($pdf, $core, "TOTAL DE PRODUCTOS",98,10,0,0,105);

		checkPositionBreak($pdf, $core);

		$pdf->setY($core->getNextSpaceCell(7));
		printText($pdf, $core, date("d/m/Y", strtotime($sell->created_at)),98,6,1,1,10,255,0);
		printText($pdf, $core, $total_products,98,6,1,1,108,255,20);
		checkPositionBreak($pdf, $core);
		$pdf->setY($core->getNextSpaceCell(12));

		$pdf->Cell(0,30, "",1,0,'C',1);
		$pdf->setX(10);

		$pdf->setY($core->getNextSpaceCell(12));
		printText($pdf, $core, "_________________   _________________   _________________   _________________",0,6,0,0,12,0,255);
		$pdf->setY($core->getNextSpaceCell());
		printText($pdf, $core, "ENTREGA              TRANSPORTA                 RECIBE                        AUDITA",196,6,0,0,35,0,255,'L');


		$pdf->addPage();
		$core->setCurrentCellPosition($core->base_cell_report_global);
		$core->setSpaceCell(6);
		$pdf->setX(90);
		$pdf->Cell(0,0,($pdf->Image($image,null,null,45,35)),0,1,"C");
		$pdf->SetFont('Arial','B',22);

		printText($pdf, $core, utf8_decode("ORDEN DE DESPACHO"),0,15,1,1,10,255,0);

		$pdf->SetFont('Arial','B',18);
		$pdf->setY($core->getNextSpaceCell(21));
		$pdf->setX(10);
		$pdf->Cell(0,0, "PEDIDO",0,0,'C',0);
		$pdf->setY($core->getNextSpaceCell(7));
		$pdf->setX(10);
		$pdf->Cell(0,0, "#".$sell->ref_id,0,0,'C',0);

		$pdf->setY($core->getNextSpaceCell());
		$pdf->SetFont('Arial','B',11);
		printText($pdf, $core, "ATENDIDO POR",98,6,1,0,10,0,255);
		printText($pdf, $core, "TRANSPORTA",98,6,1,0,107,0,255);

		$pdf->setY($core->getNextSpaceCell());
		$pdf->SetFont('Arial','',9);
		printText($pdf, $core, $seller_info['name'],97,6,1,0,10,0,255);
		printText($pdf, $core, "",98,6,1,0,107,0,255);

		$pdf->setY($core->getNextSpaceCell());
		$pdf->SetFont('Arial','B',11);
		printText($pdf, $core, "CLIENTE",98,6,1,0,10,0,255);
		printText($pdf, $core, "FECHA",98,6,1,0,107,0,255);

		$pdf->setY($core->getNextSpaceCell());
		$pdf->SetFont('Arial','',9);
		printText($pdf, $core, $person_info['name'],97,6,1,0,10,0,255);
		printText($pdf, $core, date("d/m/Y", strtotime($sell->created_at)),98,6,1,0,107,0,255);

		$pdf->SetFont('Arial','B',9);
		$pdf->setY($core->getNextSpaceCell(18));
		printTitleWhite($pdf, $core, utf8_decode("CÓDIGO DE BARRA"),35,6,1,1);
		printTitleWhite($pdf, $core, "NOMBRE DEL PRODUCTO",117.5,6,1,1,45.5);
		printTitleWhite($pdf, $core, "PESO",20,6,1,1,163.5);
		printTitleWhite($pdf, $core, "CANTIDAD",22,6,1,1,184);

		$pdf->SetFont('Arial','',9);
		foreach ($products as $product) {
			$pdf->setY($core->getNextSpaceCell());
			printTitleBlack($pdf, $core, $product['barcode'],35,6,1,1);
			printTitleBlack($pdf, $core, substr(utf8_decode($product['name']), 0,63),118.5,6,1,1,45);
			printTitleBlack($pdf, $core, $product['unit'],20.5,6,1,1,163.5);
			printTitleBlack($pdf, $core, $product['q'],22,6,1,1,184);
			checkPositionBreak($pdf, $core);
		}

		$pdf->setX(10);
		$pdf->setY($core->getNextSpaceCell());
		$pdf->Cell(0,25, "",1,0,'C',1);
		$pdf->SetFont('Arial','B',12);
		$pdf->setY($core->getNextSpaceCell(6));
		printTitleBlack($pdf, $core, "TOTAL DE PRODUCTOS",98,10,0,0,105);

		checkPositionBreak($pdf, $core);

		$pdf->setY($core->getNextSpaceCell(7));
		printText($pdf, $core, $total_products,98,6,1,1,108,255,20);
		checkPositionBreak($pdf, $core);
		$pdf->setY($core->getNextSpaceCell(12));

		$pdf->Cell(0,30, "",1,0,'C',1);
		$pdf->setX(10);

		$pdf->setY($core->getNextSpaceCell(12));
		printText($pdf, $core, "_________________      _________________      _________________",0,6,0,0,12,0,255);
		$pdf->setY($core->getNextSpaceCell());
		printText($pdf, $core, "ENTREGA                       RECIBE                        AUDITA",196,6,0,0,53,0,255,'L');

		
		$pdf->Output();
		
	}
?>