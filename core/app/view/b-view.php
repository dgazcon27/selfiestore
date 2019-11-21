<section class="content">
<div class="row">
	<div class="col-md-12">

<!-- Single button -->
<div class="btn-group pull-right">
<a href="./index.php?view=boxhistory" class="btn btn-default"><i class="fa fa-clock-o"></i> Historial</a>
<div class="btn-group">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-download"></i> Descargar <span class="caret"></span>
  </button>
  <ul class="dropdown-menu pull-right" role="menu">
    <li><a href="report/box-word.php?id=<?php echo $_GET["id"];?>">Word 2007 (.docx)</a></li>
  </ul>
</div>
</div>
		<h1><i class='fa fa-archive'></i> Corte de Caja #<?php echo $_GET["id"]; ?></h1>
		<div class="clearfix"></div>


<?php
$products = SellData::getByBoxId($_GET["id"]);
if(count($products)>0){
$total_total = 0;
	$efectivo = 1;
	$punto = 2;
	$transferencia = 3;
	$zelle = 4;
	$dual = 5;
	$total_efectivo = 0;
	$total_punto = 0;
	$total_transferencia = 0;
	$total_zelle = 0;
	$total_dual = 0;
	
?>
<br>
<div class="box box-primary">
<table class="table table-bordered table-hover	">
	<thead>
		<th></th>
		<th>FACTURA</th>
		<th>METODO DE PAGO</th>
		<th>REFERENCIA</th>
		<th>VENTA</th>
		<th>EFECTIVO</th>
		<th>TRANSFERENCIA</th>
		<th>ZELLE</th>
		<th>TOTAL</th>
		<th>FECHA</th>
	</thead>
	<?php foreach($products as $sell):?>
	
	

	<tr>
		<td style="width:30px;">
<a href="./index.php?view=onesell&id=<?php echo $sell->id; ?>" class="btn btn-default btn-xs"><i class="fa fa-arrow-right"></i></a>			


<?php
$operations = OperationData::getAllProductsBySellId($sell->id);
?>
</td>

		<td style="width:100px;"><?php echo "#".$sell->ref_id; ?></td>
		

<?php
	
	if($sell->f_id == 1){
	$variable = "EFECTIVO";
	$total_efectivo = $total_efectivo + $sell->total-$sell->discount;
	}
elseif($sell->f_id == 2){
	$variable = "PUNTO DE VENTA";
	$total_punto = $total_punto + $sell->total-$sell->discount;
	}
elseif($sell->f_id == 3){
	$variable = "TRANSFERENCIA";
	$total_transferencia = $total_transferencia + $sell->total-$sell->discount;
	}
	elseif($sell->f_id == 4){
	$variable = "ZELLE";
	$total_zelle = $total_zelle + $sell->total-$sell->discount;
	}
	elseif($sell->f_id == 5){
	$variable = "DUAL";
	$total_efectivo = $total_efectivo + $sell->efe;
	$total_punto = $total_punto + $sell->pun;
	$total_transferencia = $total_transferencia + $sell->tra;	
	$total_zelle = $total_punto + $sell->zel;
	}
?>		
		
		<td style="width:150px;"><?php echo strtoupper($variable); ?></td>
		<td style="width:100px;"><?php echo $sell->refe; ?></td>

<td style="width:42%;">		
	<?php
	

	
	
  		foreach($operations as $operation){
    		$product  = $operation->getProduct();
					
  			
			
	?>

  		<?php echo "<b> [ </b>",$operation->q," x ",strtoupper($product->name)," -> $",number_format($operation->price_out,2,".",","),"<b> ] </b>";?>

	<?php
  	}
  	?>
</td>


		
		<td style="width:100px;">

<?php
		
		echo "<b>$ ".number_format($sell->efe,2,".",",")."</b>";

?>			

		</td style="width:180px;">
		
		<td style="width:100px;">

<?php
		
		echo "<b>$ ".number_format($sell->tra,2,".",",")."</b>";

?>			

		</td style="width:180px;">
		
		
		
		<td style="width:100px;">

<?php
		
		echo "<b>$ ".number_format($sell->zel,2,".",",")."</b>";

?>			

		</td style="width:180px;">
		
		
		
		<td style="width:100px;">

<?php
		
		$total_total += $sell->total-$sell->discount;
		echo "<b>$ ".number_format($sell->total-$sell->discount,2,".",",")."</b>";

?>			

		</td style="width:180px;">
		<td><?php echo $sell->created_at; ?></td>
	</tr>

<?php endforeach; ?>

</table>
</div>
<h1>TOTAL: <?php echo "$ ".number_format($total_total,2,".",",")?></h1>
		<h4>EFECTIVO: <?php echo "$ ".number_format($total_efectivo,2,".",",")."&nbsp;&nbsp;&nbsp;&nbsp; PUNTO DE VENTA: $ ".number_format($total_punto,2,".",",")."&nbsp;&nbsp;&nbsp;&nbsp; TOTAL TRANSFERENCIA: $ ".number_format($total_transferencia,2,".",",")."&nbsp;&nbsp;&nbsp;&nbsp; ZELLE: $ ".number_format($total_zelle,2,".",","); ?></h4>

	<?php
}else {

?>
	<div class="jumbotron">
		<h2>No hay ventas</h2>
		<p>No se ha realizado ninguna venta.</p>
	</div>

<?php } ?>
<br><br><br><br><br><br><br><br><br><br>
	</div>
</div>
</section></section>