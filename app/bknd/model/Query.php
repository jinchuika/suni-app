<?php
/**
* Creación de querys para MySQL
*/
abstract class Query
{
    
    /**
     * Crea el texto para hacer filtros en una consulta MySQL
     * @param  Array  $arrFiltros Cada elemento en forma {condición}
     * @param string $conector el conector entre condiciones, OR o AND
     * @param boolean $usaWhere para saber si lleva la palabra WHERE o no
     * @return string              El texto para el filtro
     */
    public static function armarFiltros(Array $arrFiltros=null, $conector='AND', $usaWhere=true, $nombreCampo=null)
    {
        $texto = '';
        if(!empty($arrFiltros) && is_array($arrFiltros)){
            $texto = $usaWhere ? ' where ' : '';
            
            $arrFiltrosSalida = array();

            foreach ($arrFiltros as $campo => $valor) {
                //si el campo es un array se toma un OR para usar varias veces ese campo
                if(is_array($valor)){
                    $condicion = ' ('.self::armarFiltros($valor, 'OR', false, $campo).') ';
                }
                else{

                    //Si el usuario no definio un igual, para mayor o menor que
                    //para eso el operador se pone entre corchetes [>=]
                    $filtro = explode("[" , rtrim($campo, "]"));
                    
                    //En este punto
                    //$filtro[0] => campo, $filtro[1] = operador logico
                    //si el usuario definio que fuera un solo campo
                    $campo = $nombreCampo ? $nombreCampo : $filtro[0];
                    $filtroCompuesto = isset($filtro[1]) ? $campo.$filtro[1] : $campo.'=';
                    
                    //Para encerrar en comillas si es string
                    $valor = is_string($valor) ? "'".addslashes($valor)."'" : $valor;
                    $condicion = $filtroCompuesto.$valor;
                    
                    //Une el campo, operador logico, comillas y valor
                }
                array_push($arrFiltrosSalida, $condicion);
            }
            $texto .= implode(' '.$conector.' ', $arrFiltrosSalida);
        }
        return $texto;
    }

    /**
     * Prepara un query de select a la base de datos
     * @param  string $tabla      El nombre de la tabla o vista
     * @param  string $campos     Los campos a buscar
     * @param  Array $arrFiltros Los filtros que apliquen
     * @param Array $conector el conector entre condiciones, si es OR o AND
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
        //Para obtener el nombre de los campos
        if(StdFW::isAssoc($arrDatos)){
            $campos = '('.implode(',' ,array_keys($arrDatos)).')';
        }
        //Para quitar los caracteres especiales y agregar comillas a los textos
        foreach ($arrDatos as &$dato) {
            $comilla = is_string($dato) ? "'" : '';
            $dato = $comilla.addslashes($dato).$comilla;
        }

        $query = "INSERT INTO ".$tabla." ".$campos." VALUES (".implode(",", $arrDatos).")";
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

    /**
     * Crea un query para eliminar
     * @param  string $tabla    el nombre de la tabla
     * @param  Array  $arrWhere las condiciones para saber el registro a borrar
     * @return string           La query
     */
    public function armarDelete($tabla, Array $arrWhere)
    {
        $condiciones = self::armarFiltros($arrWhere);
        $query = "DELETE FROM ".$tabla." ".$condiciones;
        return $query;
    }
}
?>