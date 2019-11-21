<section class="content">
<div class="row">
	<div class="col-md-12">
	<h1>NUEVO CLIENTE</h1>
	<br>
<div class="box box-primary"><br>
		<form class="form-horizontal" method="post" id="addproduct" action="index.php?view=addclient" role="form">

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">IDENTIFICACIÓN*</label>
    <div class="col-md-6">
      <input type="text" name="no" class="form-control" id="no" placeholder="Número de identificación" required="required">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">NOMBRE*</label>
    <div class="col-md-6">
      <input type="text" name="name" class="form-control" id="name" placeholder="Nombre Completo" required="required">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">DIRECCION</label>
    <div class="col-md-6">
      <input type="text" name="address1" class="form-control" id="address1" placeholder="Dirección">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">TELEFONO</label>
    <div class="col-md-6">
      <input type="text" name="phone1" class="form-control" id="phone1" placeholder="Teléfono">
    </div>
  </div>
 <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label" >ACTIVAR CREDITO</label>
    <div class="col-md-6">
<div class="checkbox">
    <label>
      <input type="checkbox" name="has_credit">
    </label>
  </div>
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">LIMITE DE CREDITO</label>
    <div class="col-md-6">
      <input type="text" name="credit_limit" class="form-control" id="" placeholder="Limite de credito">
    </div>
  </div>

 
  <p class="alert alert-info">* CAMPOS OBLIGATORIOS</p>

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