<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

class ElementoAutocompletar {
   //propiedades de los elementos
    var $value;
    var $label;

   //constructor que recibe los datos para inicializar los elementos
    function __construct($label, $value, $desc, $genero, $escuela, $udi, $grupo, $asignacion, $curso, $nombre_curso){
        $this->label = $label;
        $this->value = $value;
        $this->desc = $desc;
        $this->genero = $genero;
        $this->escuela = $escuela;
        $this->udi = $udi;
        $this->grupo = $grupo;
        $this->asignacion = $asignacion;
        $this->curso = $curso;
        $this->nombre_curso = $nombre_curso;
    }
}

//Función para crear registristros únicos en el array de respuesta
$respuesta = array();
function my_array_unique($array, $keep_key_assoc = false)
{
    $duplicate_keys = array();
    $tmp         = array();

    foreach ($array as $key=>$val)
    {
        // Convierte el objeto en un array
        if (is_object($val))
            $val = (array)$val;

        if (!in_array($val, $tmp))
            $tmp[] = $val;
        else
            $duplicate_keys[] = $key;
    }

    foreach ($duplicate_keys as $key)
        unset($array[$key]);

    return $keep_key_assoc ? $array : array_values($array);
}

if (isset($_GET['term'])) {
    $term = $_GET['term'];
    $id_sede = $_GET["id_sede"];
    $id_curso = $_GET["id_curso"];
    $id_muni = $_GET["id_muni"];
    $id_depto = $_GET["id_depto"];
    $id_escuela = $_GET["id_escuela"];


    $arr_sede = array();
    $query = "
    select
    gn_participante.id,
    gn_persona.nombre,
    gn_persona.apellido,
    pr_genero.genero,
    gn_grupo.id as id_grupo,
    gn_grupo.numero,
    gn_asignacion.id as id_asignacion,
    gn_curso.id as id_curso,
    gn_curso.nombre as nombre_curso,
    gn_escuela.nombre as nombre_escuela,
    gn_escuela.codigo
    from
    gn_sede
    gn_sede
    left join gn_grupo ON gn_grupo.id_sede=gn_sede.id
    left join gn_curso ON gn_curso.id=gn_grupo.id_curso
    left join gn_asignacion ON gn_asignacion.grupo = gn_grupo.id
    left join gn_participante on gn_participante.id=gn_asignacion.participante
    left join gn_persona ON gn_persona.id=gn_participante.id_persona
    left join pr_genero on pr_genero.id=gn_persona.genero
    left join gn_escuela on gn_escuela.id=gn_participante.id_escuela
    where gn_participante.id!=0 
    ";
    if(empty($id_sede)){
        if(empty($id_muni) && !empty($id_depto)){
            $query_muni = "select id from gn_municipio where id_departamento=".$id_depto;
            
            $stmt_muni = $bd->ejecutar($query_muni);
            $array_muni = array();
            while ($muni = $bd->obtener_fila($stmt_muni, 0)) {
                array_push($array_muni, $muni[0]);
            }
            $query .= " AND gn_sede.id_muni IN (".implode(',', $array_muni).")";
        }
        if(!empty($id_muni)){
            $query .= " AND gn_sede.id_muni=".$id_muni;
        }
    }
    else{
        $query .= " AND gn_sede.id=".$id_sede;
    }
    if(!empty($id_curso)){
        $query .= " AND gn_grupo.id_curso=".$id_curso;
    }
    if(!empty($id_escuela)){
        $query .= " AND gn_escuela.codigo='".$id_escuela."' ";
    }
    $palabras=explode(" ",$term);
    $numero=count($palabras);
    if($numero==1){
        $query .= " AND (gn_persona.nombre LIKE '".$term."%' OR apellido LIKE '".$term."%') ";
    }
    elseif($numero>1){
        $query .= " AND (CONCAT(gn_persona.nombre, ' ', apellido) LIKE '%".implode("%' AND CONCAT(gn_persona.nombre, ' ', apellido) LIKE '%", $palabras)."%') ";
    }
    
    $stmt = $bd->ejecutar($query);
    while ($participante = $bd->obtener_fila($stmt, 0)) {
        array_push($respuesta, new ElementoAutocompletar($participante["nombre"]." ".$participante["apellido"], $participante["id"], $participante["numero"], $participante["genero"], $participante["nombre_escuela"], $participante["codigo"], $participante["id_grupo"], $participante["id_asignacion"], $participante["id_curso"], $participante["nombre_curso"]));
    }
    echo json_encode(my_array_unique($respuesta));
}
?>