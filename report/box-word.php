<?php
include "../core/autoload.php";
include "../core/app/model/BoxData.php";
include "../core/app/model/SellData.php";
include "../core/app/model/PersonData.php";
include "../core/app/model/OperationData.php";
include "../core/app/model/ProductData.php";
include "../core/app/model/ConfigurationData.php";

require_once '../core/controller/PhpWord/Autoloader.php';

use PhpOffice\PhpWord\Autoloader;
use PhpOffice\PhpWord\Settings;

Autoloader::register();

$word = new  PhpOffice\PhpWord\PhpWord();
$clients = PersonData::getClients();

$section1 = $word->AddSection(["paperSize" => "Letter", 'marginLeft' => 600, 'marginRight' => 600, 'marginTop' => 600, 'marginBottom' => 600]);

$sells = SellData::getByBoxId($_GET["id"]);
//config table style start
$word->addFontStyle('r2Style', array('bold'=>true,'size'=>15));
$word->addFontStyle('estilocelda', array(
                                            'bold'=>true,
                                            'size'=>7,
                                            'cellMargin'=>0, 
                                            'spaceBefore' => 0, 
                                            'spaceAfter' => 0,
                                            'spacing' => 0,
                                            'spaceAfter' => -10,
                                        ));
$word->addFontStyle('estilofecha', array('bold'=>true,'size'=>10));
$word->addParagraphStyle('p2Style', array('align'=>'center'));
//config table style end
$nombreDeSucursal = ConfigurationData::getByPreffix("company_name")->val;
$section1->addText($nombreDeSucursal,'r2Style', 'p2Style');
$section1->addText("CORTE DE CAJA #".$_GET["id"],'r2Style', 'p2Style');
$section1->addText(date("d/m/Y", strtotime($sells[0]->created_at)),'estilofecha', 'p2Style');


$styleTable = array('borderSize' => 6, 'borderColor' => '888888', 'cellMargin' => 40);
$styleFirstRow = array('borderBottomColor' => '0000FF', 'bgColor' => 'AAAAAA');

$table1 = $section1->addTable("table1",['cellMargin'  => 0,'spaceAfter' => 0]);
$table1->addRow();
$table1->addCell()->addText("FACTURA",'estilocelda', 'p2Style');
$table1->addCell(2400)->addText("METODO DE PAGO",'estilocelda', 'p2Style');
$table1->addCell(2400)->addText("REFERENCIA",'estilocelda', 'p2Style');
$table1->addCell(6800)->addText("TOTAL",'estilocelda', 'p2Style');
$total_total = 0;
$efectivo = 1;
$transferencia = 2;
$zelle = 3;
$dual = 4;
$punto = 5;
$total_efectivo = 0;
$total_transferencia = 0;
$total_zelle = 0;
$total_dual = 0;
$total_punto = 0;
$iterator = 10;
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
	if($zelle == $sell->f_id)
	{
		$total_zelle = $total_zelle + $sell->total -$sell->discount;
	}
	if($punto == $sell->f_id)
	{
		$total_punto = $total_punto + $sell->total -$sell->discount;
	}
	if($dual == $sell->f_id)
	{
		$total_efectivo = $total_efectivo + $sell->efe;
		$total_transferencia = $total_transferencia + $sell->tra;
		$total_zelle = $total_zelle + $sell->zel;
		$total_punto = $total_punto + $sell->pun;
	}
	foreach($operations as $operation){
		$product  = $operation->getProduct();
		$total += $operation->q*$product->price_out;
	}
	$total_total +=$total-$sell->discount;
    $table1->addRow();
    $table1->addCell(0)->addText("# ".number_format($sell->ref_id),'estilocelda', 'p2Style');
	
	
	if($sell->f_id == 1)
    	$variable = "EFECTIVO";
    elseif($sell->f_id == 2)
    	$variable = "TRANSFERENCIA";
    elseif($sell->f_id == 3)
    	$variable = "ZELLE";
    elseif($sell->f_id == 4)
    	$variable = "DUAL";	
    elseif($sell->f_id == 5)
    	$variable = "PUNTO";	
			

    $table1->addCell(850)->addText(strtoupper($variable),'estilocelda', 'p2Style');
    
    if($sell->refe==0 || $sell->refe==""){
        $sell->refe="N/A";
    }
    
    $table1->addCell(850)->addText(strtoupper($sell->refe),'estilocelda', 'p2Style');

    $cadena = "";
    	
    $array = array();
	
  	foreach($operations as $operation)
	{
		$product  = $operation->getProduct();
		$cadena = $cadena." [ ".$operation->q." x ".$product->name." -> $".$operation->price_out." ] ";
	}
	//INICIO TABLA PAGOS DUALES
	if($sell->f_id == 4){
    	$currentTable = "";
    	$currentTable = $table1->addCell(8000,'estilocelda', 'p2Style')->addTable("table".$iterator,'estilocelda', 'p2Style');
    	$currentTable->addRow('estilocelda', 'p2Style');
    	$centerCounter=0;
    	if($sell->efe>0 && $sell->efe!=""){
    	    $centerCounter=$centerCounter+1;
    	}
    	if($sell->tra>0 && $sell->tra!=""){
    	    $centerCounter=$centerCounter+1;
    	}
    	if($sell->zel>0 && $sell->zel!=""){
    	    $centerCounter=$centerCounter+1;
    	}
    	if($sell->pun>0 && $sell->pun!=""){
    	    $centerCounter=$centerCounter+1;
    	}
    	if($sell->efe>0 && $sell->efe!=""){
    	    $currentTable->addCell(8000/$centerCounter)->addText("EFECTIVO",'estilocelda', 'p2Style');
        }
        if($sell->tra>0 && $sell->tra!=""){
    	    $currentTable->addCell(8000/$centerCounter)->addText("TRANSFERENCIA",'estilocelda', 'p2Style');
        }
        if($sell->zel>0 && $sell->zel!=""){
    	    $currentTable->addCell(8000/$centerCounter)->addText("ZELLE",'estilocelda', 'p2Style');
        }
        if($sell->pun>0 && $sell->pun!=""){
    	    $currentTable->addCell(8000/$centerCounter)->addText("PUNTO",'estilocelda', 'p2Style');
        }
    	$currentTable->addRow();
    	if($sell->efe>0 && $sell->efe!=""){
    	    $currentTable->addCell(8000/$centerCounter)->addText("$ ".number_format($sell->efe,2,".",","),'estilocelda', 'p2Style');
        }
        if($sell->tra>0 && $sell->tra!=""){
    	    $currentTable->addCell(8000/$centerCounter)->addText("$ ".number_format($sell->tra,2,".",","),'estilocelda', 'p2Style');
        }
        if($sell->zel>0 && $sell->zel!=""){
    	    $currentTable->addCell(8000/$centerCounter)->addText("$ ".number_format($sell->zel,2,".",","),'estilocelda', 'p2Style');
        }
        if($sell->pun>0 && $sell->pun!=""){
    	    $currentTable->addCell(8000/$centerCounter)->addText("$ ".number_format($sell->pun,2,".",","),'estilocelda', 'p2Style');
        }
	}
	else{
        $table1->addCell(800)->addText("$ ".number_format($total-$sell->discount,2,".",","),'estilocelda', 'p2Style');
	}
	$iterator = $iterator+1;
	//FIN TABLA PAGOS DUALES
}

    $section1->addText(".....................................................................................................................................................................................................",array("size"=>10));

$table2 = $section1->addTable("table2");
$table2->addRow();
$table2->addCell(6000)->addText("TOTAL EN EFECTIVO",'estilofecha', 'p2Style');
$table2->addCell(6000)->addText("TOTAL EN TRANSFERENCIA",'estilofecha', 'p2Style');
$table2->addRow();
$table2->addCell(6000)->addText("$".number_format($total_efectivo,2,".",","),'estilofecha', 'p2Style');
$table2->addCell(6000)->addText("$".number_format($total_transferencia,2,".",","),'estilofecha', 'p2Style');
$table2->addRow();
$table2->addCell(6000)->addText("TOTAL EN ZELLE",'estilofecha', 'p2Style');
$table2->addCell(6000)->addText("TOTAL EN PUNTO DE VENTA",'estilofecha', 'p2Style');
$table2->addRow();
$table2->addCell(6000)->addText("$".number_format($total_zelle,2,".",","),'estilofecha', 'p2Style');
$table2->addCell(6000)->addText("$".number_format($total_punto,2,".",","),'estilofecha', 'p2Style');
$section1->addText(".....................................................................................................................................................................................................",array("size"=>10));
$section1->addText("TOTAL, EN VENTAS: $".number_format($total_total,2,".",","), 'r2Style', 'p2Style');

$section1->addText("");
$section1->addText("");

$table3 = $section1->addTable("table3");
$table3->addRow();
$table3->addCell(6000)->addText("________________________________",'estilofecha', 'p2Style');
$table3->addCell(6000)->addText("________________________________",'estilofecha', 'p2Style');
$table3->addCell(6000)->addText("________________________________",'estilofecha', 'p2Style'); 
$table3->addRow();
$table3->addCell(6000)->addText("ENTREGA",'estilocelda', 'p2Style');
$table3->addCell(6000)->addText("AUDITA",'estilocelda', 'p2Style');
$table3->addCell(6000)->addText("RECIBE",'estilocelda', 'p2Style'); 


$word->addTableStyle('table1', $styleTable,$styleFirstRow);
$styleTable2 = array('align' => 'center');
/// datos bancarios

$filename = "box-".time().".doc";
#$word->setReadDataOnly(true);
$word->save($filename,"Word2007");
//chmod($filename,0444);
header("Content-Disposition: attachment; filename='$filename'");
readfile($filename); // or echo file_get_contents($filename);
unlink($filename);  // remove temp file


?>