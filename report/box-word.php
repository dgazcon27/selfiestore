<?php
include "../core/autoload.php";
include "../core/app/model/BoxData.php";
include "../core/app/model/SellData.php";
include "../core/app/model/PersonData.php";
include "../core/app/model/OperationData.php";
include "../core/app/model/ProductData.php";

require_once '../core/controller/PhpWord/Autoloader.php';

use PhpOffice\PhpWord\Autoloader;
use PhpOffice\PhpWord\Settings;

Autoloader::register();

$word = new  PhpOffice\PhpWord\PhpWord();
$clients = PersonData::getClients();


$section1 = $word->AddSection();
$section1->addText("SELFIE",array("size"=>18,"bold"=>true,"align"=>"center"));
$section1->addText("CORTE DE CAJA #".$_GET["id"],array("size"=>12,"bold"=>true,"align"=>"right"));

$sells = SellData::getByBoxId($_GET["id"]);



$styleTable = array('borderSize' => 6, 'borderColor' => '888888', 'cellMargin' => 40);
$styleFirstRow = array('borderBottomColor' => '0000FF', 'bgColor' => 'AAAAAA');

$table1 = $section1->addTable("table1");
$table1->addRow();
$table1->addCell()->addText("FACTURA",array("size"=>4,"bold"=>true,"align"=>"right"));
$table1->addCell()->addText("METODO DE PAGO",array("size"=>4,"bold"=>true,"align"=>"right"));
$table1->addCell()->addText("REFERENCIA",array("size"=>4,"bold"=>true,"align"=>"right"));
$table1->addCell()->addText("DESCRIPCION DE VENTA",array("size"=>4,"bold"=>true,"align"=>"right"));
$table1->addCell()->addText("EFECTIVO",array("size"=>4,"bold"=>true,"align"=>"right"));
$table1->addCell()->addText("TRANSFERENCIA",array("size"=>4,"bold"=>true,"align"=>"right"));
$table1->addCell()->addText("ZELLE",array("size"=>4,"bold"=>true,"align"=>"right"));
$table1->addCell()->addText("TOTAL",array("size"=>4,"bold"=>true,"align"=>"right"));
$table1->addCell()->addText("FECHA",array("size"=>4,"bold"=>true,"align"=>"right"));
$total_total = 0;
$efectivo = 1;
$transferencia = 2;
$punto = 3;
$dual = 4;
$total_efectivo = 0;
$total_transferencia = 0;
$total_punto = 0;
$total_dual = 0;


foreach($sells as $sell)
{
	
	$total=0;
$operations = OperationData::getAllProductsBySellId($sell->id);
	if($efectivo == $sell->f_id)
	{
		$total_efectivo = $total_efectivo + $sell->total -$sell->discount;
	}
	if($transferencia == $sell->f_id)
	{
		$total_transferencia = $total_transferencia + $sell->total -$sell->discount;
	}
	if($punto == $sell->f_id)
	{
		$total_punto = $total_punto + $sell->total -$sell->discount;
	}
	if($dual == $sell->f_id)
	{
		$total_transferencia = $total_transferencia + $sell->tra;
		$total_efectivo = $total_efectivo + $sell->efe;
		$total_punto = $total_punto + $sell->zel;
	}
	foreach($operations as $operation){
		
		$product  = $operation->getProduct();
		$total += $operation->q*$product->price_out;
	}
	$total_total +=$total-$sell->discount;
	

$table1->addRow();
$table1->addCell(0)->addText("# ".number_format($sell->ref_id),array("size"=>4,"align"=>"right"));
	
	
	if($sell->f_id == 1)
	$variable = "EFECTIVO";
elseif($sell->f_id == 2)
	$variable = "TRANSFERENCIA";
elseif($sell->f_id == 3)
	$variable = "ZELLE";
elseif($sell->f_id == 4)
	$variable = "DUAL";	
			
		
$table1->addCell(850)->addText(strtoupper($variable),array("size"=>4,"align"=>"right"));
$table1->addCell(850)->addText(strtoupper($sell->refe),array("size"=>4,"align"=>"right"));

$cadena = "";
	
$array = array();
	
  		foreach($operations as $operation)
		{
    		$product  = $operation->getProduct();
			$cadena = $cadena." [ ".$operation->q." x ".$product->name." -> $".$operation->price_out." ] ";
		}
	
	
	
$table1->addCell(6000)->addText($cadena,array("size"=>4,"align"=>"right"));
	
	$table1->addCell(800)->addText("$ ".number_format($sell->efe,2,".",","),array("size"=>4,"align"=>"right"));
	$table1->addCell(800)->addText("$ ".number_format($sell->tra,2,".",","),array("size"=>4,"align"=>"right"));
	$table1->addCell(800)->addText("$ ".number_format($sell->zel,2,".",","),array("size"=>4,"align"=>"right"));
	
$table1->addCell(800)->addText("$ ".number_format($total-$sell->discount,2,".",","),array("size"=>4,"align"=>"right"));
$table1->addCell(1000)->addText($sell->created_at,array("size"=>4,"align"=>"right"));



}





$section1->addText("");
$section1->addText(".................................................................................................................................................................",array("size"=>10));
$section1->addText("VENTAS EN EFECTIVO: $".number_format($total_efectivo,2,".",",")."  | VENTAS EN TRANSFERENCIA: $".number_format($total_transferencia,2,".",",")."  |   VENTAS EN ZELLE: $".number_format($total_punto,2,".",","),array("size"=>6));
$section1->addText(".................................................................................................................................................................",array("size"=>10));
$section1->addText("TOTAL, EN VENTAS: $".number_format($total_total,2,".",","),array("size"=>10,"bold"=>true));
$word->addTableStyle('table1', $styleTable,$styleFirstRow);
/// datos bancarios

$filename = "box-".time().".docx";
#$word->setReadDataOnly(true);
$word->save($filename,"Word2007");
//chmod($filename,0444);
header("Content-Disposition: attachment; filename='$filename'");
readfile($filename); // or echo file_get_contents($filename);
unlink($filename);  // remove temp file



?>