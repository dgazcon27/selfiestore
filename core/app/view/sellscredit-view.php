<section class="content"> 
	<div class="row">
		<div class="col-md-12">
			<?php if(isset($_SESSION["client_id"])):?>
				<h1><i class='glyphicon glyphicon-shopping-cart'></i> Mis Compras a credito</h1>
			<?php else:?>
				<div class="btn-group pull-right">
			 		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
			    		<i class="fa fa-download"></i> Descargar <span class="caret"></span>
			  		</button>
			  		<ul class="dropdown-menu" role="menu">
					    <li><a href="report/sells-word.php">Word 2007 (.docx)</a></li>
					    <li><a href="report/sells-xlsx.php">Excel 2007 (.xlsx)</a></li>
						<li><a onclick="thePDF()" id="makepdf" class="">PDF (.pdf)</a></li>
				  	</ul>
				</div>
				<h1><i class='glyphicon glyphicon-shopping-cart'></i> VENTAS A CREDITO</h1>
			<?php endif;?>
			<div class="clearfix"></div>
			<?php
			$products=null;
			$total_total = 0;
			$total_payments = 0;
			if(Core::$user->kind==3){
			$products = SellData::getCreditsByUserId(Core::$user->id);
			}
			else if(Core::$user->kind==2){
			$products = SellData::getCreditsByStockId(Core::$user->stock_id);
			}
			else{
			$products = SellData::getCredits();

			}
			if(count($products)>0){
			?>
				<br>
				<div class="box box-primary">
					<div class="box-header">
						<h3 class="box-title">VENTAS</h3>
					</div>
					<table class="table table-bordered datatable table-hover	">
						<thead>
							<th></th>
							<th>FACTURA</th>
							<th>CLIENTE</th>
							<th>PAGO</th>
							<th>ENTREGA</th>
							<th>TOTAL</th>
							<th>FECHA</th>
							<th></th>
						</thead>
						<?php foreach($products as $sell):?>
						<tr>
							<td style="width:30px;">
								<a href="index.php?view=onesell&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-default"><i class="glyphicon glyphicon-eye-open"></i></a>
							</td>
							<td>#<?php echo $sell->ref_id; ?></td>
							<td>
								<?php
								$c= $sell->getPerson();
								echo strtoupper($c->name." ".$c->lastname);
								?>
							</td>
							<td><?php echo strtoupper($sell->getP()->name); ?></td>
							<td><?php echo strtoupper($sell->getD()->name); ?></td>
							<td>
								<?php
								$total= $sell->total-$sell->discount;
								$total_payments = $total_payments + $sell->payments;
								$total_total = $total_total + ($total-$sell->payments);
								/*foreach($operations as $operation){
									$product  = $operation->getProduct();
									$total += $operation->q*$product->price_out;
								}*/
								echo "<b>$ ".number_format($total,2,".",",")."</b>";
								?>			
							</td>
							<td><?php echo $sell->created_at; ?></td>
							<td style="width:30px;">
								<?php if(isset($_SESSION["user_id"])):?>
										<a href="./?action=pay3&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-primary" onclick="return confirm('CONFIRMAS QUE QUIERES COBRAR ESTA VENTA');">Cobrar</a>
										<!--a href="index.php?view=delsell&id=<?php //echo $sell->id; ?>" class="btn btn-xs btn-danger" onclick="return confirm('CONFIRMAS QUE QUIERES ELIMINAR ESTA VENTA');"><i class="fa fa-trash"></i></a-->
								<?php endif;?>
							</td>
						</tr>
						<?php endforeach; ?>
					</table>
				</div>
				<div class="clearfix"></div>
				<?php
			}else{
			?>
			<div class="jumbotron">
				<h2>No hay ventas</h2>
				<p>No se ha realizado ninguna venta.</p>
			</div>
			<?php
			}
			?>
			<h1>
				PENDIENTE POR COBRAR EN FACTURAS A CRÉDITO: <?php echo "$ ".number_format($total_total,2,".",",")?>
			</h1>
			<h2>
				ABONADO EN FACTURAS INCOMPLETAS: <?php echo "$ ".number_format($total_payments,2,".",",")?>
			</h1>
			<br><br><br><br><br><br><br><br><br><br>
		</div>
	</div>
</section>
<script type="text/javascript">
        function thePDF() {
var doc = new jsPDF('p', 'pt');
        doc.setFontSize(26);
        doc.text("<?php echo ConfigurationData::getByPreffix("company_name")->val;?>", 240, 65);
        doc.setFontSize(18);
        doc.text("VENTAS A CREDITO", 220, 80);
        doc.setFontSize(12);
        doc.text("Usuario: <?php echo Core::$user->name." ".Core::$user->lastname; ?>  -  Fecha: <?php echo date("d-m-Y h:i:s");?> ", 130, 90);
var columns = [
    {title: "Id", dataKey: "id"}, 
    {title: "Cliente", dataKey: "client"}, 
    {title: "Total", dataKey: "total"}, 
    {title: "Estado de pago", dataKey: "p"}, 
    {title: "Estado de entrega", dataKey: "d"}, 
    {title: "Almacen", dataKey: "stock"}, 
    {title: "Fecha", dataKey: "created_at"}, 
];
var rows = [
  <?php foreach($products as $sell):
  ?>
    {
      "id": "<?php echo $sell->id; ?>",
      "client": "<?php if($sell->person_id!=null){$c= $sell->getPerson();echo $c->name." ".$c->lastname;} ?>",
      "total": "<?php
$total= $sell->total-$sell->discount;
		echo "$ ".number_format($total,2,".",",");
?>	",
      "p": "<?php echo $sell->getP()->name; ?>",
      "d": "<?php echo $sell->getD()->name; ?>",
      "stock": "<?php echo $sell->getStockTo()->name; ?>",
      "created_at": "<?php echo $sell->created_at; ?>",
      },
 <?php endforeach; ?>
];
doc.autoTable(columns, rows, {
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
doc.setFontSize(12);
doc.text("<?php echo Core::$pdf_footer;?>", 40, doc.autoTableEndPosY()+25);
<?php 
$con = ConfigurationData::getByPreffix("report_image");
if($con!=null && $con->val!=""):
?>
var img = new Image();
img.src= "storage/configuration/<?php echo $con->val;?>";
img.onload = function(){
doc.addImage(img, 'PNG', 495, 20, 60, 60,'mon');	
doc.save('sellscredit-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
}
<?php else:?>
doc.save('sellscredit-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
<?php endif; ?>
}
</script>