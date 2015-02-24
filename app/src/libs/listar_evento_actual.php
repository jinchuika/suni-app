<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
function listar_evento()
{
    $bd = Db::getInstance();
    $respuesta = array();
    $query = "SELECT gn_persona.nombre, gn_persona.apellido, gn_curso.id, gn_curso.nombre, gn_sede.id, gn_sede.nombre, gn_grupo.numero, gr_calendario.hora_inicio, gr_calendario.hora_fin, gn_grupo.id FROM gr_calendario
    INNER JOIN gn_grupo ON gn_grupo.id=gr_calendario.id_grupo
    INNER JOIN gn_curso ON gn_curso.id=gn_grupo.id_curso
    INNER JOIN gn_sede ON gn_grupo.id_sede=gn_sede.id
    INNER JOIN gn_persona ON gn_persona.id=gn_sede.capacitador
    WHERE fecha=CURDATE() AND ADDTIME( curtime( ) , '01:00:00' ) between hora_inicio and hora_fin;";
    
    $stmt = $bd->ejecutar($query);
    while ($resp = $bd->obtener_fila($stmt, 0)) {
        $resp["tipo_resp"] = 1;
        array_push($respuesta, $resp);
    }

    $query = "SELECT
    gn_sede.id as id_sede,
    gn_sede.nombre as nombre_sede,
    gr_asesoria.descripcion,
    gr_asesoria.fecha,
    gr_asesoria.hora_inicio,
    gr_asesoria.hora_fin,
    gn_persona.nombre,
    gn_persona.apellido,
    gn_sede.capacitador
    FROM gr_asesoria
    inner join gn_sede ON gn_sede.id=gr_asesoria.id_sede
    inner join gn_persona on gn_persona.id = gn_sede.capacitador
    WHERE fecha=CURDATE() AND ADDTIME( curtime( ) , '01:00:00' ) between hora_inicio and hora_fin";
    $stmt = $bd->ejecutar($query);
    while ($resp = $bd->obtener_fila($stmt, 0)) {
        $resp["tipo_resp"] = 2;
        array_push($respuesta, $resp);
    }
    return $respuesta;
}
class calendario {
    
    function __construct($title, $start, $end, $allDay, $other, $url, $color)
    {
        $this->title = $title;
        $this->start = $start;
        $this->end = $end;
        $this->allDay = $allDay;
        $this->other = $other;
        $this->url = $url;
        $this->color = $color;
    }
}
function listar_eventos_diarios($id_persona)
{
    $bd = Db::getInstance();
    $respuesta = array();
    $query = "SELECT gn_grupo.id, fecha, hora_inicio, hora_fin, gn_grupo.numero, gn_curso.nombre, gn_sede.nombre, gn_persona.nombre, gn_persona.id, cr_asis_descripcion.modulo_num
    FROM gr_calendario
    left join cr_asis_descripcion ON gr_calendario.id_cr_asis_descripcion=cr_asis_descripcion.id
    INNER JOIN gn_grupo ON gn_grupo.id = gr_calendario.id_grupo
    INNER JOIN gn_sede ON gn_sede.id = gn_grupo.id_sede
    INNER JOIN gn_persona ON gn_persona.id = gn_sede.capacitador
    INNER JOIN gn_curso ON gn_curso.id = gn_grupo.id_curso
    WHERE fecha= CURDATE()";
    if(!empty($id_persona)){
        $query .= " AND gn_sede.capacitador=".$id_persona;
    }
    $stmt = $bd->ejecutar($query);
    while ($horario = $bd->obtener_fila($stmt, 0)) {
        array_push($respuesta, $horario);
    }
    return $respuesta;
}

function listar_asesoria_diario($id_persona)
{
    $bd = Db::getInstance();
    $respuesta = array();
    $query = "SELECT
    gn_sede.id as id_sede,
    gn_sede.nombre as nombre_sede,
    gr_asesoria.descripcion,
    gr_asesoria.fecha,
    gr_asesoria.hora_inicio,
    gr_asesoria.hora_fin,
    gn_persona.nombre,
    gn_sede.capacitador
    FROM gr_asesoria
    inner join gn_sede ON gn_sede.id=gr_asesoria.id_sede
    inner join gn_persona on gn_persona.id = gn_sede.capacitador
    where gr_asesoria.fecha = curdate()";
    if(!empty($id_persona)){
        $query .= " AND gn_sede.capacitador=".$id_persona;
    }
    $stmt = $bd->ejecutar($query);
    while ($horario = $bd->obtener_fila($stmt, 0)) {
        array_push($respuesta, $horario);
    }
    return $respuesta;  
}

function elegir_color($id_capacitador)
{
    switch ($id_capacitador) {
        case '48':
        return "#CC4509";
        break;
        case '49':
        return "#25A5FF";
        break;
        case '44':
        return "#11bb88";
        break;
        case '45':
        return "#9966FF";
        break;
        case '51':
        return "#5B778C";
        break;
        case '43':
        return "#0778CC";
        break;
        case '4889':
        return "#F7B043";
        break;
        case 'asesoria':
        return '#B88A8A';
        break;
        default:
                        # code...
        break;
    }
}

function informe_asistencias($id_per, $id_sede, $fecha_inicio, $fecha_fin)
{
    $bd = Db::getInstance();
    $query = "
    select count(*) from gn_nota
    right outer join gr_calendario on gn_nota.id_gr_calendario=gr_calendario.id
    inner join gn_grupo on gn_grupo.id=gr_calendario.id_grupo
    inner join gn_sede on gn_sede.id=gn_grupo.id_sede
    where nota >0
    ";
    if($fecha_inicio==$fecha_fin){
        $query .= " and fecha='".$fecha_inicio."' ";
    }
    else{
        $query .= " and fecha between '".$fecha_inicio."' and '".$fecha_fin."' ";
    }

    if(!empty($id_per)){
        $query .= " and capacitador='".$id_per."' ";
    }
    if(!empty($id_sede)){
        $query .= " and gn_sede.id='".$id_sede."' ";
    }
    $stmt = $bd->ejecutar($query);
    if($resultado = $bd->obtener_fila($stmt, 0)){
        return array("cont" => $resultado[0]);
    }
    else{
        return array("cont" => "Error al obtener los datos");
    }
}

if(isset($_GET['ejecutar'])){
    echo json_encode(listar_evento());
}
if(isset($_GET['ejecutar_diario'])){
    $array_respuesta = array();
    $array_horario = listar_eventos_diarios($_POST['id_persona']);
    foreach ($array_horario as $key => $evento) {
        $title= $evento[6]." [Clase no. ".$evento['modulo_num']."]";
        $start  = $evento['fecha']." ".$evento['hora_inicio'];
        $end    = $evento['fecha']." ".$evento['hora_fin'];
        $allDay = false;
        $other = array("curso" => $evento[5], "sede" => $evento[6], "grupo" => $evento[4], "inicio" => $evento['hora_inicio'], "fin" => $evento['hora_fin']);
        $color = elegir_color($evento[8]);
        $url= "cap/grp/buscar.php?id_grupo=".$evento[0].'';

        array_push($array_respuesta, new calendario($title, $start, $end, $allDay, $other, $url, $color));
    }
    $array_asesoria = listar_asesoria_diario($_POST['id_persona']);
    foreach ($array_asesoria as $key => $asesoria) {
        $title= $asesoria[1];
        $start  = $asesoria['fecha']." ".$asesoria['hora_inicio'];
        $end    = $asesoria['fecha']." ".$asesoria['hora_fin'];
        $allDay = false;
        $other = array("curso" => "Asesoría", "sede" => $asesoria[1], "grupo" => "", "inicio" => $asesoria['hora_inicio'], "fin" => $asesoria['hora_fin']);
        $color = elegir_color(empty($_POST['id_persona']) ? $asesoria['capacitador'] : 'asesoria');
        $url= "http://funsepa.net/suni/app/cap/sed/sede.php?id=".$asesoria[0].'';
        array_push($array_respuesta, new calendario($title, $start, $end, $allDay, $other, $url, $color));
    }
    echo json_encode($array_respuesta);
}
if(isset($_GET['informe_asistencias'])){
    echo json_encode(informe_asistencias($_GET['id_per'], $_GET['id_sede'], $_GET['fecha_inicio'], $_GET['fecha_fin']));
}
?>