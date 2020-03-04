<section class="content">

<?php 
  $exchange = ExchangeData::getById($_GET['id']);
  
?>
<div class="row">
	<div class="col-md-12">
	<h1>EDITAR TASA DE CAMBIO</h1>
	<br>
  <div class="box box-primary">
  <table class="table">
  <tr><td>
		<form class="form-horizontal" method="post" id="updateexchange" action="index.php?action=addexchange" role="form">
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">DESCRIPCION*</label>
    <div class="col-md-6">
      <input value="<?php echo $exchange->description;?>" type="text" name="description" required class="form-control" id="description" placeholder="INGRESAR DESCRIPCION DE LA TASA DE CAMBIO">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">VALOR*</label>
    <div class="col-md-6">
      <input value="<?php echo $exchange->value;?>" type="text" name="value" required class="form-control" id="value" placeholder="VALOR">
    </div>
  </div>
    <input value="<?php echo $exchange->id;?>" type="text" hidden name="id" id="id">

  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <button type="submit" class="btn btn-primary">EDITAR TASA DE CAMBIO</button>
    </div>
  </div>
</form>
</td>
</tr>
</table>
</div>
	</div>
</div>
</section>