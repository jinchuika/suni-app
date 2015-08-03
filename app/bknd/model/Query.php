<?php
/**
* Creación de querys para MySQL
*/
abstract class Query
{
    
    /**
     * Crea el texto para hacer filtros en una consulta MySQL
     * @param  Array  $arrFiltros Cada elemento en forma {condición}
     * @return string              El texto para el filtro
     */
    public static function armarFiltros(Array $arrFiltros=null, $conector='AND', $usaWhere=true)
    {
        $texto = '';
        if(!empty($arrFiltros) && is_array($arrFiltros)){
            
            $arrFiltrosSalida = array();
            
            foreach ($arrFiltros as $campo => $valor) {
                //Si el usuario no definio un igual, para mayor o menor que
                $filtro = explode("[" , rtrim($campo, "]"));
                
                $filtroCompuesto = isset($filtro[1]) ? $filtro[0].$filtro[1] : $filtro[0].'=';
                
                //Para encerrar en comillas si es string
                $comilla = is_string($valor) ? "'" : '';
                
                //Une el campo, operador logico, comillas y valor
                array_push($arrFiltrosSalida, $filtroCompuesto.$comilla.$valor.$comilla);
            }
            
            $texto = $usaWhere ? ' where ' : '';
            $texto .= implode(' '.$conector.' ', $arrFiltrosSalida);
        }
        return $texto;
    }

    /**
     * Prepara un query de select a la base de datos
     * @param  string $tabla      El nombre de la tabla o vista
     * @param  string $campos     Los campos a buscar
     * @param  Array $arrFiltros Los filtros que apliquen
     * @return string             La query armada
     */
    public static function armarSelect($tabla, $campos='*', Array $arrFiltros=null, $conector='AND')
    {
        $string_filtros = self::armarFiltros($arrFiltros, $conector);
        return 'select '.$campos.' from '.$tabla.' '.$string_filtros;
    }

    /**
     * Prepara un insert para ejecutar en la base de datos
     * @param  string $tabla    el nombre de la tabla
     * @param  Array  $arrDatos Los datos a insertar
     * @return string           La query armada
     */
    public static function armarInsert($tabla, Array $arrDatos)
    {
        $campos = '';
        if(StdFW::isAssoc($arrDatos)){
            $campos = '('.implode(',' ,array_keys($arrDatos)).')';
        }
        foreach ($arrDatos as &$dato) {
            $dato = addslashes($dato);
        }
        $query = "INSERT INTO ".$tabla." ".$campos." VALUES ('".implode("','", $arrDatos)."')";
        return $query;
    }

    /**
     * Crea un query de Update 
     * @param  string $tabla    El nombre de la tabla
     * @param  Array  $arrDatos Los datos a modificar campo=>valor
     * @param  Array  $arrWhere la condicion en la que se basa la edición campo=>valor
     * @return string
     */
    public static function armarUpdate($tabla, Array $arrDatos, Array $arrWhere)
    {
        $campos = self::armarFiltros($arrDatos, ',', false);
        $condiciones = self::armarFiltros($arrWhere);
        $query = "UPDATE ".$tabla." SET ".$campos." ".$condiciones;
        return $query;
    }
}
?>