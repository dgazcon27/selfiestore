<section class="content"> 
	<div class="row">
		<div class="col-md-12">
			<h1><i class='glyphicon glyphicon-shopping-cart'></i> PEDIDOS APROBADOS</h1>
			<div class="clearfix"></div>
			<?php
			$products=null;
			if (isset($_SESSION['is_admin'])) {
				$products = SellData::getOrdersApproved();
			} else {
				$products = SellData::getOrdersApprovedByUser($_SESSION['user_id']);
			}
			if(count($products)>0){
			?>
				<br>
				<div class="box box-primary">
					<div class="box-header">
						<h3 class="box-title">PEDIDOS APROBADOS</h3>
					</div>
					<table class="table table-bordered table-hover	">
						<thead>
							<th style="text-align: center;"></th>
							<th style="text-align: center;"><span class="hidden-xs hidden-sm">COTIZACION</span> Nº</th>
							<?php if (isset($_SESSION['is_admin'])): ?>
							<th style="text-align: center;">CLIENTE</th>
							<th style="text-align: center;">TELEFONO</th>
							<?php endif ?>
							<th style="text-align: center;">ESTADO</th>
							<th  class="hidden-xs" style="text-align: center;">TOTAL DE PRODUCTOS</th>
							<th  class="visible-xs" style="text-align: center;">N° PRODUCTOS</th>
							<th style="text-align: center;">TOTAL</th>
							<th class="hidden-xs"  style="text-align: center;">FECHA</th>
							<?php if (isset($_SESSION['is_admin'])): ?>
							<th style="width:100px; text-align: center;"></th>
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
							
							<td style="text-align: center;">#<?php echo $cotizationsCouner; ?></td>
							<?php if (isset($_SESSION['is_admin'])): ?>
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
								echo $sell->getD()->name;
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
							<td class="hidden-xs"  style="text-align: center;"><?php echo $sell->created_at; ?></td>
							<?php if (isset($_SESSION['is_admin'])): ?>
							<td style="width:200px;text-align: center;">
								<?php if ($sell->d_id == 5 && isset($_SESSION['is_admin'])): ?>
									<a href="index.php?view=processsell&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-primary">
									<i class="fa fa-send"></i><span class="hidden-xs hidden-sm"> CONVERTIR EN VENTA</span>
									</a>
								<?php endif ?>
								<?php if (isset($_SESSION['is_admin'])): ?>
									<?php if ($sell->d_id != 7): ?>
										<a href="index.php?action=cancelcotization&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-danger" onclick="return confirm('CONFIRMAS QUE QUIERES CANCELAR ESTA COTIZACION');">
											<i class="fa fa-ban"></i><span class="hidden-xs hidden-sm"> CANCELAR</span>
										</a>
									<?php endif ?>
									<a href="index.php?view=delcotization&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-danger" onclick="return confirm('CONFIRMAS QUE QUIERES ELIMINAR ESTA COTIZACION');">
										<i class="fa fa-trash"></i>
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