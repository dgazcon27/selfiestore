<div class="content">
<?php $user = UserData::getById($_GET["id"]);?>
<?php 
  $person = null;
  if ($_SESSION['is_admin']) {
    $person = PersonData::getByUserId($user->id);
  }
?>
  <?php Core::back(); ?>
<div class="row">
	<div class="col-md-12">
	<h1>Editar Usuario</h1>
	<br>
		<form class="form-horizontal" method="post" id="updateuser" enctype="multipart/form-data" action="index.php?view=updateuser" role="form">
      <input type="hidden" name="kind" value="<?php echo $user->kind;?>">
      <div class="col-lg-12">
        <h3>Datos de usuario</h3>
      </div>
      <div class="form-group">
        <label for="inputEmail1" class="col-lg-2 control-label">Imagen (160x160)</label>
        <div class="col-md-6">
          <?php
            if($user->image!=""){
              $url = "storage/profiles/".$user->image;
              if(file_exists($url)){
                echo "<img src='$url' style='width:80px;'>";
              }
            }
          ?>
        <br><br>
          <input type="file" name="image" id="image" placeholder="">
        </div>
      </div>
      <?php if (isset($person) && ($user->kind == 4 || $user->kind == 8 || $user->kind == 5)): ?>
        <div class="form-group">
          <label for="ci" class="col-lg-2 control-label">Identificacion</label>
          <div class="col-md-6">
            <input value="<?php echo $person->ci;?>" type="text" name="ci" required class="form-control" id="ci" placeholder="Identificacion">
          </div>
        </div>
      <?php endif ?>
      <div class="form-group">
        <label for="username" required class="col-lg-2 control-label">Nombre de usuario*</label>
        <div class="col-md-6">
          <input readonly type="text" value="<?php echo $user->username;?>" name="username" class="form-control" required id="username" placeholder="Nombre de usuario">
        </div>
      </div>
      <?php if ($user->kind != 1): ?>
        <div class="form-group">
          <label for="inputEmail1" class="col-lg-2 control-label">Nombre*</label>
          <div class="col-md-6">
            <input type="text" required name="name" value="<?php echo $user->name;?>" class="form-control" id="name" placeholder="Nombre">
          </div>
        </div>
        <div class="form-group">
          <label for="inputEmail1" class="col-lg-2 control-label">Apellido*</label>
          <div class="col-md-6">
            <input type="text" required name="lastname" value="<?php echo $user->lastname;?>" class="form-control" id="lastname" placeholder="Apellido">
          </div>
        </div>
      <?php endif ?>
      <?php if (isset($person) && ($user->kind == 4 || $user->kind == 8 || $user->kind == 5)): ?>
        <div class="form-group">
            <label for="phone1" class="col-lg-2 control-label">Teléfono</label>
          <div class="col-md-6">
            <input type="text" value="<?php echo $person->phone1;?>" name="phone1" required class="form-control" id="phone1" placeholder="Teléfono">
          </div>
        </div> 
      <?php endif ?>
      <?php if ($user->kind != 1): ?>
        <div class="form-group">
          <label for="inputEmail1" class="col-lg-2 control-label">Email*</label>
          <div class="col-md-6">
            <input type="text" required name="email" value="<?php echo $user->email;?>" class="form-control" id="email" placeholder="Email">
          </div>
        </div>
      <?php endif ?>
<?php if($user->kind==3):?>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Comision de ventas(%)</label>
    <div class="col-md-6">
      <input type="text" value="<?php echo $user->comision; ?>" name="comision" class="form-control" id="inputEmail1" placeholder="Comision de ventas(%)">
    </div>
  </div>
<?php endif; ?>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Contrase&ntilde;a</label>
    <div class="col-md-6">
      <input type="password" name="password" class="form-control" id="password" placeholder="Contrase&ntilde;a">
<p class="help-block">La contrase&ntilde;a solo se modificara si escribes algo, en caso contrario no se modifica.</p>
    </div>
  </div>
<?php if($user->kind==2 || $user->kind==3 || $user->kind==8 ):?>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Almacen</label>
    <div class="col-md-6">
    <?php 
         $clients = StockData::getAll();
      ?>
      <select name="stock_id" class="form-control" required>
      <option value="">-- NINGUNO --</option>
      <?php foreach($clients as $client):?>
        <option value="<?php echo $client->id;?>" <?php if($client->id==$user->stock_id){ echo "selected"; }?>><?php echo $client->name;?></option>
      <?php endforeach;?>
        </select>
      </div>
    </div>
  <?php endif; ?>
  <?php if (isset($person) && ($user->kind == 4 || $user->kind == 8)): ?>
    <div class="col-lg-12">
      <h3>
        Datos de la empresa
      </h3>
    </div>
      <div class="form-group">
        <label for="rif" class="col-lg-2 control-label">RIF</label>
        <div class="col-md-6">
          <input value="<?php echo $person->rif;?>" type="text" name="rif" class="form-control" id="rif" placeholder="RIF">
        </div>
      </div>
      <div class="form-group">
        <label for="company_name" class="col-lg-2 control-label">Nombre de la empresa</label>
        <div class="col-md-6">
          <input type="text" required value="<?php echo $person->company;?>" name="company_name" class="form-control" id="company_name" placeholder="Nombre de la empresa">
        </div>
      </div>
      <div class="form-group">
        <label for="company_phone" class="col-lg-2 control-label">Telefono de la empresa</label>
        <div class="col-md-6">
          <input type="text" required value="<?php echo $person->phone2;?>" name="company_phone" class="form-control" id="company_phone" placeholder="Telefono de la empresa">
        </div>
      </div>
      <div class="form-group">
        <label for="company_address" class="col-lg-2 control-label">Direccion de la empresa</label>
        <div class="col-md-6">
          <input required value="<?php echo $person->address2;?>" type="text" name="company_address" class="form-control" id="company_address" placeholder="Direccion de la empresa">
        </div>
      </div>
  <?php endif ?>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label" >Esta activo</label>
    <div class="col-md-6">
      <div class="checkbox">
        <label>
          <input type="checkbox" name="status" <?php if($user->status){ echo "checked";}?>> 
        </label>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
  <p class="text-info">* Campos obligatorios</p>
    <input type="hidden" name="user_id" value="<?php echo $user->id;?>">
      <button type="submit" class="btn btn-success">Actualizar Usuario</button>
    </div>
  </div>
</form>
	</div>
</div>
</div>
