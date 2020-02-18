<section class="content">
    <?php 
$categories = CategoryData::getAll();
$products = ProductData::getAll();
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
    <label for="inputEmail1" class="col-lg-3 control-label"></label>
    <div class="col-md-6">
	<label class="control-label">TIPO</label>
    <select name="kind" class="form-control">
    <option value="1">PRODUCTO</option>
    <option value="2">SERVICIO</option>
      </select>    
      </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label"></label>
    <div class="col-md-6">
		<label class="control-label">IMAGEN</label>
      <input type="file" name="image" id="image" placeholder="">
    </div>
  </div>

			
			
			
	<div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label"></label>
    <div class="col-md-3">
    <label class="control-label">NOMBRE*</label>
      <input type="text" name="name" required class="form-control" id="name" placeholder="INGRESAR EL NOMBRE DEL PRODUCTO">
    </div>
    <div class="col-md-3">
    <label class="control-label">CODIGO DE BARRAS</label>
      <input type="text" name="barcode" id="product_code" class="form-control" id="barcode" placeholder="INGRESAR EL CODIGO DE BARRAS DEL PRODUCTO">
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
      <input type="text" name="price_in" value="0" required class="form-control" id="price_in" placeholder="INGRESAR EL PRECIO DE ENTRADA">
    </div>
    <div class="col-md-3">
    <label class="control-label">PRECIO DE SALIDA*</label>
      <input type="text" name="price_out" value="0" required class="form-control" id="price_out" placeholder="INGRESAR EL PRECIO DE SALIDA">
    </div>
  </div>
			
			
			
<div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label"></label>
    <div class="col-md-2">
    <label class="control-label">MINIMA EN INVENTARIO:</label>
      <input type="number" name="inventary_min" value="0" class="form-control" id="inputEmail1" placeholder="INGRESAR EL MINIMO EN EL INVENTARIO(DEFAULT 10)">
    </div>
    <div class="col-md-2">
    <label class="control-label">INVENTARIO INICIAL*</label>
      <input type="number" name="q" class="form-control" id="inputEmail1" placeholder="INGRESAR EL INVENTARIO INICIAL" required>
    </div>
    <div class="col-md-2">
    <label class="control-label">PESO*</label>
      <input type="text" name="unit" class="form-control" id="unit" placeholder="PESO" required>
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label"></label>
    <div class="col-md-3">
    <label class="control-label">FECHA DE VENCIMIENTO:</label>
      <input type="date" name="expired_at" value="" class="form-control" id="expired_at" placeholder="FECHA DE VENCIMIENTO">
    </div>
    <div class="col-md-3">
    <label class="control-label">ALERTA DE VENCIMIENTO</label>
      <input type="date" name="expired_alert" class="form-control" id="expired_alert" placeholder="ALERTA DE VENCIMIENTO" value="" required>
    </div>
  </div>

			
			
		
	
	<?php 
			
	if(count(ProductData::getAll()) == 0)
	{		
		$contad = 1;		
	}
	else
		$contad = count(ProductData::getAll())+1;
	?>		
			
			
			
			
	<div class="form-group">
	<label for="inputEmail1" class="col-lg-3 control-label"></label>
    <div class="col-md-3">
      <input type="hidden" name="code" value="<?php echo $contad; ?>" id="product_code" class="form-control" id="barcode" placeholder="Codigo Interno" required>
    </div>
	<div class="col-md-3">
      <input type="hidden" name="unit" class="form-control" id="unit" placeholder="Unidad del Producto">
    </div>
  </div>	
			

  <div class="form-group">  
  
		<div class="col-lg-offset-3 col-lg-8">
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

  Mousetrap.bind('f', function(e) {
    $("#name").focus();
  });

</script>
</section>