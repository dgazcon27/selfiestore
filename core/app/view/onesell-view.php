<?php
$sell = SellData::getById($_GET["id"]);
?>
<section class="content">
<div class="btn-group pull-right">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-download"></i> DESCARGAR <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu">
    <li><a href="ticket.php?id=<?php echo $_GET["id"];?>">Ticket (.pdf)</a></li>
    <li><a href="report/onesell-word.php?id=<?php echo $_GET["id"];?>">Word 2007 (.docx)</a></li>
<li><a onclick="thePDF()" id="makepdf" class=""><i class="fa fa-download"></i> Descargar PDF</a>
  </ul>
</div>
<h1>RESUMEN DE VENTA #<?php echo $sell->ref_id;?></h1>
<?php if(isset($_GET["id"]) && $_GET["id"]!=""):?>
<?php
$operations = OperationData::getAllProductsBySellId($_GET["id"]);
$total = 0;
$costo = 0;
$gasto = 0;
?>
<?php
//if($product->kind==1){
if(isset($_COOKIE["selled"])){
  foreach ($operations as $operation) {
//    print_r($operation);
    $qx = OperationData::getQByStock($operation->product_id,StockData::getPrincipal()->id);
    // print "qx=$qx";
      $p = $operation->getProduct();
    if($p->kind==1&&$qx==0){
      echo "<p class='alert alert-danger'>El producto <b style='text-transform:uppercase;'> $p->name</b> no tiene existencias en inventario.</p>";      
    }else if($p->kind==1&&$qx<=$p->inventary_min/2){
      echo "<p class='alert alert-danger'>El producto <b style='text-transform:uppercase;'> $p->name</b> tiene muy pocas existencias en inventario.</p>";
    }else if($p->kind==1&&$qx<=$p->inventary_min){
      echo "<p class='alert alert-warning'>El producto <b style='text-transform:uppercase;'> $p->name</b> tiene pocas existencias en inventario.</p>";
    }
  }
  setcookie("selled","",time()-18600);
}

?>
<div class="row">
<div class="col-md-8">
<div class="box box-primary">
<table class="table table-bordered">
<?php if($sell->person_id!=""):
$client = $sell->getPerson();
?>
<tr>
  <td style="width:150px;">CLIENTE</td>
  <td><?php echo strtoupper($client->name." ".$client->lastname);?></td>
</tr>

<?php endif; ?>
<?php if($sell->user_id!=""):
$user = $sell->getUser();
?>
<tr>
  <td>ATENDIDO POR</td>
  <td><?php echo strtoupper($user->name." ".$user->lastname);?></td>
</tr>
<?php endif; ?>
</table>
</div>
<br>
<div class="box box-primary">
<table class="table table-bordered table-hover">
  <thead>
    <th>CODIGO</th>
	<th>IMAGEN</th>
	<th>NOMBRE DEL PRODUCTO</th>
    <th>CANTIDAD</th>
    <th>PRECIO UNITARIO</th>
    <th>TOTAL</th>

  </thead>
<?php
  foreach($operations as $operation){
    $product  = $operation->getProduct();
?>
<tr>
  <td><?php echo $product->id ;?></td>
	<td><img src="storage/products/<?php echo $product->image;?>" style="width:40px;"></td>
	<td><?php echo $product->name ;?></td>
  <td><?php echo $operation->q ;?></td>
  
  
  <td>$ <?php echo number_format($operation->price_out,2,".",",") ;?></td>
  <td><b>$ <?php echo number_format($operation->q*$operation->price_out,2,".",",");$total+=$operation->q*$operation->price_out;
	  
	  
	$costo+=$operation->q*$operation->price_in;  
	  
	  
	  ?></b></td>
</tr>
<?php
  }
  ?>
</table>
</div>
<br><br>
<div class="row">
<div class="col-md-4">
	
	
<?php if($sell->person_id!=""):
$credit=PaymentData::sumByClientId($sell->person_id)->total;

?>

<?php endif;?>	
	
	
<div class="box box-primary">
<table class="table table-bordered">
  <tr>
    <td><h4>DESCUENTO:</h4></td>
    <td><h4>$ <?php echo number_format($sell->discount,2,'.',','); ?></h4></td>
  </tr>
  <tr>
    <td><h4>SUBTOTAL:</h4></td>
    <td><h4>$ <?php echo number_format($total,2,'.',','); ?></h4></td>
  </tr>
  <tr>
    <td><h4>TOTAL:</h4></td>
    <td><h4>$ <?php echo number_format($total-  $sell->discount,2,'.',','); ?></h4></td>
  </tr>
</table>
</div>


</div>
</div>


</div>
<div class="col-md-4">
<form method="post" class="form-horizontal" action="./?action=updatesell" id="processsell" enctype="multipart/form-data">
<div class="row">
<div class="col-md-12">
<div>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#main" aria-controls="main" role="tab" data-toggle="tab">Principal</a></li>
    <li role="presentation"><a href="#extra"  aria-controls="extra" role="tab" data-toggle="tab">Extra</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="main">

<div class="row">

<div class="col-md-6">
    <label class="control-label">ALMACEN</label>
    <div class="col-lg-12">
    <h4 class=""><?php 
    echo StockData::getPrincipal()->name;
    ?></h4>
    </div>
  </div>

<div class="col-md-6">
    <label class="control-label">CLIENTE</label>
    <div class="col-lg-12">
    <?php 
$clients = PersonData::getClients();
    ?>
    <select name="client_id" id="client_id" class="form-control">
    <option value="">NINGUNO</option>
    <?php foreach($clients as $client):?>
      <option value="<?php echo $client->id;?>" <?php if($client->id==$sell->person_id){ echo "selected"; }?>><?php echo strtoupper($client->name." ".$client->lastname);?></option>
    <?php endforeach;?>
      </select>
    </div>
  </div>
  </div>

<div class="row">

<div class="col-md-12">
    <label class="control-label">FORMA DE PAGO</label>
    <div class="col-lg-12">
    <?php 
$clients = FData::getAll();
    ?>
    <select name="f_id" id="p_id" class="form-control">
    <?php foreach(FData::getAll() as $client):?>
      <option value="<?php echo $client->id;?>" <?php if($client->id==$sell->f_id){ echo "selected"; }?>><?php echo strtoupper($client->name);?></option>
    <?php endforeach;?>
      </select>
    </div>
  </div>
	
	
	<div class="row">

<div class="col-md-12">
    <label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;REFERENCIA</label>
    <div class="col-lg-12">
      <input type="text" name="refe" value="<?php echo $sell->refe;?>" class="form-control" id="refe" placeholder="NUMERO DE REFERENCIA DE TRANSFERENCIA">
    </div>
  </div>
</div>
	
	
<div class="row">

<div class="col-md-6">
    <label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DUAL EFECTIVO</label>
    <div class="col-lg-12">
      <input type="text" name="efe" class="form-control" value="<?php echo $sell->efe;?>" id="efe" placeholder="EFECTIVO">
    </div>
  </div>
<div class="col-md-6">
    <label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DUAL PUNTO DE VENTA</label>
    <div class="col-lg-12">
      <input type="text" name="pun" class="form-control" value="<?php echo $sell->pun;?>" id="pun" placeholder="PUNTO DE VENTA">
    </div>
  </div>
 <div class="col-md-6">
    <label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DUAL TRANSFERENCIA</label>
    <div class="col-lg-12">
      <input type="text" name="tra" value="<?php echo $sell->tra;?>" class="form-control" id="tra" placeholder="TRANSFERENCIA">
    </div>
  </div>
<div class="col-md-6">
    <label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DUAL ZELLE</label>
    <div class="col-lg-12">
      <input type="text" name="zel" class="form-control" value="<?php echo $sell->zel;?>" id="zel" placeholder="ZELLE">
    </div>
  </div>
  </div>
	



<div class="row">

<div class="col-md-6" hidden=¨true¨>
    <label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ACTUALIZAR VENTA</label>
    <div class="col-lg-12">
      <input type="text" name="total" value="<?php echo $total; ?>" class="form-control" id="total" placeholder="ACTUALIZAR VENTA">
    </div>
  </div>
</div>
		
		<div class="row" >

<div class="col-md-6">
    <label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;INVOICE</label>
    <div class="col-lg-12">
      <input type="text" name="invoice_code" value="<?php echo $costo; ?>" class="form-control" id="invoice_code" placeholder="ACTUALIZAR VENTA">
    </div>
  </div>
</div>





	

</div>
    </div>
    <div role="tabpanel" class="tab-pane" id="extra">

<div class="row">

<div class="col-md-12">
    <label class="control-label">ARCHIVO FACTURA</label>
    <div class="col-lg-12">
    <?php if($sell->invoice_file!=""):?>
      <a href="./storage/invoice_files/<?php echo $sell->invoice_file;?>" target="_blank" class="btn btn-default"><i class="fa fa-file"></i> ARCHIVO FACTURA (<?php echo $sell->invoice_file; ?>)</a>
      <br><br>
    <?php endif; ?>
      <input type="file" name="invoice_file"  placeholder="Archivo Factura">
    </div>
  </div>
  </div>

<div class="row">

<div class="col-md-12">
    <div class="">
    <label class="control-label">INFORMACIÓN EXTRA</label>
      <textarea name="comment"  placeholder="INGRESAR IMEI" class="form-control" rows="10"><?php echo $sell->comment;?></textarea>
    </div>
  </div>
  </div>
		
		
		

    </div>
  </div>

</div>
</div>
</div>




<input type="hidden" name="id" value="<?php echo $sell->id; ?>">
  <div class="row">
<div class="col-md-12">

<div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <div class="checkbox">
        <label>
        <button class="btn btn-success"> ACTUALIZAR VENTA</button>
        </label>
      </div>
    </div>
  </div>
</form>
</div>

</div>






<script type="text/javascript">
        function thePDF() {

var columns = [
//    {title: "Reten", dataKey: "reten"},
    {title: "Codigo", dataKey: "code"}, 
    {title: "Cantidad", dataKey: "q"}, 
    {title: "Nombre del Producto", dataKey: "product"}, 
    {title: "Precio unitario", dataKey: "pu"}, 
    {title: "Total", dataKey: "total"}, 
//    ...
];


var columns2 = [
//    {title: "Reten", dataKey: "reten"},
    {title: "", dataKey: "clave"}, 
    {title: "", dataKey: "valor"}, 
//    ...
];

var rows = [
  <?php foreach($operations as $operation):
  $product  = $operation->getProduct();
  ?>

    {
      "code": "<?php echo $product->id; ?>",
      "q": "<?php echo $operation->q; ?>",
      "product": "<?php echo $product->name; ?>",
      "pu": "$ <?php echo number_format($operation->price_out,2,".",","); ?>",
      "total": "$ <?php echo number_format($operation->q*$operation->price_out,2,".",","); ?>",
      },
 <?php endforeach; ?>
];

var rows2 = [
<?php if($sell->person_id!=""):
$person = $sell->getPerson();
?>

    {
      "clave": "Cliente",
      "valor": "<?php echo $person->name." ".$person->lastname; ?>",
      },
      <?php endif; ?>
    {
      "clave": "Atendido por",
      "valor": "<?php echo $user->name." ".$user->lastname; ?>",
      },

];

var rows3 = [

    {
      "clave": "Descuento",
      "valor": "$ <?php echo number_format($sell->discount,2,'.',',');; ?>",
      },
    {
      "clave": "Subtotal",
      "valor": "$ <?php echo number_format($sell->total,2,'.',',');; ?>",
      },
    {
      "clave": "Total",
      "valor": "$ <?php echo number_format($sell->total-$sell->discount,2,'.',',');; ?>",
      },
];


// Only pt supported (not mm or in)
var doc = new jsPDF('p', 'pt');
        doc.setFontSize(26);
        doc.text("NOTA DE VENTA #<?php echo $sell->ref_id; ?>", 40, 65);
        doc.setFontSize(14);
        doc.text("Fecha: <?php echo $sell->created_at; ?>", 40, 80);
//        doc.text("Operador:", 40, 150);
//        doc.text("Header", 40, 30);
  //      doc.text("Header", 40, 30);

doc.autoTable(columns2, rows2, {
    theme: 'grid',
    overflow:'linebreak',
    styles: {
        fillColor: [100, 100, 100]
    },
    columnStyles: {
        id: {fillColor: 255}
    },
    margin: {top: 100},
    afterPageContent: function(data) {
//        doc.text("Header", 40, 30);
    }
});


doc.autoTable(columns, rows, {
    theme: 'grid',
    overflow:'linebreak',
    styles: {
        fillColor: [100, 100, 100]
    },
    columnStyles: {
        id: {fillColor: 255}
    },
    margin: {top: doc.autoTableEndPosY()+15},
    afterPageContent: function(data) {
//        doc.text("Header", 40, 30);
    }
});

doc.autoTable(columns2, rows3, {
    theme: 'grid',
    overflow:'linebreak',
    styles: {
        fillColor: [100, 100, 100]
    },
    columnStyles: {
        id: {fillColor: 255}
    },
    margin: {top: doc.autoTableEndPosY()+15},
    afterPageContent: function(data) {
//        doc.text("Header", 40, 30);
    }
});

//doc.setFontsize
//img = new Image();
//img.src = "liberacion2.jpg";
//doc.addImage(img, 'JPEG', 40, 10, 610, 100, 'monkey'); // Cache the image using the alias 'monkey'
doc.setFontSize(20);
doc.setFontSize(12);
doc.text("Generado por el Sistema de inventario", 40, doc.autoTableEndPosY()+25);
doc.save('sell-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
//doc.output("datauri");

        }
    </script>

<script>
  $(document).ready(function(){
  //  $("#makepdf").trigger("click");
  });
</script>




<?php else:?>
  501 Internal Error
<?php endif; ?>
</section>