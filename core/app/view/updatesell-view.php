<?php
$sell = SellData::getById($_GET["id"]);
?>
<style type="text/css">
  .list-products {
      margin-top: 20px; 
  }
</style>
<section class="content">

<h1>
  RESUMEN DE VENTA #<?php $acumulador = 100000; $code = $acumulador+$sell->ref_id; echo $code; ?>
  <a id="change_sell" class="btn btn-warning">EDITAR VENTA <i class="fa fa-pencil"></i></a>
  <a id="cancel_change" class="btn btn-danger hidden">CANCELAR <i class="fa fa-ban"></i></a>

</h1>

<?php if(isset($_GET["id"]) && $_GET["id"]!=""):?>
<?php
$operations = OperationData::getAllProductsBySellId($_GET["id"]);
$total = 0;
$costo = 0;
$gasto = 0;
?>
<?php
//if($product->kind==1){
if(isset($_COOKIE["selled"]) && isset($_SESSION['is_admin'])){
  foreach ($operations as $operation) {
//    print_r($operation);
    $qx = OperationData::getQByStock($operation->product_id,StockData::getPrincipal()->id);
    // print "qx=$qx";
      $p = $operation->getProduct();
    if($p->kind==1&&$qx==0){
      echo "<p class='alert alert-danger'>El producto <b style='text-transform:uppercase;'> $p->name</b> no tiene existencias en inventario.</p>";      
    }else if($p->kind==1&&$qx<=$p->inventary_min/2){
      echo "<p class='alert alert-danger'>El producto <b style='text-transform:uppercase;'> $p->name</b> tiene muy pocas existencias en inventario.</p>";
    }else if($p->kind==1&&$qx<=$p->inventary_min){
      echo "<p class='alert alert-warning'>El producto <b style='text-transform:uppercase;'> $p->name</b> tiene pocas existencias en inventario.</p>";
    }
  }
  setcookie("selled","",time()-18600);
}

?>
<div class="row">
  <div id="search_box" >
    <div class="col-md-12 col-lg-12 hidden" id="search_bar">
      <div class="row">
        <div class="col-md-8">
          <input type="hidden" name="view" value="sell">
          <input type="text" id="product_name" name="product_name" class="form-control" placeholder="NOMBRE DEL PRODUCTO O CODIGO DE BARRA">
        </div>

        <div class="col-md-2">
          <a id="search_button" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i> BUSCAR</a>
        </div>
      </div>
      <div id="search_response" class="row list-products hidden">
        <div class="col-md-10">
          <div class="box">
            <table id="search_products" class="table table-bordered table-hover">
              
            </table>
          </div>
        </div>
      </div>

      <div class="row list-products">
        <div class="col-md-10">
          <div class="box">
            <table id="products" class="table table-bordered table-hover">
              
            </table>
          </div>
        </div>
      </div>
    </div>
    
  </div>
<div class="col-md-8"  id="box_info_sell">
<div class="box box-primary">
<table class="table table-bordered">
<?php 

if(isset($sell->person_id)){
  $client = PersonData::getById($sell->person_id);
} else {
  $client = null;
}


?>

<?php if (isset($client)): ?>
  <tr>
    <td style="width:150px;">CLIENTE</td>
    <td><?php echo strtoupper($client->name." ".$client->lastname);?></td>
  </tr>
<?php endif ?>

<?php if($sell->receive_by!=""):
$seller = UserData::getById($sell->receive_by);
?>
<?php if (isset($_SESSION['is_admin']) && $sell->is_cotization == 0): ?>
  <tr>
    <td>ATENDIDO POR</td>
    <td>
      <?php echo strtoupper($seller->name." ".$seller->lastname);?>
    </td>
  </tr>
  
<?php endif ?>
<?php endif; ?>
</table>
</div>
<br>
<div class="box box-primary">
<table class="table table-bordered table-hover">
  <thead>
    <th class="visible-xs">#</th>
  <th>IMAGEN</th>
  <th>NOMBRE DEL PRODUCTO</th>
    <th class="hidden-xs">CANTIDAD</th>
    <th class="visible-xs">C.</th>
    <th>PRECIO UNITARIO</th>
    <th>TOTAL</th>

  </thead>
<?php
  foreach($operations as $operation){
    $product  = $operation->getProduct();
?>
<tr>
  <td><img src="storage/products/<?php echo $product->image;?>" style="width:40px;"></td>
  <td><?php echo $product->name ;?></td>
  <td><?php echo $operation->q ;?></td>
  
  
  <td>$ <?php echo number_format($product->price_out,2,".",",") ;?></td>
  <td><b>$ <?php echo number_format($operation->q*$product->price_out,2,".",",");$total+=$operation->q*$product->price_out;
    
    
  $costo+=$operation->q*$product->price_in;  
    
    
    ?></b></td>
</tr>
<?php
  }
  ?>
</table>
</div>
<br><br>
<div class="row">
<div class="col-md-4">
  
  
<?php if($sell->person_id!=""):
$credit=PaymentData::sumByClientId($sell->person_id)->total;

?>

<?php endif;?>  
  
  
<div class="box box-primary">
<table class="table table-bordered">
  <tr>
    <td><h4>DESCUENTO:</h4></td>
    <td><h4>$ <?php echo number_format($sell->discount,2,'.',','); ?></h4></td>
  </tr>
  <tr>
    <td><h4>SUBTOTAL:</h4></td>
    <td><h4>$ <?php echo number_format($total,2,'.',','); ?></h4></td>
  </tr>
  <tr>
    <td><h4>TOTAL:</h4></td>
    <td><h4>$ <?php echo number_format($total-  $sell->discount,2,'.',','); ?></h4></td>
  </tr>
</table>
</div>


</div>
</div>


</div>
  <div class="col-md-4" id="pay_box">
    <form method="post" class="form-horizontal" action="./?action=changesell" id="processsell" enctype="multipart/form-data">
    <div class="row">
    <div class="col-md-12">
    <div>

      <!-- Nav tabs -->
      <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#main" aria-controls="main" role="tab" data-toggle="tab">Principal</a></li>
        <li role="presentation"><a href="#extra"  aria-controls="extra" role="tab" data-toggle="tab">Extra</a></li>
      </ul>

      <!-- Tab panes -->
      <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="main">

    <div class="row">

    <div class="col-md-12">
        <label class="control-label">CLIENTE</label>
        <div class="col-lg-12">
        <?php 
    $clients = PersonData::getClients();
        ?>
        <select name="client_id" id="client_id" class="form-control">
        <option value="">NINGUNO</option>
        <?php foreach($clients as $client):?>
          <option value="<?php echo $client->id;?>" <?php if($client->id==$sell->person_id){ echo "selected"; }?>><?php echo strtoupper($client->name." ".$client->lastname);?></option>
        <?php endforeach;?>
          </select>
        </div>
      </div>
      </div>


        <label class="control-label">FORMA DE PAGO</label>
        <div class="col-lg-12">
        <?php 
    $clients = FData::getAll();
        ?>
        <select name="f_id" id="p_id" class="form-control">
        <?php foreach(FData::getAll() as $client):?>
          <option value="<?php echo $client->id;?>" <?php if($client->id==$sell->f_id){ echo "selected"; }?>><?php echo strtoupper($client->name);?></option>
        <?php endforeach;?>
          </select>
        </div>
      
      
        <div class="row">

        <div class="col-md-12">
            <label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;REFERENCIA</label>
            <div class="col-lg-12">
              <input type="text" name="refe" value="<?php echo $sell->refe;?>" class="form-control" id="refe" placeholder="NUMERO DE REFERENCIA DE TRANSFERENCIA">
            </div>
          </div>
        </div>
      

        </div>
        <div role="tabpanel" class="tab-pane" id="extra">

    <div class="row">

    <div class="col-md-12">
        <label class="control-label">ARCHIVO FACTURA</label>
        <div class="col-lg-12">
        <?php if($sell->invoice_file!=""):?>
          <a href="./storage/invoice_files/<?php echo $sell->invoice_file;?>" target="_blank" class="btn btn-default"><i class="fa fa-file"></i> ARCHIVO FACTURA (<?php echo $sell->invoice_file; ?>)</a>
          <br><br>
        <?php endif; ?>
          <input type="file" name="invoice_file"  placeholder="Archivo Factura">
        </div>
      </div>
      </div>

    <div class="row">

    <div class="col-md-12">
        <div class="">
        <label class="control-label">INFORMACIÓN EXTRA</label>
          <textarea name="comment"  placeholder="INGRESAR IMEI" class="form-control" rows="10"><?php echo $sell->comment;?></textarea>
        </div>
      </div>
      </div>
        
        
        

        </div>
      </div>

    </div>
    </div>
    </div>




    <input type="hidden" name="id" id="sell_id" value="<?php echo $sell->id; ?>">
      <div class="row">
    <div class="col-md-12">

    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
          <div class="checkbox">
            <label>
            <button class="btn btn-success" style="position: relative; top: 20px;"> ACTUALIZAR DATOS DE VENTA</button>
            </label>
          </div>
        </div>
      </div>
    </form>
    </div>

</div>
  

<?php else:?>
  501 Internal Error
<?php endif; ?>

</section>

<script type="text/javascript">
  
  let sell_id = $("#sell_id").val();
  let list_products = [];
  function getHeader(column) {
    text = '<thead>';
    text += '<th>IMAGEN</th>';
    text += '<th>NOMBRE DEL PRODUCTO</th>';
    if (column.price) {
      text += '<th>PRECIO UNITARIO</th>';
    }
    if (column.total) {
      text += '<th>TOTAL</th>';
    }
    if (column.q) {
      text += '<th>CANTIDAD</th>';
    } 
    if (column.stock) {
      text += '<th>STOCK</th>';
    }
    if (column.option) {
      text += '<th></th>';
    }
    text += '</thead>';
    return text;
  }

  function addRows(products, box) {
    box.empty()
    text = getHeader({price: 1, total: 1, q: 1, option: 1} );
    for (var i = 0; i < products.length; i++) {
      text += `<tr>
                <td><img src="storage/products/${products[i].image}" style="width:40px;"></td>
                <td>${products[i].name}</td>
                <td>${products[i].q}</td>
                <td>${products[i].price}</td>
                <td>${products[i].total}</td>
                <td><a class='btn btn-danger' onClick='removeProduct(${products[i].total})'>Quitar</a></td>
              </tr>`;
      
    }
    box.append(text);
  }

  function addSearchProducts(products, box) {
    box.empty()

    text = getHeader({price: 1, option: 1, stock:1});
    for (var i = 0; i < products.length; i++) {
      readonly = products[i].stock > 0 ? '': 'readonly';
      disabled = products[i].stock > 0 ? '': 'disabled';
      console.log(readonly)
      text += `<tr>
                <td><img src="storage/products/${products[i].image}" style="width:40px;"></td>
                <td>${products[i].name}</td>
                <td>${products[i].price}</td>
                <td>${products[i].stock}</td>
                <td><div class="input-group">
                    <input type="text" class="form-control" required name="q" id="quantity${products[i].id}" ${readonly} placeholder="Cantidad ...">
                    <span class="input-group-btn add-product">
                        <a ${disabled} class="btn btn-primary" onClick="addProduct(${products[i].id})">
                            <i class="glyphicon glyphicon-plus-sign"></i>
                        </a>
                    </span>
                    </div>
              </td>
              </tr>`; 
       
    }
    box.append(text);
  }

  $("#change_sell").click(function () {
    $("#box_info_sell").addClass('hidden');  
    $("#change_sell").addClass('hidden');
    $("#pay_box").addClass('hidden');


    $("#cancel_change").removeClass('hidden');
    $("#search_bar").removeClass('hidden');
  })

  $("#cancel_change").click(function (argument) {
    $("#box_info_sell").removeClass('hidden');  
    $("#change_sell").removeClass('hidden');
    $("#pay_box").removeClass('hidden');


    $("#cancel_change").addClass('hidden');  
    $("#search_bar").addClass('hidden');  
  })

  $("#search_button").click(function (a) {
    $("#search_response").addClass('hidden');
    a.preventDefault()
    let q = $("#product_name").val();
    $.get("./?action=getProducts&q="+q,function(data){
      let products = JSON.parse(data);
      addSearchProducts(products.products, $("#search_products"))
      $("#search_response").removeClass('hidden');

    });
  })

  function removeProduct (id) {
    i = 0;
    find = false;
    while (i < products.length && !find) {
        if (products[i].id == id) {
            find = true;
        }
        i++;
    }
  }

  function addProduct(id) {
    console.log(list_products);
    let q = $("#quantity"+id).val();
    x = new RegExp(/[a-z]/gi)
    let products = list_products
    if (!x.test(q)) {
        $.get("./?action=getProducts&product="+id+"&quantity="+q,function(data){
            i = 0;
            find = false;
            while (i < products.length && !find) {
                if (products[i].id == id) {
                    find = true;
               }
                i++;
            }
            if (find) {
                products[i-1].q = parseInt(products[i-1].q, 10) + parseInt(q,10);
                total = new Intl.NumberFormat("de-DE", {
                    maximumSignificantDigits: 3
                })
                .format(parseInt(products[i-1].q*products[i-1].price, 10))
                products[i-1].total = total;
            } else {
                list_products = products.concat(JSON.parse(data).products[0])
            }
            addRows(list_products, $("#products"))
        });
    }
      
  }

  $.get("./?action=getProducts&id="+sell_id,function(data){
      list_products = JSON.parse(data).products;
      addRows(list_products, $("#products"))
  });

  

  
</script>