<section class="content">
<div class="row">
	<div class="col-md-12">
<div class="btn-group pull-right">
	<a href="index.php?view=newclient" class="btn btn-default"><i class='fa fa-smile-o'></i> NUEVO CLIENTE</a>
<div class="btn-group pull-right">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-download"></i> DESCARGAR <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu">
    <li><a href="report/clients-word.php">CLIENTES</a></li>

  </ul>
</div>
</div>
		<h1>DIRECTORIO DE CLIENTES</h1>
<br>
		<?php

		$users = PersonData::getClients();
		if(count($users)>0){
			// si hay usuarios
			?>
<div class="box box-primary">
<div class="box-body">
			<table class="table table-bordered datatable table-hover">
			<thead>
			<th>IDENTIFICACION</th>
			<th>NOMBRE</th>
			<th>DIRECCION</th>
			<th>TELEFONO</th>
			<th>CREDITO</th>
			<th></th>
			</thead>
			<?php
			foreach($users as $user){
				?>
				<tr>
				<td><?php 
					if(isset($user->no)) {
						echo $user->no;
					} else if (isset($user->ci)) {
						echo $user->ci;
					}
				?>
					
				</td>
				<td><?php echo strtoupper($user->name." ".$user->lastname); ?></td>
				<td>
					<?php
						if (isset($user->address1)) {
							echo strtoupper($user->address1);
						} elseif (isset($user->address2)) {
							echo strtoupper($user->address2);
						}
					?>
						
				</td>
				<td>
					<?php
						if (isset($user->phone1)) {
							echo $user->phone1;
						} elseif($user->phone2) {
							echo $user->phone2;
						}
					?>
						
				</td>
				<td><?php if($user->has_credit): ?><i class="fa fa-check"></i><?php endif;?></td>
				<td style="width:130px;">
					<?php if (Core::$user->kind == 1): ?>
						<a href="index.php?view=editclient&id=<?php echo $user->id;?>" class="btn btn-warning btn-xs">EDITAR</a>
						<a href="index.php?view=delclient&id=<?php echo $user->id;?>" class="btn btn-danger btn-xs" onclick="return confirm('CONFIRMAS QUE QUIERES ELIMINAR ESTE CLIENTE');">ELIMINAR</a>
					<?php endif ?>
				</td>
				</tr>
				<?php

			}?>
			</table>
			</div>
			</div>
			<?php
		}else{
			echo "<p class='alert alert-danger'>NO HAY CLIENTES</p>";
		}


		?>


	</div>
</div>
</section>

<script type="text/javascript">
        function thePDF() {
var doc = new jsPDF('p', 'pt');
        doc.setFontSize(26);
        doc.text("<?php echo ConfigurationData::getByPreffix("company_name")->val;?>", 40, 65);
        doc.setFontSize(18);
        doc.text("DIRECTORIO DE CLIENTES", 40, 80);
        doc.setFontSize(12);
        doc.text("Usuario: <?php echo Core::$user->name." ".Core::$user->lastname; ?>  -  Fecha: <?php echo date("d-m-Y h:i:s");?> ", 40, 90);
var columns = [
    {title: "Id", dataKey: "id"}, 
    {title: "RFC/RUT", dataKey: "no"}, 
    {title: "Nombre completo", dataKey: "name"}, 
    {title: "Direccion", dataKey: "address"}, 
    {title: "Email", dataKey: "email"}, 
    {title: "Telefono", dataKey: "phone"}, 
];
var rows = [
  <?php foreach($users as $product):
  ?>
    {
      "id": "<?php echo $product->id; ?>",
      "no": "<?php echo $product->no; ?>",
      "name": "<?php echo $product->name." ".$product->lastname; ?>",
      "address": "<?php echo $product->address1; ?>",
      "email": "<?php echo $product->email1; ?>",
      "phone": "<?php echo $product->phone1; ?>",
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
doc.save('clients-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
}
<?php else:?>
doc.save('clients-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
<?php endif; ?>
}
</script>


