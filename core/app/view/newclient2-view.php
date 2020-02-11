<section class="content">
<div class="row">
	<div class="col-md-12">
	<h1>NUEVO CLIENTE</h1>
	<br>
  <p class="alert alert-info">* CAMPOS OBLIGATORIOS</p>
<div class="box box-primary" style="height: 350px;"><br>

		<form class="form-horizontal" method="post" id="addproduct" action="index.php?view=addclient" role="form">
      <?php if (isset($_GET['from'])): ?>
        <input type="hidden" name="from" value="<?php echo $_GET['from']; ?>">
      <?php endif ?>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">IDENTIFICACION*</label>
    <div class="col-md-6">
      <input type="text" name="no" class="form-control" id="no" placeholder="INGRESAR EL NUMERO DE IDENTIFICACION" required="required">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">NOMBRE*</label>
    <div class="col-md-6">
      <input type="text" name="name" class="form-control" id="name" placeholder="INGRESAR EL NOMBRE" required="required">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">APELLIDO*</label>
    <div class="col-md-6">
      <input type="text" name="lastname" class="form-control" id="lastname" placeholder="INGRESAR EL APELLIDO" required="required">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">DIRECCION</label>
    <div class="col-md-6">
      <input type="text" name="address1" class="form-control" id="address1" placeholder="INGRESAR LA DIRECCION">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">TELEFONO</label>
    <div class="col-md-6">
      <input type="text" name="phone1" class="form-control" id="phone1" placeholder="INGRESAR EL NUMERO DE TELEFONO">
    </div>
  </div>



 

  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <button type="submit" class="btn btn-primary" target="_blank" onclick="window.location.href='./?view=sell'">AGREGAR CLIENTE</button>
    </div>
  </div>
</form>
</div>
	</div>
</div>
</section>