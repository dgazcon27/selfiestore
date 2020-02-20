<section class="content">
<?php $user = PersonData::getById($_GET["id"]);
?>
<div class="row">
	<div class="col-md-12">
	<h1>EDITAR CLIENTE</h1>
	<br>
		<form class="form-horizontal" method="post" id="addproduct" action="index.php?view=updateclient" role="form">

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">NUMERO DE IDENTIFICACION*</label>
    <div class="col-md-6">
      <input type="text" name="no" value="<?php echo $user->no;?>" class="form-control" id="no" placeholder="INGRESAR EL NUMERO DE IDENTIFICACION">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">NOMBRE COMPLETO*</label>
    <div class="col-md-6">
      <input type="text" name="name" value="<?php echo $user->name?>" class="form-control" id="name" placeholder="INGRESAR EL NOMBRE COMPLETO">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">DIRECCION</label>
    <div class="col-md-6">
      <input type="text" name="address1" value="<?php echo $user->address1;?>" class="form-control" id="address1" placeholder="INGRESAR LA DIRECCION">
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">TELEFONO</label>
    <div class="col-md-6">
      <input type="text" name="phone1"  value="<?php echo $user->phone1;?>"  class="form-control" id="phone1" placeholder="INGRESAR EL NUMERO DE TELEFONO">
    </div>
  </div>
			
			

<div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label" >ACTIVAR CREDITO</label>
    <div class="col-md-6">
      <div class="checkbox">
        <label>
          <input type="checkbox" name="has_credit" <?php if($user->has_credit){ echo "checked";}?>>
        </label>
      </div>
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">LIMITE DE CREDITO</label>
    <div class="col-md-6">
      <input type="text" name="credit_limit"  value="<?php echo $user->credit_limit;?>"  class="form-control" id="credit_limit" placeholder="INGRESAR EL LIMITE DE CREDITO">
    </div>
  </div>
			
			

  <p class="alert alert-info">* CAMPOS OBLIGATORIOS</p>

  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
    <input type="hidden" name="user_id" value="<?php echo $user->id;?>">
      <button type="submit" class="btn btn-primary">ACTUALIZAR CLIENTE</button>
    </div>
  </div>
</form>
	</div>
</div>
</section>