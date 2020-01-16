<style type="text/css">
  .button-submit {
    margin-left: 17.5%;
  }

  .error-border {
    border: solid 1px red;
  }

  .error-color {
    color:red;
  }
</style>

<section class="content">
  <div class="row">
  	<div class="col-md-12">
      <h1>Agregar Usuario</h1>

  		<form 
        class="form-horizontal" 
        enctype="multipart/form-data"  
        method="post" 
        id="adduser"
        action="index.php?view=adduser" 
        role="form">
        <input type="hidden" name="kind" value="<?php echo $_GET["kind"];?>">
        <!--  BEGIN SECTION CLIENTS DATA -->
        <div class="col-lg-12">
          <p class="alert alert-info">* Campos obligatorios</p>
          <div class="col-lg-12">
            <h3>
              Datos del Cliente
            </h3>
          </div>
            <div class="form-group">
              <label for="ci" class="col-lg-2 control-label">Identificacion*</label>
              <div class="col-md-6">
                <input type="text" name="ci" required class="form-control" id="ci" placeholder="Identificacion">
              </div>
            </div>
            <div class="form-group">
              <label for="name" class="col-lg-2 control-label">Nombre*</label>
              <div class="col-md-6">
                <input type="text" name="name" required class="form-control" id="name" placeholder="Nombre">
              </div>
            </div>
            <div class="form-group">
              <label for="lastname" class="col-lg-2 control-label">Apellido*</label>
              <div class="col-md-6">
                <input type="text" name="lastname" required class="form-control" id="lastname" placeholder="Apellido">
              </div>
            </div>
            <div class="form-group">
              <label for="phone1" class="col-lg-2 control-label">Teléfono*</label>
              <div class="col-md-6">
                <input type="text" name="phone1" required class="form-control" id="phone1" placeholder="Teléfono">
              </div>
            </div>  
            <div class="form-group">
              <label for="email" class="col-lg-2 control-label">Email</label>
              <div class="col-md-6">
                <input type="text" name="email" class="form-control" id="email" placeholder="Email" >
              </div>
            </div>
            
        </div>
        <!-- END SECTION CLIENTS DATA -->
        <!-- BEGIN SECTION USER DATA -->
        <div class="col-lg-12">
          <div class="col-lg-12">
            <h3>
              Datos de Cuenta
            </h3>
          </div>
          <div class="form-group">
            <label for="image" class="col-lg-2 control-label">Imagen (160x160)</label>
            <div class="col-md-6">
              <input type="file" name="image" id="image" placeholder="">
            </div>
          </div>
          <div class="form-group">
            <label for="username" required class="col-lg-2 control-label">Nombre de usuario*</label>
            <div class="col-md-6">
              <input type="text" name="username" class="form-control" required id="username" placeholder="Nombre de usuario">
              <label id="error_user" class="hidden">Este usuario ya se encuentra registrado</label>
            </div>
          </div>
          <div class="form-group">
            <label for="password" class="col-lg-2 control-label">Contrase&ntilde;a*</label>
            <div class="col-md-6">
              <input type="password" name="password" class="form-control" required id="password" placeholder="Contrase&ntilde;a">
            </div>
          </div>
          <div class="form-group">
            <label for="check_pass" class="col-lg-2 control-label">Verificar contrase&ntilde;a*</label>
            <div class="col-md-6">
              <input type="password" name="check_pass" class="form-control" required id="check_pass" placeholder="Verificar contrase&ntilde;a">
            </div>
          </div>
          <?php if(isset($_GET["kind"]) && $_GET["kind"]=="3"):?>
            <div class="form-group">
              <label for="comision" class="col-lg-2 control-label">Comision de ventas(%)</label>
              <div class="col-md-6">
                <input type="text" name="comision" class="form-control" id="inputEmail1" placeholder="Comision de ventas(%)">
              </div>
            </div>
          <?php endif; ?>
          <?php if(isset($_GET["kind"]) &&$_GET["kind"]=="2" || $_GET["kind"]=="3"):?>
            <div class="form-group">
              <label for="stock" class="col-lg-2 control-label">Almacen</label>
              <div class="col-md-6">
              <?php 
                $clients = StockData::getAll();
              ?>
              <select name="stock_id" class="form-control" required>
              <option value="">-- NINGUNO --</option>
              <?php foreach($clients as $client):?>
                <option value="<?php echo $client->id;?>"><?php echo $client->name;?></option>
              <?php endforeach;?>
                </select>
              </div>
            </div>
          <?php endif; ?>
        </div>
        <!-- END SECTION USER DATA -->
        <!-- BEGIN SECTION COMPANY DATA -->
        <div class="col-lg-12">
          <div class="col-lg-12">
            <h3>
              Datos de la empresa
            </h3>
          </div>
          <div class="form-group">
            <label for="rif" class="col-lg-2 control-label">RIF</label>
            <div class="col-md-6">
              <input type="text" name="rif" class="form-control" id="rif" placeholder="RIF">
            </div>
          </div>
          <div class="form-group">
            <label for="company_name" class="col-lg-2 control-label">Nombre de la empresa</label>
            <div class="col-md-6">
              <input type="text" name="company_name" class="form-control" id="company_name" placeholder="Nombre de la empresa">
            </div>
          </div>
          <div class="form-group">
            <label for="company_phone" class="col-lg-2 control-label">Telefono de la empresa</label>
            <div class="col-md-6">
              <input type="text" name="company_phone" class="form-control" id="company_phone" placeholder="Telefono de la empresa">
            </div>
          </div>
          <div class="form-group">
            <label for="company_address" class="col-lg-2 control-label">Direccion de la empresa</label>
            <div class="col-md-6">
              <input type="text" name="company_address" class="form-control" id="company_address" placeholder="Direccion de la empresa">
            </div>
          </div>
        </div>
        <!-- BEGIN SECTION COMPANY DATA -->
        <div class="form-group">
          <div class="col-lg-offset-2 col-lg-10 button-submit">
            <button class="btn btn-primary" id="btn-add">Agregar Usuario</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>
<script>
  $("#adduser").submit( function (e) {
    if (pass != check_pass) {
      alert("LAS CONTRASEÑAS DEBEN COINCIDIR")
      e.preventDefault();
    }

  })

  let timer = null;

  $("#username").keypress(function (a) {
    $("#username").removeClass('error-border');
    $("#error_user").addClass('hidden');
    $("#error_user").removeClass('error-color');
    $('#btn-add').removeAttr("disabled");
    clearTimeout(timer);

    timer = setTimeout(function (e) {
      let username = $("#username").val();
      if (username.trim().length > 0) {
        $.get("./?action=checkusername&username="+username,
          function (response) {
            if (response == "true") {
              $("#username").addClass('error-border')
              $("#error_user").removeClass('hidden');  
              $("#error_user").addClass('error-color')
              $("#username").focus()
              $("#btn-add").attr("disabled", true);
            }
          })
      }
    }, 1000)

      
  })
</script>