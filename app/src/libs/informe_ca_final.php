<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

$id_sede = $_POST['id_sede'];
$id_curso = $_POST['id_curso'];

/**
* 
*/
class respuesta {
	//$capacitador, $sede, $curso, $hombres, $mujeres, $total, $maestros_m1, $maestros_m11, $maestros_ap, $alumnos_ap, $atendidos_m1, $atendidos_m11, $evaluaciones
	function __construct($capacitador, $sede, $curso, $hombres, $mujeres, $cant_m1, $cant_m11, $cant_m_aprobado, $cant_a_aprobado, $cant_afe1, $cant_afe11){
		$this->capacitador = $capacitador;
		$this->sede = $sede;
		$this->curso = $curso;
		$this->hombres = $hombres;
		$this->mujeres = $mujeres;
		$this->total = $hombres + $mujeres;
		$this->cant_m1 = $cant_m1;
		$this->cant_m11 = $cant_m11;
		$this->cant_m_aprobado = $cant_m_aprobado;
		$this->cant_a_aprobado = $cant_a_aprobado;
		$this->cant_afe1 = $cant_afe1;
		$this->cant_afe11 = $cant_afe11;
		$this->cant_afe = $cant_afe1 + $cant_afe11;
	}
}

if(!empty($id_sede)){
	$respuesta = array();
	
	$query_sede = "SELECT * FROM gn_sede WHERE id=".$id_sede;
	$stmt_sede = $bd->ejecutar($query_sede);
	if($sede = $bd->obtener_fila($stmt_sede, 0)){

		$query_capacitador = "SELECT * FROM gn_persona WHERE id=".$sede['capacitador'];
		$stmt_capacitador = $bd->ejecutar($query_capacitador);
		$capacitador = $bd->obtener_fila($stmt_capacitador, 0);

		$query_grupo = "SELECT * FROM  gn_grupo WHERE id_sede=".$id_sede;

		if(!empty($id_curso)){
			$query_grupo .= " AND id_curso=".$id_curso;
		}

		$stmt_grupo = $bd->ejecutar($query_grupo);
		$cont_hombre = 0;
		$cont_mujer = 0;
		$cont_m_aprobado = 0;
		$cont_a_aprobado = 0;
		$grupo_temp = 0;
		$cant_m1 = 0;
		$cant_m11 = 0;
		$cant_afe1 = 0;
		$cant_afe11 = 0;
		while ($grupo = $bd->obtener_fila($stmt_grupo, 0)) {
			if(($grupo['id_curso'] !== $grupo_temp) && ($grupo_temp!==0)){
				array_push($respuesta, new respuesta($r_nombre_capa, $r_sede, $r_curso, $r_hombres, $r_mujeres, $cant_m1, $cant_m11, $cant_m_aprobado, $cant_a_aprobado, $cant_afe1, $cant_afe11));
				$cont_hombre = 0;
				$cont_mujer = 0;
				$cont_m_aprobado = 0;
				$cont_a_aprobado = 0;
				$cant_m1 = 0;
				$cant_m11 = 0;
				$cant_afe1 = 0;
				$cant_afe11 = 0;
			}
			$query_curso = "SELECT * FROM gn_curso WHERE id=".$grupo['id_curso'];
			$stmt_curso = $bd->ejecutar($query_curso);
			$curso = $bd->obtener_fila($stmt_curso, 0);

			$query_asignacion = "SELECT * FROM gn_asignacion where grupo=".$grupo[0];
			$stmt_asignacion = $bd->ejecutar($query_asignacion);
			while ($asignacion = $bd->obtener_fila($stmt_asignacion, 0)) {

				$query_participante = "SELECT * FROM gn_participante WHERE id=".$asignacion['participante'];
				$stmt_participante = $bd->ejecutar($query_participante);
				$participante = $bd->obtener_fila($stmt_participante, 0);

				$query_persona = "SELECT * FROM gn_persona WHERE id=".$participante['id_persona'];
				$stmt_persona = $bd->ejecutar($query_persona);
				$persona = $bd->obtener_fila($stmt_persona, 0);

				$query_m1 = "SELECT * FROM gn_nota WHERE id_asignacion=".$asignacion[0]." AND  tipo=2 ";
				$stmt_m1 = $bd->ejecutar($query_m1);
				$m1 = $bd->obtener_fila($stmt_m1, 0);

				$query_nota = "SELECT * FROM gn_nota WHERE id_asignacion=".$asignacion[0];
				$stmt_nota = $bd->ejecutar($query_nota);
				$suma_nota = 0;
				$bnd = 0;
				while ($nota = $bd->obtener_fila($stmt_nota, 0)) {
					$suma_nota = $suma_nota + $nota['nota'];

					if($nota['tipo']==2){
						$bnd = 1;
						$nota_temp = $nota['nota'];
						//$cant_m11 = $cant_m11 - 1;
						//$cant_m11 = $cant_m11 + 1;
					}
					else{
						//$cant_m11 = $cant_m11 - 1;
					}
				}
				if($bnd==1){
					if($nota_temp>0){
						$cant_m11 = $cant_m11 + 1;
						$bnd = 0;
					}
				}

				/* Contadores */

				/* Alumnos aprobados */
				if(($participante['id_rol']==8) && ($suma_nota>=$curso['nota_aprobacion'])){
					$cant_a_aprobado = $cant_a_aprobado+1;
				}
				/* Maestros aprobados */
				if(($participante['id_rol']<8) && ($suma_nota>=$curso['nota_aprobacion'])){
					$cant_m_aprobado = $cant_m_aprobado+1;
				}

				/* Maestros M1 */
				if($m1['nota']>0){
					$cant_m1 = $cant_m1 + 1;
				}

				/* Cantidad hombres/mujeres */
				if($persona['genero']==1){
					$cont_hombre = $cont_hombre + 1;
				}
				else{
					$cont_mujer = $cont_mujer + 1;
				}
				
			}//fin while asignación

			/* AFMSP */
			$query_calendario = "SELECT * FROM gr_calendario WHERE id_grupo = ".$grupo['id'];
			$stmt_calendario = $bd->ejecutar($query_calendario);
			while ($calendario = $bd->obtener_fila($stmt_calendario, 0)) {
				$query_afe_enc = "SELECT * FROM gr_afe_encabezado WHERE id_gr_calendario=".$calendario['id']." AND numero=1";
				$stmt_afe_enc = $bd->ejecutar($query_afe_enc);
				if($afe_enc = $bd->obtener_fila($stmt_afe_enc, 0)){
					$query_afe_cue = "SELECT * FROM afe_ev_cuerpo WHERE id_afe_ev_encabezado=".$afe_enc['id'];
					$stmt_afe_cue = $bd->ejecutar($query_afe_cue);
					while ($afe_cue = $bd->obtener_fila($stmt_afe_cue, 0)) {
						$cant_afe1 = $cant_afe1 + 1;
					}
					
				}
				$query_afe_enc = "SELECT * FROM gr_afe_encabezado WHERE id_gr_calendario=".$calendario['id']." AND numero>1";
				$stmt_afe_enc = $bd->ejecutar($query_afe_enc);
				if($afe_enc = $bd->obtener_fila($stmt_afe_enc, 0)){
					$query_afe_cue = "SELECT * FROM afe_ev_cuerpo WHERE id_afe_ev_encabezado=".$afe_enc['id'];
					$stmt_afe_cue = $bd->ejecutar($query_afe_cue);
					while ($afe_cue = $bd->obtener_fila($stmt_afe_cue, 0)) {
						$cant_afe11 = $cant_afe11 + 1;
					}
					
				}
			}

			$r_nombre_capa = $capacitador['nombre']." ".$capacitador['apellido'];
			$r_sede = $sede['nombre'];
			$r_curso = $curso['nombre'];
			$r_hombres = $cont_hombre;
			$r_mujeres = $cont_mujer;

			$grupo_temp = $grupo['id_curso'];
		}
		array_push($respuesta, new respuesta($r_nombre_capa, $r_sede, $r_curso, $r_hombres, $r_mujeres, $cant_m1, $cant_m11, $cant_m_aprobado, $cant_a_aprobado, $cant_afe1, $cant_afe11));
	}
	echo json_encode($respuesta);
}
?>