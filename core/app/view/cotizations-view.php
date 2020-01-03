<section class="content"> 
	<div class="row">
		<div class="col-md-12">
			<a href="./?view=newcotization" class="btn btn-default pull-right">
				<i class="fa fa-asterisk"></i> COTIZAR PEDIDO
			</a>
			<h1><i class='glyphicon glyphicon-shopping-cart'></i> COTIZACIONES</h1>
			<div class="clearfix"></div>
			<?php
			$products=null;
			if(isset($_SESSION["client_id"])){
				$products = SellData::getCotizationsByClientId($_SESSION["client_id"]);
			}else if(isset($_SESSION["user_id"])){
				if (isset($_SESSION['is_admin'])) {
					$products = SellData::getCotizations();
				} else {
					$products = SellData::getCotizatiosByUser($_SESSION["user_id"]);
				}
			}
			if(count($products)>0){
			?>
				<br>
				<div class="box box-primary">
					<div class="box-header">
						<h3 class="box-title">COTIZACIONES</h3>
					</div>
					<table class="table table-bordered table-hover	">
						<thead>
							<th style="text-align: center;"></th>
							<th style="text-align: center;">COTIZACION Nº</th>
							<th style="text-align: center;">ESTADO</th>
							<th style="text-align: center;">TOTAL</th>
							<th style="text-align: center;">FECHA</th>
							<th style="width:100px; text-align: center;"></th>
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
							<td style="text-align: center;">

							<?php
							$operations = OperationData::getAllProductsBySellId($sell->id);
							if($sell->status==1){
								echo $sell->getD()->name;
							}
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
									echo "<b>$ ".number_format($total,2,".",",")."</b>";

							?>			

							</td>
							<td style="text-align: center;"><?php echo $sell->created_at; ?></td>
							<td style="width:200px;text-align: center;">
							<?php if(isset($_SESSION["user_id"]) && isset($_SESSION['is_admin']) && $sell->d_id == 4):?>
								<a href="index.php?view=processcotization&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-primary" onclick="return confirm('CONFIRMAS QUE QUIERES PROCESAR  ESTA COTIZACION');">
									<i class="fa fa-check"></i> PROCESAR
								</a>
							<?php endif;?>
							<?php if ($sell->d_id == 2): ?>
								<a href="index.php?action=inprocesscotization&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-success" onclick="return confirm('CONFIRMAS QUE QUIERES SOLICITAR ESTA COTIZACION');">
									<i class="fa fa-check"></i> CONFIRMAR
								</a>	
							<?php endif ?>

								<a href="index.php?action=cancelcotization&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-danger" onclick="return confirm('CONFIRMAS QUE QUIERES CANCELAR ESTA COTIZACION');">
									CANCELAR
								</a>
								<a href="index.php?view=delcotization&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-danger" onclick="return confirm('CONFIRMAS QUE QUIERES ELIMINAR ESTA COTIZACION');">
									<i class="fa fa-trash"></i>
								</a>
							</td>
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
					<h2>NO HAY COTIZACIONES</h2>
					<p>NO SE HA REALIZADO NINGUNA COTIZACION.</p>
				</div>
				<?php
			}
			?>
			</br></br></br></br></br></br></br></br></br></br>
		</div>
	</div>
</section>