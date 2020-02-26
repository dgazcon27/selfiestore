<link href="plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

<style type="text/css">
	
</style>
<?php if(true):?>
	<?php
		$page = 0;
		$products = null;
		$q_search = isset($_GET["product"])?$_GET["product"]:"";
		if (isset($_GET['page'])) {
			$page = $_GET['page'];
			$products = ProductData::getProductsSearchByPage($q_search, $page);
		} else {
			$products = ProductData::getLike($q_search);
		}
		if(count($products)>0){
	?>
<input type="hidden" name="q_search" id="q_search" value="$q_search_res">
<input type="hidden" name="page_desk" id="page_desk" value="$page">
<h3 style="margin-bottom: 25px;">RESULTADO DE LA BUSQUEDA</h3>
<div class="box box-primary table-desktop">
<table class="table table-bordered table-hover">
	<thead>
		<th>IMAGEN</th>
		<th>NOMBRE</th>
		<th>MEDIDA</th>
		<th style="width: 150px;">PRECIO UNITARIO</th>
		<th style="width: 100px;text-align: center;">STOCK</th>
	</thead>
	<?php
$products_in_cero=0;
	 foreach($products as $product):
	 	$stock = StockData::getPrincipal()->id;
		$q = OperationData::getQByStock($product->id, $stock);
		if ($q <= 0 && (Core::$user->kind == 8 || Core::$user->kind == 2)) {
			$stock = Core::$user->stock_id;
			$q = OperationData::getQByStock($product->id,$stock);
		}
	?>
	<?php 
	if($q>0):?>
		
	<tr class="<?php if($q<=$product->inventary_min){ echo "danger"; }?>">
		<td><img src="storage/products/<?php echo $product->image;?>" style="width:80px;"></td>
		<td><?php echo $product->name; ?></td>
		<td><?php echo $product->unit; ?></td>
		<td style="text-align: center;"><b>$<?php echo $product->price_out; ?></b></td>
		<td style="text-align: center;"><b><?php echo $q; ?></b></td>
		<td style="width:250px;">
			<form method="post" action="index.php?view=addtocotization">
				<input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
				<input type="hidden" name="stock_id" value="<?php echo $stock; ?>">
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
<?php 
	$q_search_res = isset($_GET["product"]) ? $_GET["product"] : "";
	$total_result_desk = count(ProductData::getTotalSearchResponsive($q_search_res));
	$total_pages_desk = ceil($total_result_desk/10);
?>
</table>
<nav id="paginator-desktop" aria-label="Page navigation example" style="margin: auto;float: right;cursor: pointer">
	  <ul class="pagination">
	  	<?php
	  		$ii=0; 
	  		if ($total_pages_desk > 1): 
  		?>
	    	<li class="page-item-desktop" id="previous-p-desktop" data-page="0"><a class="page-link">Anterior</a></li>
	  	<?php endif ?>
		<?php
			if ($total_pages_desk > 1) {
				while($ii < $total_pages_desk && $ii < 5) {
					$x = $ii+1;
					echo '<li class="page-item-desktop" data-page="'.$ii.'"><a class="page-link">'.$x.'</a></li>';
					$ii++;
				}	
			 } 
		?>
		<?php if ($total_pages_desk > 1): ?>
	    	<li class="page-item-desktop" id="next-p-desktop" data-page="1"><a class="page-link">Siguiente</a></li>
		<?php endif ?>
	  </ul>
	  <input type="hidden" name="total_pages-desk" id="total_pages-desk" value="<?php echo $ii;?>">
	</nav>
</div>

<div class="small-responsive">
	<div id="search-result">
		<?php
			$q_res = isset($_GET["product"]) ? $_GET["product"] : "";
			$products_resp = ProductData::getLikeResponsive($q_res);
	 		foreach($products_resp as $product):
				$stock = StockData::getPrincipal()->id;
				$q = OperationData::getQByStock($product->id, $stock);
				if ($q <= 0 && (Core::$user->kind == 8 || Core::$user->kind == 2)) {
					$stock = Core::$user->stock_id;
					$q = OperationData::getQByStock($product->id,$stock);
				}
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
							<input type="hidden" name="stock_id" value="<?php echo $stock; ?>">
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
		$q_res = isset($_GET["product"]) ? $_GET["product"] : "";
		$total_result = count(ProductData::getTotalSearchResponsive($q_res));
		$total_pages = ceil($total_result/10);
	?>
	</div>
	<nav id="paginator" aria-label="Page navigation example" style="margin: auto;">
	  <ul class="pagination">
	  	<?php
	  		$ii=0; 
	  		if ($total_pages > 1): 
  		?>
	    	<li class="page-item" id="previous-p" data-page="0"><a class="page-link">Anterior</a></li>
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
	    	<li class="page-item" id="next-p" data-page="1"><a class="page-link">Siguiente</a></li>
		<?php endif ?>
	  </ul>
	  <input type="hidden" name="total_pages" id="total_pages" value="<?php echo $ii;?>">
	</nav>
</div>
<?php

	if($products_in_cero > 0 && isset($_SESSION['is_admin'])){ 
		echo "<p class='alert alert-warning'>Se omitieron <b>$products_in_cero productos</b> que no tienen existencias en el inventario. <a href='index.php?view=inventary&stock=".StockData::getPrincipal()->id."'>Ir al Inventario</a></p>";
	}
?>

	<?php
}else{
	echo "<br><p class='alert alert-danger'>No se encontro el producto</p>";
}
?>
<?php else:
?>
<?php endif; ?>

<!-- PAGINADOR VERSION MOVIL -->
<script type="text/javascript">
	let q_s = $("#q_search").val();
	$("#previous-p").addClass('hidden');

	$(".page-item").on('click', function (a) {
		let page = parseInt(a.currentTarget.dataset.page);
		let total_pages = parseInt($("#total_pages").val());
		if (page == 0) {
			$("#previous-p").addClass('hidden')
		} else if(page >= 1) {
			$("#previous-p").removeClass('hidden')
		}
		if (page < total_pages-1) {
			$("#next-p").removeClass('hidden')
			$("#next-p")[0].dataset.page = parseInt(page) + 1;
		} else {
			$("#next-p").addClass('hidden')
		}

		$("#previous-p")[0].dataset.page = parseInt(page)-1;

		$.get("./?action=searchproductpagination&q="+q_s+"&page="+page, function (data) {
			$("#search-result").empty()
			$("#search-result").html(data);
		})
	})
</script>



<script type="text/javascript">
	let q_s = $("#q_search").val();
	let page_desk = $("#page_desk").val();
	if (page_desk == 0) {
		$("#previous-p-desktop").addClass('hidden');
	} else if (page_desk >= 1) {
		$("#previous-p-desktop").removeClass('hidden');
	}

	$(".page-item-desktop").on('click', function (a) {
		let page = parseInt(a.currentTarget.dataset.page);
		let total_pages = parseInt($("#total_pages-desk").val());
		if (page == 0) {
			$("#previous-p-desktop").addClass('hidden')
		} else if(page >= 1) {
			$("#previous-p-desktop").removeClass('hidden')
		}
		if (page < total_pages-1) {
			$("#next-p-desktop").removeClass('hidden')
			$("#next-p-desktop")[0].dataset.page = parseInt(page) + 1;
		} else {
			$("#next-p-desktop").addClass('hidden')
		}

		$("#previous-p-desktop")[0].dataset.page = parseInt(page)-1;
		$.get("./?action=searchproduct2&q="+q_s+"&page="+page, function (data) {
			$("#show_search_results").empty()
			$("#show_search_results").html(data);
		})
	})
</script>