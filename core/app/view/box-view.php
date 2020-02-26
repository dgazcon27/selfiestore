<section class="content">
<div class="row">
	<div class="col-md-12">
<div class="btn-group pull-right">
<a href="./index.php?view=boxhistory" class="btn btn-primary "><i class="fa fa-clock-o"></i> HISTORIAL</a>
<a href="./index.php?view=processbox" class="btn btn-primary ">REALIZAR CIERRE DE CAJA <i class="fa fa-arrow-right"></i></a>
</div>
<?php
$products = SellData::getSellsUnBoxed();
$currentDay = "";
if(isset($products[0]->created_at) && ($products[0]->created_at != ""))
{
	$currentDay =  " - Día: ".date("d/m/Y", strtotime($products[0]->created_at));
}
?>
<h1><i class='fa fa-archive'></i> Caja<?php echo $currentDay; ?></h1>
<p>AL REALIZAR EL CIERRE DE CAJA SE GENERARA UN CORTE DE CAJA PARA TODAS LAS VENTAS DEL ALMACEN: <b><?php echo strtoupper(StockData::getPrincipal()->name);?></b></p>
<div class="clearfix"></div>


<?php
	$total_total = 0;
if(count($products)>0){
	$efectivo = 1;
	$transferencia = 2;
	$zelle = 3;
	$dual = 4;
	$punto = 5;
	$total_efectivo = 0;
	$total_transferencia = 0;
	$total_zelle = 0;
	$total_dual = 0;
	$total_punto = 0;
	$total_descuento = 0;
	$total_vuelto = 0;
	$total_vuelto_efectivo = 0;
	$total_vuelto_trans = 0;
	$total_vuelto_zelle = 0;
	$total_vuelto_dual = 0;
	$total_vuelto_punto = 0;
	?>
	<br>
	<div class="box box-primary">
	<table class="table table-bordered table-hover	">
		<thead>
			<th></th>
			<th style="text-align: center;">FACTURA</th>
			<th style="text-align: center;">PRODUCTOS</th>
			<th style="text-align: center;">METODO DE PAGO</th>
			<th style="text-align: center;">REFERENCIA</th>
			<th style="text-align: center;">TOTAL</th>
			<th style="text-align: center;">ALMACEN</th>
		</thead>
		<?php foreach($products as $sell):?>

			<tr>
				<td style="width:30px;">
					<a href="./index.php?view=onesell&id=<?php echo $sell->id; ?>" class="btn btn-default btn-xs"><i class="fa fa-arrow-right"></i></a>
				</td>
				<td style="text-align: center;"><?php echo "#".$sell->ref_id; ?></td>
				<td style="text-align: center;">
					<?php
					$operations = OperationData::getAllProductsBySellId($sell->id);
					echo count($operations);
					?>
				</td>
				<?php		
			if($sell->f_id == 1)
			{
				$variable = "EFECTIVO";
				$total_efectivo = $total_efectivo + $sell->total-$sell->discount;
			}
			elseif($sell->f_id == 2)
			{
				$variable = "TRANSFERENCIA";
				$total_transferencia = $total_transferencia + $sell->total-$sell->discount;
			}
			elseif($sell->f_id == 3)
			{
				$variable = "ZELLE";
				$total_zelle = $total_zelle + $sell->total-$sell->discount;
			}
			elseif($sell->f_id == 4)
			{
				$variable = "PAGO DUAL";
				$total_efectivo = $total_efectivo + $sell->efe;
				$total_transferencia = $total_transferencia + $sell->tra;	
				$total_zelle = $total_zelle + $sell->zel;
				$total_punto = $total_punto + $sell->pun;
			}
			elseif($sell->f_id == 5)
			{
				$variable = "PUNTO DE VENTA";	
				$total_punto = $total_punto + $sell->total-$sell->discount;
			}
			$total_total += $sell->total-$sell->discount;
			?>	

			<?php		
			if($sell->refe == 0 )
				$variablerefe = "N/A";
			else
				$variablerefe = $sell->refe;

			if ($sell->type_change == 1) {
				$total_vuelto += $sell->change_sell;
				$total_vuelto_efectivo += $sell->change_sell;
			}
			if ($sell->type_change == 2) {
				$total_vuelto += $sell->change_sell;
				$total_vuelto_trans += $sell->change_sell;		
			}
			if ($sell->type_change == 3) {
				$total_vuelto += $sell->change_sell;
				$total_vuelto_zelle += $sell->change_sell;
			}
			if ($sell->type_change == 5) {
				$total_vuelto += $sell->change_sell;
				$total_vuelto_punto += $sell->change_sell;
			}
			if ($sell->type_change == 4) {
				$total_vuelto_efectivo += $sell->change_sell;
				$total_vuelto_trans += $sell->change_sell;
				$total_vuelto_zelle += $sell->change_sell;
				$total_vuelto_punto += $sell->change_sell;
			}
			?>
			<td style="text-align: center;"><?php echo strtoupper($variable); ?></td>
			<td style="text-align: center;"><?php echo $variablerefe; ?></td>
			<td style="text-align: center;">
				<?php
				if($sell->f_id == 4)
				{
				?>
				<table width="100%">
					<tbody>
						<tr>
						  	<?php
							if($sell->efe>0 && $sell->efe!=""){
							?>
							<td style="width: 300px;">
								EFECTIVO
							</td>
							<?php
							}
							if($sell->tra>0 && $sell->tra!=""){
							?>
							<td style="width: 200px;">
								TRANSFERENCIA
							</td>
							<?php
							}
							if($sell->zel>0 && $sell->zel!=""){
							?>
							<td style="width: 200px;">
								ZELLE
							</td>
							<?php
							}
							if($sell->pun>0 && $sell->pun!=""){
							?>
							<td style="width: 200px;">
								PUNTO
							</td>
							<?php
							}
							?>
						</tr>
						<tr>
							<?php
							if($sell->efe>0 && $sell->efe!=""){
							?>
							<td>
								<?php
									echo "<b>$ ".number_format($sell->efe,2,".",",")."</b>";
								?>
							</td>
							<?php
							}
							if($sell->tra>0 && $sell->tra!=""){
							?>
							<td>
								<?php
									echo "<b>$ ".number_format($sell->tra,2,".",",")."</b>";
								?>
							</td>
							<?php
							}
							if($sell->zel>0 && $sell->zel!=""){
							?>
							<td>
								<?php
									echo "<b>$ ".number_format($sell->zel,2,".",",")."</b>";
								?>
							</td>
							<?php
							}
							if($sell->pun>0 && $sell->pun!=""){
							?>
							<td>
								<?php
									echo "<b>$ ".number_format($sell->pun,2,".",",")."</b>";
								?>
							</td>
							<?php
							}
							?>
						</tr>
					</tbody>
				</table>
			
			<?php
				}
				else
				{
					echo "<b>$ ".number_format($sell->total-$sell->discount,2,".",",")."</b>";
				}
			?>
			</td>
			
			<td style="text-align: center;"><?php echo $sell->getStockTo()->name; ?></td>
		</tr>
		<?php 
			$total_descuento = $total_descuento + $sell->discount;
		endforeach; 
		?>
	</table>
</div>


<h2>TOTAL DE VENTAS: <?php echo "$ ".number_format($total_total,2,".",","); ?></h2>

<h4>TOTAL EFECTIVO: <?php echo "$ ".number_format($total_efectivo,2,".",",")."&nbsp;&nbsp;|&nbsp;&nbsp; TOTAL PUNTO DE VENTA: $ ".number_format($total_punto,2,".",",")."&nbsp;&nbsp;|&nbsp;&nbsp; TOTAL TRANSFERENCIA: $ ".number_format($total_transferencia,2,".",",")."&nbsp;&nbsp;|&nbsp;&nbsp; TOTAL ZELLE: $ ".number_format($total_zelle,2,".",",")."&nbsp;&nbsp;|&nbsp;&nbsp; TOTAL DESCUENTO: $ ".number_format($total_descuento,2,".",","); ?></h4>
<h2>TOTAL VUELTO: <?php echo "$ ".number_format($total_vuelto,2,".",","); ?></h2>
<h4>TOTAL EFECTIVO: <?php echo "$ ".number_format($total_vuelto_efectivo,2,".",",")."&nbsp;&nbsp;|&nbsp;&nbsp;TOTAL PUNTO DE VENTA: $ ".number_format($total_vuelto_punto,2,".",",")."&nbsp;&nbsp;|&nbsp;&nbsp;TOTAL TRANSFERENCIA: $ ".number_format($total_vuelto_trans,2,".",",")."&nbsp;&nbsp;|&nbsp;&nbspTOTAL ZELLE: $ ".number_format($total_vuelto_zelle,2,".",",")."&nbsp;&nbsp;";
?></h4>
	<?php
}else {

?>
	<div class="jumbotron">
		<h2>No hay ventas</h2>
		<p>No se ha realizado ninguna venta.</p>
	</div>

<?php } ?>
<br>
<?php 
	$spends = SpendData::getAllUnBoxed();
	$spend_total = 0;
?>
<?php if (count($spends) > 0): ?>
	<div class="box box-primary">
		<table class="table table-bordered table-hover	">
			<thead>
				<th style="text-align: center;">FACTURA</th>
				<th style="text-align: center;">CONCEPTO</th>
				<th style="text-align: center;">MONTO</th>
				<th style="text-align: center;">FECHA</th>
			</thead>
			<?php foreach ($spends as $spend): ?>
			<tr>
				<td style="text-align: center;"><?php echo $spend->id ?></td>
				<td style="text-align: center;"><?php echo $spend->price ?></td>
				<td style="text-align: center;"><?php echo $spend->name ?></td>
				<td style="text-align: center;"><?php echo date("d-m-Y",strtotime($spend->created_at)) ?></td>
			</tr>
			<?php
				$spend_total += $spend->price;
			?>
			<?php endforeach ?>
		</table>
	</div>
	<h2>TOTAL DE GASTOS: <?php echo "$ ".number_format($spend_total,2,".",","); ?></h2>

<?php else: ?>
	<div class="jumbotron">
		<h2>No hay gastos</h2>
		<p>No se ha realizado ningun gastos.</p>
	</div>
<?php endif ?>
<hr style="border: solid 1px #3c8dbc">

<h1 style="float:right;">TOTAL: <?php echo "$ ".number_format($total_total-$spend_total,2,".",","); ?></h1>
<br><br><br><br><br><br><br><br><br><br>
	</div>
</div>
</section>
