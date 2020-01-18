<style type="text/css">
	@media (max-width: 528px) {
		.box {
			font-size: 13px;
		}
	}
</style>
<section class="content">
<div class="btn-group pull-right">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-download"></i> Descargar <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu">
    <li><a href="report/cotization-word.php?id=<?php echo $_GET["id"];?>">Word 2007 (.docx)</a></li>
  </ul>
</div>
<h1>Cotizacion</h1>
<?php if(isset($_GET["id"]) && $_GET["id"]!=""):?>
<?php
$sell = SellData::getById($_GET["id"]);
$operations = OperationData::getAllProductsBySellId($_GET["id"]);
$total = 0;
?>
<?php
/*
if(isset($_COOKIE["selled"])){
	foreach ($operations as $operation) {
//		print_r($operation);
		$qx = OperationData::getQYesF($operation->product_id);
		// print "qx=$qx";
			$p = $operation->getProduct();
		if($qx==0){
			echo "<p class='alert alert-danger'>El producto <b style='text-transform:uppercase;'> $p->name</b> no tiene existencias en inventario.</p>";			
		}else if($qx<=$p->inventary_min/2){
			echo "<p class='alert alert-danger'>El producto <b style='text-transform:uppercase;'> $p->name</b> tiene muy pocas existencias en inventario.</p>";
		}else if($qx<=$p->inventary_min){
			echo "<p class='alert alert-warning'>El producto <b style='text-transform:uppercase;'> $p->name</b> tiene pocas existencias en inventario.</p>";
		}
	}
	setcookie("selled","",time()-18600);
}
*/
?>
<div class="box box-primary">
<table class="table table-bordered">
<?php if($sell->person_id!=""):
$client = $sell->getPerson();
?>
<tr>
	<td style="width:150px;">Proveedor</td>
	<td><?php echo $client->name." ".$client->lastname;?></td>
</tr>

<?php endif; ?>


<?php if($sell->user_id!=""):
$user = $sell->getUser();
?>
<tr>
	<?php if (isset($sell->receive_by)): ?>
		<?php  
			$receive_by = $sell->getSellUser($sell->receive_by);
		?>
		<td>Atendido por</td>
		<td><?php echo $receive_by->name." ".$receive_by->lastname;?></td>
	<?php endif ?>
</tr>
<?php endif; ?>
</table>
</div>

<div class="box box-primary">
<br><table class="table table-bordered table-hover">
	<thead>
		<th class="hidden-xs">Codigo</th>
		<th class="visible-xs">#</th>
		<th class="hidden-xs">Cantidad</th>
		<th class="visible-xs">N°</th>
		<th><span class="hidden-xs">Nombre del</span> Producto</th>
		<?php if (isset($_SESSION['is_admin'])): ?>
		<th>Precio</th>
		<?php endif ?>
		<th><span class="hidden-xs">Precio </span>Unitario</th>
		<th>Total</th>

	</thead>
<?php
	foreach($operations as $operation){
		$product  = $operation->getProduct();
?>
<tr>
	<td><?php echo $product->id ;?></td>
	<td><?php echo $operation->q ;?></td>
	<td><?php echo $product->name ;?></td>
	<?php if (isset($_SESSION['is_admin'])): ?>
	<td>$ <?php echo number_format($product->price_in,2,".",",") ;?></td>
	<?php endif ?>
	<td>$ <?php echo number_format($product->price_out,2,".",",") ;?></td>
	<td><b>$ <?php echo number_format($operation->q*$product->price_out,2,".",",");$total+=$operation->q*$product->price_out;?></b></td>
</tr>
<?php
	}
	?>
</table>
</div>
<br><br><h1>Total: $ <?php echo number_format($total,2,'.',','); ?></h1>
	<?php

?>	
<?php else:?>
	501 Internal Error
<?php endif; ?>
</section>