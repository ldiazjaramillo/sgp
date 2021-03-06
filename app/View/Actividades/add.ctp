<h3>Agregar actividad</h3>
<?php
$this->Html->addCrumb('Proyectos', array('controller'=>'Proyectos', 'action'=>'index'));
$this->Html->addCrumb('Ver', array('controller'=>'Proyectos', 'action'=>'view', $proyecto_id));
$this->Html->addCrumb('Agregar actividad', '');

$defaults = array('label'=>false, 'div'=>false, 'class'=>'form-control');
echo $this->Form->create('Actividad', array('class'=>'form', 'inputDefaults'=>$defaults));

$fecha_inicio = $fechas['Proyecto']['fecha_inicio'];
$fecha_culminacion = $fechas['Proyecto']['fecha_culminacion'];
echo $this->Form->input('objetivo_id', array('type'=>'hidden', 'value'=>$objetivo_id));
$statusOpc = array(1=>'Activo', 2=>'Inactivo', 3=>'Culminado', 4=>'Cancelado');
?>
<div class="form-group">
	<label for="ActividadNombre">Nombre de la actividad:</label>
	<?= $this->Form->input('nombre', array('required'=>true)) ?>
</div>
<div class="form-group">
	<label for="ActividadProducto">Producto:</label>
	<?= $this->Form->input('producto', array('required'=>true)) ?>
</div>
<div class="form-group form-horizontal">
	<label for="ActividadResponsable" class="col-sm-1 control-label">Responsable:</label>
	<div class="col-sm-10">
	<?php
		echo $this->Form->input('responsable_name', array('required'=>true, 'readonly'=>true));
		echo $this->Form->input('responsable_id', array('type'=>'hidden'));
	 ?>
	 </div>
	 <div class="col-sm-1">
	 	<a  href="#personal" class='btn btn-default' data-toggle="modal" data-target="#personal">
	 		Buscar <span class="glyphicon glyphicon-search"></span>
	 	</a>
	 </div>
</div>
<div>&nbsp;</div>
<div class="form-group form-horizontal">
	<label for="ActividadFechaInicio" class="col-sm-2 control-label">Fecha de inicio:</label>
	<div class="col-sm-1">
		<?= $this->Form->day('fecha_inicio', array('required'=>true, 'class'=>'form-control')); ?>
	</div>
	<div class="col-sm-1">
		<?= $this->Form->month('fecha_inicio', array('required'=>true, 'class'=>'form-control', 'monthNames' => false)); ?>
	</div>
	<div class="col-sm-2">
		<?= $this->Form->year('fecha_inicio', date('Y')-2, date('Y')+2, array('required'=>true, 'class'=>'form-control')); ?>
	</div>

	<label for="ActividadFechaCulminacion" class="col-sm-2 control-label">Fecha de Culminación:</label>
	<div class="col-sm-1">
		<?= $this->Form->day('fecha_culminacion', array('required'=>true, 'class'=>'form-control')); ?>
	</div>
	<div class="col-sm-1">
		<?= $this->Form->month('fecha_culminacion', array('required'=>true, 'class'=>'form-control', 'monthNames' => false)); ?>
	</div>
	<div class="col-sm-2">
		<?= $this->Form->year('fecha_culminacion', date('Y')-2, date('Y')+2, array('required'=>true, 'class'=>'form-control')); ?>
	</div>

</div>
<div>&nbsp;</div>
<div class="form-group">
	<label for="ActividadObservacinoes">Observaciones:</label>
	<?= $this->Form->input('observaciones', array('required'=>true)) ?>
</div>
<div class="form-group form-inline">
	<label for="ActividadHoras">Horas:</label>
	<?= $this->Form->input('horas', array()) ?>
	<label for="ActividadPeso">Peso: (%):</label>
	<?= $this->Form->input('peso', array()) ?>
	<label for="ActividadStatus">Status:</label>
	<?= $this->Form->input('status', array('required'=>true, 'options'=>$statusOpc, 'empty'=>'Seleccione...')) ?>
</div>

<div class="btn-group">
	<?php echo $this->Form->end(array('label'=>'Guardar', 'div'=>false, "class"=>"btn btn-default")); ?>
	<a href="<?= $this->Html->url(array('controller'=>'Proyectos', 'action'=>'view', $proyecto_id)) ?>" class="btn btn-default">Cancelar</a>
</div>
<div class="modal fade" id="personal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Personal del CPDI</h4>
      </div>
      <div class="modal-body">
      	<div class="list-group">
	        <?php
			foreach ($personal as $row) {
				//pr($row);
				$fullname = $row['Usuario']['fullname'];
				$personal_id = $row['Usuario']['id'];
				echo "<a href='#divCoord'  class='list-group-item' id='$personal_id' valor='$fullname'>$fullname</a>";
			} 
			?>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
$(document).ready(function() {
	$(".list-group-item").on("click", function() {
		valor = $(this).attr('id');
		nombre = $(this).attr('valor');
		$("#ActividadResponsableId").val(valor);
		$("#ActividadResponsableName").val(nombre);
		$('#personal').modal('hide');
		//$.fancybox.close();
	})
});
</script>
