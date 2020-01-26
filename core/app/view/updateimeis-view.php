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
      <h1>Agregar IMEIS</h1>

  		<form 
        class="form-horizontal" 
        enctype="multipart/form-data"  
        method="post" 
        id="updateimei"
        action="index.php?action=updateimei" 
        role="form">
        <!--  BEGIN SECTION CLIENTS DATA -->
          <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
          <div class="col-lg-12">
              <div class="form-group">
                <div class="col-md-12" style="text-align: center;">
                  <textarea required="" id="imei" name="comment" cols="100" rows="10"></textarea>
                </div>
              </div>
          </div>
        <div class="form-group">
          <div class="col-lg-12" style="text-align: center;">
            <button class="btn btn-primary" id="btn-add">Agregar IMEIS</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>
