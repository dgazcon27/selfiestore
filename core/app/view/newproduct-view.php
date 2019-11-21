<section class="content">
    <?php 
$categories = CategoryData::getAll();
    ?>
<div class="row">
	<div class="col-md-12">
	<h1>NUEVO PRODUCTO</h1>
	<br>
  <div class="box box-primary">
  <table class="table">
  <tr>
  <td>
		<form class="form-horizontal" method="post" enctype="multipart/form-data" id="addproduct" action="index.php?view=addproduct" role="form">

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">TIPO</label>
    <div class="col-md-6">
    <select name="kind" class="form-control">
    <option value="1">Producto</option>
    <option value="2">Servicio</option>
      </select>    
      </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">IMAGEN</label>
    <div class="col-md-6">
      <input type="file" name="image" id="image" placeholder="">
    </div>
  </div>

			
			
			
	<div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label"></label>
    <div class="col-md-3">
    <label class="control-label">CODIGO INTERNO*</label>
      <input type="text" name="code" id="product_code" class="form-control" id="barcode" placeholder="Codigo Interno" required>
    </div>
    <div class="col-md-3">
    <label class="control-label">CODIGO DE BARRAS</label>
      <input type="text" name="barcode" id="product_code" class="form-control" id="barcode" placeholder="Codigo de Barras del Producto">
    </div>
  </div>				
			
			
			
			
			
			
			
	<div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label"></label>
    <div class="col-md-3">
    <label class="control-label">NOMBRE*</label>
      <input type="text" name="name" required class="form-control" id="name" placeholder="Nombre del Producto">
    </div>
    <div class="col-md-3">
    <label class="control-label">UNIDAD*</label>
      <input type="text" name="unit" class="form-control" id="unit" placeholder="Unidad del Producto">
    </div>
  </div>		
					
		
			
	<div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label"></label>
    <div class="col-md-3">
    <label class="control-label">CATEGORIA</label>
      <select name="category_id" class="form-control">
    <option value="">-- NINGUNA --</option>
    <?php foreach($categories as $category):?>
      <option value="<?php echo $category->id;?>"><?php echo $category->name;?></option>
    <?php endforeach;?>
      </select>    
    </div>
    <div class="col-md-3">
    <label class="control-label">MARCA</label>
      <select name="brand_id" class="form-control">
    <option value="">-- NINGUNA --</option>
    <?php foreach(BrandData::getAll() as $category):?>
      <option value="<?php echo $category->id;?>"><?php echo $category->name;?></option>
    <?php endforeach;?>
      </select>
    </div>
  </div>		

  
			
		
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label"></label>
    <div class="col-md-3">
    <label class="control-label">PRECIO DE ENTRADA*</label>
      <input type="text" name="price_in" required class="form-control" id="price_in" placeholder="Precio de entrada">
    </div>
    <div class="col-md-3">
    <label class="control-label">PRECIO DE SALIDA*</label>
      <input type="text" name="price_out" required class="form-control" id="price_out" placeholder="Precio de salida">
    </div>
  </div>
			
			
			
<div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label"></label>
    <div class="col-md-3">
    <label class="control-label">MINIMA EN INVENTARIO:</label>
      <input type="text" name="inventary_min" class="form-control" id="inputEmail1" placeholder="Minima en Inventario (Default 10)">
    </div>
    <div class="col-md-3">
    <label class="control-label">INVENTARIO INICIAL*</label>
      <input type="text" name="q" class="form-control" id="inputEmail1" placeholder="Inventario inicial" required>
    </div>
  </div>
			

  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <button type="submit" class="btn btn-primary">AGREGAR PRODUCTO</button>
    </div>
  </div>
</form>
</td>
</tr>
</table>
</div>
	</div>
</div>

<script>
  $(document).ready(function(){
    $("#product_code").keydown(function(e){
        if(e.which==17 || e.which==74 ){
            e.preventDefault();
        }else{
            console.log(e.which);
        }
    })
});

</script>
</section>