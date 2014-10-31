<h2>Solicitud de Concesión de Licencias o Permisos<br/>Remunerados o No Remunerados</h2>
<hr/>
<?php
$this->Html->addCrumb('Permisos', array('controller'=>'Proyectos', 'action'=>'index'));
$this->Html->addCrumb('Ver', '');
$defaults = array('label'=>false, 'div'=>false, 'class'=>'form-control', 'disabled'=>true);
echo $this->Form->create('Permiso', array('class'=>'form', 'inputDefaults'=>$defaults));
$this->request->data = $permiso;
//pr($this->request->data);
$statusOpc = array(1=>'Solicitado', 2=>'Aprobado', 3=>'Negado', 4=>'Cancelado');
$permiso_id = $permiso['Permiso']['id'];
//pr($permiso);
$nombre = $permiso['Usuario']['fullname'];
$usuario_id = AuthComponent::user('id');
$centro_id = AuthComponent::user('centro_id');
$cargo_id =  AuthComponent::user('cargo_id');
$cargo =  AuthComponent::user('Cargo.name');
$tipos_permiso = array('1'=>'Remunerado', 2=>'No Remunerado');

$cant_dias = array('0'=>'1/2 Dia', '1'=>'01 Dia', '2'=>'02 Días', '3'=>'03 Días')
?>
<div class="row">
	<div class="form-group form-horizontal">
		<label for="PermisoNro" class="col-sm-1 control-label">Número: </label>
		<div class="col-sm-1">
			<?= $this->Form->input('nro', array('class'=>'form-control')) ?>
		</div>
		<label for="PermisoUsuarioId" class="col-sm-2 control-label">Trabajador Solicitante: </label>
		<div class="col-sm-3">
			<?= $this->Form->input('usuario', array('type'=>'text', 'class'=>'form-control', 'value'=>$nombre, 'readonly')) ?>
			<?= $this->Form->input('usuario_id', array('type'=>'hidden', 'value'=>$usuario_id)) ?>
		</div>
		<label for="PermisoFechaSolicitud" class="col-sm-1 control-label">Fecha de Solicitud:</label>
		<div class="col-sm-1">
			<?= $this->Form->day('fecha_solicitud', array('required'=>true, 'class'=>'form-control', 'default'=>date('d'), 'disabled'=>true)); ?>
		</div>
		<div class="col-sm-1">
			<?= $this->Form->month('fecha_solicitud', array('required'=>true, 'class'=>'form-control', 'default'=>date('m'), 'disabled'=>true, 'monthNames' => false)); ?>
		</div>
		<div class="col-sm-2">
			<?= $this->Form->year('fecha_solicitud', date('Y')-2, date('Y')+2, array('required'=>true, 'default'=>date('Y'), 'disabled'=>true, 'class'=>'form-control')); ?>
		</div>
	</div>
</div>

<div>&nbsp;</div>

<div class="row">
	<div class="form-group form-horizontal">
		<label for="PermisoCentroId" class="col-sm-2 control-label">Unidad de Adscripción: </label>
		<div class="col-sm-3">
			<?= $this->Form->input('centro_id', array('disabled'=>true, 'class'=>'form-control', 'value'=>$centro_id)) ?>
		</div>
		<label for="PermisoCargoId" class="col-sm-2 control-label">Denominación del cargo: </label>
		<div class="col-sm-5">
			<?= $this->Form->input('cargo', array('type'=>'text', 'class'=>'form-control', 'value'=>$cargo, 'readonly')) ?>
			<?= $this->Form->input('cargo_id', array('type'=>'hidden', 'class'=>'form-control', 'value'=>$cargo_id)) ?>
		</div>
	</div>
</div>

<div>&nbsp;</div>

<div class="row">
	<div class="form-group form-horizontal">
		<label for="PermisoFechaDesde" class="col-sm-2 control-label">Desde:</label>
		<div class="col-sm-1">
			<?= $this->Form->day('fecha_desde', array('required'=>true, 'class'=>'form-control', 'disabled'=>true)); ?>
		</div>
		<div class="col-sm-1">
			<?= $this->Form->month('fecha_desde', array('required'=>true, 'class'=>'form-control', 'monthNames' => false, 'disabled'=>true)); ?>
		</div>
		<div class="col-sm-2">
			<?= $this->Form->year('fecha_desde', date('Y')-2, date('Y')+2, array('required'=>true, 'class'=>'form-control', 'disabled'=>true)); ?>
		</div>

		<label for="PermisoFechaDesde" class="col-sm-2 control-label">Cantidad de día(s) a solicitar:</label>
		<div class="col-sm-2">
			<?= $this->Form->input('nro_dias', array('required'=>true, 'class'=>'form-control', 'options'=>$cant_dias, 'empty'=>'Seleccione...')); ?>
		</div>

	</div>
</div>

<div>&nbsp;</div>
<div class="row">
	<div class="form-group form-horizontal">
		<label for="PermisoTipoPermiso" class="col-sm-2 control-label">Tipo de Permiso: </label>
		<div class="col-sm-2">
			<?= $this->Form->input('tipo_permiso', array('required'=>true, 'default'=>1, 'options'=>$tipos_permiso)) ?>
		</div>
	</div>
</div>

<div>&nbsp;</div>

<div class="row">
	<div class="form-group form-horizontal">
		<label for="PermisoCausa" class="col-sm-2 control-label">Causa: </label>
		<div class="col-sm-10">
			<?= $this->Form->input('causa', array('required'=>true, 'class'=>'form-control')) ?>
		</div>
	</div>
</div>
<div>&nbsp;</div>
<div class="btn-group">
	<a href="<?= $this->Html->url(array('controller'=>'Permisos', 'action'=>'edit', $permiso_id)) ?>" class="btn btn-default">Editar</a>
	<a href="<?= $this->Html->url(array('controller'=>'Panel', 'action'=>'index')) ?>" class="btn btn-default">Regresar</a>
</div>
