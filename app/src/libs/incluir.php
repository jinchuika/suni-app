<?php
/**
* Encabezado de inclusión, para llenar librerías (intento de tabla de rutas)
*/
class librerias
{
	/*
	Utilizada para averiguar la cantidad de saltos entre carpetas que debe hacerse hasta llegar a donde se encuentren las librerías.
	*/
	var $nivel;
	var $nivel_entrada;
	function __construct($nivel_entrada)
	{
		$this->nivel_entrada = $nivel_entrada;
		for ($i=0; $i < $nivel_entrada; $i++) { 
			$this->nivel .= "../";
		}
	}
	public function incluir($llamada, $extra=null)
	{
		switch ($llamada) {
			case 'jquery':
				$this->imprimir("js", "js/framework/jquery.js");
				$this->imprimir("js-libs1", "general.js");
				break;
			case 'jquery-form':
				$this->imprimir("js", "js/framework/jquery.form.js");
				break;
			case 'stupidtable':
				$this->imprimir("js", "js/framework/stupidtable.min.js");
				break;
			case 'gn-date':
				$this->imprimir("js-libs", "general_datetime.js");
				break;
			case 'bs':
				$this->imprimir("css", "css/bootplus/css/bootplus.css");
				$this->imprimir("css", "css/bootplus/css/bootplus-responsive.min.css");

				$this->imprimir("js", "js/framework/bootstrap.js");
				$this->imprimir("js", "js/framework/bootbox.js");

				$this->imprimir("css", "js/framework/select2/select2.css");
				$this->imprimir("js", "js/framework/select2/select2.js");
				break;
			case 'bs-editable':
				$this->imprimir("css", "js/framework/bootstrap-editable/css/bootstrap-editable.css");
				$this->imprimir("js", "js/framework/bootstrap-editable/js/bootstrap-editable.js");
				$this->imprimir("css", "js/framework/bootstrap-datepicker/css/datepicker.css");
				$this->imprimir("js", "js/framework/bootstrap-datepicker/js/bootstrap-datepicker.es.js");
				break;
			case 'jquery-ui':
				$this->imprimir("css", "css/jqueryui/flick/jquery-ui-1.10.3.custom.css");
				$this->imprimir("js", "js/framework/jquery-ui.min.js");
				break;
			case 'time-picker':
				$this->imprimir("js", "js/framework/jquery-ui-timepicker.js");
				break;
			case 'seguridad':
				$this->imprimir("php", "includes/auth/login.class.php");
				$vlog = vLog("usuario", "0",$this->nivel."admin.php");
				$this->imprimir("php", "includes/auth/sesion.class.php");
				$sesion = sesion::getInstance($vlog);
				//$sesion->set_instance($vlog);
				if(is_array($extra)){
					if($extra['tipo']=='validar'){
						$sesion->validar_acceso($extra['id_area'], $this->nivel_entrada);
					}
				}
				return $sesion;
				break;
			case 'cabeza':
				$this->imprimir("src", "cabeza.php");
				break;
			case 'mapa':
				$this->imprimir("src", "mapa.php");
				break;
			case 'bd':
				$this->imprimir("php", "includes/auth/Db.class.php");
				$this->imprimir("php", "includes/auth/Conf.class.php");
				return Db::getInstance();
				break;
			case 'bd_tpe':
				$this->imprimir("php", "includes/auth/Db_tpe.class.php");
				$this->imprimir("php", "includes/auth/Conf_tpe.class.php");
				return Db::getInstance();
				break;
			case 'bd0':
				$this->imprimir("php", "Db.class.php");
				$this->imprimir("php", "Conf.class.php");
				return Db::getInstance();
				break;
			case 'handson':
				$this->imprimir("css", "js/framework/handsontable/jquery.handsontable.full.css");
				$this->imprimir("js", "js/framework/handsontable/jquery.handsontable.full.js");
				break;
			case 'bs-confirm':
				$this->imprimir("js", "js/framework/bootstrap-confirm.js");
				break;
			case 'calendario':
				$this->imprimir("css", "js/framework/fullcalendar/fullcalendar.css");
				$this->imprimir("js", "js/framework/fullcalendar/fullcalendar.js");
				$this->imprimir("js", "js/framework/fullcalendar/gcal.js");
				break;
			case 'timeline':
				$this->imprimir("js", "js/framework/timeline/timeline-min.js");
				$this->imprimir("js", "js/framework/timeline/timeline-locales.js");
				$this->imprimir("css", "js/framework/timeline/timeline.css");
				break;
			case 'notify':
				$this->imprimir("css", "js/framework/pnotify/jquery.pnotify.default.css");
				$this->imprimir("js", "js/framework/pnotify/jquery.pnotify.js");
				break;
			case 'meta':
				$this->imprimir("meta", 'name="viewport" content="width=device-width"');
				break;
			case 'listar':
				$this->imprimir("js-libs", 'listar.js');
				$this->imprimir("js", 'js/framework/filtro_lista.js');
				break;
			case 'gn-listar':
				$this->imprimir("js-libs", 'general_listar.js');
				$this->imprimir("js", 'js/framework/filtro_lista.js');
				$this->imprimir("css", 'css/lista_filtrada.css');
				break;
			case 'chartsjs':
				$this->imprimir("js", 'js/framework/chartjs/Lib/js/globalize.min.js');
				$this->imprimir("js", 'js/framework/chartjs/Lib/js/dx.chartjs.js');
				break;
			case 'listjs':
				$this->imprimir("js", 'js/framework/list.min.js');
				break;
			case 'google_chart':
				echo '<script type="text/javascript" src="https://www.google.com/jsapi"></script>
				';
				$this->imprimir("js-libs", 'google_chart.js');
				break;
			case 'paginacion':
				/* Para utilizar paginación en base a una tabla*/
				$this->imprimir("php", "app/src/libs_tpe/paginacion.php");
				break;
			default:
				# code...
				break;
		}
	}
	public function defecto()
	{
		$this->incluir('cabeza');
		$this->incluir('jquery');
		$this->incluir('bs');
		$this->incluir('meta');
		$sesion_r = sesion::getInstance();
		$this->incluir_general($sesion_r->get('id_per'), $this->nivel);
	}
	public function incluir_general($id_per, $rol)
	{
		echo '<script id="js_general" id_per="'.$id_per.'" nivel="'.$this->nivel.'" src="'.$this->nivel.'app/src/js-libs/general.js"></script>
		';
	}
	private function imprimir($tipo, $archivo)
	{
		if ($tipo=='js') {
			echo '<script src="'.$this->nivel.$archivo.'"></script>
			';
		}
		if ($tipo=='css') {
			echo '<link href="'.$this->nivel.$archivo.'" rel="stylesheet"/>
			';
		}
		if ($tipo=='js-libs') {
			echo '<script src="'.$this->nivel.'/app/src/js-libs/'.$archivo.'"></script>
			';
		}
		
		if ($tipo=='php') {
			if(require_once($this->nivel.$archivo)){
				
			}
		}
		if ($tipo=='src') {
			if(require_once($archivo)){
				//echo "incluido ".$archivo;
			}
			else{
				echo $archivo." no pudo cargarse";
			}
		}
		if ($tipo=='libs_tpe') {
			if(require_once("../libs_tpe/".$archivo)){
				//echo "incluido ".$archivo;
			}
			else{
				echo $archivo." no pudo cargarse";
			}
		}
		if ($tipo=='meta') {
			echo '<meta '.$archivo.'>
			';
		}
	}
}
?>