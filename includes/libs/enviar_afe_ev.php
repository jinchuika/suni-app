<?php
	include 'connect.php';
	$bd=Db::getInstance();

	$fecha = $_POST['fecha'];
	$jornada = $_POST['jornada'];
	$semana = $_POST['semana'];
	$nombre_usr = $_POST['capacitador'];
	$id_depto = $_POST['depto'];
	$municipio = $_POST['muni'];
	$sede = $_POST['sede'];
	$grupo = $_POST['grupo'];

	$u1 = $_POST['u1'];
	$u2 = $_POST['u2'];
	$u3 = $_POST['u3'];
	$u4 = $_POST['u4'];
	
	$c1 = $_POST['c1'];
	$c2 = $_POST['c2'];
	$c3 = $_POST['c3'];
	$c4 = $_POST['c4'];
	$s1 = $_POST['s1'];
	$s2 = $_POST['s2'];
	$s3 = $_POST['s3'];
	$s4 = $_POST['s4'];
	$p1 = $_POST['p1'];
	$p2 = $_POST['p2'];
	$p3 = $_POST['p3'];
	$p4 = $_POST['p4'];
	$p5 = $_POST['p5'];
	$l1 = $_POST['l1'];
	$l2 = $_POST['l2'];
	$l3 = $_POST['l3'];
	$comentario = $_POST['comentario'];

	function validarFecha($fecha)
	{
	    return preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $fecha);
	}


	//Consulta para obtener nombre de departamento
	$sql="SELECT * FROM gn_depto WHERE id_depto='$id_depto'";
     $stmt=$bd->ejecutar($sql);
     $x=$bd->obtener_fila($stmt,0);
     $depto = $x[1];

    //Consulta para obtener ID de capacitador
	$sql="SELECT * FROM usr WHERE nombre='$nombre_usr'";
     $stmt=$bd->ejecutar($sql);
     $y=$bd->obtener_fila($stmt,0);
     $id_usr = $y[0];

    //Consulta para obtener ID de municipio
	$sql="SELECT * FROM gn_muni WHERE nombre_muni='$municipio'";
     $stmt=$bd->ejecutar($sql);
     $y=$bd->obtener_fila($stmt,0);
     $id_muni = $y[0];

    if(validarFecha($fecha)){

	     //Convertir fecha de formato DD/MM/AAAA a AAAA-MM-DD
		$fechaF = explode("/", $fecha);
		$dia = $fechaF[0];
		$mes = $fechaF[1];
		$anno = $fechaF[2];
		$fecha = $anno."-".$mes."-".$dia;
		
		//Validación de campos en blanco
		
		
		if(($fecha!=="")&&($jornada!=="")&&($grupo!=="")&&($semana!=="")&&($nombre_usr!=="")&&($depto!=="")&&($municipio!=="")&&($sede!=="")){
	    	$sql = "SELECT * FROM afe_ev_encabezado WHERE (fecha ='$fecha') AND (semana ='$semana') AND (capacitador='$nombre_usr') AND (jornada='$jornada') AND (grupo ='$grupo') AND (sede ='$sede') AND (depto ='$depto') AND (municipio ='$municipio')";
	    	$stmt=$bd->ejecutar($sql);
	    	$x=$bd->obtener_fila($stmt,0);


	    	//Validación de encabezados anteriores
	    	if($x[0]==""){

				//$sql="INSERT INTO afe_ev_encabezado(fecha, jornada, semana, capacitador, id_usr, depto, id_depto, municipio, id_muni, sede, grupo) VALUES ('2013-03-11', '1', '2', 'David', 'dsalazar', 'JUTIAPA', '22', 'GUAZACAPAN', '611', 'Pruebita', '1')";
				
				//$sql = "INSERT INTO afe_ev_cuerpo(id_afe_ev_encabezado, u1, u2, u3, u4, c1, c2, c3, c4, s1, s2, s3, s4, p1, p2, p3, p4, p5, l1, l2, l3, comentario) VALUES ('2', '$u1', '$u2', '$u3', '$u4', '$c1', '$c2', '$c3', '$c4', '$s1', '$s2', '$s3', '$s4', '$p1', '$p2', '$p3', '$p4', '$p5', '$l1', '$l2', '$l3', '$comentario')";
				//$stmt=$bd->ejecutar($sql);

		    	$sql="INSERT INTO afe_ev_encabezado(fecha, jornada, semana, capacitador, id_usr, depto, id_depto, municipio, id_muni, sede, grupo) VALUES ('$fecha', '$jornada', '$semana', '$nombre_usr', '$id_usr', '$depto', '$id_depto', '$municipio', '$id_muni', '$sede', '$grupo')";
	    		$stmt=$bd->ejecutar($sql);

	    		$id_actual = $bd->lastID();
	    		//echo json_encode(array('returned_val' => $id_actual));
			}
			else{
				$id_actual = $x[0];
			}
			$sql = "INSERT INTO afe_ev_cuerpo(id_afe_ev_encabezado, u1, u2, u3, u4, c1, c2, c3, c4, s1, s2, s3, s4, p1, p2, p3, p4, p5, l1, l2, l3, comentario) VALUES ('$id_actual', '$u1', '$u2', '$u3', '$u4', '$c1', '$c2', '$c3', '$c4', '$s1', '$s2', '$s3', '$s4', '$p1', '$p2', '$p3', '$p4', '$p5', '$l1', '$l2', '$l3', '$comentario')";
			$stmt=$bd->ejecutar($sql);
			
			$query_cantidad = "SELECT * FROM afe_ev_cuerpo WHERE id_afe_ev_encabezado =".$id_actual;
			$stmt_cantidad = $bd->ejecutar($query_cantidad);
			$sumatoria = 0;
			while ($cantidad = $bd->obtener_fila($stmt_cantidad, 0)) {
				$sumatoria = $sumatoria + 1;
			}
			//echo json_encode(array('returned_val' => 'Formulario enviado'.$fecha.$jornada.$semana.$nombre_usr.$id_usr.$depto.$id_depto.$municipio.$id_muni.$sede.$grupo.$id_actual));
			echo json_encode(array('returned_val' => 'Formulario enviado Exitosamente!', 'cantidad' => $sumatoria));
				
		}
		else{
			echo json_encode(array('returned_val' => 'Faltan datos, Revise porfavor.'));

		}
	}
	else{
		echo json_encode(array('returned_val' => 'Error en el formato de fecha, ingrese DD/MM/AAAA'));
	}

?>