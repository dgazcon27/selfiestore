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

<section class="content">
<div class="row">
	<div class="col-md-12">
	<h1>COTIZAR PEDIDO</h1>
	<p><b>BUSCAR PRODUCTO POR NOMBRE O POR CODIGO:</b></p>
		<form id="searchp">
		<div class="row">
			<div class="col-md-6 input-search">
				<input type="hidden" name="view" value="newcotization">
				<input type="text" id="product_code" name="product" class="form-control">
			</div>
			<div class="col-md-3 button-search">
			<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i> BUSCAR</button>
			</div>
		</div>
		</form>
		<?php if(isset($_SESSION["errors"])):?>
		<h2>Error</h2>
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
<div id="show_search_results"></div>

<script>
//jQuery.noConflict();

$(document).ready(function(){
	$("#searchp").on("submit",function(e){
		e.preventDefault();
		
		$.get("./?action=searchproduct2",$("#searchp").serialize(),function(data){
			$("#show_search_results").html(data);
		});
		$("#product_code").val("");

	});

	if ($("#is_client").val() == "1") {
		$.get("./?action=searchproductmovil",$("#searchp").serialize(),function(data){
			$("#show_search_results").html(data);
		});
	}
});

$(document).ready(function(){
    $("#product_code").keydown(function(e){
        if(e.which==17 || e.which==74){
            e.preventDefault();
        }else{
            console.log(e.which);
        }
    })
});
</script>




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
	$("#processsell").submit(function (e) {
		let total = $("#total_products").val();
		if (is_admin == "0" && parseInt(total) <= 5) {
			alert("NO ES POSIBLE COTIZAR MENOS DE 6 PRODUCTOS")
			e.preventDefault();
		}

	})
</script>