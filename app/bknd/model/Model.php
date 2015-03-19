<?php
/**
* Modelo general
*/
class Model
{
	/**
	 * La conexión a la base de datos
	 * @var Database
	 */
	public $bd;

	/**
	 * Crea la instancia de la conexión a la base de datos
	 */
	public function __construct()
	{
		$this->bd = Database::getInstance();
	}

	/**
	 * Crea el texto para hacer filtros en una consulta MySQL
	 * @param  Array  $arrFiltros Cada elemento en forma {condición}
	 * @return string              El texto para el filtro
	 */
	public function crearFiltros(Array $arrFiltros=null)
	{
		$texto = '';
		if(!empty($arrFiltros) && is_array($arrFiltros)){
			$arrFiltrosSalida = array();
			foreach ($arrFiltros as $campo => $valor) {
				$filtro = explode("[" , rtrim($campo, "]"));
				$filtroCompuesto = isset($filtro[1]) ? $filtro[0].$filtro[1] : $filtro[0].'=';
				array_push($arrFiltrosSalida, $filtroCompuesto."'".$valor."'");
			}
			$texto = ' where '.implode(' AND ', $arrFiltrosSalida);
		}
		
		return $texto;
	}

	/**
	 * Crea las condiciones para filtrar un rango de fechas
	 * @param  string $limite_minimo Fecha límite inferior (desde)
	 * @param  string $limite_maximo Fecha límite superior (hasta)
	 * @param  string $nombreCampo         El nombre del nombreCampo de fecha
	 * @return string                La condición creada
	 */
	public function ensamblarRangoFechas($limite_minimo, $limite_maximo, $nombreCampo='fecha')
    {
        $string_filtros = '';
        
        if(!empty($limite_minimo) && !empty($limite_maximo)){
        	$string_filtros = $nombreCampo." between '".$limite_minimo."' and '".$limite_maximo."' ";
        }
        else{
        	$string_filtros = (!empty($limite_minimo) ? $nombreCampo.">='".$limite_minimo."' " : '');
        	$string_filtros .= (!empty($limite_maximo) ? $nombreCampo."<='".$limite_maximo."' " : '');
        }

        return $string_filtros;
    }

    /**
     * Prepara un query de select a la base de datos
     * @param  string $tabla      El nombre de la tabla o vista
     * @param  string $campos     Los campos a buscar
     * @param  Array $arrFiltros Los filtros que apliquen
     * @return string             La query armada
     */
    public function armarSelect($tabla, $campos='*', $arrFiltros=null)
    {
    	$string_filtros = $this->crearFiltros($arrFiltros);
    	return 'select '.$campos.' from '.$tabla.' '.$string_filtros;
    }

    /**
     * Prepara un insert para ejecutar en la base de datos
     * @param  string $tabla    el nombre de la tabla
     * @param  Array  $arrDatos Los datos a insertar
     * @return string           La query armada
     */
    public function armarInsert($tabla, Array $arrDatos)
    {
    	$campos = '';
    	if(StdFW::isAssoc($arrDatos)){
    		$campos = '('.implode(',' ,array_keys($arrDatos)).')';
    	}
    	foreach ($arrDatos as &$dato) {
    		$dato = $this->bd->limpiarString($dato);
    	}
    	$query = "INSERT INTO ".$tabla." ".$campos." VALUES ('".implode("','", $arrDatos)."')";
    	return $query;
    }

    /**
     * Abre una fila de la tabla que controla el modelo actual
     * @param  string     $campos     Los campos a pedir
     * @param  Array|null $arrFiltros Los filtros pedidos
     * @param  string|null     $tabla      La tabla | definida en del objeto acutal
     * @return array|boolean                 El resultado | false si no funciono
     */
    public function abrirFila($campos='*', Array $arrFiltros=null, $tabla=null)
    {
    	if(!$tabla)
    		$tabla = $this->tabla;
    	if(is_array($campos)){
    		$arrFiltros = $campos;
    		$campos = '*';
    	}
		$query = $this->armarSelect($tabla, $campos, $arrFiltros);
		$registro = $this->bd->getFila($query, true);
		return $registro ? $registro : false;
    }

    public function listar($campos='*', Array $arrFiltros=null, $tabla=null)
    {
    	if(!$tabla)
    		$tabla = $this->tabla;
    	if(is_array($campos)){
    		$arrFiltros = $campos;
    		$campos = '*';
    	}
		$query = $this->armarSelect($tabla, $campos, $arrFiltros);
		$registro = $this->bd->getResultado($query, true);
		return $registro ? $registro : false;
    }
}
?>