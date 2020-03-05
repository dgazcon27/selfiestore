<?php


	
	

header('Content-Type: text/html; charset=UTF-8');

include "core/controller/Core.php";
include "core/controller/Database.php";
include "core/controller/Executor.php";
include "core/controller/Model.php";
include "core/app/model/UserData.php";
include "core/app/model/SellData.php";
include "core/app/model/OperationData.php";
include "core/app/model/ProductData.php";
include "core/app/model/PersonData.php";
include "core/app/model/StockData.php";
include "core/app/model/ConfigurationData.php";
include "core/app/model/ExchangeData.php";
include "fpdf/fpdf.php";

include 'barcode.php';



session_start();

if(isset($_SESSION["user_id"])){ Core::$user = UserData::getById($_SESSION["user_id"]); }

if(ConfigurationData::getByPreffix("ticket_title")->val == TRUE)
{
	$title = ConfigurationData::getByPreffix("ticket_title")->val;
}
else
{
	$title = "";
}
if(ConfigurationData::getByPreffix("slogan")->val == TRUE)
{
	$slogan = ConfigurationData::getByPreffix("slogan")->val;
}
else
{
	$slogan = "";
}

if(ConfigurationData::getByPreffix("rif")->val == TRUE)
{
	$rif = ConfigurationData::getByPreffix("rif")->val;
	$simbolorif = "J-";
}
else
{
	$rif = "";
	$simbolorif = "";
}

if(ConfigurationData::getByPreffix("direccion")->val == TRUE)
{
	$direccion = ConfigurationData::getByPreffix("direccion")->val;
}
else
{
	$direccion = "";
}

if(ConfigurationData::getByPreffix("telefono")->val == TRUE)
{
	$telefono = ConfigurationData::getByPreffix("telefono")->val;
}
else
{
	$telefono = "";
}

$image = ConfigurationData::getByPreffix('report_image')->val;
if (isset($image) && $image != "") {
	$image = "storage/configuration/".$image;
} else {
	$image = "img/logo.png";
}

$stock = StockData::getPrincipal();
$sell = SellData::getById($_GET["id"]);
$operations = OperationData::getAllProductsBySellId($_GET["id"]);
$user = isset($sell->receive_by) ? SellData::getSellUser($sell->receive_by) : PersonData::getById($sell->receive_by);

$servidor = "localhost";
$usuario = "root";
$pass = "";


if(isset($sell->person_id))
{
	$con = Database::getCon();
	$c = $con->query("SELECT * FROM person WHERE ID=$sell->person_id");
	$row = $c->fetch_array();
	$id = $row["id"];
	$nombre = $row["name"];
	$lastname = $row["lastname"];
	$identificacion = $row["no"];
	$phone = $row["phone1"];
	$address1 = $row["address1"];		
}
else{
	$id = '';
	$nombre = '';
	$lastname = '';
	$identificacion = '';
	$phone = '';
	$address1 = '';
}


$exchange = ExchangeData::getById(1);
$simbolo = "Bs.  ";
$tasa = $exchange->value;
	


if($sell->f_id == 1)
	$variable = "EFECTIVO";
elseif($sell->f_id == 2)
	$variable = "TRANSFERENCIA";
elseif($sell->f_id == 3)
	$variable = "ZELLE";
elseif($sell->f_id == 4)
	$variable = "DUAL";
elseif($sell->f_id == 5)
	$variable = "PUNTO DE VENTA";

if($sell->user_id == $user->id)
	$variable2 = $user->name;


$pdf = new FPDF($orientation='P',$unit='mm', array(45,550));
$pdf->AddPage();


$acumulador = 100000;
$code = $acumulador+$sell->ref_id;

$pdf->SetFont('Arial','B',14);    //Letra Arial, negrita (Bold), tam. 20
//$pdf->setXY(5,0);


$pdf->setY(4);
$pdf->setX(12);
$pdf->Cell(0,0,($pdf->Image($image,null,null,24,18)),0,1,"C");
	


$pdf->setY(22);
$pdf->setX(11);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,0,utf8_decode($title),0,1,"C");
$pdf->setY(25);
$pdf->SetFont('Arial','',7);
$pdf->Cell(0,0,utf8_decode($slogan),0,1,"C");
$pdf->SetFont('Arial','',7);
$pdf->setY(27.60);
$pdf->Cell(0,0,$rif,0,1,"C");
$pdf->setY(31);
$pdf->SetFont('Arial','B',4.45);
$pdf->Cell(0,0,utf8_decode($direccion),0,1,"C");
$pdf->setY(33);
$pdf->SetFont('Arial','B',4.45);
$pdf->Cell(0,0,utf8_decode($telefono),0,1,"C");



$pdf->Image("img/1.png",0,37.30,46,4);
$pdf->Image("img/1.png",0,60.70,46,4);
$pdf->Image("img/1.png",0,83.70,46,4);


$pdf->Image("img/factura1.png",0.50,43,3,3);
$pdf->Image("img/icon1.png",0.50,47.10,3,3);
$pdf->Image("img/usuario3.png",0.50,51.10,3.20,3.20);
$pdf->Image("img/pago.png",0.50,55.10,3,3);

$pdf->Image("img/nombre.png",0.50,65.90,3,3);
$pdf->Image("img/carnet.png",0.50,69.90,3,3);
$pdf->Image("img/ubicacion.png",0.50,73.90,3,3);
$pdf->Image("img/telefono.png",0.50,77.90,3,3);


$pdf->SetFont('Arial','',10); 
$pdf->setY(39.50);
$pdf->Cell(0,0,"COMPROBANTE",0,1,"C");

$pdf->setY(33);
$pdf->setX(0);
$pdf->SetFont('Arial','',10); 
$pdf->Cell(0,5,'________________________________________');

$pdf->setY(38);
$pdf->setX(0.10);
$pdf->SetFont('Arial','',10); 
$pdf->Cell(5,5,'.............................................');





$pdf->SetFont('Arial','B',6);
$pdf->setY(42.20);
$pdf->setX(3.50);
$pdf->Cell(5,5,"No DE COMPROBANTE:");
$pdf->SetFont('Arial','',6);
$pdf->setY(42.30);
$pdf->setX(28.20);
$pdf->Cell(5,5," #".$code);
$pdf->setY(46.20);
$pdf->setX(3.50);
$pdf->SetFont('Arial','B',6);
$pdf->Cell(5,5,"FECHA: ");
$pdf->setY(46.20);
$pdf->setX(12.30);
$pdf->SetFont('Arial','',6);
$pdf->Cell(5,5,$sell->created_at);
$pdf->SetFont('Arial','',3.50);
$pdf->setY(50.20);
$pdf->setX(22);
$pdf->SetFont('Arial','B',6); 
$pdf->setY(50.20);
$pdf->setX(3.50);
$pdf->Cell(5,5,"VENDEDOR: ");
$pdf->setY(50.20);
$pdf->setX(17);
$pdf->SetFont('Arial','',6);
$pdf->Cell(5,5,strtoupper(utf8_decode($user->name." ".$user->lastname)));
$pdf->setY(54.20);
$pdf->setX(3.50);
$pdf->SetFont('Arial','B',6);
$pdf->Cell(5,5,"METODO DE PAGO: ");
$pdf->setY(54.20);
$pdf->setX(24.60);
$pdf->SetFont('Arial','',6);
$pdf->Cell(5,5,$variable);





$pdf->setY(57);
$pdf->setX(0.10);
$pdf->SetFont('Arial','',10); 
$pdf->Cell(5,5,'.............................................');
$pdf->setY(60);
$pdf->setX(9);
$pdf->SetFont('Arial','B',7); 
$pdf->Cell(5,5,"DATOS DEL CLIENTE ");
$pdf->setY(61);
$pdf->setX(0.10);
$pdf->SetFont('Arial','',10); 
$pdf->Cell(5,5,'.............................................');
$pdf->setY(65.20);
$pdf->setX(3.50);
$pdf->SetFont('Arial','B',6);
$pdf->Cell(5,5,"NOMBRE: ");
$pdf->setY(65.20);
$pdf->setX(14.40);
$pdf->SetFont('Arial','',6);
$pdf->Cell(5,5,strtoupper(utf8_decode(substr($nombre." ".$lastname, 0,24))));
$pdf->setY(69.20);
$pdf->setX(3.50);
$pdf->SetFont('Arial','B',6);
$pdf->Cell(5,5,"IDENTIFICACION: ");
$pdf->setY(69.20);
$pdf->setX(22.20);
$pdf->SetFont('Arial','',6);
$pdf->Cell(5,5,strtoupper(utf8_decode($identificacion)));
$pdf->setY(73.20);
$pdf->setX(3.50);
$pdf->SetFont('Arial','B',6);
$pdf->Cell(5,5,"DIRECCION: ");
$pdf->setY(73.20);
$pdf->setX(17);
$pdf->SetFont('Arial','',6);
$pdf->Cell(5,5,strtoupper(utf8_decode(substr($address1, 0,23))));
$pdf->setY(77.20);
$pdf->setX(3.50);
$pdf->SetFont('Arial','B',6);
$pdf->Cell(5,5,"TELEFONO: ");
$pdf->setY(77.20);
$pdf->setX(16.65);
$pdf->SetFont('Arial','',6);
$pdf->Cell(5,5,strtoupper($phone));



$pdf->setY(80);
$pdf->setX(0.10);
$pdf->SetFont('Arial','',10); 
$pdf->Cell(5,5,'............................................');
$pdf->SetFont('Arial','B',4);
$pdf->setY(83);
$pdf->setX(0.10);
$pdf->Cell(5,5," ART.                                CANT.                                  TOTAL.");
$pdf->setY(84);
$pdf->setX(0.10);
$pdf->SetFont('Arial','',10); 
$pdf->Cell(5,5,'............................................');


$pdf->SetFont('Arial','',4.70);
$total =0;
$off = 1;
$posi = 0;
foreach($operations as $op){
$product = $op->getProduct();
$pdf->setY(89);
$pdf->setX(0.25);
$pdf->Cell(5,$off,  strtoupper(substr($product->name, 0,12)));
$pdf->setY(89);
$pdf->setX(18.50);
$pdf->Cell(33,$off,"$op->q");
$pdf->setY(89);
$pdf->setX(33);
$pdf->Cell(11,$off,$simbolo.number_format(($op->q*$product->price_out*$tasa),2,",","."),0,0,'R');
	
//    ".."  ".number_format($op->q*$product->price_out,2,"."."."));
$total += $op->q*$product->price_out;
$off+=6;
$posi+=3;
}




$pdf->setY(87);
$pdf->setX(0.10);
$pdf->SetFont('Arial','',10); 
$pdf->Cell(5,$off,'________________________________________________');



$pdf->SetFont('Arial','',6);
$pdf->setY(91);
$pdf->setX(0.10);
$pdf->Cell(5,$off,"PAGO: " );
$pdf->setY(91);
$pdf->setX(39);
$pdf->Cell(5,$off,$simbolo.number_format(($sell->cash*$tasa),2,",","."),0,0,'R');
$pdf->setY(94);
$pdf->setX(0.10);
$pdf->Cell(5,$off,"CAMBIO: " );
$pdf->setY(94);
$pdf->setX(39);
$totalcambio= $total - $sell->discount;
$totalcambio= $sell->cash - $totalcambio;
$pdf->Cell(5,$off,$simbolo.number_format((0),2,",","."),0,0,'R');
$pdf->setY(97);
$pdf->setX(0.10);
$pdf->SetFont('Arial','',6);
$pdf->Cell(5,$off,"SUBTOTAL:  " );
$pdf->setY(97);
$pdf->setX(39);
$pdf->Cell(5,$off,$simbolo.number_format(($total*$tasa),2,",","."),0,0,'R');
$pdf->setY(100);
$pdf->setX(0.10);
$pdf->Cell(5,$off,"DESCUENTO: " );
$pdf->setY(100);
$pdf->setX(39);
$pdf->Cell(5,$off,$simbolo.number_format(($sell->discount*$tasa),2,",","."),0,0,'R');







$pdf->SetFont('Arial','B',6);
$pdf->setY(103);
$pdf->setX(0.10);
$pdf->Cell(5,$off,"TOTAL: " );
$pdf->setY(103);
$pdf->setX(39);
$pdf->Cell(5,$off,$simbolo.number_format((($total - $sell->discount)*$tasa),2,".",","),0,0,'R');


$pdf->setY(109);
$pdf->setX(0.10);
$pdf->SetFont('Arial','',10); 
$pdf->Cell(5,$off,'............................................');


$pdf->SetAutoPageBreak(true, 20);
	$y = $pdf->GetY();
	
	
		
		$acumulador = 100000;
		$code = $acumulador+$sell->ref_id;
		
		barcode('codigos/'.$code.'.png', $code, 20, 'horizontal', 'code128', true);
		
		$pdf->Image('codigos/'.$code.'.png',12,$posi+113,20,0,'PNG');
		
		$y = $y+15;
	

$pdf->setY(119);
$pdf->setX(0.10);
$pdf->SetFont('Arial','',10); 
$pdf->Cell(5,$off,'............................................');

$pdf->output();


