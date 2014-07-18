<div class="horas form">
<?php echo $this->Form->create('Hora'); ?>
		<h2>Registro de Horas de dedicación.</h2>
		<h2>Semana <?= $semana?></h2>
	<? if(!empty($horas)): ?>
	<h3>Horas registradas</h3>
	<table>
		<tr>
			<th>Tipo de Actividad</th>
			<th>Actividad</th>
			<th>Cantidad (horas)</th>
			<th>Observación</th>
		</tr>
	<? endif;?>
	<?php
		
		//pr($horas);
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
	<?php
		endforeach;
		echo "</table>";
		echo $this->Form->input('tipo_actividad_id', array(
			'label'=>array('text'=>'Tipo de Actividad: ', 'class'=>'span-4 derecha'), 'empty'=>'Seleccione...', 'class'=>'span-6 last',
			'div'=>array('class'=>'span-10'), 'required'=>true
		));
		echo $this->Form->input('actividad_id', array(
			'label'=>array('text'=>'Actividad: ', 'class'=>'span-4 derecha'), 'empty'=>'Seleccione...', 'class'=>'span-6 last',
			'div'=>array('class'=>'span-12 last', 'id'=>'actividad_div')
		));
		echo $this->Form->input('cantidad', array(
			'label'=>array('text'=>'Cantidad de horas dedicadas: ', 'class'=>'span-4 derecha'), 'class'=>'span-2',
			'div'=>array('class'=>'span-24 last'), 'required'=>true, 'type'=>'text'
		));
		echo $this->Form->input('semana', array('type'=>'hidden', 'value'=>$semana));
		echo $this->Form->input('mes', array('type'=>'hidden', 'value'=>date('n')));
		echo $this->Form->input('year', array('type'=>'hidden', 'value'=>date('Y')));
		echo $this->Form->input('observacion', array(
			'label'=>array('text'=>'Descripción /<br/>Observaciones: ', 'class'=>'span-4 derecha'), 'class'=>'span-10',
			'div'=>array('class'=>'span-24 last'), 'required'=>true
		));
	?>
	<div class="span-24 last">
		<?php echo $this->Form->end('Guardar'); ?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Horas'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Tipo Actividades'), array('controller' => 'tipo_actividades', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tipo Actividad'), array('controller' => 'tipo_actividades', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Actividades'), array('controller' => 'actividades', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Actividad'), array('controller' => 'actividades', 'action' => 'add')); ?> </li>
	</ul>
</div>
<script>
$(document).ready(function() {
	$("#HoraAddForm").validate();
	$("#actividad_div").hide();
	$("#HoraTipoActividadId").on("click", function(){
		valor = $(this).val();
		if(valor==1){
			$("#actividad_div").show();
			$("#HoraActividadId").attr('required', true);
		}else{
			$("#HoraActividadId").val("");
			$("#actividad_div").hide();
		}
	})
});
</script>