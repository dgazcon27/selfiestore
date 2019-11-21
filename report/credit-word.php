<?php
include "../core/autoload.php";
include "../core/app/model/PersonData.php";
include "../core/app/model/PaymentData.php";

require_once '../core/controller/PhpWord/Autoloader.php';
use PhpOffice\PhpWord\Autoloader;
use PhpOffice\PhpWord\Settings;

Autoloader::register();

$word = new  PhpOffice\PhpWord\PhpWord();
$clients = PersonData::getClients();


$section1 = $word->AddSection();
$section1->addText("CREDITO",array("size"=>22,"bold"=>true,"align"=>"right"));


$styleTable = array('borderSize' => 6, 'borderColor' => '888888', 'cellMargin' => 40);
$styleFirstRow = array('borderBottomColor' => '0000FF', 'bgColor' => 'AAAAAA');

$table1 = $section1->addTable("table1");
$table1->addRow();
$table1->addCell()->addText("NOMBRE");
$table1->addCell()->addText("DIRECCION");
$table1->addCell()->addText("EMAIL");
$table1->addCell()->addText("TELEFONO");
$table1->addCell()->addText("SALDO PENDIENTE");
foreach($clients as $client){
$table1->addRow();
$table1->addCell(5000)->addText(strtoupper($client->name." ".$client->lastname));
$table1->addCell(2500)->addText(strtoupper($client->address1));
$table1->addCell(2000)->addText(strtoupper($client->email1));
$table1->addCell(2000)->addText($client->phone1);
$table1->addCell(2000)->addText("$". number_format(PaymentData::sumByClientId($client->id)->total,2,".",","));

}

$word->addTableStyle('table1', $styleTable,$styleFirstRow);
/// datos bancarios

$filename = "credit-".time().".docx";
#$word->setReadDataOnly(true);
$word->save($filename,"Word2007");
//chmod($filename,0444);
header("Content-Disposition: attachment; filename='$filename'");
readfile($filename); // or echo file_get_contents($filename);
unlink($filename);  // remove temp file



?>