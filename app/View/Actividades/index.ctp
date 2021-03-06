<?php
$this->Html->addCrumb('Actividades', '');
$statusOpc = array(1=>'Activo', 2=>'Inactivo', 3=>'Culminado', 4=>'Cancelado');
?>
<h2>Listado de Actividades</h2>

<table class="table table-bordered table-hover table-responsive">
<thead>
	<tr class="info">
		<th><?php echo $this->Paginator->sort('nombre'); ?></th>
		<th><?php echo $this->Paginator->sort('fecha_inicio', 'Inicio'); ?></th>
		<th><?php echo $this->Paginator->sort('fecha_culminacion', 'Culminación'); ?></th>
		<th><?php echo $this->Paginator->sort('status'); ?></th>
		<th>% Avance</th>
		<th class="actions" width="78px">Acciones</th>
	</tr>
</thead>
<?php
//pr($actividades);
foreach ($actividades as $actividad): 
	$culminado = 0;
	foreach ($actividad['Avance'] as $avance) {
		$culminado += $avance['porcentaje'];
	}
?>
<tr>
	<td><?php echo h($actividad['Actividad']['nombre']); ?>&nbsp;</td>
	<td><?php echo h($actividad['Actividad']['fecha_inicio']); ?>&nbsp;</td>
	<td><?php echo h($actividad['Actividad']['fecha_culminacion']); ?>&nbsp;</td>
	<td><?php echo h($statusOpc[$actividad['Actividad']['status']]); ?>&nbsp;</td>
	<td><?php echo h($culminado); ?>&nbsp;</td>
	<td class='actions'>
		<?= $this->Html->link('<span class="glyphicon glyphicon-eye-open"></span>', 
		array('controller'=>'Actividades',  'action'=>'view', $actividad['Actividad']['id']), array("confirm"=>null, "indicator"=>null, "escape"=>false, 
			"data-toggle"=>"tooltip", "data-placement"=>"top", "title"=>"Ver información completa"
		)); ?>

		<? $this->Html->link('<span class="glyphicon glyphicon-refresh"></span>',
		array('controller'=>'Actividades', 'action'=>'update_avance', $actividad['Actividad']['id']), array("confirm"=>null, "indicator"=>null, "escape"=>false,
			"data-toggle"=>"tooltip", "data-placement"=>"top", "title"=>"Actualizar avance"
		)); ?>
	</td>
</tr>
<?php endforeach; ?>
</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>

	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
<script>
$(document).ready(function() {
	$("#liActividades").addClass('active');
	$("#ulActividades").addClass('in');
	$("#lnk_actividades").addClass('current');
	$('.actions a').tooltip();
});
</script>
