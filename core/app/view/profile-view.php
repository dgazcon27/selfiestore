<div class="content">
<?php 
  $user = UserData::getById($_SESSION["user_id"]);
  $person = null;
  if ($user->kind == 8 || $user->kind == 4) {
    $person = PersonData::getByUserId($user->id);
  }
?>

<div class="row">
  <div class="col-md-12">
  <h1>Mi Perfil</h1>
  <p class="alert alert-warning">* Solo se podra modificar la contraseña, para cambios de otros datos contactar a nuestro equipo de soporte.</p>

  <br>
    <form class="form-horizontal" method="post" id="addproduct" enctype="multipart/form-data" action="index.php?action=updateprofile" role="form">
  <h3>Datos de usuario</h3>
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
  <?php if ($user->kind == 4 || $user->kind == 8 && isset($person->ci)): ?>
    <div class="form-group">
      <label for="ci" class="col-lg-2 control-label">Identificacion</label>
      <div class="col-md-6">
        <input readonly value="<?php echo $person->ci;?>" type="text" name="ci" required class="form-control" id="ci" placeholder="Identificacion">
      </div>
    </div>
  <?php endif ?>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Nombre</label>
    <div class="col-md-6">
      <input readonly type="text" name="name" value="<?php echo $user->name;?>" class="form-control" id="name" placeholder="Nombre">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Apellido</label>
    <div class="col-md-6">
      <input readonly type="text" name="lastname" value="<?php echo $user->lastname;?>" required class="form-control" id="lastname" placeholder="Apellido">
    </div>
  </div>
  <?php if ($user->kind == 4 || $user->kind == 8 && isset($person->phone1)): ?>
    <div class="form-group">
        <label for="phone1" class="col-lg-2 control-label">Teléfono</label>
      <div class="col-md-6">
        <input readonly type="text" value="<?php echo $person->phone1;?>" name="phone1" required class="form-control" id="phone1" placeholder="Teléfono">
      </div>
    </div> 
  <?php endif ?>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Nombre de usuario</label>
    <div class="col-md-6">
      <input readonly type="text" name="username" value="<?php echo $user->username;?>" class="form-control" required id="username" placeholder="Nombre de usuario">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Email</label>
    <div class="col-md-6">
      <input readonly type="text" name="email" value="<?php echo $user->email;?>" class="form-control" id="email" placeholder="Email">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Contrase&ntilde;a</label>
    <div class="col-md-6">
      <input type="password" name="password" class="form-control" id="inputEmail1" placeholder="Contrase&ntilde;a">
<p class="help-block">La contrase&ntilde;a solo se modificara si escribes algo, en caso contrario no se modifica.</p>
    </div>
  </div>
    <?php if ($user->kind == 4 || $user->kind == 8): ?>
     <div class="col-lg-12">
      <?php if (isset($person->rif)): ?>
       <div class="col-lg-12">
         <h3>
          Datos de la empresa
        </h3>
       </div>
          <div class="form-group">
              <label for="rif" class="col-lg-2 control-label">RIF</label>
              <div class="col-md-6">
                <input readonly value="<?php echo $person->rif;?>" type="text" name="rif" class="form-control" id="rif" placeholder="RIF">
              </div>
            </div>
        <?php endif ?>
        <?php if (isset($person->company)): ?>
          <div class="form-group">
            <label for="company_name" class="col-lg-2 control-label">Nombre de la empresa</label>
            <div class="col-md-6">
              <input readonly type="text" value="<?php echo $person->company;?>" name="company_name" class="form-control" id="company_name" placeholder="Nombre de la empresa">
            </div>
          </div>
        <?php endif ?>
        <?php if (isset($person->phone2)): ?>
          <div class="form-group">
            <label for="company_phone" class="col-lg-2 control-label">Telefono de la empresa</label>
            <div class="col-md-6">
              <input readonly type="text" value="<?php echo $person->phone2;?>" name="company_phone" class="form-control" id="company_phone" placeholder="Telefono de la empresa">
            </div>
          </div>
        <?php endif ?>
        <?php if (isset($person->address2)): ?>
          <div class="form-group">
            <label for="company_address" class="col-lg-2 control-label">Direccion de la empresa</label>
            <div class="col-md-6">
              <input readonly value="<?php echo $person->address2;?>" type="text" name="company_address" class="form-control" id="company_address" placeholder="Direccion de la empresa">
            </div>
          </div>
        <?php endif ?>
     </div>
    <?php endif ?>





  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
    <input type="hidden" name="user_id" value="<?php echo $user->id;?>">
      <button type="submit" class="btn btn-success">Actualizar Perfil</button>
    </div>
  </div>
</form>
  </div>
</div>
</div>