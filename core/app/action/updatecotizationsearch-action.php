
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
		<th style="width: 150px;">PRECIO UNITARIO</th>
		<th style="width: 100px;text-align: center;">STOCK</th>
	</thead>
	<?php
$products_in_cero=0;
	 foreach($products as $product):
		$stock = StockData::getPrincipal()->id;
		$q = OperationData::getQByStock($product->id, $stock);
		if ($q <= 0 && Core::$user->kind == 8) {
			$stock = Core::$user->stock_id;
			$q = OperationData::getQByStock($product->id,$stock);
		}
	?>
	<?php 
	if($q>0):?>
		
	<tr class="<?php if($q<=$product->inventary_min){ echo "danger"; }?>">
		<td><img src="storage/products/<?php echo $product->image;?>" style="width:80px;"></td>
		<td><?php echo $product->name; ?></td>
		<td style="text-align: center;"><b>$<?php echo $product->price_out; ?></b></td>
		<td style="text-align: center;"><b><?php echo $q; ?></b></td>
		<td style="width:250px;">
		<form method="post" action="index.php?view=updateproductcotization&id=<?php echo $_GET['id']; ?>">
			<input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
			<input type="hidden" name="stock_id" value="<?php echo $stock; ?>">


<div class="input-group">
		<input type="" class="form-control" required name="q" placeholder="Cantidad ...">
      <span class="input-group-btn">
		<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-plus-sign"></i> Agregar</button>
      </span>
    </div>


		</form></td>
	</tr>
	
<?php else:$products_in_cero++;
?>
<?php  endif; ?>
	<?php endforeach;?>
</table>
</div>

<div class="small-responsive">
	<div id="search-result">
		<?php
			$products_resp = ProductData::getLikeResponsive($_GET["product"]);
	 		foreach($products_resp as $product):
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
						<form method="post" action="index.php?view=updateproductcotization&id=<?php echo $_GET['id'];?>">
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
		$total_result = count(ProductData::getTotalSearchResponsive($_GET["product"]));
		$total_pages = ceil($total_result/10);
	?>
	</div>
	<nav id="paginator" aria-label="Page navigation example" style="margin: auto;">
	  <ul class="pagination">
	  	<?php
	  		$ii=0; 
	  		if ($total_pages > 1): 
  		?>
	    	<li class="page-item" id="previous-p" data-page="0"><a class="page-link">Previous</a></li>
	  	<?php endif ?>
		<?php
			if ($total_pages > 1) {
				while($ii < $total_pages && $ii < 5) {
					$x = $ii+1;
					echo '<li class="page-item" data-page="'.$ii.'"><a class="page-link">'.$x.'</a></li>';
					$ii++;
				}	
			 } 
		?>
		<?php if ($total_pages > 1): ?>
	    	<li class="page-item" id="next-p" data-page="1"><a class="page-link">Next</a></li>
		<?php endif ?>
	  </ul>
	  <input type="hidden" name="total_pages" id="total_pages" value="<?php echo $ii;?>">
	</nav>
</div>

<?php 
	if($products_in_cero > 0 && isset($_SESSION['is_admin'])){ 
		echo "<p class='alert alert-warning'>Se omitieron <b>$products_in_cero productos</b> que no tienen existencias en el inventario. <a href='index.php?view=inventary&stock=".StockData::getPrincipal()->id."'>Ir al Inventario</a></p>"; }
	}else{
		echo "<br><p class='alert alert-danger'>No se encontro el producto</p>";
	}
?>
<?php else:
?>
<?php endif; ?>