<section class="content">
<div class="row">
	<div class="col-md-12">
<?php
$products = SellData::getByBoxId($_GET["id"]);
$box_day = "";
if (count($products) > 0) {
	$box_day = $products[0]->created_at;
} else {
	$spend = SpendData::getByBoxId($_GET["id"]);
	if (count($spend) > 0) {
		$box_day = $spend[0]->created_at;
	} else {
		$payt = PaymentData::getByBoxId($_GET["id"]);
		$box_day = $payt[0]->created_at;
	}
}
?>

<!-- Single button -->
<div class="btn-group pull-right">
<a href="./index.php?view=boxhistory" class="btn btn-default"><i class="fa fa-clock-o"></i> Historial</a>
<div class="btn-group">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-download"></i> Descargar <span class="caret"></span>
  </button>
  <ul class="dropdown-menu pull-right" role="menu">
    <li><a href="report/box-word.php?id=<?php echo $_GET["id"];?>">Word 2007 (.docx)</a></li>
    <li><a href="index.php?action=boxReport&id=<?php echo $_GET["id"];?>" target="_blank" id="makepdf" class="btn btn-default" class="">PDF (.pdf)</a></li>
  </ul>
</div>
</div>

		<h1><i class='fa fa-archive'></i> Corte de Caja #<?php echo $_GET["id"]." - Día: ".date("d/m/Y", strtotime($box_day)); ?></h1>
		<div class="clearfix"></div>


<?php
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
if(count($products)>0){
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
			<td style="text-align: center;"><?php echo $spend->name ?></td>
			<td style="text-align: center;"><?php echo $spend->price ?></td>
			<td style="text-align: center;"><?php echo date("d-m-Y",strtotime($spend->created_at)) ?></td>
		</tr>
		<?php
			$spend_total += $spend->price;
		?>
		<?php endforeach ?>
	</table>
</div>
<h2>TOTAL DE GASTOS: <?php echo "$ ".number_format($spend_total,2,".",","); ?></h2>
<br>
<br>
<?php 
	$payments = PaymentData::getBoxedPayments($_GET["id"]);
	$payments_total = 0;
?>
<?php if ($payments): ?>
	<h2>ABONOS DEL DIA</h2>
	<div class="box box-primary">
		<table class="table table-bordered table-hover	">
			<thead>
				<th style="text-align: center;">FACTURA</th>
				<th style="text-align: center;">CLIENTE</th>
				<th style="text-align: center;">MONTO</th>
				<th style="text-align: center;">FECHA</th>
			</thead>
			<?php foreach ($payments as $pay): 
				$sell = SellData::getById($pay->sell_id);
				$person = PersonData::getById($pay->person_id);
				$paym = $pay->val;
				if ($paym < 0) {
					$paym = $paym*-1;
				}
			?>
			<tr>
				<td style="text-align: center;"><?php echo "#".$sell->ref_id;?></td>
				<td style="text-align: center;"><?php echo $person->name." ".$person->lastname;?></td>
				<td style="text-align: center;"><?php echo $paym;?></td>
				<td style="text-align: center;"><?php echo date("d-m-Y",strtotime($pay->created_at));?></td>
			</tr>
			<?php 
				$payments_total += $paym;
			?>
			<?php endforeach ?>
		</table>
	</div>
	<h2>TOTAL DE ABONOS: <?php echo "$ ".number_format($payments_total,2,".",","); ?></h2>
<?php else: ?>
	<div class="jumbotron">
		<h2>No hay abonos</h2>
		<p>No se ha realizado ningun abono.</p>
	</div>
<?php endif ?>



<h1 style="float:right;">TOTAL: <?php echo "$ ".number_format($total_total+$payments_total-$spend_total,2,".",","); ?></h1>

<br><br><br><br><br><br><br><br><br><br>

</section></section>
<script type="text/javascript">
	function thePDF(start) {
		var doc = new jsPDF('p', 'pt');
    	<?php $lines = 0;?>
        doc.setFontSize(26);
        doc.text("<?php echo ConfigurationData::getByPreffix("company_name")->val;?>", 180, 65);
    	<?php $lines += 1;?>

    	newdate = new Date(start);
		console.log(newdate);

    	month = parseInt(newdate.getMonth())+1;
    	month_ = month < 10 ? "0"+month : month;

    	printdate2 = newdate.getDate()+"/"+month_+"/"+newdate.getFullYear();
		doc.setFontSize(18);
        doc.text("REPORTE DE CAJA "+printdate2, 150, 90);
		var columns = [
		    {title: "Factura", dataKey: "id"},
			{title: "Metodo de pago", dataKey: "gasto"}, 
			{title: "Referencia", dataKey: "ganacia"}, 
		    {title: "Total", dataKey: "subtotal"}, 
		];
		;
		var rows = [
		  <?php 
		  	foreach($products as $operation):
		  		$lines +=1;
			  	$variable = "";
			  	if($operation->f_id == 1){
	        		$variable = "EFECTIVO";
		        }elseif($operation->f_id == 2){
					$variable = "TRANSFERENCIA";
				}
		        elseif($operation->f_id == 3){
					$variable = "ZELLE";
				}
		    	elseif($operation->f_id == 4){
		        	$variable = "DUAL";
				}
		    	elseif($operation->f_id == 5){
		        	$variable = "PUNTO DE VENTA";
				} else {
					$variable = "N/A";
				}
		  ?>
		    {
		      "id": "#<?php echo $operation->ref_id; ?>",
			  "gasto": "<?php echo $variable; ?>",
			  "ganacia": "<?php echo isset($operation->refe)? $operation->refe: 'N/A'; ?>",
		      "subtotal": "$ <?php echo number_format($operation->total,2,'.',','); ?>",
		      },
		 <?php

			endforeach; 
		  ?>
		];
		doc.setFontSize(14);
		doc.text("VENTAS DEL DIA ", 40, 140);
		<?php $lines +=1; ?>
		doc.text("_______________", 40, 145);
		<?php $lines +=1; ?>
		var resumen = [
		    {title: "", dataKey: "id"},
			{title: "", dataKey: "gasto"}, 
		];


		var resumenCaja = [
			{"id":"TOTAL EN EFECTIVO","gasto":"TOTAL EN TRANSFERENCIA"},
			{"id":"$ <?php echo number_format($total_efectivo,2,".",",");?>","gasto":"$ <?php echo number_format($total_transferencia,2,".",",");?>"},
			{"id":"TOTAL EN ZELLE", "gasto":"TOTAL EN PUNTO DE VENTA"},
			{"id":"$ <?php echo number_format($total_zelle,2,".",",");?>","gasto":"$ <?php echo number_format($total_punto,2,".",",");?>"},
		];
		<?php $lines +=4; ?>


		doc.autoTable(columns, rows, {
		    theme: 'grid',
		    overflow:'linebreak',
		    styles: { 
		        fillColor: <?php echo Core::$pdf_table_fillcolor;?>,
		        overflow: 'ellipsize'
		    },
		    columnStyles: {
		        id: {fillColor: <?php echo Core::$pdf_table_column_fillcolor;?>}
		    },
		    startY: 170,
		});
		doc.addPage();

		


		doc.autoTable(resumen, resumenCaja, {
		    theme: 'grid',
		    overflow:'linebreak',
		    styles: { 
		        fillColor: <?php echo Core::$pdf_table_fillcolor;?>
		    },
		    columnStyles: {
		        id: {fillColor: <?php echo Core::$pdf_table_column_fillcolor;?>}
		    },
		    startY: 20,
		    afterPageContent: function(data) {
		    }
		});

		doc.text('TOTAL DE VENTAS: <?php echo "$ ".number_format($total_total,2,".",","); ?>', 350, doc.autoTableEndPosY()+20);
		doc.addPage();

    	doc.text("GASTOS ", 40, 70);
    	doc.text("________", 40, 75);
		<?php $lines = 2; ?>
    	var columns2 = [
		    {title: "Factura", dataKey: "id"},
			{title: "Concepto", dataKey: "gasto"}, 
			{title: "Monto", dataKey: "ganacia"}, 
		    {title: "Fecha", dataKey: "subtotal"}, 
		];


		var rows2 = [
	 		<?php foreach($spends as $spend):?>
				<?php $lines += 1; ?>
  		 	{
				"id": "#<?php echo $spend->id ?>",
				"gasto": "<?php echo $spend->name ?>",
				"ganacia": "$ <?php echo number_format($spend->price,2,".",",") ?>",
				"subtotal": "$ <?php echo date("d-m-Y",strtotime($spend->created_at)) ?>",
	      	},
			<?php
				endforeach; 
			?>
		];

		doc.autoTable(columns2, rows2, {
		    theme: 'grid',
		    overflow:'linebreak',
		    styles: { 
		        fillColor: <?php echo Core::$pdf_table_fillcolor;?>
		    },
		    columnStyles: {
		        id: {fillColor: <?php echo Core::$pdf_table_column_fillcolor;?>}
		    },
		    margin: {top: 100},
		    afterPageContent: function(data) {
		    }
		});

		doc.text('TOTAL DE GASTOS: <?php echo "$ ".number_format($spend_total,2,".",","); ?>',365, doc.autoTableEndPosY()+30);
		var abonos = [
		    {title: "Factura", dataKey: "id"},
			{title: "Cliente", dataKey: "client"}, 
			{title: "Monto", dataKey: "ganacia"}, 
		    {title: "Fecha", dataKey: "subtotal"}, 
		];

		var rowsAbonos = [
			<?php foreach ($payments as $pay):
				$sell = SellData::getById($pay->sell_id);
				$person = PersonData::getById($pay->person_id);
				$paym = $pay->val; 
				if ($paym < 0) {
					$paym = $paym*-1;
				}
			?>
				{
					"id":"#<?php echo $sell->ref_id;?>",
					"client": "<?php echo $person->name." ".$person->lastname;?>",
					"ganacia": "$ <?php echo number_format($paym,2,".",",");?>",
					"subtotal": "<?php echo date("d-m-Y",strtotime($pay->created_at));?>"
				},
			<?php endforeach ?>

		];
    	doc.text("ABONOS ", 40, doc.autoTableEndPosY()+60);
    	doc.text("________ ", 40, doc.autoTableEndPosY()+65);

    	doc.autoTable(abonos, rowsAbonos, {
		    theme: 'grid',
		    styles: { 
		        fillColor: <?php echo Core::$pdf_table_fillcolor;?>,
		    	overflow:'linebreak',
		    },
		    columnStyles: {
		        id: {fillColor: <?php echo Core::$pdf_table_column_fillcolor;?>}
		    },
		    margin: {top: doc.autoTableEndPosY()+90},
		    afterPageContent: function(data) {
		    }
		});

    	doc.text('ABONOS DEL DIA: <?php echo "$ ".number_format($payments_total,2,".",","); ?>', 380, doc.autoTableEndPosY()+30);

    	doc.setFontSize(16);
		doc.text('TOTAL: <?php echo "$ ".number_format($total_total+$payments_total-$spend_total,2,".",","); ?>',415, doc.autoTableEndPosY()+80);

		doc.text('______________           ______________           ______________',60, doc.autoTableEndPosY()+200);
    	doc.setFontSize(12);
		doc.text('ENTREGA                                   AUDITA                                      RECIBE',100, doc.autoTableEndPosY()+230);
		doc.save('boxreport-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
	}
</script>


</script>