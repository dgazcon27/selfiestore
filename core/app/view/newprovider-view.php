<section class="content">
<div class="row">
	<div class="col-md-12">
	<h1> NUEVO PROVEEDOR</h1>
	<br>
  <p class="alert alert-info">* CAMPOS OBLIGATORIOS</p>
		<form class="form-horizontal" method="post" id="addproduct" action="index.php?view=addprovider" role="form">

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">RIF/RUT*</label>
    <div class="col-md-6">
      <input type="text" name="no" class="form-control" id="no" placeholder="INGRESAR EL RIF/RUT">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">NOMBRE*</label>
    <div class="col-md-6">
      <input type="text" name="name" class="form-control" id="name" placeholder="INGRESAR EL NOMBRE COMPLETO">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">DIRECCION*</label>
    <div class="col-md-6">
      <input type="text" name="address1" class="form-control" required id="address1" placeholder="INGRESAR LA DIRECCION">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">TELEFONO*</label>
    <div class="col-md-6">
      <input type="text" name="phone1" class="form-control" id="phone1" placeholder="INGRESAR EL NUMERO DE TELEFONO">
    </div>
  </div>
  <div class="form-group">
      <label for="specialties" class="col-lg-2 control-label">ESPECIALIDADES*</label>
      <div class="col-md-6">
        <textarea maxlength="255" name="specialties" id="specialties" cols="64" rows="10"></textarea>
      </div>
    </div>




  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <button type="submit" class="btn btn-primary">AGREGAR PROVEEDOR</button>
    </div>
  </div>
</form>
	</div>
</div>
	</section>