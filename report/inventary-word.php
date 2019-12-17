<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

include "../core/autoload.php";
include "../core/app/model/ProductData.php";
include "../core/app/model/OperationData.php";
include "../core/app/model/OperationTypeData.php";
include "../core/app/model/ConfigurationData.php";


require_once '../core/controller/PhpWord/Autoloader.php';
use PhpOffice\PhpWord\Autoloader;
use PhpOffice\PhpWord\Settings;

Autoloader::register();

$word = new  PhpOffice\PhpWord\PhpWord();
$products = ProductData::getAll();


$section1 = $word->AddSection(["paperSize" => "Letter", 'marginLeft' => 600, 'marginRight' => 600, 'marginTop' => 600, 'marginBottom' => 600]);
$word->addFontStyle('r2Style', array('bold'=>true,'size'=>15));
$word->addParagraphStyle('p2Style', array('align'=>'center'));
$word->addFontStyle('estilofecha', array('bold'=>true,'size'=>10));
$nombreDeSucursal = ConfigurationData::getByPreffix("company_name")->val;
$date = isset($products[0]->created_at) ? date("d/m/Y", strtotime($products[0]->created_at)) : date("d/m/Y");

$section1->addText($nombreDeSucursal,'r2Style', 'p2Style');
$section1->addText("INVENTARIO", 'r2Style', 'p2Style');
$section1->addText($date,'estilofecha', 'p2Style');


$styleTable = array('borderSize' => 6, 'borderColor' => '888888', 'cellMargin' => 40);
$styleFirstRow = array('borderBottomColor' => '0000FF', 'bgColor' => 'AAAAAA');

$table1 = $section1->addTable("table1");
$table1->addRow();
$table1->addCell()->addText("Id");
$table1->addCell()->addText("Nombre");

$table1->addCell()->addText("Disponible");

foreach($products as $product){
//    $q=OperationData::getQYesF($product->id);
	$r=OperationData::getRByStock($product->id,$_GET["stock_id"]);
	$q=OperationData::getQByStock($product->id,$_GET["stock_id"]);
	$d=OperationData::getDByStock($product->id,$_GET["stock_id"]);


$table1->addRow();
$table1->addCell(300)->addText($product->id);
$table1->addCell(11000)->addText($product->name);

$table1->addCell(500)->addText($q);


}

$word->addTableStyle('table1', $styleTable,$styleFirstRow);
/// datos bancarios

$filename = "inventary-".time().".docx";
#$word->setReadDataOnly(true);
$word->save($filename,"Word2007");
//chmod($filename,0444);
header("Content-Disposition: attachment; filename=$filename");
readfile($filename); // or echo file_get_contents($filename);
unlink($filename);  // remove temp file



?>