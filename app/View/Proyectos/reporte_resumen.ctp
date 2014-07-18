<?php
$this->Html->addCrumb('Proyectos', array('controller'=>'Proyectos', 'action'=>'index'));
$this->Html->addCrumb('Reportes', array('controller'=>'Proyectos', 'action'=>'reportes'));
$this->Html->addCrumb('Resumen de proyectos', '');
?>
<h2>Resumen de Proyectos</h2>


<div class="list-group col-md-6">
	<?php
		echo $this->Html->link('Por Proyectos individuales', '#proyectos', 
			array('id'=>'selProyectos', "class"=>"list-group-item list-group-item-info", 'data-toggle'=>'modal'));
		echo $this->Html->link('Todos los proyectos', array('action'=>'resumenPdf', 2), array("class"=>"list-group-item list-group-item-success"));
		echo $this->Html->link('Solo Proyectos Activos', array('action'=>'resumenPdf', 3), array("class"=>"list-group-item list-group-item-warning"));
		echo $this->Html->link('Solo Proyectos Culminados', array('action'=>'resumenPdf', 4), array("class"=>"list-group-item list-group-item-danger"));
	?>
</div>
<div class="modal fade" id="proyectos">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Listado de Proyectos</h3>
      </div>
      <div class="modal-body">
        <div class="list-group">
			<?php
				//echo count($personal);
				
				foreach ($proyectos as $row) {
					//pr($row);
					$name = $row['Proyecto']['name'];
					$proyecto_id = $row['Proyecto']['id'];
					
					echo $this->Html->link($name, array('action'=>'resumenPdf', 1, $proyecto_id), 
						array("class"=>"list-group-item list-group-item-default lnkPro")
					);
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
	$("#liProyectos").addClass('active');
	$("#ulProyectos").addClass('in');
	$("#lnk_reportes").addClass('current');

	$(".lnkPro").on("click", function() {
		$('#proyectos').modal('hide');
		//$.fancybox.close();
	})
	
});
</script>