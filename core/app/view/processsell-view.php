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
	<?php
		$person = PersonData::getById($sell->person_id)
	?>
    <label class="control-label">Cliente: </label> <h4><?php echo $person->name." ".$person->lastname ?></h4>
</div>
<div class="col-md-4">
    <label class="control-label">&nbsp;</label>
<div class="col-lg-12">
    <button class="btn btn-primary btn-block" id="process_sell"><i class="glyphicon glyphicon-usd"></i><i class="glyphicon glyphicon-usd"></i> Convertir en venta</button>
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
    <td><?php echo $operation->q_approved ;?></td>
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

    $("#process_sell").click(function () {
    	let procss = confirm("¿DESEA CONVERTIR EN UNA VENTA?");
    	if (procss) {
    		$.get("./?action=ordertosell&id=<?echo $_GET['id']?>",function(data){
    			if (data == "success") {
    				window.location='index.php?view=orders-approved';
    			}
    		});
    	}
    })
</script>