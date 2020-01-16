<link href="plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

<style type="text/css">
	@media (max-width: 728px){
		.table-desktop {
			display: none;
		}

		.small-responsive{
			display: inline;
		}

		.row-product-small {
			height: 140px;
		}

		.image-small{
			width: 30%;
    		display: inline-block;
    		position: relative;
		 	top: -20px;
		}

		.info-product {
			display: inline-block;
		    width: 65%;
		}

		.title-product {
			font-size: 23px;
		}

		.value-product b{
			font-size: 17px;
		}
	}
	
	@media (min-width: 729px){
		.table-desktop {
			display: inline;
		}

		.small-responsive{
			display: none;
		}
	}
</style>

<?php if(isset($_GET["product"]) && $_GET["product"]!=""):?>
	<?php
$products = ProductData::getLike($_GET["product"]);
if(count($products)>0){
	?>
<h3>RESULTADO DE LA BUSQUEDA</h3>
<div class="box box-primary table-desktop">
<table class="table table-bordered table-hover">
	<thead>
		<th>IMAGEN</th>
		<th>NOMBRE</th>
		<th>PRECIO UNITARIO</th>
		<th>CANTIDAD APROXIMADA/ESTIMADO DISPONIBLE EN STOCK</th>
	</thead>
	<?php
$products_in_cero=0;
	 foreach($products as $product):
$q= OperationData::getQByStock($product->id,StockData::getPrincipal()->id);
	?>
	<?php 
	if($q>0):?>
		
	<tr class="<?php if($q<=$product->inventary_min){ echo "danger"; }?>">
		<td><img src="storage/products/<?php echo $product->image;?>" style="width:80px;"></td>
		<td><?php echo $product->name; ?></td>
		<td><b>$<?php echo $product->price_out; ?></b></td>
		<td><b><?php echo $q; ?></b></td>
		<td style="width:250px;">
			<form method="post" action="index.php?view=addtocotization">
				<input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
				<div class="input-group">
					<input type="" class="form-control" required name="q" placeholder="Cantidad ...">
			      	<span class="input-group-btn">
					<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-plus-sign"></i> Agregar</button>
			      </span>
			    </div>
			</form>
		</td>
	</tr>
	
<?php else:$products_in_cero++;
?>
<?php  endif; ?>
	<?php endforeach;?>
</table>
</div>

<div class="small-responsive">

	
	<?php foreach($products as $product):
		$q= OperationData::getQByStock($product->id,StockData::getPrincipal()->id);
		if($q > 0):
	?>
		<div class="row-product-small">
			<div class="image-small">
				<img src="storage/products/<?php echo $product->image;?>" style="width:80px;">
			</div>
			<div class="info-product">
				<div>
					<b class="title-product"><?php echo $product->name; ?></b>
				</div>
				<div class="value-product">
					<b>Stock:
					<?php echo $q;?></b> |
					<b>Precio:
					$<?php echo $product->price_out; ?></b>
				</div>
				<div>
					<form method="post" action="index.php?view=addtocotization">
						<input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
						<div class="input-group">
							<input class="form-control" required name="q" placeholder="Cantidad ...">
					      	<span class="input-group-btn">
							<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-plus-sign"></i> Agregar</button>
					      </span>
					    </div>
					</form>
				</div>
			</div>
		</div>	
	<?php
		endif;	 
		endforeach; 
	?>	
</div>


<?php if($products_in_cero>0){ echo "<p class='alert alert-warning'>Se omitieron <b>$products_in_cero productos</b> que no tienen existencias en el inventario. <a href='index.php?view=inventary&stock=".StockData::getPrincipal()->id."'>Ir al Inventario</a></p>"; }?>

	<?php
}else{
	echo "<br><p class='alert alert-danger'>No se encontro el producto</p>";
}
?>
<hr><br>
<?php else:
?>
<?php endif; ?>