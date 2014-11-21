<?php
/* Para ejecutar la función */
$nombre_funcion = $_GET['fn_nombre'];
switch ($nombre_funcion) {
	/* Etiquetas */
	case 'listar_etiqueta':
	require_once('ctc_etiqueta.php');
		echo json_encode(listar_etiqueta(''));
		break;
	
	case 'abrir_etiqueta':
		require_once('ctc_etiqueta.php');
		echo json_encode(abrir_etiqueta($_POST['id_tag']));
		break;
	
	case 'editar_etiqueta':
		require_once('ctc_etiqueta.php');
		echo json_encode(editar_etiqueta($_POST['pk'], $_POST['name'], $_POST['value']));
		break;
	case 'crear_etiqueta':
		require_once('ctc_etiqueta.php');
		echo json_encode(crear_etiqueta($_POST['nueva_tag'], $_POST['nueva_desc']));
		break;
	case 'agregar_tag':
		require_once('ctc_etiqueta.php');
		echo json_encode(agregar_etiqueta($_POST['value'], $_POST['pk']));
		break;
	case 'listar_rel_etiqueta':
		require_once('ctc_etiqueta.php');
		echo json_encode(listar_rel_etiqueta($_POST['id']));
		break;
	case 'borrar_tag':
		require_once('ctc_etiqueta.php');
		echo json_encode(borrar_etiqueta($_POST['id_tag'], $_POST['id_ctc']));
		break;

	/* Empresas */
	case 'listar_empresa':
		require_once('ctc_empresa.php');
		echo json_encode(listar_empresa());
		break;
	case 'abrir_empresa':
		require_once('ctc_empresa.php');
		echo json_encode(abrir_empresa($_POST['id_emp']));
		break;
	case 'editar_empresa':
		require_once('ctc_empresa.php');
		echo json_encode(editar_empresa($_POST['pk'], $_POST['name'], $_POST['value']));
		break;
	case 'crear_empresa':
		require_once('ctc_empresa.php');
		echo json_encode(crear_empresa($_POST['nombre_empresa'], $_POST['direccion_empresa'], $_POST['telefono_empresa'],$_POST['descripcion_empresa']));
		break;
	case 'listar_rel_empresa':
		require_once('ctc_empresa.php');
		echo json_encode(listar_rel_empresa($_POST['id']));
		break;

	/* Eventos */
	case 'listar_evento':
		require_once('gn_evento.php');
		echo json_encode(listar_evento());
		break;
	case 'listar_tipo_evento':
		require_once('gn_evento.php');
		break;
	case 'abrir_evento':
		require_once('gn_evento.php');
		echo json_encode(abrir_evento($_POST['id_evn']));
		break;
	case 'eliminar_evento':
		require_once('gn_evento.php');
		echo json_encode(eliminar_evento($_POST['id_evn']));
		break;
	case 'editar_evento':
		require_once('gn_evento.php');
		echo json_encode(editar_evento($_POST['pk'], $_POST['name'], $_POST['value']));
		break;
	case 'listar_rel_evento':
		require_once('gn_evento.php');
		echo json_encode(listar_rel_evento($_POST['id']));
		break;
	case 'agregar_evn':
		require_once('gn_evento.php');
		echo json_encode(agregar_evento($_POST['value'], $_POST['pk']));
		break;
	case 'crear_evento':
		require_once('gn_evento.php');
		echo json_encode(crear_evento($_POST['nombre_evento'], $_POST['tipo_evento'], $_POST['descripcion_evento'], $_POST['direccion_evento'], $_POST['h_fecha_evento'],$_POST['hora_evento']));
		break;
	case 'borrar_rel_evento':
		require_once('gn_evento.php');
		echo json_encode(borrar_rel_evento($_POST['id_evn'], $_POST['id_ctc']));
		break;
	
	/* Contactos */
	case 'exportar':
		require_once('gn_contacto.php');
		echo json_encode(exportar_datos(json_decode($_POST['arr_campos']), json_decode($_POST['arr_filtros'], true)));
		break;
	case 'listar_contacto':
		require_once('gn_contacto.php');
		echo json_encode(listar_contacto());
		break;
	case 'validar_contacto':
		require_once('gn_contacto.php');
		echo json_encode(validar_contacto($_POST['email']));
		break;
	case 'crear_contacto':
		require_once('gn_contacto.php');
		if(!empty($_POST['nombre_contacto']) && !empty($_POST['apellido_contacto']) && !empty($_POST['etiqueta_contacto']) && !empty($_POST['mail_contacto']) && ($_POST['nombre_contacto']!=="null") && ($_POST['apellido_contacto']!=="null") && ($_POST['etiqueta_contacto']!=="null") && ($_POST['mail_contacto']!=="null") ){
			echo json_encode(crear_contacto($_POST['nombre_contacto'],$_POST['apellido_contacto'],$_POST['genero_contacto'],$_POST['direccion_contacto'],$_POST['mail_contacto'],$_POST['tel_casa_contacto'],$_POST['tel_movil_contacto'],$_POST['fecha_nac_contacto'],$_POST['observaciones_contacto'],$_POST['etiqueta_contacto'],$_POST['empresa_contacto'],$_POST['evento_contacto']));
		}
		else{
			echo "no";
		}
		break;
	case 'crear_contacto_lista':
		require_once('gn_contacto.php');
		if(!empty($_POST['nombre_contacto']) && !empty($_POST['apellido_contacto']) && !empty($_POST['etiqueta_contacto']) && !empty($_POST['mail_contacto']) && ($_POST['nombre_contacto']!=="null") && ($_POST['apellido_contacto']!=="null") && ($_POST['etiqueta_contacto']!=="null") && ($_POST['mail_contacto']!=="null") ){
			echo json_encode(crear_contacto_lista($_POST['nombre_contacto'],$_POST['apellido_contacto'],$_POST['mail_contacto'],$_POST['telelono_contacto'],$_POST['etiqueta_contacto'],$_POST['evento_contacto']));
		}
		else{
			if($_POST['nombre_contacto']!=="null" && $_POST['apellido_contacto']!=="null"){
				echo json_encode(array("msj"=>"no", "nombre"=>$_POST['nombre_contacto']." ".$_POST['apellido_contacto']));
			}
			else{
				echo "no";
			}
		}
		break;
	case 'abrir_contacto':
		require_once('gn_contacto.php');
		echo json_encode(abrir_contacto($_POST['id_ctc']));
		break;
	case 'editar_persona':
		require_once('gn_contacto.php');
		echo json_encode(editar_persona($_POST['pk'], $_POST['name'], $_POST['value']));
		break;
	case 'editar_contacto':
		require_once('gn_contacto.php');
		echo json_encode(editar_contacto($_POST['pk'], $_POST['name'], $_POST['value']));
		break;
	case 'eliminar_contacto':
		require_once('gn_contacto.php');
		echo json_encode(eliminar_contacto($_POST['id_ctc']));
		break;
	default:
		# code...
		break;
}
?>