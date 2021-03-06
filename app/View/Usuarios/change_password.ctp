<h2>Cambiar clave</h2>
<?php
$this->Html->addCrumb('Cambiar clave', '');

$defaults = array('label'=>false, 'div'=>false, 'class'=>'form-control');
echo $this->Form->create('Usuario', array('class'=>'form', 'inputDefaults'=>$defaults));
$usuario_id = AuthComponent::user('id');
?>
<div class="col-md-4">
	<div class="row">
		<div class="form-group has-feedback" id='div1'>
			<label for="UsuarioClave" class="control-label">Nueva Contraseña: </label>
			<?= $this->Form->input('clave', array('required'=>true, 'class'=>'form-control', 'type'=>'password')) ?>
			<span id='span1' class="glyphicon glyphicon-ok form-control-feedback"></span>
		</div>
	</div>
	<div class="row">
		<div class="form-group has-feedback" id='div2'>
			<label for="UsuarioClave2" class="control-label">Confirmar Contraseña: </label>
			<?= $this->Form->input('clave2', array('required'=>true, 'class'=>'form-control', 'type'=>'password')) ?>
			<span id='span2' class="glyphicon glyphicon-ok form-control-feedback"></span>
		</div>
	</div>
	
	<div class="btn-group">
		<?php echo $this->Form->end(array('label'=>'Guardar', 'div'=>false, "class"=>"btn btn-default", 'id'=>'btn_submit')); ?>
		<a href="<?= $this->Html->url(array('controller'=>'Panel', 'action'=>'index')) ?>" class="btn btn-default">Cancelar</a>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function () {
	var valido=false;
	$('#span1, #span2').removeClass('glyphicon-ok');
	$("#UsuarioClave").keyup(function(e) {
        var pass = $("#UsuarioClave").val();
        if(pass===''){
        	$('#div1').addClass('has-warning');
            $('#span1').addClass('glyphicon-warning');
            $('#btn_submit').attr('disabled', true);
            valido=true;
        }else if(pass.length < 6){
        	$('#div1').addClass('has-warning');
            $('#span1').addClass('glyphicon-warning-sign');
            $('#btn_submit').attr('disabled', true);
            valido=true;
        }else{
        	$('#div1').removeClass('has-warning');
        	$('#div1').addClass('has-success');
        	$('#span1').removeClass('glyphicon-warning-sign');
            $('#span1').addClass('glyphicon-ok');
            $('#btn_submit').attr('disabled', true);
            valido=true;
        }
    });//fin keyup repass
    $("#UsuarioClave2").keyup(function(e) {
        var pass = $("#UsuarioClave").val();
        var re_pass=$("#UsuarioClave2").val();
 		
        if(pass != re_pass){
        	$('#span2').removeClass('glyphicon-warning-sign');
            $('#span2').removeClass('glyphicon-ok');
            $('#div2').removeClass('has-success');
            $('#div2').addClass('has-warning');
            $('#span2').addClass('glyphicon-warning-sign');
            $('#btn_submit').attr('disabled', true);
            valido=true;
        }else{
        	$('#span2').removeClass('glyphicon-warning-sign');
        	$('#span2').addClass('glyphicon-ok');
        	$('#div2').removeClass('has-warning');
            $('#div2').addClass('has-success');
            $('#btn_submit').attr('disabled', false);
            
            valido=true;
        }
    });//fin keyup repass
});
</script>