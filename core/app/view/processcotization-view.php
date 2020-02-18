<section class="content">

<h1>Procesar Cotizacion</h1>
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


<form method="post" class="form-horizontal" id="processcotization" action="index.php?action=processcotization">
<input type="hidden" name="cotization_id" value="<?php echo $_GET["id"]; ?>">
<input type="hidden" name="total" value="<?php echo $total; ?>" class="form-control" placeholder="Total">
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
    <label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NUEVO CLIENTE</label>
    <div class="col-lg-12">
      <a href="index.php?view=newclient2&from=<?php echo $_GET['id'] ?>" class="form-control"><i class='fa fa-smile-o'></i>&nbsp;&nbsp;&nbsp;&nbsp;AGREGAR</a>
    </div>
  </div>

<div class="col-md-3">
    <label class="control-label">Cliente</label>
    <div class="col-lg-12">
    <?php
        $clients = [];
        if (isset($sell->person_id)) {
            $person = PersonData::getById($sell->person_id);
            echo '<input type="hidden" id="client_id" name="client_id" value="'.$sell->person_id.'">';
            echo '<h4>'.$person->name." ".$person->lastname.'</h4>';
        } else {
            $clients = PersonData::getClients();
        } 
    ?>
    <?php if (count($clients) > 0): ?>
        <select id="client_id" name="client_id" class="form-control">
        <option value="">-- NINGUNO --</option>
        <?php foreach($clients as $client):?>
            <option value="<?php echo $client->id;?>"><?php echo $client->name." ".$client->lastname;?></option>
        <?php endforeach;?>
            </select>
    <?php endif ?>
    </div>

  </div>
<div class="col-md-3">
    <label class="control-label">&nbsp;</label>
<div class="col-lg-12">
    <button class="btn btn-primary btn-block"><i class="glyphicon glyphicon-usd"></i><i class="glyphicon glyphicon-usd"></i> Procesar</button>
</div>
</div>
<div class="col-md-3 hidden">
    <label class="control-label">Descuento</label>
    <div class="col-lg-12">
      <input type="text" name="discount" class="form-control" required value="0" id="discount" placeholder="Descuento">
    </div>
  </div>
 <div class="col-md-3 hidden">
    <label class="control-label">Efectivo</label>
    <div class="col-lg-12">
      <input type="text" name="money" required class="form-control" id="money" placeholder="Efectivo">
    </div>
  </div>
  </div>
<div class="row">

<div class="col-md-4 hidden">
    <label class="control-label">Pago</label>
    <div class="col-lg-12">
    <?php 
$clients = PData::getAll();
    ?>
    <select name="p_id" class="form-control">
    <?php foreach($clients as $client):?>
        <option value="<?php echo $client->id;?>"><?php echo $client->name;?></option>
    <?php endforeach;?>
        </select>
    </div>
  </div>

</div>
<input hidden type="text" name="d_id" value="5">
<?php if (isset($_SESSION['is_admin'])): ?>
    <input hidden type="text" name="cotization_admin" value="true">
<?php endif ?>
</form>
<br>




<div class="box box-primary">
<br><table class="table table-bordered table-hover">
    <thead>
        <th>Codigo</th>
        <th>Cantidad solicitada</th>
        <!-- <th>Cantidad a despachar</th> -->
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
    <!-- <td><input class="inputs-type" data-id="<?php //echo $operation->id; ?>" data-q="<?php //echo $operation->q; ?>" type="text" name="q_approved" value="<?php// echo $operation->q ;?>"></td> -->
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
</div>
<br><br><h1 id="total_price">Total: $ <?php echo number_format($total,2,'.',','); ?></h1>
    <?php

?>  
<?php else:?>
    501 Internal Error
<?php endif; ?>
</section>
<script>
    $("#money").val(<?php echo $total;?>)
    $(".inputs-type").bind('keypress',function (ev) {
        let elm = ev.currentTarget;
        let id = $(elm).data('id');
        let q_approved = $(elm).data('q_approved');
        let price_tx = $('.total_price_'+id)[0];
        let price = $('#price_out_'+id).data('price');
        
    })

    $("#processcotization").submit(function(e){
        if ($("#client_id").val() != undefined) {
            discount = $("#discount").val();
            money = $("#money").val();
            client_id = $("#client_id").val();
            let inputs = $('.inputs-type');
            obj = [];
            for (var i = 0; i < inputs.length; i++) {
                let elem = inputs[i];
                obj.push({
                    id:$(elem).data('id'),
                    q_ap:$(elem).data('q'),

                })
            }
            $('#op-q').val(JSON.stringify(obj));

            if(money<(<?php echo $total;?>-discount)){
                alert("Efectivo insificiente!");
                e.preventDefault();
            }else if (client_id == "") {
                alert("Debe seleccionar un cliente");
                e.preventDefault();
            }else{
                if(discount==""){ discount=0;}
                go = confirm("Cambio: $"+(money-(<?php echo $total;?>-discount ) ) );
                if(go){}
                    else{e.preventDefault();}
            }
        } else {
            e.preventDefault();
            alert("Debes seleccionar un cliente o agregar uno nuevo.")
        }
    });
</script>