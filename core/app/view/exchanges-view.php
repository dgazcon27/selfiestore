 <section class="content-header">
  <h1>
    TASA DE CAMBIO
  </h1>
</section>

<div class="row">
	<div class="col-md-12">
		<br>
		<br>
		<a href="index.php?view=newexchange" class="btn btn-default"><i class='fa fa-th-list'></i> NUEVA TASA DE CAMBIO</a>
		<br>
<br>
		<?php

		$exchanges = ExchangeData::getAll();
		if(count($exchanges)>0){
			// si hay usuarios
			?>
<div class="box">
  <div class="box-header">
	
  </div><!-- /.box-header -->
  <div class="box-body">

			<table class="table table-bordered datatable table-hover">
			<thead>
			<th>DESCRIPCION</th>
			<th>VALOR</th>
			<th></th>
			</thead>
			<?php
			foreach($exchanges as $exchange){
				?>
				<tr>
				<td><?php echo $exchange->description; ?></td>
				<td><?php echo number_format($exchange->value,2,'.',',') ; ?></td>
				<td style="width:130px;"><a href="index.php?view=updateexchange&id=<?php echo $exchange->id;?>" class="btn btn-warning btn-xs">EDITAR</a> <a href="index.php?action=deleteexchange&id=<?php echo $exchange->id;?>" class="btn btn-danger btn-xs" onclick="return confirm('CONFIRMAS QUE QUIERES ELIMINAR ESTA CATEGORIA');">ELIMINAR</a></td>
				</tr>
				<?php

			}

?>
			</table>
  </div><!-- /.box-body -->
</div><!-- /.box -->
			
			<?php


		}else{
			echo "<p class='alert alert-danger'>NO TASA DE CAMBIO AGREGADAS</p>";
		}


		?>


	</div>
</div>