<?php $user = PersonData::getById($_GET["id"]);?>
<section class="content">
<div class="row">
	<div class="col-md-12">
	<h1>EDITAR PROVEEDOR</h1>
	<br>
  <p class="alert alert-info">* CAMPOS OBLIGATORIOS</p>
		<form class="form-horizontal" method="post" id="addproduct" action="index.php?view=updateprovider" role="form">

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">RFC/RUT*</label>
    <div class="col-md-6">
      <input type="text" name="no" value="<?php echo $user->no;?>" class="form-control" id="no" placeholder="INGRESAR EL RFC/RUT">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">NOMBRE*</label>
    <div class="col-md-6">
      <input type="text" name="name" value="<?php echo $user->name;?>" class="form-control" id="name" placeholder="INGRESAR EL NOMBRE COMPLETO">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">DIRECCION*</label>
    <div class="col-md-6">
      <input type="text" name="address1" value="<?php echo $user->address1;?>" class="form-control" required id="username" placeholder="INGRESAR LA DIRECCION">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">TELEFONO</label>
    <div class="col-md-6">
      <input type="tel" name="phone1"  value="<?php echo $user->phone1;?>"  class="form-control" id="inputEmail1" placeholder="INGRESAR EL NUMERO DE TELEFONO">
    </div>
  </div>

  <div class="form-group">
      <label for="specialties" class="col-lg-2 control-label">ESPECIALIDADES*</label>
      <div class="col-md-6">
        <textarea maxlength="255" name="specialties" id="specialties" cols="64" rows="10">
          <?php echo $user->specialties ?>
        </textarea>
      </div>
    </div>



  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
    <input type="hidden" name="user_id" value="<?php echo $user->id;?>">
      <button type="submit" class="btn btn-primary">ACTUALIZAR PROVEEDOR</button>
    </div>
  </div>
</form>
	</div>
</div>
	</section>