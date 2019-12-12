<?php

$client = PersonData::getById($_GET["id"]);
$currentSell = $_GET["sell"];
$total = PaymentData::sumByClientId($client->id)->total;

?>

<section class="content">
<div class="row">
	<div class="col-md-12">
	<h1>REALIZAR PAGO</h1>
	<br>
  <div class="box box-primary">
  <table class="table">
  <tr>
  <td>
		<form class="form-horizontal" method="post" enctype="multipart/form-data" id="addpayment" action="index.php?action=addpayment" role="form">

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">CLIENTE</label>
    <div class="col-md-6">
      <input type="text" name="" id="product_code" class="form-control" id="barcode" placeholder="Cliente" value="<?php echo strtoupper($client->name." ".$client->lastname); ?>" readonly>
      <input type="hidden" name="client_id" class="form-control"  value="<?php echo $client->id; ?>">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">TOTAL ADEUDADO</label>
    <div class="col-md-6">
      <input type="text" name="" id="" class="form-control" placeholder="Total adeudado" value="$ <?php echo $total; ?>" readonly>

      <input type="hidden" name="" id="total" class="form-control"  value="<?php echo $total; ?>">
      <input type="hidden" name="sellid" id="sellid" class="form-control"  value="<?php echo $currentSell; ?>">

    </div>
  </div>


  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">PAGO A REALIZAR*</label>
    <div class="col-md-6">
      <input type="text" name="val" required id="val" class="form-control" placeholder="PAGO A REALIZAR">
    </div>
  </div>


  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <button type="submit" class="btn btn-primary">REALIZAR PAGO</button>
      <a href="./?view=credit" class="btn btn-danger">CANCELAR</a>
    </div>
  </div>
</form>
</td>
</tr>
</table>
</div>
	</div>
</div>

<script>
  $(document).ready(function(){
    $("#addpayment").submit(function(e){
      total = $("#total").val();
      val = $("#val").val();
      if( val!="" && val>0 ){
        console.log(total);
        if(parseFloat(val)<=parseFloat(total)){
          // procesamos
          go = confirm("Esta seguro que desea continuar?");
          if(!go){ e.preventDefault(); }
        }else{
        alert("No es posible ingresar un pago mayor a la deuda total.")
        e.preventDefault();          
        }

      }else{
        alert("Debes ingresar un valor mayor que 0.")
        e.preventDefault();
      }
    });
});

</script>
</section>