<section class="content">
<?php
$product = ProductData::getById($_GET["id"]);
$categories = CategoryData::getAll();

if($product!=null):
?>
<div class="row">
	<div class="col-md-12">
	<h1><?php echo $product->name ?> <small>EDITAR PRODUCTO</small></h1>
  <?php if(isset($_COOKIE["prdupd"])):?>
    <p class="alert alert-info">La informacion del producto se ha actualizado exitosamente.</p>
  <?php setcookie("prdupd","",time()-18600); endif; ?>
	<br>
<div class="box box-primary">
  <table class="table">
  <tr>
  <td>
		<form class="form-horizontal" method="post" id="addproduct" enctype="multipart/form-data" action="index.php?view=updateproduct" role="form">

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label"></label>
    <div class="col-md-6">
		<label class="control-label">IMAGEN</label>
      <input type="file" name="image" id="name" placeholder="">
<?php if($product->image!=""):?>
  <br>
        <img src="storage/products/<?php echo $product->image;?>" class="img-responsive">
<?php endif;?>
    </div>
  </div>

			
			
<div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label"></label>
    <div class="col-md-3">
    <label class="control-label">NOMBRE*</label>
      <input type="text" name="name" class="form-control" id="name" value="<?php echo $product->name; ?>" placeholder="INGRESAR EL NOMBRE DEL PRODUCTO">
    </div>
    <div class="col-md-3">
    <label class="control-label">CODIGO DE BARRAS</label>
      <input type="text" name="barcode" class="form-control" id="barcode" value="<?php echo $product->barcode; ?>" placeholder="INGRESAR EL CODIGO DE BARRAS DEL PRODUCTO">
    </div>
  </div>		
			

			
			
<div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label"></label>
    <div class="col-md-3">
    <label class="control-label">CATEGORIA</label>
      <select name="category_id" class="form-control">
    <option value="">-- NINGUNA --</option>
    <?php foreach($categories as $category):?>
      <option value="<?php echo $category->id;?>" <?php if($product->category_id!=null&& $product->category_id==$category->id){ echo "selected";}?>><?php echo $category->name;?></option>
    <?php endforeach;?>
      </select>   
    </div>
    <div class="col-md-3">
    <label class="control-label">MARCA</label>
      <select name="brand_id" class="form-control">
    <option value="">-- NINGUNA --</option>
    <?php foreach(BrandData::getAll() as $category):?>
      <option value="<?php echo $category->id;?>" <?php if($product->brand_id!=null&& $product->brand_id==$category->id){ echo "selected";}?>><?php echo $category->name;?></option>
    <?php endforeach;?>
      </select>
    </div>
  </div>				
			

<div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label"></label>
    <div class="col-md-3">
    <label class="control-label">PRECIO DE ENTRADA*</label>
      <input type="number" name="price_in" class="form-control" value="<?php echo $product->price_in; ?>" id="price_in" placeholder="INGRESAR EL PRECIO DE ENTRADA">
    </div>
    <div class="col-md-3">
    <label class="control-label">PRECIO DE SALIDA*</label>
      <input type="number" name="price_out" class="form-control" id="price_out" value="<?php echo $product->price_out; ?>" placeholder="INGRESAR EL PRECIO DE SALIDA">
    </div>
  </div>		
			
  

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label"></label>
    <div class="col-md-6">
    <label class="control-label">MINIMA EN INVENTARIO</label>
      <input type="number" name="inventary_min" class="form-control" value="<?php echo $product->inventary_min;?>" id="inputEmail1" placeholder="INGRESAR EL MINIMO EN EL INVENTARIO(DEFAULT 10)">
    </div>
  </div>


  <div class="form-group">
    <div class="col-lg-offset-3 col-lg-8">
    <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
      <button type="submit" class="btn btn-success">ACTUALIZAR PRODUCTO</button>
    </div>
  </div>
</form>
</td>
</tr>
</table>
</div>
	</div>
</div>
<?php endif; ?>
</section>