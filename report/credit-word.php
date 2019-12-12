<?php
include "../core/autoload.php";
include "../core/app/model/PersonData.php";
include "../core/app/model/PaymentData.php";
include "../core/app/model/ConfigurationData.php";

require_once '../core/controller/PhpWord/Autoloader.php';
use PhpOffice\PhpWord\Autoloader;
use PhpOffice\PhpWord\Settings;

Autoloader::register();

$word = new  PhpOffice\PhpWord\PhpWord();
$clients = PersonData::getClients();

$section1 = $word->AddSection(["paperSize" => "Letter", 'marginLeft' => 600, 'marginRight' => 600, 'marginTop' => 600, 'marginBottom' => 600]);

$word->addFontStyle('r2Style', array('bold'=>true,'size'=>30));
$word->addFontStyle('estilofecha', array('bold'=>true,'size'=>10));
$word->addParagraphStyle('p2Style', array('align'=>'center'));

$nombreDeSucursal = ConfigurationData::getByPreffix("company_name")->val;
$section1->addText($nombreDeSucursal,'r2Style', 'p2Style');
$section1->addText("CREDITO",'r2Style', 'p2Style');
$section1->addText(date("d/m/Y", strtotime($clients[0]->created_at)),'estilofecha', 'p2Style');

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
header("Content-Disposition: attachment; filename=$filename");
readfile($filename); // or echo file_get_contents($filename);
unlink($filename);  // remove temp file



?>