<style type="text/css">
	#barcode {
		width: 60px !important;
	}

	#name_product {
		width: 250px !important;
	}
</style>
<?php
// $symbol = ConfigurationData::getByPreffix("currency")->val;
$iva_name = ConfigurationData::getByPreffix("imp-name")->val;
$iva_val = ConfigurationData::getByPreffix("imp-val")->val;
if (isset($_SESSION['is_admin'])) {
	echo '<input type="hidden" id="is_admin" value="1">';
} else {
	echo '<input type="hidden" id="is_admin" value="0">';
}

if (isset($_SESSION['is_client'])) {
	echo '<input type="hidden" id="is_client" value="1">';
} else {
	echo '<input type="hidden" id="is_client" value="0">';
}
?>
<?php 
	$products = OperationData::getStockOfProductsAvailables();
?>
<section class="content">
	<div class="box box-primary">
		<div class="row">
			<div class="col-md-12">
			<?php if (Core::$user->kind == 1): ?>
				<div class="btn-group pull-right" style="margin-top: 12px;margin-right: 10px;">
					<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-download"></i> Descargar <span class="caret"></span>
					</button>
					<ul class="dropdown-menu" role="menu">
						<li><a onclick="thePDF()" id="makepdf" class="">PDF (.pdf)</a>
					</ul>
				</div>
			<?php endif ?>
			<?php if(isset($_SESSION["errors"])):?>
				<h2>Producto insuficiente</h2>
				<p></p>
				<table class="table table-bordered table-hover">
				<tr class="danger">
					<th>Codigo</th>
					<th>Producto</th>
					<th>Mensaje</th>
				</tr>
				<?php foreach ($_SESSION["errors"]  as $error):
				$product = ProductData::getById($error["product_id"]);
				?>
				<tr class="danger">
					<td><?php echo $product->id; ?></td>
					<td><?php echo $product->name; ?></td>
					<td><b><?php echo $error["message"]; ?></b></td>
				</tr>

				<?php endforeach; ?>
				</table>
				<?php
				unset($_SESSION["errors"]);
			 endif; ?>

			<h1>COTIZAR PEDIDO</h1>
				<div class="box-body no-padding">
					<div class="box-body table-responsive" style="overflow: hidden !important;">
						<table class="table table-bordered datatable table-hover" >
							<thead>
								<th id="barcode">CODIGO</th>
								<th>IMAGEN</th>
								<th id="name_product">NOMBRE</th>
								<th>PRECIO</th>
								<th>PESO</th>
								<th>MARCA</th>
								<th>DISPONIBLE</th>
								<th></th>
							</thead>
							<?php foreach ($products as $product): ?>
								<tr>
									<td><?php echo $product->barcode; ?></td>
									<td>
										<?php if($product->image!=""):?>
											<img src="storage/products/<?php echo $product->image;?>" style="width:80px;">
										<?php endif;?>
									</td>
									<td><?php echo strtoupper($product->name); ?></td>
									<td style="text-align: center">$ <?php echo number_format($product->price_out,2,'.',','); ?></td>
									<td style="text-align: center" class="center"><?php echo $product->unit; ?></td>
									<td style="text-align: center"><?php if($product->brand_id!=null){echo $product->getBrand()->name;}else{ echo "<center>----</center>"; }  ?></td>
									<td style="text-align: center"><?php echo strtoupper($product->q); ?></td>
									<td >
										<form method="post" action="index.php?view=addtocotization">
											<input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
											<input type="hidden" name="stock_id" value="<?php echo $product->stock; ?>">
											<div class="input-group">
												<input type="" class="form-control" required name="q" placeholder="Cantidad ...">
										      	<span class="input-group-btn">
												<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-plus-sign"></i></button>
										      </span>
										    </div>
										</form>
									</td>
								</tr>	
							<?php endforeach ?>
							
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>


<!--- Carrito de compras :) -->
<?php if(isset($_SESSION["cotization"])):
$total = 0;
?>
<h2 style="margin-bottom: 20px; margin-top: 80px;">LISTA DE PRODUCTOS A COTIZAR </h2>
<!-- BEGIN DESKTOP TABLE OF PRODUCTS  -->
<div class="box box-primary" style="margin-top: 45px;">
<table class="table table-bordered table-hover table-responsive">
<thead>
	<th style="width:30px;">IMAGEN</th>
	<th style="width:270px;">NOMBRE</th>
	<th style="width:270px;">MEDIDA</th>
	<th style="width:110px;">PRECIO <span class="hidden-xs">UNITARIO</span></th>
	<th style="width:30px;"><span class="hidden-xs">CANTIDAD</span><span class="visible-xs">C.</span></th>
	<th style="width:100px;"><span class="hidden-xs">PRECIO</span> TOTAL</th>
	<th ></th>
</thead>
<?php 
$total_products = 0;

foreach($_SESSION["cotization"] as $p):
$product = ProductData::getById($p["product_id"]);
$total_products += $p['q'];
?>
<tr >
	<td><img src="storage/products/<?php echo $product->image;?>" style="width:80px;"></td>
	<td><?php echo $product->name; ?></td>
	<td><?php echo $product->unit; ?></td>
	<td><b>$<?php echo number_format($product->price_out,2,".",","); ?></b></td>
	<td style="text-align: center;"><?php echo $p["q"];?></td>
	<td><b>$<?php  $pt = $product->price_out*$p["q"]; $total +=$pt; echo number_format($pt,2,".",","); ?></b></td>
	<td style="width:30px;"><a href="index.php?view=clearcart&product_id=<?php echo $product->id; ?>" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> <span class="hidden-xs">Quitar</span></a></td>
</tr>
<?php endforeach; ?>
</table>
</div>
<!-- END DESKTOP TABLE OF PRODUCTS -->
<!-- BEGIN MOVIL RESPONSIVE -->
<div class="movil-added-products">
	<?php
		foreach ($_SESSION["cotization"] as $p): 
		$product = ProductData::getById($p["product_id"]);
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
					<span>Stock</span>:<b>
					<?php echo $p['q'];?></b> |
					<span>Precio</span>:<b>
					$<?php echo $product->price_out; ?></b>
				</div>
				<div class="remove-item">
						<a href="index.php?view=clearcart&product_id=<?php echo $product->id; ?>" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> <span>Quitar</span></a>
					<!--  -->
				</div>
				
			</div>
		</div>
	<?php endforeach ?>
</div>
<!-- END MOVIL RESPONSIVE -->
<input type="hidden" name="total_products" id="total_products" value="<?php echo $total_products;?>">
<form method="post" class="form-horizontal" id="processsell" action="index.php?action=savecotization">
<h2>RESUMEN</h2>


      <input type="hidden" name="total" value="<?php echo $total; ?>" class="form-control" placeholder="Total">
      <div class="clearfix"></div>
<br>
  <div class="row">
<div class="col-md-6 col-md-offset-6">
<div class="box box-primary">
<table class="table table-bordered">
<tr>
	<td><p>Subtotal</p></td>
	<td><p><b>$ <?php echo number_format($total*(1 - ($iva_val/100) ),2,'.',','); ?></b></p></td>
</tr>
<tr class="hidden">
	<td><p><?php echo $iva_name." (".$iva_val."%) ";?></p></td>
	<td><p><b>$ <?php echo number_format($total*($iva_val/100),2,'.',','); ?></b></p></td>
</tr>
<tr>
	<td><p>Total</p></td>
	<td><p><b>$ <?php echo number_format($total,2,'.',','); ?></b></p></td>
</tr>


</table>
</div>
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <div class="checkbox">
        <label>
          <input name="is_oficial" type="hidden" value="1">
        </label>
      </div>
    </div>
  </div>
<div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <div class="checkbox">
        <label>
		<a href="index.php?view=clearcart" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> Cancelar</a>
        <button class="btn btn-success"><i class="glyphicon glyphicon-send"></i> Guardar Cotizacion</button>
        </label>
      </div>
    </div>
  </div>
</form>
</div>
</div>

<?php endif; ?>

</div>
</section>

<script>
	let is_admin = $("#is_admin").val();
	// $("#processsell").submit(function (e) {
	// 	let total = $("#total_products").val();
	// 	if (is_admin == "0" && parseInt(total) <= 5) {
	// 		alert("NO ES POSIBLE COTIZAR MENOS DE 6 PRODUCTOS")
	// 		e.preventDefault();
	// 	}

	// })
</script>


<script type="text/javascript">
        function thePDF() {

			var doc = new jsPDF('p', 'pt');
	        doc.setFontSize(15);
	        doc.text("<?php echo ConfigurationData::getByPreffix("company_name")->val;?>", 40, 65);
	        doc.setFontSize(10);
	        doc.text("PRODUCTOS", 40, 80);
	        doc.setFontSize(12);
	        
			var columns = [
			    {title: "", dataKey: "image"}, 
			    {title: "NOMBRE DEL PRODUCTO", dataKey: "name"}, 
			    {title: "PRECIO DE SALIDA", dataKey: "price_out"},
				{title: "CATEGORIA", dataKey: "category_id"},
				{title: "MARCA", dataKey: "brand_id"},
			];
			var rows = [
			  <?php foreach($products as $product):
			  ?>
			    {
			      "name": "<?php echo $product->name; ?>",
			      "price_out": "$ <?php echo number_format($product->price_out,2,'.',',');?>",
					"category_id": "<?php if($product->category_id!=null){echo $product->getCategory()->name;}else{ echo "<center>----</center>"; }  ?>",
					"brand_id": "<?php if($product->brand_id!=null){echo $product->getBrand()->name;}else{ echo "<center>----</center>"; }  ?>",
			      },
			 <?php endforeach; ?>
			];
			doc.autoTable(columns, rows, {
			    theme: 'grid',
			    overflow:'linebreak',
			    styles: { 
			        fillColor: <?php echo Core::$pdf_table_fillcolor;?>,
			        halign: 'center'
			    },
			    columnStyles: {
			        id: {fillColor: <?php echo Core::$pdf_table_column_fillcolor;?>}
			    },
			    margin: {top: 100},
			    afterPageContent: function(data) {
			    }
			});
			doc.setFontSize(12);
			doc.text("<?php echo Core::$pdf_footer;?>", 40, doc.autoTableEndPosY()+25);
			<?php 
			$con = ConfigurationData::getByPreffix("report_image");
			if($con!=null && $con->val!=""):
			?>
			var img = new Image();
			img.src= "storage/configuration/<?php echo $con->val;?>";
			img.onload = function(){
			doc.addImage(img, 'PNG', 495, 20, 60, 60,'mon');	
			doc.save('products-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
			}
			<?php else:?>
			doc.save('products-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
			<?php endif; ?>
			}
</script>