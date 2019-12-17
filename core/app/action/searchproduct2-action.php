<?php if(isset($_GET["product"]) && $_GET["product"]!=""):?>
	<?php
$products = ProductData::getLike($_GET["product"]);
if(count($products)>0){
	?>
<h3>RESULTADO DE LA BUSQUEDA</h3>
<div class="box box-primary">
<table class="table table-bordered table-hover">
	<thead>
		<th>CODIGO</th>
		<th>IMAGEN</th>
		<th>NOMBRE</th>
		<th>TIPO</th>
		<th>PRECIO UNITARIO</th>
		<th>CANTIDAD</th>
	</thead>
	<?php
$products_in_cero=0;
	 foreach($products as $product):
$q= OperationData::getQByStock($product->id,StockData::getPrincipal()->id);
	?>
	<?php 
	if($q>0):?>
		
	<tr class="<?php if($q<=$product->inventary_min){ echo "danger"; }?>">
		<td style="width:80px;"><?php echo $product->id; ?></td>
		<td><img src="storage/products/<?php echo $product->image;?>" style="width:80px;"></td>
		<td><?php echo $product->name; ?></td>
		
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
			<?php echo $q; ?>
		</td>
		<td style="width:250px;"><form method="post" action="index.php?view=addtocotization">
		<input type="hidden" name="product_id" value="<?php echo $product->id; ?>">

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