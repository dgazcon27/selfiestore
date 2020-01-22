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
if (isset($_GET['id'])) {
	if (!isset($_GET['set'])) {
		$products = OperationData::getAllProductsBySellId($_GET['id']);
		$items = [];
		foreach ($products as $value) {
			array_push($items, array('product_id' => $value->product_id, 'q' => $value->q, 'stock_id' => $value->stock_id));
		}
		$_SESSION['cotization'] = $items;
	}
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
<div id="show_search_results"></div>

<script>
//jQuery.noConflict();

$(document).ready(function(){
	$("#searchp").on("submit",function(e){
		e.preventDefault();
		
		$.get("./?action=updatecotizationsearch&id=<?php echo $_GET['id'];?>",$("#searchp").serialize(),function(data){
			$("#show_search_results").html(data);
		});
		$("#product_code").val("");

	});
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

<?php if(isset($_SESSION["errors"])):?>
<h2>Errores</h2>
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


<!--- Carrito de compras :) -->
<?php if(isset($_SESSION["cotization"])):
$total = 0;
?>
<h2>LISTA DE PRODUCTOS A COTIZAR </h2>
<div class="box box-primary desktop-table-responsive">
<table class="table table-bordered table-hover">
<thead>
	<th style="width:30px;">IMAGEN</th>
	<th style="width:270px;">NOMBRE</th>
	<th style="width:110px;">PRECIO <span class="hidden-xs">UNITARIO</span></th>
	<th style="width:30px;"><span class="hidden-xs">CANTIDAD</span><span class="visible-xs">C.</span></th>
	<?php if (isset($_SESSION['is_admin']) || Core::$user->kind == 5): ?>
		<th style="width: 60px;"></th>
	<?php endif ?>
	<th style="width:100px;">PRECIO <span class="hidden-xs">TOTAL</span></th>
	<th ></th>
</thead>
<?php 
	$total_products = 0;
?>
<?php foreach($_SESSION["cotization"] as $p):
$product = ProductData::getById($p["product_id"]);
$total_products += $p['q'];
?>
<tr>
	<td><img src="storage/products/<?php echo $product->image;?>" style="width:80px;"></td>
	<td><?php echo $product->name; ?></td>
	<td><b>$ <?php echo number_format($product->price_out,2,".",","); ?></b></td>
	<td style="text-align: center;"><b><?php echo $p["q"]; ?></b></td>
	<?php if (isset($_SESSION['is_admin']) || Core::$user->kind == 5): ?>
		<td style="text-align: center;">
			<div class="input-group input-group-lg" style="margin:auto;">
			  <input data-id="<?php echo $product->id; ?>" style="text-align: center;margin-top: -5px;" min="1" class="form-control q_update" type="number" name="q_update" value="<?php echo $p['q'];?>">
			</div>
		</td>
	<?php endif ?>
	<td><b>$ <?php  $pt = $product->price_out*$p["q"]; $total +=$pt; echo number_format($pt,2,".",","); ?></b></td>
	<td style="width:30px;"><a href="index.php?view=clearcart_update_cotization&product_id=<?php echo $product->id; ?>&id=<?php echo $_GET['id']?>" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> <span class="hidden-xs">QUITAR</span></a></td>
</tr>

<?php endforeach; ?>
</table>
</div>
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
						<a href="index.php?view=clearcart&product_id=<?php echo $product->id; ?>" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> <span>QUITAR</span></a>
					<!--  -->
				</div>
				
			</div>
		</div>
	<?php endforeach ?>
</div>

<input type="hidden" name="total_products" id="total_products" value="<?php echo $total_products;?>">
<form method="post" class="form-horizontal" id="processsell" action="index.php?action=updatecotization">
	<input type="hidden" name="q_update" id="q_update">
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
<tr  class="hidden">
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
          <input name="id_sell" type="hidden" value="<?php echo $_GET['id'];?>">
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
	let total = $("#total_products");
	$("#processsell").submit(function (e) {
		if (is_admin == "0" && parseInt(total.val()) <= 5) {
			alert("NO ES POSIBLE COTIZAR MENOS DE 6 PRODUCTOS")
			e.preventDefault();
		} else {
			inputs = $(".q_update").length;
			data = []
			for (var i = 0; i < inputs; i++) {
				data.push({
					'product_id':$(".q_update")[i].dataset.id,
					'q': $(".q_update")[i].value
				})
			}
			$("#q_update").val(JSON.stringify(data));	
		}
	})

	$(".q_update").keyup(function (a) {
		items = $(".q_update");
		total_products = 0;
		for (var i = 0; i < items.length; i++) {
			total_products += parseInt(items[i].value)
		}
		total.val(total_products);
	})
</script>