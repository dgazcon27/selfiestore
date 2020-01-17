<?php 
	if (isset($_GET['page']) && isset($_GET['q'])) {
		$products = ProductData::getLikeResponsivePaginate($_GET['q'], $_GET['page']);
		if (count($products) > 0) {
			$rows = "";
			foreach ($products as $product) {
				$q= OperationData::getQByStock($product->id,StockData::getPrincipal()->id);
				if($q > 0) {
					$rows .= '<div class="row-product-small">
								<div class="image-small">
									<img src="storage/products/'.$product->image.'" style="width:80px;">
								</div>
								<div class="info-product">
									<div>
										<b class="title-product">'.$product->name.'</b>
									</div>
									<div class="value-product">
										<b>Stock:'.$q.'</b> | <b>Precio:'.$product->price_out.'</b>
									</div>
								</div>
								<div style="width: 69%;float: right;">
									<form method="post" action="index.php?view=addtocotization">
										<input type="hidden" name="product_id" value="'.$product->id.'">
										<div class="input-group">
											<input class="form-control" required name="q" placeholder="Cantidad ...">
											<span class="input-group-btn">
												<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-plus-sign"></i> Agregar</button>
											</span>
										</div>
									</form>
								</div>
							</div>';
				}
			}
			echo $rows;
		}
	}

?>
					
							

