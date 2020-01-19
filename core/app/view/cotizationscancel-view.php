<section class="content"> 
	<div class="row">
		<div class="col-md-12">
			<?php 
			if(isset($_SESSION["client_id"])):?>
			<h1><i class='glyphicon glyphicon-shopping-cart'></i> Cotizaciones Canceladas</h1>
			<?php 
			else:
			?>
			<!-- <div class="btn-group pull-right">
  				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    				<i class="fa fa-download"></i> descargar <span class="caret"></span>
  				</button>
  				<ul class="dropdown-menu" role="menu">
				    <li><a href="report/sells-word.php">Word 2007 (.docx)</a></li>
				    <li><a href="report/sells-xlsx.php">Excel 2007 (.xlsx)</a></li>
					<li><a onclick="thePDF()" id="makepdf" class="">PDF (.pdf)</a></li>
  				</ul>
			</div> -->
			<h1><i class='glyphicon glyphicon-shopping-cart'></i> Cotizaciones Canceladas</h1>
			<?php
			endif;
			?>
			<div class="clearfix"></div>
			<?php
			$products = null;
			if (isset($_SESSION['is_admin'])) {
				$products = SellData::getCancelsCotizacion();
			} else {
				$products = SellData::getCancelsCotizacionByUser(Core::$user->id);
			}
			if(count($products)>0){
			?>
			<br>
			<div class="box box-primary">
				<div class="box-body">
					<table class="table table-bordered table-hover table-responsive datatable">
						<thead>
							<th></th>
							<th class="hidden-xs" style="text-align: center;">Venta</th>	
							<th style="text-align: center;">
								<span class="hidden-xs">Cantidad</span>
								<span class="visible-xs">C.</span>
							</th>
							<th style="text-align: center;">Total</th>
							<th style="text-align: center;">Vendedor</th>
							<?php if (isset($_SESSION['is_admin'])): ?>
								<th style="text-align: center;">Cliente</th>
								<th style="text-align: center;">Almacen</th>
							<?php endif ?>
							<th class="hidden-xs" style="text-align: center;">Fecha</th>
							<th style="text-align: center;">
								<span class="hidden-xs">Opciones</span>
							</th>
						</thead>
						<?php 
						foreach($products as $sell):
							$operations = OperationData::getAllProductsBySellId($sell->id);
							$totalPrice = 0;
							$quantity = 0;
							$stock_id = 0;
							foreach($operations as $operation){
								$product  = $operation->getProduct();
								$totalPrice+= $operation->q*$product->price_out;
								$quantity += $operation->q;
								$stock_id = $operation->stock_id;
							}
						?>
						<tr>
							<td class="hidden-xs" style="width:30px;">
								<a href="index.php?view=onesell&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-default">
									<i class="glyphicon glyphicon-eye-open"></i>
								</a>
							</td>
							<td style="text-align: center;">
								<a class="visible-xs" href="index.php?view=onesell&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-default">
									#<?php echo $sell->id; ?>
								</a>
								<span class="hidden-xs">#<?php echo $sell->id; ?></span>
							</td>

							<td style="text-align: center;"><?php echo 	$quantity; ?></td>
							<td style="text-align: center;">
								<?php
								echo "<b>$ ".number_format($totalPrice,2,".",",")."</b>";
								?>			
							</td>
							
							<td style="text-align: center;">
								<?php
								if($sell->user_id!=null)
								{
									$c= $sell->getUser();echo $c->name." ".$c->lastname;
								} 
								?>
							</td>
							<?php if (isset($_SESSION['is_admin'])): ?>
								<td style="text-align: center;">
									<?php 
									if($sell->person_id!=null){
										$c= $sell->getPerson();
										echo $c->name." ".$c->lastname;
									} 
									?>
								</td>
								<td style="text-align: center;">
									<?php 
										//Mejorar - Esto debe ser dinámico
										if($stock_id==1){
											echo "Principal"; 
										}
									?>					
								</td>
							<?php endif ?>
							<td class="hidden-xs" style="text-align: center;">
								<?php 
									if(isset($sell->created_at)){
										echo $sell->created_at; 
									}
								?>
							</td>
							<td style="width:130px;text-align: center;">
								
								<?php if(isset($_SESSION["user_id"])):?>
								<a href="index.php?view=delcotization&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-danger" onclick="return confirm('CONFIRMAS QUE QUIERES ELIMINAR ESTA COTIZACION');">
									<i class="fa fa-trash"></i> <span class="hidden-xs">ELIMINAR</span>
								</a>
								<?php endif;?>
							</td>
						</tr>
						<?php
						endforeach;
						?>
					</table>
				</div>
			</div>
			<div class="clearfix"></div>
			<?php
			}
			else
			{
			?>
			<div class="jumbotron">
				<p>No se ha cancelado ninguna cotizacion.</p>
			</div>
			<?php
			}
			?>
			</br></br></br></br></br></br></br></br></br></br>
		</div>
	</div>
</section>


