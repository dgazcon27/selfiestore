<section class="content">
<div class="row">
	<div class="col-md-12">
		<h1>Lista de Clientes</h1>
<br><br>
		<?php

		$users = UserData::getBranchs();
		if(count($users)>0){
			// si hay usuarios
			?>
			<div class="box box-primary">
			<div class="box-body">
			<table class="table table-bordered datatable table-hover">
			<thead>
			<th></th>
			<th>Nombre completo</th>
			<th>Nombre de usuario</th>
			<th>Email</th>
			<th>Almacen</th>
			<th>Activo</th>
			<th>Tipo</th>
			<th></th>
			</thead>
			<?php
			foreach($users as $user){
				?>
				<tr>
				<td style='width:24px;'>
					<?php
					if($user->image!=""){
						$url = "storage/profiles/".$user->image;
						if(file_exists($url)){
							echo "<img src='$url' style='width:24px;'>";
						}
					}
					?>
				</td>
				<td><?php echo $user->name." ".$user->lastname; ?></td>
				<td><?php echo $user->username; ?></td>
				<td><?php echo $user->email; ?></td>
				<td><?php if($user->stock_id!=null){ echo $user->getStock()->name; } ?></td>
				<td>
					<?php if($user->status==1):?>
						<i class="glyphicon glyphicon-ok"></i>
					<?php endif; ?>
				</td>
				<td>
				<?php
					$role = RolesData::getById($user->kind);
					echo $role->name;
				?>
				</td>
				<td style="width:70px;">
					<?php if (Core::$user->kind == 1): ?>
						<a href="index.php?view=edituser&id=<?php echo $user->id;?>" class="btn btn-warning btn-xs">Editar</a>
					<a href="./?action=deluser&id=<?php echo $user->id; ?>" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>

					<?php endif ?>
				</td>

				</tr>
				<?php

			}
 echo "</table></div></div>";


		}else{
			// no hay usuarios
		}


		?>


	</div>
</div>
</section>