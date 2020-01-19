<section class="content"> 
	<div class="row">
		<div class="col-md-12">
			<?php 
			if(isset($_SESSION["client_id"])):?>
			<h1><i class='glyphicon glyphicon-shopping-cart'></i> Cotizaciones eliminadas</h1>
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
			<h1><i class='glyphicon glyphicon-shopping-cart'></i> Cotizaciones eliminadas</h1>
			<?php
			endif;
			?>
			<div class="clearfix"></div>
			<?php
			$products = null;
			if (isset($_SESSION['is_admin'])) {
				$products = SellData::getDeleteCotizacion();
			} else {
				$products = SellData::getDeleteCotizacionByUser(Core::$user->id);
			}
			if(count($products)>0){
			?>
			<br>
			<div class="box box-primary">
				<div class="box-body">
					<table class="table table-bordered table-hover table-responsive datatable">
						<thead>
							<th></th>
							<th style="text-align: center;">Venta</th>	
							<th style="text-align: center;">Cantidad</th>
							<th style="text-align: center;">Total</th>
							<th style="text-align: center;">Cliente</th>
							<th style="text-align: center;">Vendedor</th>
							<th style="text-align: center;">Almacen</th>
							<th style="text-align: center;">Fecha</th>
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
							<td style="width:30px;">
								<a href="index.php?view=onesell&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-default">
									<i class="glyphicon glyphicon-eye-open"></i>
								</a>
							</td>
							<td style="text-align: center;">#<?php echo $sell->id; ?></td>
							<td style="text-align: center;"><?php echo 	$quantity; ?></td>
							<td style="text-align: center;">
								<?php
								echo "<b>$ ".number_format($totalPrice,2,".",",")."</b>";
								?>			
							</td>
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
								if($sell->user_id!=null)
								{
									$c= $sell->getUser();echo $c->name." ".$c->lastname;
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
							<td style="text-align: center;">
								<?php 
									if(isset($sell->created_at)){
										echo $sell->created_at; 
									}
								?>
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
				<p>No se ha eliminado ninguna cotizacion.</p>
			</div>
			<?php
			}
			?>
			</br></br></br></br></br></br></br></br></br></br>
		</div>
	</div>
</section>


