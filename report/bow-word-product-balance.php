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

$section1 = $word->AddSection(["paperSize" => "Letter", 'marginLeft' => 600, 'marginRight' => 600, 'marginTop' => 600, 'marginBottom' => 600]);

if(isset($_GET["sd_word"]) && isset($_GET["ed_word"]) ){
    if($_GET["sd_word"]!=""&&$_GET["ed_word"]!=""){
        $operations = array();
        $operations = OperationData::getPPByDateOfficial($_GET["sd_word"],$_GET["ed_word"]);
    }
}

//config table style start
$word->addFontStyle('r2Style', array('bold'=>true,'size'=>15));
$word->addFontStyle('estilocelda', array(
                                            'bold'=>true,
                                            'size'=>9,
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
$section1->addText("BALANCE DE PRODUCTOS",'r2Style', 'p2Style');
$section1->addText("DESDE: ".$_GET["sd_word"]." HASTA: ".$_GET["ed_word"],'estilofecha', 'p2Style');


$styleTable = array('borderSize' => 6, 'borderColor' => '888888', 'cellMargin' => 40);
$styleFirstRow = array('borderBottomColor' => '0000FF', 'bgColor' => 'AAAAAA');

$table1 = $section1->addTable("table1",['cellMargin'  => 0,'spaceAfter' => 0]);
$table1->addRow();
$table1->addCell(1800)->addText("ID",'estilocelda', 'p2Style');
$table1->addCell(6000)->addText("PRODUCTO",'estilocelda', 'p2Style');
$table1->addCell(1500)->addText("CANTIDAD",'estilocelda', 'p2Style');
$table1->addCell(1500)->addText("OPERACION",'estilocelda', 'p2Style');

foreach($operations as $operation){
    
    $entradaOsalida="ENTRADA";
    if($operation->operation_type_id==2){
       $entradaOsalida="SALIDA"; 
    }
    
    $table1->addRow();
    $table1->addCell(0)->addText($operation->getProduct()->id,'estilocelda', 'p2Style');
    $table1->addCell(850)->addText(strtoupper($operation->getProduct()->name),'estilocelda', 'p2Style');
    $table1->addCell(850)->addText($operation->total,'estilocelda', 'p2Style');
    $table1->addCell(800)->addText($entradaOsalida,'estilocelda', 'p2Style');
}


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

$filename = "box-".time().".doc";
$word->save($filename,"Word2007");
//chmod($filename,0444);
header("Content-Disposition: attachment; filename='$filename'");
readfile($filename); // or echo file_get_contents($filename);
unlink($filename);  // remove temp file


?>