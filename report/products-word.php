<?php
include "../core/autoload.php";
include "../core/app/model/ProductData.php";
include "../core/app/model/CategoryData.php";
include "../core/app/model/BrandData.php";
include "../core/app/model/ConfigurationData.php";

require_once '../core/controller/PhpWord/Autoloader.php';
use PhpOffice\PhpWord\Autoloader;
use PhpOffice\PhpWord\Settings;

Autoloader::register();

$word = new  PhpOffice\PhpWord\PhpWord();
$products = ProductData::getAll();


$section1 = $word->AddSection(["paperSize" => "Letter", 'marginLeft' => 600, 'marginRight' => 600, 'marginTop' => 600, 'marginBottom' => 600]);
$word->addFontStyle('r2Style', array('bold'=>true,'size'=>15));
$word->addFontStyle('r3Style', array('bold'=>true,'size'=>10));
$word->addParagraphStyle('p2Style', array('align'=>'center'));
$word->addFontStyle('estilocelda', array(
                                            'bold'=>true,
                                            'size'=>7,
                                            'cellMargin'=>0, 
                                            'spaceBefore' => 0, 
                                            'spaceAfter' => 0,
                                            'spacing' => 0,
                                            'spaceAfter' => -10,
                                        ));


$nombreDeSucursal = ConfigurationData::getByPreffix("company_name")->val;
$section1->addText($nombreDeSucursal,'r2Style', 'p2Style');

$section1->addText("PRODUCTOS",'r3Style', 'p2Style');


$styleTable = array('borderSize' => 6, 'borderColor' => '888888', 'cellMargin' => 40);
$styleFirstRow = array('borderBottomColor' => '0000FF', 'bgColor' => 'AAAAAA');

$table1 = $section1->addTable("table1");
$table1->addRow();
$table1->addCell()->addText("ID",'estilocelda', 'p2Style');
$table1->addCell()->addText("NOMBRE",'estilocelda', 'p2Style');
$table1->addCell()->addText("PRECIO DE ENTRADA",'estilocelda', 'p2Style');
$table1->addCell()->addText("PRECIO DE SALIDA",'estilocelda', 'p2Style');
$table1->addCell()->addText("CATEGORIA",'estilocelda', 'p2Style');
$table1->addCell()->addText("MARCA",'estilocelda', 'p2Style');
foreach($products as $product){
$table1->addRow();
$table1->addCell(500)->addText($product->id,'estilocelda', 'p2Style');
$table1->addCell(5000)->addText($product->name,'estilocelda', 'p2Style');
$table1->addCell(2000)->addText("$ ".number_format($product->price_in),'estilocelda', 'p2Style');
$table1->addCell(2000)->addText("$ ".number_format($product->price_out),'estilocelda', 'p2Style');
if($product->category_id!=null){
	$table1->addCell(2000)->addText($product->getCategory()->name,'estilocelda', 'p2Style');
}
else
{
	$table1->addCell(2000)->addText("---");
}
	
if($product->category_id!=null){
	$table1->addCell(2000)->addText($product->getBrand()->name,'estilocelda', 'p2Style');
}
else
{
	$table1->addCell(2000)->addText("---");
}


}

$word->addTableStyle('table1', $styleTable,$styleFirstRow);
/// datos bancarios

$filename = "products-".time().".docx";
#$word->setReadDataOnly(true);
$word->save($filename,"Word2007");
//chmod($filename,0444);
header("Content-Disposition: attachment; filename='$filename'");
readfile($filename); // or echo file_get_contents($filename);
unlink($filename);  // remove temp file



?>