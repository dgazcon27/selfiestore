<section class="content"> 
<div class="row">
	<div class="col-md-12">	
		
		
<?php if(isset($_SESSION["client_id"])):?>
		<h1><i class='glyphicon glyphicon-shopping-cart'></i> Mis Compras</h1>
<?php else:?>
<div class="btn-group pull-right">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-download"></i> DESCARGAR <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu">
<li><a onclick="thePDF()" id="makepdf" class="">VENTAS</a></li>
  </ul>
</div>
		<h1><i class='glyphicon glyphicon-shopping-cart'></i> VENTAS</h1>
<?php endif;?>
		<div class="clearfix"></div>


<?php
		
$products = null;
// print_r(Core::$user);
if(isset($_SESSION["user_id"])){
	if(Core::$user->kind==3){
		$products = SellData::getSellsForManager();

	} elseif(Core::$user->kind==2){
		$products = SellData::getAllBySQL(" where operation_type_id=2 and p_id=1 and d_id=1 and is_draft=0 and stock_to_id=".Core::$user->stock_id." order by created_at desc");
	} elseif (Core::$user->kind == 5) {
		$products = SellData::getSellsForManager();
	} else {
		$products = SellData::getSells();

	}
	
}else if(isset($_SESSION["client_id"])){
	$products = SellData::getAllBySQL(" where person_id=$_SESSION[client_id] and operation_type_id=2 and p_id=1 and d_id=1 and is_draft=0 order by created_at desc");	
}
if(count($products)>0){

	?>
<br>
<div class="box box-primary">
<div class="box-header">

<div class="box-body">
<table class="table table-bordered table-hover table-responsive datatable	">
	<thead>
		<th></th>
		<th width="1px">FACTURA Nº</th>
		<TH width="1px">FOLIO</TH>
		<th width="15px">VENDEDOR</th>
		<th width="15px">CLIENTE</th>
		<th width="6px">METODO DE PAGO</th>
		<th width="6px">REFERENCIA</th>
		<th width="10px">TOTAL</th>
		<th width="18px">FECHA</th>
		<th></th>
	</thead>
	<?php foreach($products as $sell):
	$operations = OperationData::getAllProductsBySellId($sell->id);
	$gasto = $sell->invoice_code;
	$ganancia = 0;
	$total = $sell->total;
	
	?>

	<tr>
		<td style="width:30px;">
		<a href="index.php?view=onesell&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-default"><i class="glyphicon glyphicon-eye-open"></i></a></td>
		<td>#<?php $acumulador = 100000; $code = $acumulador+$sell->ref_id; echo $code; ?></td>
		<td>#<?php echo $sell->id; ?></td>
		
		<td> <?php
				if (isset($sell->receive_by)) {
					$person = UserData::getById($sell->receive_by);
					echo strtoupper($person->name." ".$person->lastname);
				} else {
					echo "";
				} 
			?> 
		</td>
		<td> <?php if($sell->person_id!=null){$c= $sell->getPerson();echo strtoupper($c->name." ".$c->lastname);} ?> </td>
		
		<?php		
		if($sell->f_id == 1)
	$variable = "EFECTIVO";
elseif($sell->f_id == 2)
	$variable = "TRANSFERENCIA";
elseif($sell->f_id == 3)
	$variable = "ZELLE";
elseif($sell->f_id == 4)
	$variable = "PAGO DUAL";
elseif($sell->f_id == 5)
	$variable = "PUNTO DE VENTA";	
		?>
		
		
	<?php		
		if($sell->refe == 0 )
			$variablerefe = "N/A";
		else
			$variablerefe = $sell->refe;	
		?>		
		
		<td><?php echo strtoupper($variable); ?></td>
		<td><?php echo $variablerefe; ?></td>
		
		
		
		<td>

<?php

		echo "<b>$ ".number_format($total-$sell->discount,2,".",",")."</b>";

?>			
		</td>
		
		<td><?php echo $sell->created_at; ?></td>
		<td style="width:130px;">
		<a  target="_blank" href="ticket.php?id=<?php echo $sell->id; ?>" class="btn btn-xs btn-default"><i class='fa fa-ticket'></i> TICKET</a>
<?php if(isset($_SESSION["user_id"]) && Core::$user->kind==1):?>
		<a href="index.php?action=cancelsell&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-danger" onclick="return confirm('CONFIRMAS QUE QUIERES CANCELAR ESTA VENTA');">CANCELAR</a>
		<!--a href="index.php?view=delsell&id=<?php //echo $sell->id; ?>" class="btn btn-xs btn-danger" onclick="return confirm('CONFIRMAS QUE QUIERES ELIMINAR ESTA VENTA');"><i class="fa fa-trash"></i></a-->

<?php endif;?>
		<?php if (Core::$user->kind == 5 && $sell->d_id == 11): ?>
			<a href="index.php?action=setorder&status=1&from=1&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-success" onclick="return confirm('¿CORFIRMAR QUE EL PEDIDO FUE ENTREGADO?');">
				<span> ENTREGADO AL CLIENTE</span>
			</a>
		<?php endif ?>
		<?php if (Core::$user->kind == 5 && $sell->d_id == 10): ?>
			<a href="index.php?action=setorder&status=11&from=1&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-primary" onclick="return confirm('¿CORFIRMAR QUE EL PEDIDO ESTA DISPONIBLE PARA RETIRAR?');">
				<span> DISPONIBLE PARA RETIRO</span>
			</a>
		<?php endif ?>
		<?php if (isset($_SESSION['is_admin']) && $sell->user_id): ?>
			<a onclick="report(<?php echo $sell->id;?>,<?php echo $sell->ref_id;?> ,'<?php echo $sell->created_at; ?>')" class="btn btn-xs btn-default">
				<i class="fa fa-file"></i> PDF GUIA
			</a>
		<?php endif ?>
		</td>
	</tr>
	
<?php endforeach; ?>

</table>
</div>
</div>

<div class="clearfix"></div>

	<?php
}else{
	?>
	<div class="jumbotron">
		<h2>No hay ventas</h2>
		<p>No se ha realizado ninguna venta.</p>
	</div>
	<?php
}

?>
	</div>
</div>
</section>


<script type="text/javascript">
        function thePDF() {
var doc = new jsPDF('p', 'pt');
        doc.setFontSize(26);
        doc.text("<?php echo ConfigurationData::getByPreffix("company_name")->val;?>", 230, 65);
        doc.setFontSize(18);
        doc.text("VENTAS", 260, 80);
        doc.setFontSize(12);
        doc.text("Usuario: <?php echo Core::$user->name." ".Core::$user->lastname; ?>  -  Fecha: <?php echo date("d-m-Y h:i:s");?> ", 140, 90);
var columns = [
        {title: "Factura Nº", dataKey: "id"}, 
    {title: "Venderdor", dataKey: "client"}, 
    {title: "Cliente", dataKey: "total"}, 
    {title: "Metodo de pago", dataKey: "p"}, 
    {title: "Referencia", dataKey: "d"}, 
    {title: "Total", dataKey: "stock"}, 
    {title: "Fecha", dataKey: "created_at"}, 
];
var rows = [
  <?php foreach($products as $sell):
  ?>
    {
      "id": "#<?php $acumulador = 100000; $code = $acumulador+$sell->ref_id; echo $code; ?>",
      "client": "<?php if($sell->user_id!=null){$c= $sell->getUser();echo strtoupper($c->name." ".$c->lastname);} ?>",
      "total": "<?php if($sell->person_id!=null){$c= $sell->getPerson();echo strtoupper($c->name." ".$c->lastname);} ?>",
      "p": "<?php echo strtoupper($variable); ?>",
      "d": "<?php echo $variablerefe; ?>",
      "stock": "<?php
$total= $sell->total-$sell->discount;
		echo "$ ".number_format($total,2,".",",");
?>	",
      "created_at": "<?php echo $sell->created_at; ?>",
      },
 <?php endforeach; ?>
];
doc.setFontSize(14);
doc.autoTable(columns, rows, {
    theme: 'grid',
    overflow:'linebreak',
    styles: { 
        fillColor: <?php echo Core::$pdf_table_fillcolor;?>
    },
    columnStyles: {
        id: {fillColor: <?php echo Core::$pdf_table_column_fillcolor;?>}
    },
    margin: {top: 100},
    afterPageContent: function(data) {
    }
});
doc.setFontSize(12);
doc.text("<?php echo Core::$pdf_footer;?>", 40, doc.autoTableEndPosY()+25);
<?php 
$con = ConfigurationData::getByPreffix("");
if($con!=null && $con->val!=""):
?>
var img = new Image();
img.src= "storage/configuration/<?php echo $con->val;?>";
img.onload = function(){
doc.addImage(img, 'PNG', 495, 20, 60, 60,'mon');	
doc.save('sells-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
}
<?php else:?>
doc.save('sells-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
<?php endif; ?>
}
</script>



<script type="text/javascript">
    function report(id, order, date) {
    	newdate = new Date(date);
    	month = parseInt(newdate.getMonth())+1;
    	month_ = month < 10 ? "0"+month : month;
    	printdate = newdate.getDate()+"/"+month_+"/"+newdate.getFullYear();
		var doc = new jsPDF('p', 'pt');
        doc.setFontSize(26);
        doc.text("PEDIDO #"+order+" - FECHA "+printdate+" ", 100, 65);
		var columns = [
	        {title: "CODIGOS DE BARRAS", dataKey: "id"}, 
		    {title: "PRODUCTO", dataKey: "product"}, 
		    {title: "CANTIDAD", dataKey: "q"}, 
		];

		var rows = [];
		$.get("./?action=movetransferdocument&id="+id+"&seller=1 ",function(data2){
			let response = JSON.parse(data2);
			let sell = response.sell;
			let person = response.person;
			let products = response.products;
			let seller = response.seller ? response.seller : {'name':'', 'lastname':''};
			doc.setFontSize(12);
    		doc.text("_____________________________________________________________________________", 40, 90);
    		doc.text("NOMBRE DEL ENCARGADO: "+person.name+" "+person.lastname+"             TELÉFONO ENCARGADO: "+person.phone2+" ", 40, 105);
    		doc.text("_____________________________________________________________________________", 40, 108);
    		doc.text("EMPRESA: "+person.company+"        TELÉFONO: "+person.phone1+"           RIF: "+person.rif+"", 40, 125);
    		doc.text("_____________________________________________________________________________", 40, 130);
    		doc.text("DIRECCION DE ENTREGA: "+person.address2+" ", 40, 145);
    		doc.text("_____________________________________________________________________________", 40, 150);
    		doc.text("ATENTIDO POR: "+seller.name+" "+seller.lastname+" ", 40, 165);
			doc.text("_____________________________________________________________________________", 40, 170);

			doc.setFontSize(14);
			for (var i = 0; i < products.length; i++) {
				data = {
					"id" : products[i].barcode,
					"product": products[i].name,
					"q": products[i].q
				}
				rows.push(data)
			}
			doc.autoTable(columns, rows, {
			    theme: 'grid',
			    overflow:'linebreak',
			    styles: { 
			        fillColor: <?php echo Core::$pdf_table_fillcolor;?>
			    },
			    columnStyles: {
			        id: {fillColor: <?php echo Core::$pdf_table_column_fillcolor;?>}
			    },
			    margin: {top: 200},
			    afterPageContent: function(data) {
			    }
			});
			doc.setFontSize(12);
			doc.text("__________________  __________________  __________________  __________________",40, doc.autoTableEndPosY()+200);
			doc.text("           ENTREGA               TRANSPORTA                    RECIBE                       AUDITA",40, doc.autoTableEndPosY()+215);
			doc.text("<?php echo Core::$pdf_footer;?>", 40, doc.autoTableEndPosY()+603);
			doc.save('movimiento-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
		});

		
	}
</script>