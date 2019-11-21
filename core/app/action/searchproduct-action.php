<?php



?>

<?php if((isset($_GET["product_name"]) && $_GET["product_name"]!="") || (isset($_GET["product_code"]) && $_GET["product_code"]!="") ):?>
<?php
$go = $_GET["go"];
$search  ="";
if($go=="code"){ $search=$_GET["product_code"]; }
else if($go=="name"){ $search=$_GET["product_name"]; }
$products = ProductData::getLike($search);
if(count($products)>0){
	?>
<h3>RESULTADOS DE LA BUSQUEDA</h3>
<div class="box box-primary">
<table class="table table-bordered table-hover">
	<thead>
    	<th>CODIGO</th>
		<th>IMAGEN</th>
		<th>NOMBRE</th>
		<th>UNIDAD</th>
		<th>TIPO</th>
		<th>PRECIO UNITARIO</th>
		<th>EN INVENTARIO</th>
		<th>CANTIDAD</th>
	</thead>
	<?php
$products_in_cero=0;
	 foreach($products as $product):
$q= OperationData::getQByStock($product->id,StockData::getPrincipal()->id);
	?>
	<?php 
	if($product->kind==2||$q>0):?>
		
	<tr class="<?php if($product->kind==1&&$q<=$product->inventary_min){ echo "danger"; }?>">
		<td style="width:80px;"><?php echo $product->id; ?></td>
        <td><img src="storage/products/<?php echo $product->image;?>" style="width:80px;"></td>
		<td><?php echo strtoupper($product->name); ?></td>
		<td><?php echo $product->unit; ?></td>
<td>
  <?php
if($product->kind==1){
  echo "<span class='label label-info'>Producto</span>";
}else if($product->kind==2){
  echo "<span class='label label-success'>Servicio</span>";

}
  ?>


</td>

		<td><b>$<?php echo $product->price_out; ?></b></td>
		<td>
			<?php 
			if($product->kind==1){
			echo $q; 
			}else if($product->kind==2){
				echo "";
			}
			?>
		</td>
		<td style="width:250px;">
		<form method="post" action="index.php?view=addtocart" id="addtocart">
		<input type="hidden" name="product_id" value="<?php echo $product->id; ?>">

<div class="input-group">
		<input type="" class="form-control" required id="sell_q" name="q" placeholder="CANTIDAD ...">
      <span class="input-group-btn">
		<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-plus-sign"></i> AGREGAR</button>
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
<?php if($products_in_cero>0){ 
if(Core::$user->kind==1){
	echo "<p class='alert alert-warning'>SE OMITIERON <b>$products_in_cero PRODUCTOS</b> QUE NO TIENEN EXISTENCIAS EN EL INVENTARIO. <a href='index.php?view=inventary&stock=".StockData::getPrincipal()->id."'>IR AL INVENTARIO</a></p>"; }
}
?>

	<?php
}else{
	echo "<br><p class='alert alert-danger'>NO SE ENCONTRO EL PRODUCTO</p>";
}
?>
<script>
		$("#addtocart").on("submit",function(e){
		e.preventDefault();
			$.post("./?view=addtocart",$("#addtocart").serialize(),function(data){
				$.get("./?action=cartofsell",null,function(data2){
					$("#cartofsell").html(data2);
				});
			});
		$("#sell_q").val("");

	});
</script>

<?php else:
?>
<?php endif; ?>