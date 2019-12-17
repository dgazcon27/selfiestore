<?php
$clients = PersonData::getClients();
$users = UserData::getAll();
$total_efectivo = 0;
$total_transferencia = 0;
$total_zelle = 0;
$total_punto = 0;
$total_dual = 0;
?>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<h1>REPORTE DE VENTAS</h1>
				<form>
					<input type="hidden" name="view" value="sellreports">
					<div class="row">
						<div class="col-md-2">
							<select name="user_id" class="form-control">
								<option value="">-- VENDEDOR --</option>
								<?php foreach($users as $p):?>
								<option value="<?php echo $p->id;?>"><?php echo $p->name;?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="col-md-3">
							<select name="client_id" class="form-control">
								<option value="">-- CLIENTE --</option>
								<?php foreach($clients as $p):?>
								<option value="<?php echo $p->id;?>"><?php echo $p->name;?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="col-md-3">
							<input type="date" name="sd" value="<?php if(isset($_GET["sd"])){ echo $_GET["sd"]; }?>" class="form-control">
						</div>
						<div class="col-md-3">
							<input type="date" name="ed" value="<?php if(isset($_GET["ed"])){ echo $_GET["ed"]; }?>" class="form-control">
						</div>
						<div class="col-md-1">
							<button type="submit" class="btn btn-primary btn-block"><i class="fa fa-file-text"></i></button>
						</div>
					</div>
					<!--
					<br>
					<div class="row">
						<div class="col-md-4">
							<select name="mesero_id" class="form-control">
								<option value="">--  MESEROS --</option>
								<?php foreach($meseros as $p):?>
								<option value="<?php echo $p->id;?>"><?php echo $p->name;?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="col-md-4">
							<select name="operation_type_id" class="form-control">
								<option value="1">VENTA</option>
							</select>
						</div>
					</div>
					-->
				</form>
			</div>
		</div>
		<br><!--- -->
		<div class="row">
			<div class="col-md-12">
				<?php if(isset($_GET["sd"]) && isset($_GET["ed"]) ):?>
					<?php if($_GET["sd"]!=""&&$_GET["ed"]!=""):?>
						<?php 
						$operations = array();

						if($_GET["client_id"]=="" && $_GET["user_id"]==""){
						$operations = SellData::getAllByDateOp($_GET["sd"],$_GET["ed"],2);
						}
						else if($_GET["client_id"]=="" && $_GET["user_id"]!=""){
						$operations = SellData::getAllByDateOpByUserId($_GET["user_id"],$_GET["sd"],$_GET["ed"],2);
						}
						else if($_GET["client_id"]!="" && $_GET["user_id"]==""){
						$operations = SellData::getAllByDateBCOp($_GET["client_id"],$_GET["sd"],$_GET["ed"],2);
						}else{
						$operations = SellData::getAllByDateBCOpByUserId($_GET["user_id"],$_GET["client_id"],$_GET["sd"],$_GET["ed"],2);
						} 
					 	?>
			 			<?php if(count($operations)>0):?>
			 			<?php $supertotal = 0; $supergasto = 0; $superganancia = 0; $superdescuento = 0; $ganancia = 0; ?>
							<a onclick="thePDF()" id="makepdf" class="btn btn-default" class="">PDF (.pdf)</a>
							<a href="./report/sellreports-xlsx.php?client_id=<?php echo $_GET["client_id"]; ?>&sd=<?php echo $_GET["sd"]; ?>&ed=<?php echo $_GET["ed"]; ?>" class="btn btn-default">Excel (.xlsx)</a><br><br>
							<div class="box box-primary">
								<table class="table table-bordered">
									<thead>
										<th>FACTURA</th>
										<th>GASTO</th>
										<th>GANANCIA/PERDIDA</th>
										<th>SUBTOTAL</th>
										<th>DESCUENTO</th>
										<th>TOTAL</th>
										<th>METODO DE PAGO</th>
										<th>CLIENTE</th>
										<th>VENDEDOR</th>
										<th>FECHA</th>
									</thead>
									<?php foreach($operations as $operation):?>
										<tr>
											<td><?php echo "#",$operation->ref_id; ?></td>
											<td>$ <?php echo number_format($operation->invoice_code,2,'.',','); ?></td>
											<td>$ <?php echo number_format($ganancia = $operation->total-$operation->discount-$operation->invoice_code,2,'.',','); ?></td>
											<td>$ <?php echo number_format($operation->total,2,'.',','); ?></td>
											<td>$ <?php echo number_format($operation->discount,2,'.',','); ?></td>
											<td>$ <?php echo number_format($operation->total-$operation->discount,2,'.',','); ?></td>
											<td> 
												<?php 
												if($operation->f_id == 1){
													
												$variable = "EFECTIVO";
												$total_efectivo = $total_efectivo + ($operation->total-$operation->discount);

												}
												elseif($operation->f_id == 2)
												{
													$variable = "TRANFERENCIA";
													$total_transferencia = $total_transferencia + ($operation->total-$operation->discount);
												}
												elseif($operation->f_id == 3)
												{
													$variable = "ZELLE";
													$total_zelle = $total_zelle + ($operation->total-$operation->discount);
												}
												elseif($operation->f_id == 4)
												{
													$variable = "DUAL";
													$total_efectivo = $total_efectivo + $operation->efe;
										        	$total_punto = $total_punto + $operation->pun;
										        	$total_transferencia = $total_transferencia + $operation->tra;	
										        	$total_zelle = $total_zelle + $operation->zel;
												}
												elseif($operation->f_id == 5)
												{
													$variable = "PUNTO DE VENTA";
													$total_punto = $total_punto + ($operation->total-$operation->discount);
												}
												echo $variable; ?>
											</td>
											<td> <?php if($operation->person_id!=null){$c= $operation->getPerson();echo strtoupper($c->name." ".$c->lastname);} ?> </td>
											<td> <?php if($operation->user_id!=null){$c= $operation->getUser();echo strtoupper($c->name." ".$c->lastname);} ?> </td>
											<td><?php echo $operation->created_at; ?></td>
										</tr>
										<?php
										$supertotal+= ($operation->total-$operation->discount);
										$supergasto+= ($operation->invoice_code);
										$superganancia+= ($operation->total-$operation->invoice_code);
										$superdescuento+= ($operation->discount);
									endforeach; 
									if($superganancia-$superdescuento < 0)
									{
										$estado_t = " TOTAL DE PERDIDAS EN VENTAS: $ ";
									}
									else
									{
										$estado_t = " TOTAL DE GANANCIAS EN VENTAS: $ ";
									}
									?>
								</table>
							</div>
							<br><br>
							<h1>TOTAL DE VENTAS: $ <?php echo number_format($supertotal,2,'.',','); ?></h1>
							<h4><?php echo "&nbsp;&nbsp;|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TOTAL DE INVERSION EN VENTAS: $",number_format($supergasto,2,'.',','); ?><?php echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  |  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TOTAL DE DESCUENTO EN VENTAS: $",number_format($superdescuento,2,'.',','); ?><?php echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$estado_t,number_format($superganancia-$superdescuento,2,'.',','),"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  |"; ?> </h4>
								<?php 
								if($superganancia-$superdescuento < 0)
								{
									$com = 0;
									$ceo = 0;
									$ing = 0;	
									$vit = 0;	
									$mar = 0;	
									$car = 0;	
									$egl = 0;							
									$adm = 0;							
									$ofi = 0;							
									$ven = 0;							
								}
								else
								{
									$com = $superganancia-$superdescuento;
									$ceo = ($superganancia-$superdescuento)*0.47;
									$car = ($superganancia-$superdescuento)*0.16;	
									$egl = ($superganancia-$superdescuento)*0.16;	
									$adm = ($superganancia-$superdescuento)*0.10;		
									$mar = ($superganancia-$superdescuento)*0.10;		
									$ven = ($superganancia-$superdescuento)*0.06;		
									$ofi = ($superganancia-$superdescuento)*0.05;		
								}
								?>
<h4>&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TOTAL EFECTIVO: <?php echo "$ ".number_format($total_efectivo,2,".",",")."&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp; TOTAL PUNTO DE VENTA: $ ".number_format($total_punto,2,".",",")."&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp; TOTAL TRANSFERENCIA: $ ".number_format($total_transferencia,2,".",",")."&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp; TOTAL ZELLE: $ ".number_format($total_zelle,2,".",",")."&nbsp;&nbsp;&nbsp;&nbsp;|"; ?></h4>		
<br><br>
<h1>COMISIONES: $ <?php echo number_format($com,2,'.',','); ?></h1>
<h4>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp; CEO [47%] : $ <?php echo number_format($ceo,2,'.',','); ?> &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp; GERENTE [16%] : $ <?php echo number_format($car,2,'.',','); ?> &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp; EGLE [16%] : $ <?php echo number_format($egl,2,'.',','); ?> &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp; VENDEDORA [6%] : $ <?php echo number_format($ven,2,'.',','); ?>&nbsp;&nbsp;&nbsp;&nbsp;</br></br>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;OFFICE BOY [5%] : $ <?php echo number_format($ofi,2,'.',','); ?>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;ADMINISTRATIVO [10%] : $ <?php echo number_format($adm,2,'.',','); ?>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;</h4>

<script type="text/javascript">
        function thePDF() {
var doc = new jsPDF('p', 'pt');
        doc.setFontSize(26);
        doc.text("<?php echo ConfigurationData::getByPreffix("company_name")->val;?>", 230, 65);
        doc.setFontSize(18);
        doc.text("REPORTE DE VENTAS", 200, 80);
        doc.setFontSize(12);
        doc.text("Usuario: <?php echo Core::$user->name." ".Core::$user->lastname; ?>  -  Fecha: <?php echo date("d-m-Y h:i:s");?> ", 130, 90);
var columns = [
    {title: "Id", dataKey: "id"},
	{title: "gasto", dataKey: "gasto"}, 
	{title: "ganacia", dataKey: "ganacia"}, 
    {title: "Subtotal", dataKey: "subtotal"}, 
    {title: "Descuento", dataKey: "discount"}, 
    {title: "Total", dataKey: "total"}, 
    {title: "Cliente", dataKey: "client"}, 
    {title: "Vendedor", dataKey: "vendor"}, 
    {title: "Fecha", dataKey: "created_at"}, 
];
var rows = [
  <?php foreach($operations as $operation):
  ?>
    {
      "id": "<?php echo $operation->ref_id; ?>",
	  "gasto": "<?php echo $operation->invoice_code; ?>",
	  "ganacia": "<?php echo $ganancia = $operation->total-$operation->discount-$operation->invoice_code; ?>",
      "subtotal": "$ <?php echo number_format($operation->total,2,'.',','); ?>",
      "discount": "$ <?php echo number_format($operation->discount,2,'.',','); ?>",
      "total": "$ <?php echo number_format($operation->total-$operation->discount,2,'.',','); ?>",
      "client": "<?php if($operation->person_id!=null){$c= $operation->getPerson();echo $c->name." ".$c->lastname;} ?>",
      "vendor": "<?php if($operation->user_id!=null){$c= $operation->getUser();echo $c->name." ".$c->lastname;} ?>",
      "created_at": "<?php echo $operation->created_at; ?>",
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
doc.text("Total de ventas: $ <?php echo number_format($supertotal,2,'.',','); ?> | Total de inversión: $ <?php echo number_format($supergasto,2,'.',','); ?> | Total de ganancias: $ <?php echo number_format($superganancia,2,'.',','); ?>", 40, doc.autoTableEndPosY()+25);
		
doc.setFontSize(10);
doc.text("Venta en efectivo: $ <?php echo number_format($supertotal,2,'.',','); ?> | venta en trasferencia: $ <?php echo number_format($supergasto,2,'.',','); ?> | venta en punto de venta: $ <?php echo number_format($superganancia,2,'.',','); ?>", 40, doc.autoTableEndPosY()+65);
		
		
doc.setFontSize(12);
doc.text("<?php echo Core::$pdf_footer;?>", 40, doc.autoTableEndPosY()+145);
<?php 
$con = ConfigurationData::getByPreffix("report_image");
if($con!=null && $con->val!=""):
?>
var img = new Image();
img.src= "storage/configuration/<?php echo $con->val;?>";
img.onload = function(){
doc.addImage(img, 'PNG', 495, 20, 60, 60,'mon');	
doc.save('sellreports-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
}
<?php else:?>
doc.save('sellreports-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
<?php endif; ?>
}
</script>



<?php else:
// si no hay operaciones
?>
<script>
	$("#wellcome").hide();
</script>
<div class="jumbotron">
	<h2>No hay operaciones</h2>
	<p>El rango de fechas seleccionado no proporciono ningun resultado de operaciones.</p>
</div>

			 <?php endif; ?>
<?php else:?>
<script>
	$("#wellcome").hide();
</script>
<div class="jumbotron">
	<h2>Fecha Incorrectas</h2>
	<p>Puede ser que no selecciono un rango de fechas, o el rango seleccionado es incorrecto.</p>
</div>
<?php endif;?>

		<?php endif; ?>
	</div>
</div>

<br><br><br><br>
</section>