        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            PRODUCTOS
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">

<div class="row">
	<div class="col-md-12">

<div class="btn-group">
  <a href="index.php?view=newproduct" class="btn btn-default">AGREGAR PRODUCTO</a>
<div class="btn-group pull-right">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-download"></i> DESCARGAR <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu">
    <li><a href="report/products-word.php">DOCUMENTO</a></li>

  </ul>
</div>
</div>
<br><br>

<?php

$products = ProductData::getAll();
if(count($products)>0){
?>
<div class="box box-primary">
  <div class="box-header">
    <h3 class="box-title">PRODUCTOS</h3>

  </div><!-- /.box-header -->
  <div class="box-body no-padding">
<div class="box-body table-responsive">
<table class="table  table-bordered datatable table-hover">
	<thead>
		<th>CODIGO</th>
		<th>IMAGEN</th>
		<th>NOMBRE</th>
		<th>PRECIO DE ENTRADA</th>
		<th>PRECIO DE SALIDA</th>
		<th>CATEGORIA</th>
		<th>MARCA</th>
    	<th>TIPO</th>
	
		<th></th>
	</thead>
	<?php foreach($products as $product):?>
	<tr>
		<td><?php echo $product->code; ?></td>
		<td>
			<?php if($product->image!=""):?>
				<img src="storage/products/<?php echo $product->image;?>" style="width:80px;">
			<?php endif;?>
		</td>
		<td><?php echo strtoupper($product->name); ?></td>
		<td>$ <?php echo number_format($product->price_in,2,'.',','); ?></td>
		<td>$ <?php echo number_format($product->price_out,2,'.',','); ?></td>
		<td><?php if($product->category_id!=null){echo $product->getCategory()->name;}else{ echo "<center>----</center>"; }  ?></td>
		<td><?php if($product->brand_id!=null){echo $product->getBrand()->name;}else{ echo "<center>----</center>"; }  ?></td>
<td>
  <?php
if($product->kind==1){
  echo "<span class='label label-info'>Producto</span>";
}else if($product->kind==2){
  echo "<span class='label label-success'>Servicio</span>";

}
  ?>


</td>
		
		

		<td style="width:90px;">
		<a target="_blank" href="index.php?action=productqr&id=<?php echo $product->id; ?>" class="btn btn-xs btn-default"><i class="fa fa-qrcode"></i></a>
		<a href="index.php?view=editproduct&id=<?php echo $product->id; ?>" class="btn btn-xs btn-warning"><i class="glyphicon glyphicon-pencil"></i></a>
		<a href="index.php?view=delproduct&id=<?php echo $product->id; ?>" class="btn btn-xs btn-danger" onclick="return confirm('CONFIRMAS QUE QUIERES ELIMINAR ESTE PRODUCTO');"><i class="fa fa-trash"></i></a>
		</td>
	</tr>
	<?php endforeach;?>
</table>
</div>
  </div><!-- /.box-body -->
</div><!-- /.box -->


	<?php
}else{
	?>
	<div class="alert alert-info">
		<h2>NO HAY PRODUCTOS</h2>
		<p>NO SE HAN AGREGADO PRODUCTOS, PUEDES AGREGAR UNO DANDO CLICK EN EL BOTON <b>"AGREGAR PRODUCTO"</b>.</p>
	</div>
	<?php
}

?>
	</div>
</div>
        </section><!-- /.content -->



<script type="text/javascript">
        function thePDF() {
var doc = new jsPDF('p', 'pt');
        doc.setFontSize(15);
        doc.text("<?php echo ConfigurationData::getByPreffix("company_name")->val;?>", 40, 65);
        doc.setFontSize(10);
        doc.text("PRODUCTOS", 40, 80);
        doc.setFontSize(12);
var columns = [
    {title: "ID", dataKey: "id"},  
    {title: "NOMBRE DEL PRODUCTO", dataKey: "name"}, 
    {title: "PRECIO DE ENTRADA", dataKey: "price_in"}, 
    {title: "PRECIO DE SALIDA", dataKey: "price_out"},
	{title: "CATEGORIA", dataKey: "category_id"},
	{title: "MARCA", dataKey: "brand_id"},
];
var rows = [
  <?php foreach($products as $product):
  ?>
    {
      "id": "<?php echo $product->id; ?>",
      "name": "<?php echo $product->name; ?>",
      "price_in": "$ <?php echo number_format($product->price_in,2,'.',',');?>",
      "price_out": "$ <?php echo number_format($product->price_out,2,'.',',');?>",
		"category_id": "<?php if($product->category_id!=null){echo $product->getCategory()->name;}else{ echo "<center>----</center>"; }  ?>",
		"brand_id": "<?php if($product->brand_id!=null){echo $product->getBrand()->name;}else{ echo "<center>----</center>"; }  ?>",
      },
 <?php endforeach; ?>
];
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
$con = ConfigurationData::getByPreffix("report_image");
if($con!=null && $con->val!=""):
?>
var img = new Image();
img.src= "storage/configuration/<?php echo $con->val;?>";
img.onload = function(){
doc.addImage(img, 'PNG', 495, 20, 60, 60,'mon');	
doc.save('products-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
}
<?php else:?>
doc.save('products-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
<?php endif; ?>
}
</script>

