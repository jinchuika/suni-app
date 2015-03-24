<?php
/**
* Clase para crear instancias de controladores de tablas
*/
class TablaFactory
{
	/**
	 * Crea un nuevo objeto de modelo de tabla
	 * @param  string     $tabla      Nombre del modelo
	 * @param  Array|null $arrFiltros Filtros para abrir un objeto específico
	 * @return Object                 Instancia del modelo pedido
	 */
	public static function build($tabla, Array $arrFiltros=null)
	{
		if (class_exists($tabla)) {
			$tempTabla = new $tabla();
			if(method_exists($tempTabla, 'abrir') && $arrFiltros){
				$tempTabla->abrir($arrFiltros);
			}
			return $tempTabla;
		}
		else{
			throw new Exception("Error al conectar con la base de datos");
		}
	}
}
?>