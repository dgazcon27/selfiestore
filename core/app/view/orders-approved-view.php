<style type="text/css">
	.orange-style {
		background-color: #ff8100;
	}
</style>
<section class="content"> 
	<div class="row">
		<div class="col-md-12"> 
			<h1><i class='glyphicon glyphicon-shopping-cart'></i> PEDIDOS APROBADOS</h1>
			<div class="clearfix"></div>

			<?php
			$products=null;
			if (isset($_SESSION['is_admin']) || Core::$user->kind == 2) {
				$products = SellData::getOrdersApproved();
			} elseif(Core::$user->kind == 5){
				$products = SellData::getOrdersApprovedForManager();
			} else {
				$products = SellData::getOrdersApprovedByUser($_SESSION['user_id']);
			}			
			if(count($products)>0){
			?>
				<br>
				<div class="box box-primary padding-table">
					<table class="table table-bordered table-hover table-responsive datatable">
						<thead>
							<th style="text-align: center;"></th>
							<th style="text-align: center;"><span class="hidden-xs hidden-sm">N° PEDIDO</span> Nº</th>
							<?php if (isset($_SESSION['is_admin']) || Core::$user->kind == 5 || Core::$user->kind == 2): ?>
							<th style="text-align: center;">CLIENTE</th>
							<th style="text-align: center;">TELEFONO</th>
							<?php endif ?>
							<th style="text-align: center;">ESTADO</th>
							<th style="text-align: center;">
								<span class="hidden-xs">TOTAL DE PRODUCTOS</span>
								<span class="visible-xs">N° PRODUCTOS</span>
							</th>
							<th style="text-align: center;">TOTAL</th>
							<th class="hidden-xs"  style="text-align: center;">FECHA</th>
							<?php if (isset($_SESSION['is_admin']) || Core::$user->kind == 5 || Core::$user->kind == 2): ?>
							<th style="width:100px; text-align: center;"><span class="hidden-xs">OPCIONES</span></th>
							<?php endif ?>
						</thead>
						
						<?php 
						$cotizationsCouner = count($products);
						$totalTotal = 0;
						foreach($products as $sell):?>
						
						<tr>
							<td style="width:30px; text-align: center;">
								<a href="index.php?view=onecotization&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-default">
									<i class="glyphicon glyphicon-eye-open"></i>
								</a>
							</td>
							
							<td style="text-align: center;">#<?php echo $sell->ref_id; ?></td>
							<?php if (isset($_SESSION['is_admin']) || Core::$user->kind == 5 || Core::$user->kind == 2): ?>
							<td style="text-align: center;">
								<?php
									$client = PersonData::getById($sell->person_id);
									echo $client->name." ".$client->lastname;
								?>
							</td>
							<td style="text-align: center;">
								<?php
									$client = PersonData::getById($sell->person_id);
									echo $client->phone1;
								?>
							</td>
							<?php endif ?>
							<td style="text-align: center;">

							<?php
								$operations = OperationData::getAllProductsBySellId($sell->id);
								$ccs_class = "";
								switch ($sell->d_id) {
									case 9:
										$ccs_class = "label label-warning";
									break;

									case 10:
										$ccs_class = "label orange-style";
									break;
									case 11:
										$ccs_class = "label label-primary";
									break;
									
									default:
										$ccs_class = "label label-default";
									break;
								}
								echo "<span class='".$ccs_class."'>".$sell->getD()->name."</span>";
							?>
							</td>
							<?php
								$operations = OperationData::getAllProductsBySellId($sell->id);
								$totalProducts = 0;
								$total=0;
								foreach($operations as $operation){
									$product  = $operation->getProduct();
									$total += $operation->q*$product->price_out;
									$totalProducts += $operation->q;
								}
							?>
							<td style="text-align: center;">
							<?php
								echo $totalProducts;
							?>
							</td>
							<td style="text-align: center;">
							<?php			
									echo "<b>$ ".number_format($total,2,".",",")."</b>";

							?>			

							</td>
							<td class="hidden-xs"  style="text-align: center;"><?php echo $sell->created_at; ?>
								
							</td>
							<?php if (isset($_SESSION['is_admin']) || Core::$user->kind == 5 || Core::$user->kind == 2): ?>
								<td style="width:200px;text-align: center;">
									<?php if ($sell->d_id >= 5 && (isset($_SESSION['is_admin']) || Core::$user->kind == 5) && $sell->is_official == 1): ?>
										<a href="index.php?view=processsell&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-primary">
											<i class="fa fa-send"></i><span class="hidden-xs hidden-sm"> CONVERTIR EN VENTA</span>
										</a>
									<?php endif ?>
									

									<?php if (Core::$user->kind == 2 && $sell->d_id == 9): ?>
										<a href="index.php?action=setorder&status=10&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-primary" onclick="return confirm('CONFIRMAS QUE QUIERES ENVIAR ESTE PEDIDO');">
											<span> ENVIAR PEDIDO</span>
										</a>
									<?php endif ?>
									<?php if (Core::$user->kind == 5 && $sell->d_id == 10): ?>
										<a href="index.php?action=setorder&status=11&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-primary" onclick="return confirm('¿CORFIRMAR QUE EL PEDIDO ESTA DISPONIBLE?');">
											<span> DISPONIBLE PARA RETIRO</span>
										</a>
									<?php endif ?>
									<?php 
										$products_sell = OperationData::getAllProductsBySellId($sell->id);
										$is_imeis = false;
										if (count($products_sell) > 0) {
											$i = 0;
											while (!$is_imeis && $i < count($products_sell)) {
												$p = ProductData::getById($products_sell[$i]->product_id);
												if ($p->category_id == 1) {
													$is_imeis = true;
												}
												$i = $i+1;
											}
										}
									?>
									<?php if (Core::$user->kind == 2 && $sell->d_id == 5): ?>
										<!-- comprobar si tiene producto tecnologico -->
										<?php
											if ($is_imeis && $sell->comment != "") {
											?>
												<a href="index.php?action=setorder&status=9&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-primary" onclick="return confirm('CONFIRMAS QUE QUIERES ARMAR ESTE PEDIDO');">
													<span> ARMAR PEDIDO</span>
												</a>
												
											<?php
											} elseif(!$is_imeis) {
											?>
												<a href="index.php?action=setorder&status=9&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-primary" onclick="return confirm('CONFIRMAS QUE QUIERES ARMAR ESTE PEDIDO');">
													<span> ARMAR PEDIDO</span>
												</a>
											<?php
											}

											  
										?>
											
									<?php endif ?>

									<?php 
										if(Core::$user->kind == 2 && $is_imeis && $sell->d_id != 1) {
										?>
											<a href="index.php?view=updateimeis&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-default">
												<span> AGREGAR IMEIS</span>
											</a>
										<?php
										} 
									?>
									<?php if (isset($_SESSION['is_admin']) || Core::$user->kind == 5): ?>
										<?php if ($sell->d_id != 7): ?>
											<a href="index.php?action=cancelcotization&from=orders&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-danger" onclick="return confirm('CONFIRMAS QUE QUIERES CANCELAR ESTA COTIZACION');">
												<i class="fa fa-ban"></i><span class="hidden-xs hidden-sm"> CANCELAR</span>
											</a>
										<?php endif ?>
										<a href="index.php?view=delcotization&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-danger" onclick="return confirm('CONFIRMAS QUE QUIERES ELIMINAR ESTA COTIZACION');">
											<i class="fa fa-trash"></i>
										</a>
									<?php endif ?>
									<?php if ((isset($_SESSION['is_admin']) || Core::$user->kind == 2)): ?>
										<a onclick="thePDF(<?php echo $sell->id;?>,<?php echo $sell->ref_id;?> ,'<?php echo $sell->created_at; ?>')" class="btn btn-xs btn-default">
											<i class="fa fa-file"></i>
										</a>
									<?php endif ?>
								</td>
							<?php endif ?>
						</tr>

					<?php 
						$cotizationsCouner = $cotizationsCouner -1;
						endforeach;
					?>

					</table>
				</div>
				<div class="clearfix"></div>

				<?php
			}else{
				?>
				<div class="jumbotron">
					<h2>NO HAY PEDIDOS</h2>
					<p>NO SE HA REALIZADO NINGUN PEDIDO.</p>
				</div>
				<?php
			}
			?>
			</br></br></br></br></br></br></br></br></br></br>
		</div>
	</div>
</section>


<script type="text/javascript">
    function thePDF(id, order, date) {
    	newdate = new Date(date);
    	month = parseInt(newdate.getMonth())+1;
    	month_ = month < 10 ? "0"+month : month;
    	printdate = newdate.getDate()+"/"+month_+"/"+newdate.getFullYear();
		var doc = new jsPDF('p', 'pt');
        doc.setFontSize(26);
        doc.text("PEDIDO #"+order+" - FECHA "+printdate+" ", 100, 65);
		var columns = [
	        {title: "CODIGOS DE BARRAS", dataKey: "id"}, 
		    {title: "PRODUCTO", dataKey: "product"}, 
		    {title: "CANTIDAD", dataKey: "q"}, 
		];
		var rows = [];
		$.get("./?action=movetransferdocument&id="+id+" ",function(data2){
			let response = JSON.parse(data2);
			let sell = response.sell;
			let person = response.person;
			let products = response.products;
			console.log(products);
			doc.setFontSize(14);
    		doc.text("EMPRESA: "+person.company+" ", 40, 105);
    		doc.text("__________________________________________________________________", 40, 108);

			doc.setFontSize(14);
			for (var i = 0; i < products.length; i++) {
				data = {
					"id" : products[i].barcode,
					"product": products[i].name,
					"q": products[i].q
				}
				rows.push(data)
			}
			doc.autoTable(columns, rows, {
			    theme: 'grid',
			    overflow:'linebreak',
			    styles: { 
			        fillColor: <?php echo Core::$pdf_table_fillcolor;?>
			    },
			    columnStyles: {
			        id: {fillColor: <?php echo Core::$pdf_table_column_fillcolor;?>}
			    },
			    margin: {top: 130},
			    afterPageContent: function(data) {
			    }
			});
			doc.setFontSize(12);
			doc.text("                       _____________________                     _____________________",40, doc.autoTableEndPosY()+200);
			doc.text("                          AlMACEN DE ORIGEN                           ALMACEN DESTINO",40, doc.autoTableEndPosY()+215);
			doc.text("<?php echo Core::$pdf_footer;?>", 40, doc.autoTableEndPosY()+600);
			doc.save('movimiento-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
		});

		
	}
</script>