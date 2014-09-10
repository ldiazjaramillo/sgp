<div class="panel panel-info">
	<div class="panel-heading">
		<div class="panel-title">Horas registradas</div>
	</div>
	<table class='table table-responsive table-bordered'>
	<thead>
		<tr>
			<th>Tipo de Actividad</th>
			<th>Actividad</th>
			<th>Cantidad (horas)</th>
			<th>Observación</th>
		</tr>
	</thead>
	<?php
		foreach ($horas as $hora):
			$tipoActividad = $hora['TipoActividad']['name'];
			if(!empty($hora['Actividad']['nombre'])){
				$actividadN = $hora['Actividad']['nombre'];
			}else{
				$actividadN = "N/A";
			}
			$cantidad = $hora['Hora']['cantidad'];
			$observacion = $hora['Hora']['observacion'];
	?>
		<tr>
			<td><?= $tipoActividad ?></td>
			<td><?= $actividadN ?></td>
			<td><?= $cantidad ?></td>
			<td><?= $observacion ?></td>
		</tr>
		<?php endforeach; ?>
	</table>
</div>
