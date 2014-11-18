<?php 
if (AuthComponent::user('id')):
?>
<div class="sidebar-nav">
  <div class="navbar navbar-default" role="navigation">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <span class="visible-xs navbar-brand">Sistema Gestión de Proyectos</span>
    </div>
    <div class="navbar-collapse collapse sidebar-navbar-collapse">
      <ul class="nav navbar-nav">
        <li id="liHome"><?= $this->Html->link('Inicio', array('controller'=>'Panel', 'admin'=>false)) ?></li>
        <li id="liProyectos">
          <a data-toggle="collapse" data-parent="#accordion" href="#ulProyectos">Proyectos <b class="caret"></b></a>
          <ul id="ulProyectos" class="ul-collapse collapse nav nav-stacked sub-nav">
          <?php
            if(AuthComponent::user('rol_id') == 2):
          ?>
            <li><?= $this->Html->link('Agregar', array('controller'=>'Proyectos', 'action'=>'add', 'admin'=>false), array('id'=>'lnk_proyectosAdd')) ?></li>
          <?php
            endif;
          ?>
            <li><?= $this->Html->link('Información', array('controller'=>'Proyectos', 'action'=>'index', 'admin'=>false), array('id'=>'lnk_proyectos')) ?></li>
            
            <li><?= $this->Html->link('Reportes', array('controller'=>'Proyectos', 'action'=>'reportes', 'admin'=>false), array('id'=>'lnk_reportes')) ?></li>
          </ul>
        </li>
        <li id="liActividades">
          <a data-toggle="collapse" data-parent="#accordion" href="#ulActividades">Actividades <b class="caret"></b></a>
          <ul id="ulActividades" class="ul-collapse collapse nav nav-stacked sub-nav">
            <li><?= $this->Html->link('Mis actividades', array('controller'=>'Actividades', 'action'=>'index', 'admin'=>false), array('id'=>'lnk_actividades')) ?></li>
          </ul>
        </li>
        <li id="liHoras">
          <a data-toggle="collapse" data-parent="#accordion" href="#ulHoras">Registro de Horas <b class="caret"></b></a>
          <ul id="ulHoras" class="ul-collapse collapse nav nav-stacked sub-nav">
            <li><?= $this->Html->link('Registro', array('controller'=>'Horas', 'action'=>'add', 'admin'=>false), array('id'=>'lnk_registro_horas')) ?></li>
          </ul>
        </li>
        <li id="liPermisos">
          <a data-toggle="collapse" data-parent="#accordion" href="#ulPermisos">Permisos<b class="caret"></b></a>
          <ul id="ulPermisos" class="ul-collapse collapse nav nav-stacked sub-nav">
            <li><?= $this->Html->link('Solicitar', array('controller'=>'Permisos', 'action'=>'add', 'admin'=>false), array('id'=>'lnk_registro_permisos')) ?>
            <li><?= $this->Html->link('Histórico', array('controller'=>'Permisos', 'action'=>'index', 'admin'=>false), array('id'=>'lnk_historico_permisos')) ?></li>
            <li><?= $this->Html->link('Reportes', array('controller'=>'Permisos', 'action'=>'reportes', 'admin'=>false), array('id'=>'lnk_reporte_permisos')) ?></li>
          </ul>
        </li>
        <li id="liVacaciones">
          <a data-toggle="collapse" data-parent="#accordion" href="#ulVacaciones">Vacaciones<b class="caret"></b></a>
          <ul id="ulVacaciones" class="ul-collapse collapse nav nav-stacked sub-nav">
            <li><?= $this->Html->link('Solicitar', array('controller'=>'Vacaciones', 'action'=>'solicitarDisponibles', 'admin'=>false), array('id'=>'lnk_registro_vacaciones')) ?>
            <li><?= $this->Html->link('Histórico', array('controller'=>'Vacaciones', 'action'=>'index', 'admin'=>false), array('id'=>'lnk_historico_vacaciones')) ?></li>
            <li><?= $this->Html->link('Reportes', array('controller'=>'Vacaciones', 'action'=>'reportes', 'admin'=>false), array('id'=>'lnk_reporte_vacaciones')) ?></li>
          </ul>
        </li>
        <li><?= $this->Html->link('Configuración', array('controller'=>'Panel', 'admin'=>true)) ?></li>
        <li><?= $this->Html->link('Cerrar Sesión', array('controller'=>'Usuarios', 'action'=>'logout', 'admin'=>false)) ?></li>
      </ul>
    </div><!--/.nav-collapse -->
  </div>
</div>
<?php
endif;
?>