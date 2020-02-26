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
<?php
$products = SellData::getByBoxId($_GET["id"]);
?>
		<h1><i class='fa fa-archive'></i> Corte de Caja #<?php echo $_GET["id"]." - Día: ".date("d/m/Y", strtotime($products[0]->created_at)); ?></h1>
		<div class="clearfix"></div>


<?php
if(count($products)>0){
    $total_total = 0;
	$efectivo = 1;
	$transferencia = 2;
	$zelle = 3;
	$dual = 4;
	$punto = 5;
	$total_efectivo = 0;
	$total_transferencia = 0;
	$total_zelle = 0;
	$total_punto = 0;
	$total_dual = 0;
	$total_descuento = 0;	
?>
<br>
<div class="box box-primary">
<table class="table table-bordered table-hover	">
	<thead>
		<th></th>
		<th width="10%" >FACTURA</th>
		<th width="10%" style="text-align: center;">METODO DE PAGO</th>
		<th width="10%" style="text-align: center;">REFERENCIA</th>
		<!--<th width="35%" style="text-align: center;">VENTA</th>-->
		<th width="45%" style="width:500px; text-align:center;">TOTAL</th>
		<!--<th width="10%" style="width:310px; text-align:center;">FECHA</th>-->
	</thead>
	<?php foreach($products as $sell):?>
	
	

	<tr>
		<td style="width:30px;">
            <a href="./index.php?view=onesell&id=<?php echo $sell->id; ?>" class="btn btn-default btn-xs"><i class="fa fa-arrow-right"></i></a>
        </td>
		<?php
        $operations = OperationData::getAllProductsBySellId($sell->id);
        ?>

		<td><?php echo "#".$sell->ref_id; ?></td>
		<?php
    	if($sell->f_id == 1){
        	$variable = "EFECTIVO";
        	$total_efectivo = $total_efectivo + $sell->total-$sell->discount;
    	}
        elseif($sell->f_id == 2){
        	$variable = "TRANSFERENCIA";
        	$total_transferencia = $total_transferencia + $sell->total-$sell->discount;
    	}
        elseif($sell->f_id == 3){
        	$variable = "ZELLE";
        	$total_zelle = $total_zelle + $sell->total-$sell->discount;
    	}
    	elseif($sell->f_id == 4){
        	$variable = "DUAL";
        	//$total_zelle = $total_zelle + $sell->total-$sell->discount;
			
			$total_efectivo = $total_efectivo + $sell->efe;
        	$total_punto = $total_punto + $sell->pun;
        	$total_transferencia = $total_transferencia + $sell->tra;	
        	$total_zelle = $total_zelle + $sell->zel;
    	}
    	elseif($sell->f_id == 5){
        	$variable = "PUNTO DE VENTA";
        	$total_punto = $total_punto + $sell->total-$sell->discount;
    	}
        ?>
	<td style="text-align:center;"><?php echo strtoupper($variable); ?></td>
	<td style="text-align: center;">
	<?php
	    if($sell->refe==0 || $sell->refe==""){
            $sell->refe="N/A";
        }
	    echo $sell->refe; 
	?>
	</td>
    <!--<td style="width:42%;">		
	<?php
	//foreach($operations as $operation){
	//	$product  = $operation->getProduct();
    //    echo "<b> [ </b>",$operation->q," x ",strtoupper($product->name)," -> $",number_format($operation->price_out,2,".",","),"<b> ] </b>";
  	//}
  	?>
    </td-->
		
	<td style="text-align:center;">
        <?php
		$total_total += $sell->total-$sell->discount;
		if($sell->f_id == 4){
		?>
		<table style="text-align: center;width: 100%;">
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
				<td style="width: 300px;">
					<?php
						echo "<b>$ ".number_format($sell->efe,2,".",",")."</b>";
					?>
				</td>
                <?php
				}
				if($sell->tra>0 && $sell->tra!=""){
				?>
				<td style="width: 200px;">
					<?php
						echo "<b>$ ".number_format($sell->tra,2,".",",")."</b>";
					?>
				</td>
                <?php
				}
				if($sell->zel>0 && $sell->zel!=""){
				?>
				<td style="width: 200px;">
					<?php
						echo "<b>$ ".number_format($sell->zel,2,".",",")."</b>";
					?>
				</td>
                <?php
				}
				if($sell->pun>0 && $sell->pun!=""){
				?>
				<td style="width: 200px;">
					<?php
						echo "<b>$ ".number_format($sell->pun,2,".",",")."</b>";
					?>
				</td>
                <?php
				}
				?>
		 	</tr>
         </table>
		<?php
        }
        else
        {
            echo "<b>$ ".number_format($sell->total-$sell->discount,2,".",",")."</b>";
        }
        ?>
		</td>
		<!--<td style="text-align:center;"><?php //echo $sell->created_at; ?></td> -->
</tr>

<?php 
	$total_descuento = $total_descuento + $sell->discount;
	endforeach; 
?>

</table>
</div>
<h1>TOTAL: <?php echo "$ ".number_format($total_total,2,".",",")?></h1>
		<h4>EFECTIVO: <?php echo "$ ".number_format($total_efectivo,2,".",",")."&nbsp;&nbsp;|&nbsp;&nbsp; PUNTO DE VENTA: $ ".number_format($total_punto,2,".",",")."&nbsp;&nbsp;|&nbsp;&nbsp; TOTAL TRANSFERENCIA: $ ".number_format($total_transferencia,2,".",",")."&nbsp;&nbsp;|&nbsp;&nbsp; ZELLE: $ ".number_format($total_zelle,2,".",",")."&nbsp;&nbsp;|&nbsp;&nbsp; TOTAL DESCUENTO: $ ".number_format($total_descuento,2,".",","); ?></h4>

	<?php
}else {

?>
	<div class="jumbotron">
		<h2>No hay ventas</h2>
		<p>No se ha realizado ninguna venta.</p>
	</div>

<?php } ?>
	</div>
</div>
<br>
<?php 
	$spends = SpendData::getBoxedSpend($_GET["id"]);
	$spend_total = 0;
?>
<h2>GASTOS</h2>
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
<h1 style="float:right;">TOTAL: <?php echo "$ ".number_format($total_total-$spend_total,2,".",","); ?></h1>
<br><br><br><br><br><br><br><br><br><br>

</section></section>