<?php
$clients = PersonData::getClients();
$users = UserData::getSellers();
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
					 	<?php 
								$client = $_GET['client_id'] ? $_GET['client_id'] : "";
								$user = $_GET['user_id'] ? $_GET['user_id'] : "";
								$start = $_GET['sd'] ? $_GET['sd'] : "";
								$end = $_GET['ed'] ? $_GET['ed'] : "";
							?>
			 			<?php if(count($operations)>0):?>
			 			<?php $supertotal = 0; $supergasto = 0; $superganancia = 0; $superdescuento = 0; $ganancia = 0; ?>
							<a onclick="thePDF()" id="makepdf" class="btn btn-default" class="">PDF (.pdf)</a>
							<a href="./report/sellreports-xlsx.php?client_id=<?php echo $_GET["client_id"]; ?>&sd=<?php echo $_GET["sd"]; ?>&ed=<?php echo $_GET["ed"]; ?>" class="btn btn-default">Excel (.xlsx)</a>
							<a onclick="report('<?php echo $user;?>','<?php echo $client;?>','<?php echo $start;?>','<?php echo $end;?>')" id="makepdf" class="btn btn-primary" class="" style="float:right;">Exportar reporte global</a>
							<br><br>
							<div class="panel panel-default">
    							<div class="panel-heading">
								<h3>OPERACIONES DE CONTADO <a id="selled" class="arrow-close" data-status="true" data-toggle="collapse" data-target="#demo">
									<i style="float: right;" class="fa fa-chevron-down"></i>
								</a></h3>
									<div class="box box-primary collapse in" id="demo">
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
													<?php if ($operation->p_id == 1): ?>
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
													<td> <?php if($operation->receive_by!=null){$c= SellData::getSellUser($operation->receive_by);echo strtoupper($c->name." ".$c->lastname);} ?> </td>
													<td><?php echo $operation->created_at; ?></td>
													<?php 
														$supertotal+= ($operation->total-$operation->discount);
														$supergasto+= ($operation->invoice_code);
														$superganancia+= ($operation->total-$operation->invoice_code);
														$superdescuento+= ($operation->discount);
													?>
													<?php endif ?>
												</tr>
												<?php
											endforeach; 
											if($superganancia-$superdescuento < 0)
											{
												$estado_t = " TOTAL DE PERDIDAS EN CONTADO: $ ";
											}
											else
											{
												$estado_t = " TOTAL DE GANANCIAS EN CONTADO: $ ";
											}
											?>
										</table>
										<h1>TOTAL DE OPERACIONES DE CONTADO: $ <?php echo number_format($supertotal,2,'.',','); ?></h1>
										<h4><?php echo "&nbsp;&nbsp;|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TOTAL DE INVERSION EN CONTADO: $",number_format($supergasto,2,'.',','); ?><?php echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  |  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TOTAL DE DESCUENTO EN CONTADO: $",number_format($superdescuento,2,'.',','); ?><?php echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$estado_t,number_format($superganancia-$superdescuento,2,'.',','),"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  |"; ?> </h4>
											
										<h4>&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TOTAL EFECTIVO: <?php echo "$ ".number_format($total_efectivo,2,".",",")."&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp; TOTAL PUNTO DE CONTADO: $ ".number_format($total_punto,2,".",",")."&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp; TOTAL TRANSFERENCIA: $ ".number_format($total_transferencia,2,".",",")."&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp; TOTAL ZELLE: $ ".number_format($total_zelle,2,".",",")."&nbsp;&nbsp;&nbsp;&nbsp;|"; ?></h4>	
										<br><br><br>	
									</div>

    							</div>
    						</div>
							<br>

							<?php 
								$supertotal_credito = 0;
								$supergasto_credito = 0;
								$superganancia_credito = 0;
								$superdescuento_credito = 0;

								$total_efectivo_contado = $total_efectivo;
					        	$total_punto_contado = $total_punto;
					        	$total_transferencia_contado = $total_transferencia;
					        	$total_zelle_contado = $total_zelle;

								$total_efectivo = 0;
					        	$total_punto = 0;
					        	$total_transferencia = 0;
					        	$total_zelle = 0;
							?>

							<div class="panel panel-default">
    							<div class="panel-heading">
									<h3>
										OPERACIONES DE CREDITO
										<a class="arrow-close" data-status="true" data-toggle="collapse" data-target="#demo2">
											<i style="float: right;" class="fa fa-chevron-down"></i>
										</a>
									</h3>
									<div class="box box-primary collapse in" id="demo2">
										<table class="table table-bordered">
											<thead>
												<th>FACTURA</th>
												<th>GASTO</th>
												<th>GANANCIA/PERDIDA</th>
												<th>SUBTOTAL</th>
												<th>DESCUENTO</th>
												<th>TOTAL ABONADO</th>
												<th>ESTATUS</th>
												<th>CLIENTE</th>
												<th>VENDEDOR</th>
												<th>FECHA</th>
											</thead>
											<?php foreach($operations as $operation):?>
												<tr>	
													<?php if ($operation->p_id == 4 && $operation->payments > 0 ): ?>
														<td><?php echo "#",$operation->ref_id; ?></td>
														<td>$ <?php echo number_format($operation->invoice_code,2,'.',','); ?></td>
														<td>$ <?php echo number_format($ganancia = $operation->total-$operation->discount-$operation->invoice_code,2,'.',','); ?></td>
														<td>$ <?php echo number_format($operation->total,2,'.',','); ?></td>
														<td>$ <?php echo number_format($operation->discount,2,'.',','); ?></td>
														<td>
															<?php if ($operation->total != $operation->payments): ?>
															$ <?php echo number_format($operation->payments-$operation->discount,2,'.',','); ?>
																
															<?php else: ?>
															$ <?php echo number_format($operation->total-$operation->discount,2,'.',','); ?>
																
															<?php endif ?>
																
														</td>
														<td> 
															<?php
																echo "EFECTIVO"; 
															?>
														</td>
														<td> <?php if($operation->person_id!=null){$c= $operation->getPerson();echo strtoupper($c->name." ".$c->lastname);} ?> </td>
														<td> <?php if($operation->receive_by!=null){$c= SellData::getSellUser($operation->receive_by);echo strtoupper($c->name." ".$c->lastname);} ?> </td>
														<td><?php echo $operation->created_at; ?></td>
														<?php
															if ($operation->total-$operation->payments == 0) {
																$supertotal_credito += ($operation->total-$operation->discount);
																$supergasto_credito += ($operation->invoice_code);
																$superganancia_credito += ($operation->total-$operation->invoice_code);
																$superdescuento_credito += ($operation->discount);
															} else {
																$supertotal_credito += $operation->payments;
															}
														?>
													<?php endif ?>
												</tr>
												<?php
											endforeach; 
											if($superganancia_credito-$superdescuento_credito < 0)
											{
												$estado_t = " TOTAL DE PERDIDAS EN CREDITO: $ ";
											}
											else
											{
												$estado_t = " TOTAL DE GANANCIAS EN CREDITO: $ ";
											}
											?>
										</table>
										<h1>TOTAL DE OPERACIONES DE CREDITO: $ <?php echo number_format($supertotal_credito,2,'.',','); ?></h1>
										<hr style="border: solid 1px #3c8dbc;">
										<h4><?php echo "&nbsp;&nbsp;|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TOTAL DE INVERSION EN CREDITO: $",number_format($supergasto_credito,2,'.',','); ?><?php echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  |  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TOTAL DE DESCUENTO EN CREDITO: $",number_format($superdescuento_credito,2,'.',','); ?><?php echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$estado_t,number_format($superganancia_credito-$superdescuento_credito,2,'.',','),"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  |"; ?> </h4>
											
										<h4>&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TOTAL EFECTIVO: <?php echo "$ ".number_format($total_efectivo,2,".",",")."&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp; TOTAL PUNTO DE CREDITO: $ ".number_format($total_punto,2,".",",")."&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp; TOTAL TRANSFERENCIA: $ ".number_format($total_transferencia,2,".",",")."&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp; TOTAL ZELLE: $ ".number_format($total_zelle,2,".",",")."&nbsp;&nbsp;&nbsp;&nbsp;|"; ?></h4>
										<br><br><br>	
									</div>

    							</div>
    						</div>

							<br>
							<div class="panel panel-default">
    							<div class="panel-heading">
									<h3>CORRELATIVO GASTOS DEL MES<a class="arrow-close" data-status="true" data-toggle="collapse" data-target="#demo3">
											<i style="float: right;" class="fa fa-chevron-down"></i>
										</a></h3>
									<div class="box box-primary collapse in" id="demo3" >
										<table class="table table-bordered">
											<thead>
												<th>ID</th>
												<th>MONTO</th>
												<th>DESCRIPCION</th>
												<th>FECHA</th>
											</thead>
												<?php 
													$spend_total = 0;
													if ($_GET['sd'] != "" && $_GET['ed'] != "") {
														$spend = SpendData::getGroupByDateOpReport($_GET['sd'], $_GET['ed']);
													} else {
														$start = date("d-m-Y h:i:s",time());
														$end = date("d-m-Y",strtotime($start."+ 7 days")); 
														$spend = SpendData::getGroupByDateOpReport($start, $end);
													}
													if (count($spend) >0) {
														foreach ($spend as $value) {
															?>
															<tr>
																<td><?php echo $value->id ?></td>
																<td><?php echo $value->price ?></td>
																<td><?php echo $value->name ?></td>
																<td><?php echo date("d-m-Y",strtotime($value->created_at)) ?></td>
															</tr>
															<?php
															$spend_total += $value->price;
														}
													}
												?>
										</table>
										<h1>TOTAL DE GASTOS DEL MES: $ <?php echo number_format($spend_total,2,'.',','); ?></h1>
									</div>

    							</div>
    						</div>
							<hr style="border: solid 1px #3c8dbc;">
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
								$com = ($superganancia+$superganancia_credito)-$superdescuento-$superdescuento_credito-$spend_total;
								$ceo = ($com)*0.75;
								$car = ($com)*0.05;	
								$cob = ($com)*0.25;		
							}
							?>
							<h1>COMISIONES: $ <?php echo number_format($com,2,'.',','); ?></h1>
							<h4>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp; CEO [70%] : $ <?php echo number_format($ceo,2,'.',','); ?> &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp; MARKETING [5%] : $ <?php echo number_format($car,2,'.',','); ?> &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp; UTILIDAD DE DIRECTIVA [25%] $ <?php echo number_format($cob,2,'.',','); ?></h4>
							

							

<script type="text/javascript">
	$(".arrow-close").click(function (a) {
		if (a.currentTarget.dataset.status == 'true') {
			console.log('close')
			a.target.className = "fa fa-chevron-left";
			a.currentTarget.dataset.status = 'false';
		} else {
			a.target.className = "fa fa-chevron-down";
			a.currentTarget.dataset.status = 'true';
		}
	});

    function thePDF() {
		var doc = new jsPDF('p', 'pt');
        doc.setFontSize(26);
        doc.text("<?php echo ConfigurationData::getByPreffix("company_name")->val;?>", 170, 65);
        doc.setFontSize(18);
        doc.text("REPORTE DE VENTAS", 200, 80);
        doc.setFontSize(12);
        doc.text("Usuario: <?php echo Core::$user->name." ".Core::$user->lastname; ?>  -  Fecha: <?php echo date("d-m-Y h:i:s");?> ", 130, 90);
		doc.setFontSize(18);
		doc.text("OPERACIONES DE CONTADO", 40, 130);

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

		var columns2 = [
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
		  	if ($operation->p_id == 1) {

		  ?>
		    {
		      "id": "<?php echo $operation->ref_id; ?>",
			  "gasto": "<?php echo $operation->invoice_code; ?>",
			  "ganacia": "<?php echo $ganancia = $operation->total-$operation->discount-$operation->invoice_code; ?>",
		      "subtotal": "$ <?php echo number_format($operation->total,2,'.',','); ?>",
		      "discount": "$ <?php echo number_format($operation->discount,2,'.',','); ?>",
		      "total": "$ <?php echo number_format($operation->total-$operation->discount,2,'.',','); ?>",
		      "client": "<?php if($operation->person_id!=null){$c= $operation->getPerson();echo $c->name." ".$c->lastname;} ?>",
		      "vendor": "<?php if($operation->receive_by!=null){$c= SellData::getSellUser($operation->receive_by);echo $c->name." ".$c->lastname;} ?>",
		      "created_at": "<?php echo $operation->created_at; ?>",
		      },
		 <?php
		  	}

			endforeach; 
		  ?>
		];

		var rows2 = [
		  <?php foreach($operations as $operation):
		  	if ($operation->p_id == 4 && $operation->payments > 0) {

		  ?>
		    {
		      "id": "<?php echo $operation->ref_id; ?>",
			  "gasto": "<?php echo $operation->invoice_code; ?>",
			  "ganacia": "<?php echo $ganancia = $operation->total-$operation->discount-$operation->invoice_code; ?>",
		      "subtotal": "$ <?php echo number_format($operation->total,2,'.',','); ?>",
		      "discount": "$ <?php echo number_format($operation->discount,2,'.',','); ?>",
		      "total": "$ <?php echo number_format($operation->total-$operation->discount,2,'.',','); ?>",
		      "client": "<?php if($operation->person_id!=null){$c= $operation->getPerson();echo $c->name." ".$c->lastname;} ?>",
		      "vendor": "<?php if($operation->receive_by!=null){$c= SellData::getSellUser($operation->receive_by);echo $c->name." ".$c->lastname;} ?>",
		      "created_at": "<?php echo $operation->created_at; ?>",
		      },
		 <?php
		  	}
		 	
			endforeach; 
		  ?>
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
		    margin: {top: 140},
		    afterPageContent: function(data) {
		    }
		});
		doc.setFontSize(12);
		doc.text("Total de ventas: $ <?php echo number_format($supertotal,2,'.',','); ?> | Total de inversión: $ <?php echo number_format($supergasto,2,'.',','); ?> | Total de ganancias: $ <?php echo number_format($superganancia,2,'.',','); ?>", 40, doc.autoTableEndPosY()+25);
		doc.text("Total de descuento: $ <?php echo number_format($superdescuento,2,'.',','); ?>", 40, doc.autoTableEndPosY()+45);
				
		doc.setFontSize(10);
		doc.text("Venta en efectivo: $ <?php echo number_format($total_efectivo_contado,2,'.',','); ?> | venta en trasferencia: $ <?php echo number_format($total_transferencia_contado,2,'.',','); ?> | venta en punto de venta: $ <?php echo number_format($total_punto_contado,2,'.',','); ?>", 40, doc.autoTableEndPosY()+65);
		doc.text("Venta en Zelle: $ <?php echo number_format($total_zelle_contado,2,'.',','); ?>", 40, doc.autoTableEndPosY()+85);


		doc.addPage();
				
		doc.autoTable(columns2, rows2, {
		    theme: 'grid',
		    overflow:'linebreak',
		    styles: { 
		        fillColor: <?php echo Core::$pdf_table_fillcolor;?>
		    },
		    columnStyles: {
		        id: {fillColor: <?php echo Core::$pdf_table_column_fillcolor;?>}
		    },
		    margin: {top: 40},
		    afterPageContent: function(data) {
		    }
		});
		// doc.setFontSize(12);
		// doc.text("Total de ventas: $ <?php echo number_format($supertotal_credito,2,'.',','); ?> | Total de inversión: $ <?php echo number_format($supergasto_credito,2,'.',','); ?> | Total de ganancias: $ <?php echo number_format($superganancia_credito,2,'.',','); ?>", 40, doc.autoTableEndPosY()+120);
				
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

<script type="text/javascript">
    function report(user, client, start, end) {

    	newdate = new Date(start);
    	newdate2 = new Date(end);

    	month = parseInt(newdate.getMonth())+1;
    	month_ = month < 10 ? "0"+month : month;
    	month2 = parseInt(newdate2.getMonth())+1;
    	month_2 = month < 10 ? "0"+month : month;

    	printdate = newdate.getDate()+"/"+month_+"/"+newdate.getFullYear();
    	printdate2 = newdate2.getDate()+"/"+month_2+"/"+newdate2.getFullYear();
		var doc = new jsPDF('p', 'pt');
        doc.setFontSize(16);
        doc.text("SELFIE", 280, 30);
        doc.text("REPORTE DE VENTAS", 225, 50)
        doc.setFontSize(12);
        doc.text(printdate+" AL "+printdate, 240, 70);
		var columns = [
	        {title: "", dataKey: "name"}, 
		    {title: "", dataKey: "amount"}, 
		];

		var rows = [];
		$.get(`./?action=reportglobal&client=${client}&user=${user}&start=${start}&end=${end}`,function(data2){
			let response = JSON.parse(data2);
			rows = [
				{"name": "TOTAL INGRESOS DE CONTADO","amount":response.selled},
				{"name": "TOTAL ABONOS DE CRÉDITO","amount":response.payments},
				{"name": "TOTAL INGRESOS DE CRÉDITO CERRADOS","amount":response.closed_credit},
				{"name": "TOTAL INGRESOS GLOBALES","amount":response.global_total},
				{"name": "TOTAL INVERSIÓN","amount":response.invested},
				{"name": "TOTAL GASTOS","amount":response.spend},
				{"name": "TOTAL DE GANANCIAS","amount":response.gain},
				{"name": "UTILIDAD CEO 70%","amount":response.ceo},
				{"name": "PRESUPUESTO PARA MARKETING 5%","amount":response.markenting},
				{"name": "UTILIDAD PARA DIRECTIVA 25%","amount":response.manager},
				{"name": "TOTAL CUENTAS POR COBRAR","amount":response.to_get},
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
			doc.save('reporte_de_ventas-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
		});

		
	}
</script>