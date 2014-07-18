<?php
$this->Html->addCrumb('Proyectos', array('controller'=>'Proyectos', 'action'=>'index'));
$this->Html->addCrumb('Reportes', array('controller'=>'Proyectos', 'action'=>'reportes'));
$this->Html->addCrumb('Actividades por proyecto', '');
?>
<h2>Reporte de Actividades por Proyectos</h2>

<?php 

$defaults = array('label'=>false, 'div'=>false, 'class'=>'form-control');
echo $this->Form->create('Proyecto', array('class'=>'form', 'inputDefaults'=>$defaults));

	$trimestre = array(1=>'1er Trimestre (Ene-Feb-Mar)', 2=>'2do Trimestre (Abr-May-Jun)', 3=>'3er Trimestre (Jul-Ago-Sep)', 4=>'4to Trimestre (Oct-Nov-Dic)');
	$semestre = array(1=>'1er Semestre (Ene-Jun)', 2=>'2do Semestre (Jul-Dic)');
	$tiposReporte = array(1=>'Trimestral', 2=>'Semestral');
	//array_push($proyectos, 'Todos los proyectos');
?>
<div class="form-group" id='div_year'>
	<label for="ProyectoYear">Año: </label>
	<?= $this->Form->input('year', array('placeHolder'=>'YYYY', 'default'=>date('Y'), 'type'=>'number')) ?>
</div>

<div class="form-group">
	<label for="ProyectoIncluir">Proyecto: </label>
	<?= $this->Form->input('incluir', array('empty'=>'Todos los proyectos...', 'options'=>$proyectos)) ?>
</div>

<div class="form-group">
	<label for="ProyectoTipo">Tipo de reporte: </label>
	<?= $this->Form->input('tipo', array('empty'=>'Seleccione...', 'options'=>$tiposReporte, 'required'=>true)) ?>
</div>

<div class="form-group" id='div_trimestre'>
	<label for="ProyectoTrimestre">Trimestre: </label>
	<?= $this->Form->input('trimestre', array('empty'=>'Seleccione...', 'options'=>$trimestre)) ?>
</div>

<div class="form-group" id='div_semestre'>
	<label for="ProyectoSemestre">Semestre: </label>
	<?= $this->Form->input('semestre', array('empty'=>'Seleccione...', 'options'=>$semestre)) ?>
</div>

<div class="btn-group">
	<button class='btn btn-default'>
		Descargar PDF <span class="glyphicon glyphicon-save"></span>
	</button>
	<a href="<?= $this->Html->url(array('controller'=>'Proyectos', 'action'=>'reportes')) ?>" class="btn btn-default">Cancelar</a>
</div>

<script>
$(document).ready(function() {
	$("#liProyectos").addClass('active');
	$("#ulProyectos").addClass('in');
	$("#lnk_reportes").addClass('current');

	var tipoReporte = $("#ProyectoTipo");
	var inital = tipoReporte.is(":checked");
	var topics = $("#div_trimestre, #div_semestre")[inital ? "show" : "hide"]();
	iniciar();
	//var topicInputs = topics.find("input").attr("disabled", !inital);
	tipoReporte.click(function() {
		iniciar();		
	});
	
	function iniciar(){
		var valor = $("#ProyectoTipo").val();
		switch(valor){
			case '1':
				$("#div_trimestre").show();
				$("#div_semestre").hide();
				$("#ProyectoTrimestre").attr('required', true);
				break;
			case '2':
				$("#div_semestre").show();
				$("#div_trimestre").hide();
				$("#ProyectoSemestre").attr('required', true);
				break;
			default:
				$("#div_trimestre, #div_semestre").hide();
				break;
		}
	}
});
</script>