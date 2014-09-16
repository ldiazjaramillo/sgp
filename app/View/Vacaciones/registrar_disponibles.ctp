<h2>Actualizar días disponibles de Vacaciones</h2>
<hr/>
<?php
$this->Html->addCrumb('Vacaciones', array('controller'=>'Proyectos', 'action'=>'index'));
$this->Html->addCrumb('Actualizar días disponibles', '');

$defaults = array('label'=>false, 'div'=>false, 'class'=>'form-control');
echo $this->Form->create('Periodo', array('class'=>'form', 'inputDefaults'=>$defaults));

$statusOpc = array(1=>'Solicitado', 2=>'Aprobado', 3=>'Negado', 4=>'Cancelado');
$nombre = AuthComponent::user('nombre')." ".AuthComponent::user('apellido');
$usuario_id = AuthComponent::user('id');
$centro_id = AuthComponent::user('centro_id');
$cargo_id =  AuthComponent::user('cargo_id');
$cargo =  AuthComponent::user('Cargo.name');
$dias_disponibles = AuthComponent::user('DiasDisponibles.nro_dias');
//pr($periodos);
?>
<div class="row">
<div class="form-group form-horizontal">
	<label for="PeriodoUsuario" class="col-sm-2 control-label">Trabajador:</label>
	<div class="col-sm-4">
	<?php
		echo $this->Form->input('usuario', array('required'=>true, 'readonly'=>true));
		echo $this->Form->input('usuario_id', array('type'=>'hidden'));
	 ?>
	 </div>
	 <div class="col-sm-1">
	 	<a  href="#responsables" class='btn btn-default' data-toggle="modal" data-target="#responsables">
	 		Buscar <span class="glyphicon glyphicon-search"></span>
	 	</a>
	 </div>
</div>
</div>
<div>&nbsp;</div>

<div class="row">
	<div class="form-group form-horizontal">
		<label for="PeriodoYear" class="col-sm-2 control-label">Año:</label>
		<div class="col-sm-2">
			<div class="input-group">
				<div class="input-group-addon"><span id="anterior"><?= date('Y')-1 ?></span> -</div>
				<?= $this->Form->input('year', array('required'=>true, 'class'=>'form-control', 'value'=>date('Y'), 'min'=>1990, 'max'=>date('Y'),)); ?>
			</div>
		</div>
		<label for="PeriodoDisponible" class="col-sm-2 control-label">Cantidad de dias:</label>
		<div class="col-sm-1">
			<?= $this->Form->input('disponible', array('required'=>true, 'class'=>'form-control', 'min'=>1)); ?>
		</div>
	</div>
</div>

<div>&nbsp;</div>

<div class="btn-group">
	<?php echo $this->Form->end(array('label'=>'Guardar', 'div'=>false, "class"=>"btn btn-default")); ?>
	<a href="<?= $this->Html->url(array('controller'=>'Panel', 'action'=>'index')) ?>" class="btn btn-default">Cancelar</a>
</div>

<div class="modal fade" id="responsables">
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

<div class="alert alert-warning alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
  <strong>Advertencia!</strong> Debe elegir el nombre del trabajador.
</div>

<script>
$(document).ready(function() {
	$(".alert").hide();
	$( "#PeriodoYear" ).change(function() {
		var actual = $( "#PeriodoYear" ).val();
		$("#anterior").html(actual - 1);
	});

	$(".list-group-item").on("click", function() {
		valor = $(this).attr('id');
		nombre = $(this).attr('valor');
		$("#PeriodoUsuarioId").val(valor);
		$("#PeriodoUsuario").val(nombre);
		$('#responsables').modal('hide');
		//$.fancybox.close();
	});

	$( "form" ).submit(function() {
	  var usuario_id = $("#PeriodoUsuarioId");
	  if(usuario_id.val() === ""){
	  	$(".alert").show();
	  	return false;
	  }

	});
	
});
</script>