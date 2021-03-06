<section class="content">
<div class="row">
	<div class="col-md-12">
    	<h1>BALANCE DE PRODUCTOS</h1>
        <form>
    	    <input type="hidden" name="view" value="popularproductsreport">
            <div class="row">
                <div class="col-md-4">
                    <div class="btn-group pull-right" id="descargarWord" style="display:none;">
                      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-download"></i> Descargar <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu" role="menu">
                        <li><a href="report/bow-word-product-balance.php?ed_word=<?php echo $_GET["ed"]; ?>&sd_word=<?php echo $_GET["sd"]; ?>">Word 2007 (.docx)</a></li>
                      </ul>
                    </div>      
                </div>
                <div class="col-md-3">
                    <input type="date" name="sd" value="<?php if(isset($_GET["sd"])){ echo $_GET["sd"]; }?>" class="form-control">
                </div>
                <div class="col-md-3">
                    <input type="date" name="ed" value="<?php if(isset($_GET["ed"])){ echo $_GET["ed"]; }?>" class="form-control">
                </div>
    
                <div class="col-md-2">
                    <input type="submit" class="btn btn-success btn-block" value="PROCESAR">
                </div>
            </div>
        </form>
	</div>
</div>
<br><!--- -->
<div class="row">
	
	<div class="col-md-12">
		<?php if(isset($_GET["sd"]) && isset($_GET["ed"]) ):?>
    <?php if($_GET["sd"]!=""&&$_GET["ed"]!=""):?>
			<?php 
			$operations = array();

			$operations = OperationData::getPPByDateOfficial($_GET["sd"],$_GET["ed"]);
			 ?>

			 <?php if(count($operations)>0):?>
<script>
	$("#descargarWord").show();
</script>
<div class="box box-primary">
<table class="table table-bordered">
	<thead>
		<th>ID</th>
		<th>PRODUCTO</th>
		<th>CANTIDAD</th>
		<th>OPERACION</th>
		<th>FECHA</th>
	</thead>
<?php foreach($operations as $operation):?>
	<tr>
		<td><?php echo $operation->getProduct()->id; ?></td>
		<td><?php echo strtoupper($operation->getProduct()->name); ?></td>
		<td><?php echo $operation->total; ?></td>
		<td><?php echo strtoupper($operation->getOperationType()->name); ?></td>
		<td><?php echo $operation->created_at; ?></td>
	</tr>
<?php endforeach; ?>

</table>
</div>
			 <?php else:
			 // si no hay operaciones
			 ?>
<script>
	$("#wellcome").hide();
</script>
<div class="jumbotron">
	<h2>No hay operaciones</h2>
	<p>El rango de fechas seleccionado no proporciono ningun resultado de operaciones.</p>
</div>

			 <?php endif; ?>
<?php else:?>
<script>
	$("#wellcome").hide();
</script>
<div class="jumbotron">
	<h2>Fecha Incorrectas</h2>
	<p>Puede ser que no selecciono un rango de fechas, o el rango seleccionado es incorrecto.</p>
</div>
<?php endif;?>

		<?php endif; ?>
	</div>
</div>

<br><br><br><br>
</section>