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
include "core/app/model/StockData.php";
include "core/app/model/ConfigurationData.php";
include "fpdf/fpdf.php";
session_start();

if(isset($_SESSION["user_id"])){ Core::$user = UserData::getById($_SESSION["user_id"]); }
$title = ConfigurationData::getByPreffix("ticket_title")->val;

$stock = StockData::getPrincipal();
$sell = SellData::getById($_GET["id"]);
$operations = OperationData::getAllProductsBySellId($_GET["id"]);
$user = $sell->getUser();


$servidor = "localhost";
$usuario = "root";
$pass = "root";


if($sell->person_id == TRUE)
{
	$con = mysqli_connect($servidor,$usuario,$pass); 
	mysqli_select_db($con, "inventiomax"); 
	$result = mysqli_query($con,"SELECT * FROM person WHERE ID=$sell->person_id");

	$row = mysqli_fetch_array($result);
	$id = $row["id"];
	$nombre = $row["name"];
	$lastname = $row["lastname"];
	$identificacion = $row["no"];
	$phone = $row["phone1"];
	$email = $row["email1"];		
}
else{
	$id = '';
	$nombre = '';
	$lastname = '';
	$identificacion = '';
	$phone = '';
	$email = '';
}


if($sell->invoice_file == 0)
{
	$simbolo = "$  ";
	$tasa = $sell->invoice_file;
	$tasa = 1;
}
elseif($sell->invoice_file > 0)
{
	$simbolo = "Bs.  ";
	$tasa = $sell->invoice_file;
}
	


if($sell->f_id == 1)
	$variable = "EFECTIVO";
elseif($sell->f_id == 2)
	$variable = "TRANSFERENCIA";
elseif($sell->f_id == 3)
	$variable = "ZELLE";
elseif($sell->f_id == 4)
	$variable = "DUAL";


if($sell->user_id == $user->id)
	$variable2 = $user->name;



$pdf = new FPDF($orientation='P',$unit='mm', array(45,550));
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);    //Letra Arial, negrita (Bold), tam. 20
//$pdf->setXY(5,0);
$pdf->setY(2);
$pdf->setX(5,50);
$pdf->Cell(5,5,"EGLEE GOLD");
$pdf->SetFont('Arial','',5);$pdf->setY(4);
$pdf->setY(6);
$pdf->setX(16);
$pdf->Cell(5,5,"J-412546686");$pdf->setY(0,50);
$pdf->setX(3);
$pdf->SetFont('Arial','B',5);
$pdf->Cell(5,23,"BRILLO Y ELEGANCIA EN UN SOLO LUGAR");

$pdf->SetFont('Arial','B',4);$pdf->setY(15);
$pdf->setX(0.10);
$pdf->Cell(5,5,"No. FACTURA:");
$pdf->SetFont('Arial','',3.50);
$pdf->setY(15);
$pdf->setX(10);
$pdf->Cell(5,5," #".$sell->ref_id);
$pdf->setY(15);
$pdf->setX(23.75);
$pdf->SetFont('Arial','B',4);
$pdf->Cell(5,5,"FECHA: ");
$pdf->setY(15);
$pdf->setX(29.30);
$pdf->SetFont('Arial','',3.50);
$pdf->Cell(5,5,$sell->created_at);
$pdf->SetFont('Arial','',3.50);
$pdf->setY(35);
$pdf->setX(22);

$pdf->SetFont('Arial','B',4); 
$pdf->setY(18);
$pdf->setX(0.10);
$pdf->Cell(5,5,"VENDEDOR: ");
$pdf->setY(18);
$pdf->setX(8.75);
$pdf->SetFont('Arial','',3.50);
$pdf->Cell(5,5,strtoupper($user->name." ".$user->lastname));
$pdf->setY(18);
$pdf->setX(23.75);
$pdf->SetFont('Arial','B',4);
$pdf->Cell(5,5,"PAGO: ");
$pdf->setY(18);
$pdf->setX(28.60);
$pdf->SetFont('Arial','',3.50);
$pdf->Cell(5,5,$variable);
$pdf->setY(23);
$pdf->setX(10);
$pdf->SetFont('Arial','B',5); 
$pdf->Cell(5,5,"DATOS DE LA SUCURSAL ".strtoupper($stock->phone));
$pdf->setY(25);
$pdf->setX(0.10);
$pdf->SetFont('Arial','',10); 
$pdf->Cell(5,5,'............................................');
$pdf->setY(29);
$pdf->setX(0.10);
$pdf->SetFont('Arial','B',4);
$pdf->Cell(5,5,"NOMBRE: ");
$pdf->setY(29);
$pdf->setX(7);
$pdf->SetFont('Arial','',3.50);
$pdf->Cell(5,5,"EGLEE GOLD, C.A");
$pdf->setY(29);
$pdf->setX(23.75);
$pdf->SetFont('Arial','B',4);
$pdf->Cell(5,5,"IDENTIFICACION: ");
$pdf->setY(29);
$pdf->setX(35.90);
$pdf->SetFont('Arial','',3.50);
$pdf->Cell(5,5,"J-411907316");
$pdf->setY(32);
$pdf->setX(0.10);
$pdf->SetFont('Arial','B',4);
$pdf->Cell(5,5,"EMAIL: ");
$pdf->setY(32);
$pdf->setX(5.15);
$pdf->SetFont('Arial','',3.50);
$pdf->Cell(5,5,"EGLEEGOLD@GMAIL.COM");
$pdf->setY(32);
$pdf->setX(23.75);
$pdf->SetFont('Arial','B',4);
$pdf->Cell(5,5,"TELEFONO: ");
$pdf->setY(32);
$pdf->setX(32.15);
$pdf->SetFont('Arial','',3.50);
$pdf->Cell(5,5,"0241-8238660");
$pdf->setY(35);
$pdf->setX(0.10);
$pdf->SetFont('Arial','B',4);
$pdf->Cell(5,5,"DIRECCION: ");
$pdf->setY(35);
$pdf->setX(8.75);
$pdf->SetFont('Arial','',3.50);
$pdf->Cell(5,5,utf8_decode("MULTICENTRO EL VIÑEDO PRIMER NIVEL LOCAL C-117"));



$pdf->setY(40);
$pdf->setX(12);
$pdf->SetFont('Arial','B',5); 
$pdf->Cell(5,5,"DATOS DEL CLIENTE ".strtoupper($stock->phone));
$pdf->setY(42);
$pdf->setX(0.10);
$pdf->SetFont('Arial','',10); 
$pdf->Cell(5,5,'............................................');
$pdf->setY(46);
$pdf->setX(0.10);
$pdf->SetFont('Arial','B',4);
$pdf->Cell(5,5,"NOMBRE: ");
$pdf->setY(46);
$pdf->setX(7);
$pdf->SetFont('Arial','',3.50);
$pdf->Cell(5,5,utf8_decode($nombre." ".$lastname));
$pdf->setY(46);
$pdf->setX(23.75);
$pdf->SetFont('Arial','B',4);
$pdf->Cell(5,5,"IDENTIFICACION: ");
$pdf->setY(46);
$pdf->setX(35.90);
$pdf->SetFont('Arial','',3.50);
$pdf->Cell(5,5,strtoupper($identificacion));
$pdf->setY(49);
$pdf->setX(0.10);
$pdf->SetFont('Arial','B',4);
$pdf->Cell(5,5,"DIRECCION: ");
$pdf->setY(49);
$pdf->setX(8.70);
$pdf->SetFont('Arial','',3.50);
$pdf->Cell(5,5,strtoupper($email));
$pdf->setY(49);
$pdf->setX(23.75);
$pdf->SetFont('Arial','B',4);
$pdf->Cell(5,5,"TELEFONO: ");
$pdf->setY(49);
$pdf->setX(32.15);
$pdf->SetFont('Arial','',3.50);
$pdf->Cell(5,5,strtoupper($phone));
$pdf->setY(51);
$pdf->setX(0.10);
$pdf->SetFont('Arial','',10); 
$pdf->Cell(5,5,'............................................');

$pdf->SetFont('Arial','B',3.50);
$pdf->setY(56);
$pdf->setX(0.10);
$pdf->Cell(5,5,utf8_decode('TODO PRENDA TIENE 1 MES DE GARANTIA MEDIANTE LA EMPRESA.'));


$pdf->setY(59);
$pdf->setX(0.10);
$pdf->SetFont('Arial','',10); 
$pdf->Cell(5,5,'............................................');
$pdf->SetFont('Arial','B',4);
$pdf->setY(62);
$pdf->setX(0.10);
$pdf->Cell(5,5,"   ARTICULO      |      CANTIDAD      |      PRECIO      |      TOTAL");
$pdf->setY(63);
$pdf->setX(0.10);
$pdf->SetFont('Arial','',10); 
$pdf->Cell(5,5,'............................................');


$pdf->SetFont('Arial','',3.50);
$total =0;
$off = 1;
foreach($operations as $op){
$product = $op->getProduct();
$pdf->setY(68);
$pdf->setX(0.70);
$pdf->Cell(5,$off,  strtoupper(substr($product->name, 0,12)) );
$pdf->setY(68);
$pdf->setX(17);
$pdf->Cell(35,$off,"$op->q");
$pdf->setY(68);
$pdf->setX(21.30);
$pdf->Cell(11,$off,$simbolo.number_format($product->price_out,2,".",",")*($tasa),0,0,"R");
$pdf->setY(68);
$pdf->setX(31.30);
$pdf->Cell(11,$off,$simbolo.number_format($op->q*$product->price_out,2,".",",")*($tasa),0,0,"R");
	
//    ".."  ".number_format($op->q*$product->price_out,2,".",","));
$total += $op->q*$product->price_out;
$off+=6;
}



$pdf->setY(71);
$pdf->setX(0.10);
$pdf->SetFont('Arial','',10); 
$pdf->Cell(5,$off,'............................................');


$pdf->SetFont('Arial','B',5);
$pdf->setY(75);
$pdf->setX(0.10);
$pdf->Cell(5,$off,"EFECTIVO: " );
$pdf->setY(75);
$pdf->setX(10);
$pdf->Cell(5,$off,$simbolo.$sell->cash*$tasa);
$pdf->setY(78);
$pdf->setX(0.10);
$pdf->Cell(5,$off,"CAMBIO: " );
$pdf->setY(78);
$pdf->setX(10);
$totalcambio= $total - $sell->discount;
$totalcambio= $sell->cash - $totalcambio;
$pdf->Cell(5,$off,$simbolo.$totalcambio*$tasa);
$pdf->setY(75);
$pdf->setX(23);
$pdf->SetFont('Arial','B',5);
$pdf->Cell(5,$off,"SUBTOTAL:  " );
$pdf->setY(75);
$pdf->setX(35);
$pdf->Cell(5,$off,$simbolo.$total*$tasa);
$pdf->setY(78);
$pdf->setX(23);
$pdf->Cell(5,$off,"DESCUENTO: " );
$pdf->setY(78);
$pdf->setX(35);
$pdf->Cell(5,$off,$simbolo.$sell->discount*$tasa);
$pdf->SetFont('Arial','B',5);
$pdf->setY(81);
$pdf->setX(23);
$pdf->Cell(5,$off,"TOTAL: " );
$pdf->setY(81);
$pdf->setX(35);
$pdf->Cell(5,$off,$simbolo.($total - $sell->discount*$tasa));



$pdf->setY(85);
$pdf->setX(0.10);
$pdf->SetFont('Arial','',10); 
$pdf->Cell(5,$off,'............................................');


$pdf->output();