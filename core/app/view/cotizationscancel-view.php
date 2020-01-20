<section class="content"> 
	<div class="row">
		<div class="col-md-12">
			<?php 
			if(isset($_SESSION["client_id"])):?>
				<?php if (isset($_SESSION['is_admin'])): ?>
					<h1><i class='glyphicon glyphicon-shopping-cart'></i> Cotizaciones Canceladas</h1>
				<?php else: ?>
					<h1><i class='glyphicon glyphicon-shopping-cart'></i> Cotizaciones y Pedidos Cancelados</h1>
				<?php endif ?>
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
							<th class="hidden-xs" style="text-align: center;">N° COTIZACION</th>
							<?php if (isset($_SESSION['is_admin'])): ?>
								<th style="text-align: center;">CLIENTE</th>
								<th class="hidden-xs" style="text-align: center;">TELEFONO</th>
							<?php endif ?>
							<th style="text-align: center;width: 130px;">TOTAL</th>
							<th style="text-align: center;width: 100px !important;">ESTADO</th>
							<th style="text-align: center;width: 130px;">FECHA</th>
							<th></th>
						</thead>
						<?php
						$ii = 1;
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
							<td style="width:30px;">
								<a href="index.php?view=onesell&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-default">
									<i class="glyphicon glyphicon-eye-open"></i>
								</a>
							</td>
							<td style="text-align: center;">
								<?php echo $ii; ?>
							</td>
							<?php if (isset($_SESSION['is_admin'])): ?>
								<td style="text-align: center;">
									<?php
									if($sell->user_id!=null)
									{
										$c= $sell->getUser();echo $c->name." ".$c->lastname;
									} 
									?>
								</td>
								<td style="text-align: center;">
									<?php
									if($sell->user_id != null ){
										$c = PersonData::getByUserId($sell->user_id);
										if (isset($c->phone1)) {
											echo $c->phone1;
										} else {
											echo "";
										}
									} 
									?>
								</td>
							<?php endif ?>
							<td style="text-align: center;">
								<?php
								echo "<b>$ ".number_format($totalPrice,2,".",",")."</b>";
								?>			
							</td>
							<td style="text-align: center;">
								<?php
								$operations = OperationData::getAllProductsBySellId($sell->id);
								echo $sell->getD()->name;
								?>
							</td>
							<td class="hidden-xs" style="text-align: center;">
								<?php 
									if(isset($sell->created_at)){
										echo $sell->created_at; 
									}
								?>
							</td>
							<td style="width:130px;text-align: center;">
								
								<?php if(isset($_SESSION["user_id"]) && $sell->d_id == 3):?>
								<a href="index.php?view=delcotization&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-danger" onclick="return confirm('CONFIRMAS QUE QUIERES ELIMINAR ESTA COTIZACION');">
									<i class="fa fa-trash"></i> <span class="hidden-xs">ELIMINAR</span>
								</a>
								<?php endif;?>
							</td>
						</tr>
						<?php
						$ii++;
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


