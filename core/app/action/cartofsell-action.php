
<?php
// $symbol = ConfigurationData::getByPreffix("currency")->val;
$iva_name = ConfigurationData::getByPreffix("imp-name")->val;
$iva_val = ConfigurationData::getByPreffix("imp-val")->val;
?>
<?php if(isset($_SESSION["errors"])):?>
<h2>Errores</h2>
<p></p>
<table class="table table-bordered table-hover">
<tr class="danger">
	<th>Codigo</th>
	<th>Producto</th>
	<th>Mensaje</th>
</tr>
<?php foreach ($_SESSION["errors"]  as $error):
$product = ProductData::getById($error["product_id"]);
?>
<tr class="danger">
	<td><?php echo $product->id; ?></td>
	<td><?php echo $product->name; ?></td>
	<td><b><?php echo $error["message"]; ?></b></td>
</tr>

<?php endforeach; ?>
</table>
<?php
unset($_SESSION["errors"]);
 endif; ?>


<!--- Carrito de compras :) -->
<?php if(isset($_SESSION["cart"])):
$total = 0;
$gasto = 0;
?>


<div class="row">
<div class="col-md-8">


<h2>LISTA DE VENTA</h2>
<div class="box box-primary">
<table class="table table-bordered table-hover">
<thead>
  <th style="width:30px;">CODIGO</th>
  <th style="width:30px;">IMAGEN</th>
<th>PRODUCTO</th>
  <th style="width:30px;">CANTIDAD</th>
  <th style="width:150px;">PRECIO UNITARIO</th>
  <th style="width:150px;">PRECIO TOTAL</th>
  <th ></th>
</thead>
<?php foreach($_SESSION["cart"] as $p):
$product = ProductData::getById($p["product_id"]);
?>
<tr >
  <td><?php echo $product->id; ?></td>
  <td><img src="storage/products/<?php echo $product->image;?>" style="width:80px;"></td>
  <td><?php echo strtoupper($product->name); ?></td>
  <td ><?php echo $p["q"]; ?></td>
  <td><b>$ <?php echo number_format($product->price_out,2,".",",");       $product->price_in; ?></b></td>
  <td><b>$ <?php  $pt = $product->price_out*$p["q"]; $total +=$pt; echo number_format($pt,2,".",",");      $pt = $product->price_in*$p["q"]; $gasto +=$pt;  number_format($pt,2,".",","); ?></b></td>
  <td style="width:30px;"><a id="clearcart-<?php echo $product->id; ?>" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-remove"></i> QUITAR</a>

<script>
  $("#clearcart-<?php echo $product->id; ?>").click(function(){
    $.get("index.php?view=clearcartproduct","product_id=<?php echo $product->id; ?>",function(data){
        $.get("./?action=cartofsell",null,function(data2){
          $("#cartofsell").html(data2);
        });
    });
  });
</script>

  </td>
</tr>

<?php endforeach; ?>
</table>
</div>



</div>
<div class="col-md-4">
		

<form method="post" class="form-horizontal" id="processsell" enctype="multipart/form-data" name="processsell">
<h2>RESUMEN DE VENTA</h2>
<input type="hidden" name="receive_by" value="<?php echo Core::$user->id?>">
<div class="row">
<div class="col-md-12">
<div>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#main" aria-controls="main" role="tab" data-toggle="tab">PRINCIPAL</a></li>
    <li role="presentation"><a href="#extra"  aria-controls="extra" role="tab" data-toggle="tab">EXTRA</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="main">
		
<div class="row">

<div class="col-md-12">
    <label class="control-label"></label>
    <div class="col-lg-12">
      <input type="hidden" name="invoice_code" class="form-control"  placeholder="No. Factura" value="<?php echo $gasto ?>">
    </div>
  </div>
  </div>
	
		
		
	<div class="row">

<div class="col-md-12">
    <label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TASA DE CAMBIO</label>
    <div class="col-lg-12">
      <input type="text" name="invoice_file" value="0" class="form-control" id="invoice_file" placeholder="TASA DE CAMBIO">
    </div>
  </div>
</div>			
		
		
		
<div class="row">

<div class="col-md-6">
    <label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NUEVO CLIENTE</label>
    <div class="col-lg-12">
      <a href="index.php?view=newclient2" class="form-control"><i class='fa fa-smile-o'></i>&nbsp;&nbsp;&nbsp;&nbsp;AGREGAR</a>
    </div>
  </div>

<div class="col-md-6">
    <label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SELECCIONA UN CLIENTE</label>
    <div class="col-lg-12">
    <?php 
$clients = PersonData::getClientsToSell();
    ?>
    <select name="client_id" id="client_id" class="form-control">
    <option value=""> NINGUNO </option>
    <?php foreach($clients as $client):?>
      <option value="<?php echo $client->id;?>"><?php echo strtoupper($client->name." ".$client->lastname);?></option>
    <?php endforeach;?>
      </select>
    </div>
  </div>
  </div>

<div class="row">

<div class="col-md-6">
    <label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PAGO</label>
    <div class="col-lg-12">
    <?php 
$clients = PData::getAll();
    ?>
    <select name="p_id" id="p_id" class="form-control">
    <?php foreach($clients as $client):?>
    	<?php if ($client->id != 3): ?>
      		<option value="<?php echo $client->id;?>"><?php echo strtoupper($client->name);?></option>
    	<?php endif ?>
    <?php endforeach;?>
      </select>
    </div>
  </div>
<div class="col-md-6">
    <label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ENTREGA</label>

    <div class="col-lg-12">
    <select name="d_id" class="form-control">
      <option value="1">ENTREGADO</option>
    </select>
    </div>
  </div>

</div>
			
		
<div class="row">

<div class="col-md-12">
    <label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FORMA DE PAGO</label>
    <div class="col-lg-12">
    <?php 
$clients = FData::getAll();
    ?>
    <select name="f_id" id="f_id" class="form-control">
    <?php foreach(FData::getAll() as $client):?>
    	<?php if ($client->id != 4): ?>
      		<option value="<?php echo $client->id;?>"><?php echo strtoupper($client->name);?></option>
    	<?php endif ?>
    <?php endforeach;?>
  		<option value="4">DUAL</option>
      </select>
    </div>
  </div>

</div>
		
<div class="row" id="form-ref">

	<div class="col-md-12">
		<label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NUMERO DE REFERENCIA</label>
		<div class="col-lg-12">
		  <input type="text" name="refe" value="0" class="form-control" id="refe" placeholder="NUMERO DE REFERENCIA DE TRANSFERENCIA">
		</div>
	  </div>
	
</div>	
		
		
	
<div class="row" id="pay_dual">

<div class="col-md-6">
    <label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DUAL EFECTIVO</label>
    <div class="col-lg-12">
      <input type="text" name="efe" class="form-control" required value="0" id="efe" placeholder="EFECTIVO">
    </div>
  </div>
<div class="col-md-6">
    <label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DUAL PUNTO DE VENTA</label>
    <div class="col-lg-12">
      <input type="text" name="pun" class="form-control" required value="0" id="pun" placeholder="PUNTO DE VENTA">
    </div>
  </div>
 <div class="col-md-6">
    <label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DUAL TRANSFERENCIA</label>
    <div class="col-lg-12">
      <input type="text" name="tra" value="0" class="form-control" id="tra" placeholder="TRANSFERENCIA">
    </div>
  </div>
<div class="col-md-6">
    <label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DUAL ZELLE</label>
    <div class="col-lg-12">
      <input type="text" name="zel" class="form-control" required value="0" id="zel" placeholder="ZELLE">
    </div>
</div>
<div class="col-md-6">
    <label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;VUELTO</label>
    <div class="col-lg-12">
      <input readonly="" type="text" name="change_sell" class="form-control" required value="0" id="change_sell" placeholder="0">
    </div>
</div>
<div class="col-md-6">
    <label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TIPO DE VUELTO</label>
    <div class="col-lg-12">
      <?php 
		$clients = FData::getAll();
		    ?>
		 <select name="type_change" id="type_change" class="form-control">
		 	<?php foreach(FData::getAll() as $client):?>
		 		<?php if ($client->id != 4 && $client->id != 5): ?>
				<option value="<?php echo $client->id;?>"><?php echo strtoupper($client->name);?></option>
		 		<?php endif ?>
			<?php endforeach;?>
		</select>
    </div>
</div>
</div>		
		
		
		
		
<div class="row">

<div class="col-md-6">
    <label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DESCUENTO</label>
    <div class="col-lg-12">
      <input type="text" name="discount" class="form-control" required value="0" id="discount" placeholder="DESCUENTO">
    </div>
  </div>
 <div class="col-md-6">
    <label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MONTO A CANCELAR</label>
    <div class="col-lg-12">
      <input type="text"  name="money" value="0" class="form-control" id="money" placeholder="EFECTIVO">
    </div>
  </div>
  </div>
		
    </div>
    <div role="tabpanel" class="tab-pane" id="extra">

		

		
		
		
<div class="row">

<div class="col-md-12">
    <div class="">
    <label class="control-label">INFORMACIÓN EXTRA</label>
      <textarea name="comment"  placeholder="INFORMACIÓN EXTRA" class="form-control" rows="10"></textarea>
    </div>
  </div>
  </div>
		
		
		

    </div>
  </div>

</div>
</div>
</div>





<input type="hidden" name="total" id="total" value="<?php echo $total; ?>" class="form-control" placeholder="Total">
<div class="clearfix"></div>
<br>
  <div class="row">
<div class="col-md-12">
<div class="box box-primary">
<table class="table table-bordered">
<tr>
  <td><p>SUBTOTAL</p></td>
  <td><p><b>$ <?php echo number_format($total*(1 - ($iva_val/100) ),2,'.',','); ?></b></p></td>
</tr>
<tr>
  <td><p><?php echo $iva_name." (".$iva_val."%) ";?></p></td>
  <td><p><b>$ <?php echo number_format($total*($iva_val/100),2,'.',','); ?></b></p></td>
</tr>
<tr>
  <td><p>TOTAL</p></td>
  <td><p><b>$ <?php echo number_format($total,2,'.',','); ?></b></p></td>
</tr>

</table>
</div>
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <div class="checkbox">
        <label>
          <input name="is_oficial" type="hidden" value="1">
        </label>
      </div>
    </div>
  </div>
<div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <div class="checkbox">
        <label>
    <a href="index.php?view=clearcart" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> CANCELAR</a>
        <button class="btn btn-primary"><i class="glyphicon glyphicon-usd"></i><i class="glyphicon glyphicon-usd"></i> FINALIZAR VENTA</button>
        </label>
      </div>
    </div>
  </div>
</form>



</div>
</div>




<script>
	$("#processsell").submit(function(e){

		discount = $("#discount").val();
    	p = $("#f_id").val();		
    	paymentType = $("#p_id").val();
    	client = $("#client_id").val();
		money = $("#money").val().replace(",",".");
		referenceText = $("#refe").val();
		//INICIO VARIABLES PARA DUAL
		efe = parseInt($("#efe").val());
		tra = parseInt($("#tra").val());
		zel = parseInt($("#zel").val());
		pun = parseInt($("#pun").val());
		var conditionOne = false;
		var numeroNormal = efe+tra+zel+pun;
		var regexE = new RegExp(/^[A-Za-z0-9]+$/g);
		//FIN VARIABLES PARA DUAL
		// procedemos
		cli=Array();
		<?php 
		foreach(PersonData::getClients() as $cli){
		  echo " cli[$cli->id]=$cli->has_credit ;";
		}
		?>
		if((paymentType==4 || paymentType==2))
		{
			conditionOne = true;
			if(client!="")
			{
				// si el cliente tiene credito entonces procedemos a hacer la venta a credito :D
				if(cli[client]==1){
					if(discount==""){ discount=0;}
					
					if(p==4 && parseFloat(numeroNormal) == parseFloat(money) ){
						go = confirm("ESTAS SEGURO DE ASIGNARLE CREDITO A ESTE CLIENTE POR: $"+( (parseFloat(<?php echo $total;?>) - parseFloat(discount)) - parseFloat(money)) );
						if(go){
							e.preventDefault();
							$.post("./index.php?action=processsell",$("#processsell").serialize(),function(data){
								$.get("./?action=cartofsell",null,function(data2){
									$("#cartofsell").html(data);
									$("#show_search_results").html("");
								  });
							});
						}
						else{e.preventDefault();}
					} else if(p!=4){
						e.preventDefault();
						go = confirm("ESTAS SEGURO DE ASIGNARLE CREDITO A ESTE CLIENTE POR: $"+( (parseFloat(<?php echo $total;?>) - parseFloat(discount)) - parseFloat(money)) );
						if(go){
							e.preventDefault();
							$.post("./index.php?action=processsell",$("#processsell").serialize(),function(data){
								$.get("./?action=cartofsell",null,function(data2){
									$("#cartofsell").html(data);
									$("#show_search_results").html("");
								  });
							});
						}
						else{e.preventDefault();}
					} else {
					  alert("EL MONTO A CANCELAR Y LOS DATOS DE PAGO NO COINCIDEN");
					  e.preventDefault();
					}
				}else{
				  // el cliente no tiene credito
				  alert("EL CLIENTE SELECCIONADO NO CUENTA CON CREDITO!");
				  e.preventDefault();
				}
			}else{
				// 
				alert("DEBE SELECCIONAR UN CLIENTE!");
				e.preventDefault();
			}
		}
    	if(money!="")
		{
			if(p!=4)
			{
				if(parseFloat(money) < parseFloat(<?php echo $total;?>-discount))
				{
					if(paymentType!=4 && paymentType!=2){
						alert("EFECTIVO INSUFICIENTE!");
						e.preventDefault();
					}
				}
				else
				{

					if(p!=1 && (referenceText==0 || referenceText==""))	{
						alert("LA REFERENCIA ES OBLIGATORIA");
						e.preventDefault();
					} else if (p!=1 && !regexE.test(referenceText.toString())) {
						alert("LA REFERENCIA NO DEBE POSEER CARACTERES ESPECIALES");
						e.preventDefault();
					} else if(conditionOne == false){
						if(discount==""){ discount=0;}
						go = confirm("CAMBIO: $"+(parseFloat(money)-(parseFloat(<?php echo $total;?>)-parseFloat(discount) ) ) );
						if(go){
							e.preventDefault();
							$.post("./index.php?action=processsell",$("#processsell").serialize(),function(data){
								$.get("./?action=cartofsell",null,function(data2){
									$("#cartofsell").html(data);
									$("#show_search_results").html("");
								  });
							});
						}
						else{e.preventDefault();}
					}
				}
    		}else if(p==4){ // usaremos credito
				if((parseFloat(money)<parseFloat((<?php echo $total;?>-discount)) || (numeroNormal < ((parseFloat(<?php echo $total;?>))-parseFloat(discount))) && (paymentType!=4 && paymentType!=2))){
					alert("PAGO INSUFICIENTE!");
					e.preventDefault();
				}
				else {
					if(parseFloat(numeroNormal) < parseFloat(money)){
						alert("PAGO INSUFICIENTE!");
						e.preventDefault();
					} else {
						if((referenceText==0 || referenceText=="")){
							alert("LA REFERENCIA ES OBLIGATORIA");
							e.preventDefault();
						} else if(referenceText.toString().trim().length < 6){
							alert("LA REFERENCIA DEBE POSEER AL MENOS 6 CARACTERES");
							e.preventDefault();
						} else if (!regexE.test(referenceText.toString())) {
							alert("LA REFERENCIA NO DEBE POSEER CARACTERES ESPECIALES");
							e.preventDefault();
						} else if(conditionOne == false){
							if(discount==""){
								discount=0;
							}
							let cambio_sell = (parseFloat(numeroNormal)-parseFloat(<?php echo $total;?>-discount)).toFixed(2)
							let change_sell = parseFloat($("#change_sell").val()).toFixed(2)
							if (cambio_sell > 0 && cambio_sell != change_sell) {
								alert("EL CAMBIO A SER DEVUELTO NO COINCIDE");
								e.preventDefault();
							} else {
								go = confirm("CAMBIO: $"+ cambio_sell);
								if(go){
									e.preventDefault();
									$.post("./index.php?action=processsell",$("#processsell").serialize(),function(data){
										$.get("./?action=cartofsell",null,function(data2){
											$("#cartofsell").html(data);
											$("#show_search_results").html("");
										  });
									});
								}else{e.preventDefault();}
							}
							
						}
					}
						
				}
			}
	    }
		else
		{
			alert("CAMPO DE PAGO VACIO")
			e.preventDefault();
	    }
	});

	$("#pay_dual").addClass("hidden")
	$("#form-ref").addClass("hidden")
	$("#f_id").change(function (a) {
		let pay = $("#f_id").val();
		if (pay == 4) {
			$("#pay_dual").removeClass("hidden")
			$("#form-ref").removeClass("hidden")
		}
		if (pay == 1) {
			$("#pay_dual").addClass("hidden")
			$("#form-ref").addClass("hidden")
		}
		if (pay == 2 || pay == 3 || pay == 5) {
			$("#form-ref").removeClass("hidden")
			$("#pay_dual").addClass("hidden")
		}
	})
	let total_ = parseFloat($("#total").val());

	$("#efe").change(function (argument) {

		let efe = parseFloat($("#efe").val());
		let pun = parseFloat($("#pun").val());
		let tra = parseFloat($("#tra").val());
		let zel = parseFloat($("#zel").val());
		let change_sell = (efe+pun+tra+zel)-total_;
		$("#change_sell").val(change_sell.toFixed(2));
	})
	$("#pun").change(function (argument) {
		let efe = parseFloat($("#efe").val());
		let pun = parseFloat($("#pun").val());
		let tra = parseFloat($("#tra").val());
		let zel = parseFloat($("#zel").val());
		let change_sell = (efe+pun+tra+zel)-total_;
		$("#change_sell").val(change_sell.toFixed(2));
	})
	$("#tra").change(function (argument) {
		let efe = parseFloat($("#efe").val());
		let pun = parseFloat($("#pun").val());
		let tra = parseFloat($("#tra").val());
		let zel = parseFloat($("#zel").val());
		let change_sell = (efe+pun+tra+zel)-total_;
		$("#change_sell").val(change_sell.toFixed(2));
	})
	$("#zel").change(function (argument) {
		let efe = parseFloat($("#efe").val());
		let pun = parseFloat($("#pun").val());
		let tra = parseFloat($("#tra").val());
		let zel = parseFloat($("#zel").val());
		let change_sell = (efe+pun+tra+zel)-total_;
		$("#change_sell").val(change_sell.toFixed(2));
	})

</script>
</div>
</div>

<?php endif; ?>
