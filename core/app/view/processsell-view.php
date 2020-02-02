<section class="content">

<h1>Realizar Venta</h1>
<?php if(isset($_GET["id"]) && $_GET["id"]!=""):?>
<?php
$sell = SellData::getById($_GET["id"]);
$operations = OperationData::getAllProductsBySellId($_GET["id"]);
$total = 0;

foreach($operations as $operation){
    $product  = $operation->getProduct();
    $total+=$operation->q*$product->price_out;
}
//$total;
?>


<input type="hidden" name="cotization_id" value="<?php echo $_GET["id"]; ?>">
<input type="hidden" name="total" id="total_to_pay" value="<?php echo $total; ?>" class="form-control" placeholder="Total">
<input type="hidden" name="op-q" id="op-q">

<div class="row">
<div class="col-md-3">
    <label class="control-label">Almacen</label>
    <div class="col-lg-12">
    <h4 class=""><?php 
    echo StockData::getPrincipal()->name;
    ?></h4>
    </div>
  </div>

<div class="col-md-3">
	<?php
		$person = PersonData::getById($sell->person_id)
	?>
    <label class="control-label">Cliente: </label> <h4><?php echo $person->name." ".$person->lastname ?></h4>
</div>
</div>
<br>

<div class="box box-primary">
<br><table class="table table-bordered table-hover">
    <thead>
        <th>Codigo</th>
        <th>Cantidad a despachar</th>
        <th>Nombre del Producto</th>
        <th>Precio</th>
        <th>Precio Unitario</th>
        <th>Total</th>

    </thead>
<?php
    foreach($operations as $operation){
        $product  = $operation->getProduct();
?>
<tr>
    <td><?php echo $product->id ;?></td>
    <td><?php echo $operation->q ;?></td>
    <td><?php echo $product->name ;?></td>
    <td>$ <?php echo number_format($product->price_in,2,".",",") ;?></td>
    <td> <div data-price="<? echo $product->price_out;?>" id="price_out_<? echo $operation->id;?>" > $ <?php echo number_format($product->price_out,2,".",",") ;?> </div> </td>
    <td class="total_price_<?echo $operation->id;?> "><b>$ <?php echo number_format($operation->q*$product->price_out,2,".",",");
    //$total+=$operation->q*$product->price_out;?></b></td>
</tr>
<?php
    }
    ?>
</table>

<div class="row" style="padding: 0 20px;">
    <div class="col-lg-4">
        <br><br><h1 id="total_price">Total: $ <?php echo number_format($total,2,'.',','); ?></h1>
    </div>
    <div class="col-lg-8">
        <form action="index.php?action=ordertosell" class="col-lg-12" method="post" class="form-horizontal" id="processsell" enctype="multipart/form-data" name="processsell">
            <h2>RESUMEN DE VENTA</h2>
            <input type="hidden" name="sell_id" value="<?php echo $_GET['id']?>">
            <div class="row">
            <div class="col-md-12">
            <div>

              <!-- Tab panes -->
              <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="main">
                    
            <div class="row">

            <!-- <div class="col-md-12">
                <label class="control-label"></label>
                <div class="col-lg-12">
                  <input type="hidden" name="invoice_code" class="form-control"  placeholder="No. Factura" value="<?php //echo $gasto ?>">
                </div>
            </div> -->
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
                  <option value="<?php echo $client->id;?>"><?php echo strtoupper($client->name);?></option>
                <?php endforeach;?>
                  </select>
                </div>
              </div>

            </div>
                    
            <div class="row">

                <div class="col-md-12">
                    <label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NUMERO DE REFERENCIA</label>
                    <div class="col-lg-12">
                      <input type="text" name="refe" value="0" class="form-control" id="refe" placeholder="NUMERO DE REFERENCIA DE TRANSFERENCIA">
                    </div>
                  </div>
                
            </div>  
                    
                    
                
            <div class="row">

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
                  <input type="text" name="money" value="0" class="form-control" id="money" placeholder="EFECTIVO">
                </div>
              </div>
              </div>
                    
                </div>
              </div>

            </div>
            </div>
            </div>
            <input type="hidden" name="total" value="<?php echo $total; ?>" class="form-control" placeholder="Total">
            <input type="hidden" name="p_id" value="1" class="form-control" placeholder="Total">
            <div class="clearfix"></div>
            <br>
              <div class="row">
            <div class="col-md-12">
            <div class="box box-primary">

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

</div>

    <?php

?>  
<?php else:?>
    501 Internal Error
<?php endif; ?>


</section>
<script>
    // $("#money").val(<?php //echo $total;?>)
    $(".inputs-type").bind('keypress',function (ev) {
        let elm = ev.currentTarget;
        let id = $(elm).data('id');
        let q_approved = $(elm).data('q_approved');
        let price_tx = $('.total_price_'+id)[0];
        let price = $('#price_out_'+id).data('price');
        
    })

    $("#processsell").submit(function (e) {
    	let procss = confirm("¿DESEA CONVERTIR EN UNA VENTA?");
    	if (!procss) {
            // $.post("./?action=ordertosell",$("#addtocart").serialize(),function(data){
            e.preventDefault();
            // });
        } else {
            discount = $("#discount").val();
            p = $("#f_id").val();       
            money = parseInt($("#money").val());
            referenceText = $("#refe").val();
            //INICIO VARIABLES PARA DUAL
            efe = parseInt($("#efe").val()) ? parseInt($("#efe").val()) : 0;
            tra = parseInt($("#tra").val()) ? parseInt($("#tra").val()) : 0;
            zel = parseInt($("#zel").val()) ? parseInt($("#zel").val()) : 0;
            pun = parseInt($("#pun").val()) ? parseInt($("#pun").val()) : 0;
            total_to_pay = parseInt($("#total_to_pay").val())
            var conditionOne = false;
            var numeroNormal = efe+tra+zel+pun;
            if ((p == 1 || p == 2 || p == 3 || p == 5) && money < total_to_pay) {
                alert("MONTO INSUFICIENTE")
                e.preventDefault();
            } else if (p == 4 && numeroNormal < total_to_pay) {
                alert("MONTO INSUFICIENTE")
                e.preventDefault();
            } else {
                if (p != 1) {
                    if (referenceText==0 || referenceText=="") {
                        alert("LA REFERENCIA ES OBLIGATORIA");
                        e.preventDefault();
                    } else {
                        if (p == 1 || p == 2 || p == 3 || p == 5) {
                            alert("CAMBIO: $"+( parseInt(money) - parseInt(<?php echo $total;?>-discount ) ) );
                        }
                    }
                } else {
                    alert("CAMBIO: $"+( parseInt(money) - parseInt(<?php echo $total;?>-discount ) ) );
                }
            }
        }
    })
</script>