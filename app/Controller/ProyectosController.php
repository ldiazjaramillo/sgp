<?php
App::uses('AppController', 'Controller');
/**
 * Proyectos Controller
 *
 * @property Proyecto $Proyecto
 */
class ProyectosController extends AppController {

	public $components = array('RequestHandler', 'Auth', 'Acl');
	//public $helpers = array('Pdata', 'Pimage', 'Pdraw');
	public $uses = array('Proyecto', 'Usuario', 'Actividad', 'Objetivo', 'VActividad', 'ReporteActividad');
	public $helpers = array('Breadcrumbs');

	public $paginate = array('Proyecto' => array('limit' => 10, 'order' => array('Proyecto.status' => 'asc')), 'Actividad' => array('limit' => 10, 'order' => array('Actividad.status, Actividad.fecha_culminacion' => 'asc')));

	function beforeFilter() {

		//pr($this->Auth);
		parent::beforeFilter();
		//$this->Auth->autoRedirect = false;
		//$this->Auth->authorize = array('Actions');
		$this -> Auth -> mapActions(array(
			'create' => array(''), 
			'read' => array('listProyectos', 'resumen', 'reporteResumen', 'reporteActividadesPersonal', 'reporteActividadesProyecto', 'reporteActividades', 'reporte_actividades_pdf', 'reportes', 'addFile'), 
			'update' => array('edit_porcentaje', 'resumenPdf', 'deleteArchivo'),
			//'delete' => array('evento_personal_delete')
		));

		//$this->Auth->allowedActions = array('index');//Descomentar esta linea para las acciones a las cuales queremos dar libre acceso. (Usar * para dar acceso a todas las acciones.)
	}

	/**
	 * index method
	 *
	 * @return void
	 */
	public function index() {

		$this -> Proyecto -> recursive = 0;
		$this -> Actividad -> recursive = 1;
		$rol_id = $this -> Auth -> user('rol_id');
		$centro_id = $this -> Auth -> user('centro_id');
		switch ($rol_id) {
			case 1 :
				$this -> set('proyectos', $this -> paginate('Proyecto', array('Proyecto.centro_id'=>$centro_id)));
				break;
			case 2 :
				$this -> set('proyectos', $this -> paginate('Proyecto', array('Proyecto.centro_id'=>$centro_id)));
				break;

			case 3 :
				$this -> set('proyectos', $this -> paginate('Proyecto', array(
					'Proyecto.coordinador_id' => $this -> Auth -> user('id'),
					'Proyecto.centro_id'=>$centro_id
					)));
				break;

			default :
				$this -> set('proyectos', $this -> paginate('Proyecto', array('Proyecto.centro_id'=>$centro_id)));
				break;
		}

		$this -> set('rol_id', $rol_id);
		$this -> set('actividades', $this -> paginate('Actividad', array('Actividad.responsable_id' => $this -> Auth -> user('id'), 'Actividad.status' => 1)));

	}

	public function listProyectos() {
		$this -> Proyecto -> recursive = 0;
		$this -> set('proyectos', $this -> paginate());
	}

	/**
	 * view method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function view($id = null) {
		$this -> Proyecto -> id = $id;
		if (!$this -> Proyecto -> exists()) {
			throw new NotFoundException(__('Proyecto invalido'));
		}
		$this -> set('proyecto', $this -> Proyecto -> find('all', array('conditions' => array('Proyecto.id' => $id), 'recursive' => 3,
		//'order'=>'Objetivo.id, Actividad.fecha_inicio'
		)));
	}

	/**
	*  add method
	*
	*  @return void
	*/
	public function add() {
		if ($this -> request -> is('post')) {
			$this -> Proyecto -> create();
			if($this->request->data['Proyecto']['fecha_culminacion'] < $this->request->data['Proyecto']['fecha_inicio']){
				$this -> Session -> setFlash(__('La fecha de culminación debe ser mayor a la fecha de inicio del proyecto'));
				return;
			}
			if ($this -> Proyecto -> save($this -> request -> data)) {
				$this -> Session -> setFlash(__('El proyecto ha sido creado con exito!'));
				$this -> redirect(array('action' => 'index'));
			} else {
				$this -> Session -> setFlash(__('El proyecto no pudo ser guardado. Por favor, verifique los datos he intente nuevamente.'));
			}
		}
		$this -> Set('personal', $this -> Usuario -> find('all', array('fields' => 'id, fullname', 'order' => 'fullname', 'conditions' => array('Usuario.status' => 1, 'rol_id !=' => 1, 'Usuario.centro_id'=>$this->Auth->user('centro_id')))));
	}

	/**
	 * edit method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function edit($id = null) {
		$this -> Proyecto -> id = $id;
		if (!$this -> Proyecto -> exists()) {
			throw new NotFoundException(__('Proyecto Invalido'));
		}
		$responsable_id = $this -> Proyecto -> read('coordinador_id');
		$coordinador_id = $responsable_id['Proyecto']['coordinador_id'];
		if ($this -> Auth -> user('id') != $coordinador_id && $this -> Auth -> user('rol_id') != 2) {
			$this -> Session -> setFlash(__('Disculpe, solo el coordinador del proyecto puede realizar modificaciones.'));
			$this -> redirect(array('action' => 'index'));
		}
		//pr($responsable_id);
		if ($this -> request -> is('post') || $this -> request -> is('put')) {
			if ($this -> Proyecto -> save($this -> request -> data)) {
				$this -> Session -> setFlash(__('El proyecto ha sido modificado con exito!'));
				$this -> redirect(array('action' => 'index'));
			} else {
				$this -> Session -> setFlash(__('El proyecto no pudo ser modificado. Por favor, verifique los datos he intente nuevamente.'));
			}
		} else {
			$this -> request -> data = $this -> Proyecto -> read(null, $id);
			$this -> Set('personal', $this -> Usuario -> find('all', array('fields' => 'id, fullname', 'order' => 'fullname', 'conditions' => array('Usuario.status' => 1, 'rol_id !=' => 1, 'Usuario.centro_id'=>$this->Auth->user('centro_id')))));
		}
	}

	/**
	 * delete method
	 *
	 * @throws MethodNotAllowedException
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function delete($id = null) {
		if (!$this -> request -> is('post')) {
			//throw new MethodNotAllowedException();
		}
		$this -> Proyecto -> id = $id;
		if (!$this -> Proyecto -> exists()) {
			throw new NotFoundException(__('Proyecto No Existe'));
		}
		if ($this -> Proyecto -> delete()) {
			$this -> Session -> setFlash(__('Proyecto eliminado con exito'));
			$this -> redirect(array('action' => 'index'));
		}
		$this -> Session -> setFlash(__('El Proyecto no fue eliminado'));
		$this -> redirect(array('action' => 'index'));
	}

	/**
	 * admin_index method
	 *
	 * @return void
	 */
	public function admin_index() {
		$this -> Proyecto -> recursive = 0;
		$this -> set('proyectos', $this -> paginate());
	}

	/**
	 * admin_view method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function admin_view($id = null) {
		$this -> Proyecto -> id = $id;
		if (!$this -> Proyecto -> exists()) {
			throw new NotFoundException(__('Invalid proyecto'));
		}
		$this -> set('proyecto', $this -> Proyecto -> read(null, $id));
	}

	/**
	 * admin_add method
	 *
	 * @return void
	 */
	public function admin_add() {
		if ($this -> request -> is('post')) {
			$this -> Proyecto -> create();
			if ($this -> Proyecto -> save($this -> request -> data)) {
				$this -> Session -> setFlash(__('The proyecto has been saved'));
				$this -> redirect(array('action' => 'index'));
			} else {
				$this -> Session -> setFlash(__('The proyecto could not be saved. Please, try again.'));
			}
		}
	}

	/**
	 * admin_edit method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function admin_edit($id = null) {
		$this -> Proyecto -> id = $id;
		if (!$this -> Proyecto -> exists()) {
			throw new NotFoundException(__('Invalid proyecto'));
		}
		if ($this -> request -> is('post') || $this -> request -> is('put')) {
			if ($this -> Proyecto -> save($this -> request -> data)) {
				$this -> Session -> setFlash(__('The proyecto has been saved'));
				$this -> redirect(array('action' => 'index'));
			} else {
				$this -> Session -> setFlash(__('The proyecto could not be saved. Please, try again.'));
			}
		} else {
			$this -> request -> data = $this -> Proyecto -> read(null, $id);
		}
	}

	/**
	 * admin_delete method
	 *
	 * @throws MethodNotAllowedException
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function admin_delete($id = null) {
		if (!$this -> request -> is('post')) {
			throw new MethodNotAllowedException();
		}
		$this -> Proyecto -> id = $id;
		if (!$this -> Proyecto -> exists()) {
			throw new NotFoundException(__('Invalid proyecto'));
		}
		if ($this -> Proyecto -> delete()) {
			$this -> Session -> setFlash(__('Proyecto deleted'));
			$this -> redirect(array('action' => 'index'));
		}
		$this -> Session -> setFlash(__('Proyecto was not deleted'));
		$this -> redirect(array('action' => 'index'));
	}

	public function edit_porcentaje($id = null) {
		$this -> Proyecto -> id = $id;
		if (!$this -> Proyecto -> exists()) {
			throw new NotFoundException(__('Proyecto Invalido'));
		}
		if ($this -> request -> is('post')) {
			//pr($this->request->data);
			foreach ($this->request->data['peso'] as $key => $value) {
				$data = array();
				$this -> Actividad -> create();
				$this -> Actividad -> id = $key;
				$data['peso'] = $value;
				$this -> Actividad -> save($data);
			}
			$this -> Session -> setFlash(__('Pesos de las actividades actualizadas!'));
			$this -> redirect(array('action' => 'view', $id));
		}
		$this -> set('nombreP', $this -> Proyecto -> read('name'));
		$actividades = $this -> Proyecto -> Objetivo -> Actividad -> find('all', array('conditions' => array('Objetivo.proyecto_id' => $id), 'order' => 'Actividad.id'));
		$this -> set('actividades', $actividades);
	}

	public function resumen($objetivo_id = null) {
		if (!is_null($objetivo_id)) {
			$objetivo = $this -> Proyecto -> Objetivo -> findById($objetivo_id);
			$usuario_id = $objetivo['Proyecto']['coordinador_id'];
			$usuario = $this -> Usuario -> Find('all', array('conditions' => array('Usuario.id' => $usuario_id, 'Usuario.centro_id'=>$this->Auth->user('centro_id')), 'recursive' => -1));
			//pr($usuario);
			$this -> set('fullname', $usuario[0]['Usuario']['fullname']);
			$this -> set('proyecto', $objetivo['Proyecto']);
			//pr($proyecto);
		}
	}

	/*
	 * Aqui comienza las funciones para los reportes de proyectos y actividades
	 */

	public function reportes() {

	}

	public function reporteResumen() {
		$Proyecto = $this -> Proyecto -> find('all', array('conditions' => array('Proyecto.centro_id'=>$this->Auth->user('centro_id')), 'fields' => 'Proyecto.id, Proyecto.name', 'order' => 'Proyecto.name', 'recursive' => -1, ));
		$this -> set('proyectos', $Proyecto);

	}

	public function resumenPdf($tipo = null, $id = null) {

		if (is_null($id) && is_null($tipo)) {
			$this -> Session -> setFlash(__('El proyecto no existe.'));
			$this -> redirect(array('action' => 'index'));
		}
		switch ($tipo) {
			case 1 :
				//Proyectos individuales
				$Proyecto = $this -> Proyecto -> find('all', array('conditions' => array('Proyecto.id' => $id, 'Proyecto.centro_id'=>$this->Auth->user('centro_id')), 'recursive' => 3, ));
				break;
			case 2 :
				//Todos los proyectos
				$Proyecto = $this -> Proyecto -> find('all', array('conditions' => array('Proyecto.centro_id'=>$this->Auth->user('centro_id')), 'recursive' => 3, ));
				break;
			case 3 :
				//Solo activos
				$Proyecto = $this -> Proyecto -> find('all', array('conditions' => array('Proyecto.status' => 1, 'Proyecto.centro_id'=>$this->Auth->user('centro_id')), 'recursive' => 3, ));
				break;
			case 4 :
				//Solo culminados
				$Proyecto = $this -> Proyecto -> find('all', array('conditions' => array('Proyecto.status' => 3, 'Proyecto.centro_id'=>$this->Auth->user('centro_id')), 'recursive' => 3, ));
				break;

			default :
				$Proyecto = $this -> Proyecto -> find('all', array('conditions' => array('Proyecto.centro_id'=>$this->Auth->user('centro_id')), 'recursive' => 3, ));
				break;
		}
		if (empty($Proyecto)) {
			$this -> Session -> setFlash(__('El reporte seleccionado no contiene información'));
			$this -> redirect(array('action' => 'reporteResumen'));
		}
		$this -> set('Proyecto', $Proyecto);
		$this -> set('tipo', $tipo);

		$this -> layout = "pdf";

	}

	public function reporteActividadesPersonal() {
		$rol_id = $this -> Auth -> user('rol_id');
		$user_id = $this -> Auth -> user('id');
		if ($this -> request -> is('post')) {
			//pr($this->request->data);
			
			$proyecto_id = $this -> request -> data['Proyecto']['incluir'];
			$tipo_reporte = $this -> request -> data['Proyecto']['tipo'];
			$mes = $this -> request -> data['Proyecto']['mes'];
			$trimestre = $this -> request -> data['Proyecto']['trimestre'];
			$semestre = $this -> request -> data['Proyecto']['semestre'];
			$year = $this -> request -> data['Proyecto']['year'];
			$meses = array(1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Obtubre', 11 => 'Noviembre', 12 => 'Diciembre');

			switch ($tipo_reporte) {
				case 1 :
					$conditions = array('year' => date('Y'), 'mes' => $mes);
					$tipo = "Mes: " . $meses[$mes];
					break;
				case 2 :
					switch ($trimestre) {
						case 1 :
							$tri = array(1, 3);
							$tipo = "1er Trimestre";
							break;
						case 2 :
							$tri = array(4, 6);
							$tipo = "2do Trimestre";
							break;
						case 3 :
							$tri = array(7, 9);
							$tipo = "3er Trimestre";
							break;
						case 4 :
							$tri = array(10, 12);
							$tipo = "4to Trimestre";
							break;
						default :
							break;
					}
					$conditions = array('year' => date('Y'), 'mes BETWEEN ? AND ?' => $tri);
					break;
				case 3 :
					switch ($semestre) {
						case 1 :
							$sem = array(1, 6);
							$tipo = "1er Semestre";
							break;
						case 2 :
							$sem = array(7, 12);
							$tipo = "2do Semestre";
							break;
						default :
							$sem = array(1, 6);
							$tipo = "1er Semestre";
							break;
					}
					$conditions = array('year' => date('Y'), 'mes BETWEEN ? AND ?' => $sem);
					break;
				case 4 :
					$conditions = array('year' => $year);
					$tipo = "Año $year";
					break;

				default :
					$conditions = array();
					break;
			}

			if (empty($proyecto_id)) {
				$render = 'pdfActividadesPersonalProyectos';
			} else {
				$cond_proy = array('proyecto_id' => $proyecto_id);
				array_push($conditions, $cond_proy);
				$render = 'pdfActividadesPersonal';
			}
			$proyecto = $this -> VActividad -> find('all', array('conditions' => $conditions, 'resursive' => -1, 'fields' => 'fullname, proyecto, actividad, fecha_inicio, fecha_culminacion, status_actividad, SUM(avance) as avance', 'group' => 'fullname, proyecto, actividad, fecha_inicio, fecha_culminacion, status_actividad, actividad_id, year', 'order' => 'proyecto, actividad'));
			if (empty($proyecto)) {
				$this -> Session -> setFlash(__('El reporte no arrojo resultados, intente nuevamente.'));
				//$render = 'pdfActividadesPersonalProyectos';
			} else {
				$this -> set("Proyecto", $proyecto);
				$this -> set("tipo", $tipo);
				//$render = 'pdfActividadesPersonalProyectos';
				$this -> layout = "pdf";
				$this -> render($render);
			}

		}
		if($rol_id==2){
			$condicionesP = array();
		}else{
			$condicionesP = array('coordinador_id'=>$user_id);
		}
		
		$proyectos = $this -> Proyecto -> find('list', array(
				'conditions'=>$condicionesP
			));
		$this -> set('proyectos', $proyectos);
		$this -> set('rol_id', $rol_id);
	}

	public function reporteActividadesProyecto() {
		if ($this -> request -> is('post')) {
			//pr($this->request->data);
			$proyecto_id = $this -> request -> data['Proyecto']['incluir'];
			$tipo_reporte = $this -> request -> data['Proyecto']['tipo'];
			$trimestre = $this -> request -> data['Proyecto']['trimestre'];
			$semestre = $this -> request -> data['Proyecto']['semestre'];
			$year = $this -> request -> data['Proyecto']['year'];
			switch ($tipo_reporte) {
				case 1 :
					switch ($trimestre) {
						case 1 :
							$tri = array(1, 3);
							$tipo = "1er Trimestre";
							break;
						case 2 :
							$tri = array(4, 6);
							$tipo = "2do Trimestre";
							break;
						case 3 :
							$tri = array(7, 9);
							$tipo = "3er Trimestre";
							break;
						case 4 :
							$tri = array(10, 12);
							$tipo = "4to Trimestre";
							break;
						default :
							break;
					}
					$conditions = array('year' => $year, 'mes BETWEEN ? AND ?' => $tri);
					break;
				case 2 :
					switch ($semestre) {
						case 1 :
							$sem = array(1, 6);
							$tipo = "1er Semestre";
							break;
						case 2 :
							$sem = array(7, 12);
							$tipo = "2do Semestre";
							break;
						default :
							$sem = array(1, 6);
							$tipo = "1er Semestre";
							break;
					}
					$conditions = array('year' => $year, 'mes BETWEEN ? AND ?' => $sem);
					break;

				default :
					$conditions = array();
					break;
			}

			if (!empty($proyecto_id)) {
				$cond_proy = array('proyecto_id' => $proyecto_id);
				array_push($conditions, $cond_proy);
			}
			$render = 'pdfActividadesProyectos';
			$proyecto = $this -> VActividad -> find('all', array('conditions' => $conditions, 'resursive' => -1, 'fields' => 'proyecto, coordinador, inicio_proyecto, fin_proyecto, status_proyecto, SUM(avance) as porcentaje, COALESCE(peso, 0) as peso', 'group' => 'proyecto, coordinador, inicio_proyecto, fin_proyecto, status_proyecto, peso, actividad_id', 'order' => 'proyecto'));
			if (empty($proyecto)) {
				$this -> Session -> setFlash(__('El reporte no arrojo resultados, intente nuevamente.'));
				$render = 'reporteActividadesProyecto';
			} else {
				//pr($proyecto);
				$this -> set("Proyecto", $proyecto);
				$this -> set("tipo", $tipo);
				//$render = 'pdfActividadesPersonalProyectos';
			}
			$this -> layout = "pdf";
			$this -> render($render);
		}
		$proyectos = $this -> Proyecto -> find('list');
		$this -> set('proyectos', $proyectos);

	}

	public function reporteActividades() {
		$rol_id = $this -> Auth -> user('rol_id');
		if ($this->request->is('post')) {
			$semestre = $this -> request -> data['Proyecto']['semestre'];
			$proyecto_id = $this -> request -> data['Proyecto']['proyecto_id'];
			switch ($semestre) {
				case 1 :
					$sem = array(1, 6);
					$tipo = "1er Semestre";
					break;
				case 2 :
					$sem = array(7, 12);
					$tipo = "2do Semestre";
					break;
				default :
					$sem = array(1, 12);
					$tipo = "1er Semestre";
					break;
			}
			$year = date('Y');
			if(empty($proyecto_id)){
				$cond_rol = array();
				$mostrar = true;
			}else{
				$cond_rol = array('ReporteActividad.proyecto_id'=>$proyecto_id);
				$mostrar = false;
			}
			$this->set('mostrar', $mostrar);
			$condiciones = array(
				'OR'=>array(
						'month(ReporteActividad.fecha_culminacion) BETWEEN ? AND ? AND year(fecha_culminacion) ='.$year => $sem,
						'month(ReporteActividad.fecha_inicio) BETWEEN ? AND ? AND year(fecha_inicio)='.$year => $sem,
				),
				'ReporteActividad.status' => 1,
				$cond_rol
			);
			$actividades = $this->ReporteActividad -> find('all', array('conditions' => $condiciones, 'order' => 'ReporteActividad.fullname',
			//'recursive'=>-1
			));
			$db = $this -> Usuario -> getDataSource();
			$subQuery = $db -> buildStatement(array('fields' => array('"ReporteActividad"."responsable_id"'), 'table' => $db -> fullTableName($this -> ReporteActividad), 'alias' => 'ReporteActividad', 'limit' => null, 'offset' => null, 'joins' => array(), 'conditions' => $condiciones, 'order' => null, 'group' => null), $this -> Usuario);
			$subQuery = ' "Usuario"."id" NOT IN (' . $subQuery . ') and status=1 and rol_id!=1';
			$subQueryExpression = $db -> expression($subQuery);
			//pr($sem);
			//pr($subQueryExpression);
			$conditions[] = $subQueryExpression;
			$fields = array('Usuario.fullname');
			$order = array('Usuario.fullname');
			$recursive = -1;
			$personas2 = $this->Usuario->find('all', compact('conditions', 'fields', 'recursive', 'order'));
			$this -> set(compact('actividades', 'personas2'));
		}
		switch ($rol_id) {
			case 1 :
				$proyectos = $this->Proyecto->Find('list', array('conditions'=>array('Proyecto.status'=>1)));
				break;
			case 2 :
				$proyectos = $this->Proyecto->Find('list', array('conditions'=>array('Proyecto.status'=>1, 'Proyecto.status'=>1)));
				break;

			case 3 :
				//echo "estoy aqui";
				$proyectos = $this->Proyecto->Find('list', array(
					'conditions'=>array('Proyecto.coordinador_id' => $this -> Auth -> user('id'), 'Proyecto.status'=>1)
					));
				break;

			default :
				$proyectos = $this->Proyecto->Find('list', array('Proyecto.coordinador_id' => $this -> Auth -> user('id'), 'Proyecto.status'=>1));
				break;
		}
		$this -> set(compact('rol_id', 'proyectos'));
		
		
	}

	public function pdfActividadesPersonal() {
		$this -> layout = "pdf";
		
	}
	
	public function reporte_actividades_pdf($semestre="") {
		$this -> layout = "pdf";
		switch ($semestre) {
			case 1 :
				$sem = array(1, 6);
				$tipo = "1er Semestre";
				break;
			case 2 :
				$sem = array(7, 12);
				$tipo = "2do Semestre";
				break;
			default :
				$sem = array(1, 12);
				$tipo = "1er Semestre";
				break;
		}
		$year = date('Y');
		$condiciones = array(
			'OR'=>array(
					'month(ReporteActividad.fecha_culminacion) BETWEEN ? AND ? AND year(fecha_culminacion) ='.$year => $sem,
					'month(ReporteActividad.fecha_inicio) BETWEEN ? AND ? AND year(fecha_inicio)='.$year => $sem,
			),
			'ReporteActividad.status' => 1, 
		);
		$actividades = $this->ReporteActividad -> find('all', array('conditions' => $condiciones, 'order' => 'ReporteActividad.fullname',
		//'recursive'=>-1
		));
		$this -> set(compact('actividades'));
	}

	public function addFile(){
		if (!$this -> request -> is('post')) {
			throw new MethodNotAllowedException();
		}
		
		$this->request->data['Proyecto']['archivo']['proyecto_id'] = $this->request->data['Proyecto']['proyecto_id'];
		$archivo = $this->request->data['Proyecto']['archivo'];
		$proyecto_id = $this->request->data['Proyecto']['proyecto_id'];
		//pr($archivo);
		if($this->moverArchivo($archivo)){
			$this -> Session -> setFlash('El archivo fue agregado al proyecto con correctamente.');
		}else{
			$this -> Session -> setFlash('Ocurrieron errores al subir el archivo. Por favor, intente nuevamente.');
		}
		$this -> redirect(array('action' => 'view', $proyecto_id));
		
	}

	public function moverArchivo($archivo){
		$proyecto_id = $archivo['proyecto_id'];
		$ruta = WWW_ROOT."/files/proyectos/".$proyecto_id."/";
		if( !file_exists($ruta) ) mkdir($ruta, 0777, true);
		if(move_uploaded_file($archivo['tmp_name'], $ruta.$archivo['name'])) return true;
		else return false;
	}

	public function deleteArchivo($proyecto_id){
		if (!$this -> request -> is('post')) {
			throw new MethodNotAllowedException();
		}
		//pr($this->request->data);
		$ruta = "/files/proyectos/".$proyecto_id."/";
		$archivo = WWW_ROOT.$ruta.$this->request->data['name'];
		if (unlink($archivo)) http_response_code(200);
		else http_response_code(404);
		exit;

	}

}
