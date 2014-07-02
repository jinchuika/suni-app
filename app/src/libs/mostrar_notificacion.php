<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();
if($_GET['dismiss']){
	$query_notificacion = "UPDATE gn_notificacion SET estado=1 WHERE id=".$_POST['id'];
	if($stmt_notificacion = $bd->ejecutar($query_notificacion)){
		echo json_encode("correcto");
	}
}
else{
	/**
	 * Constructs the SSE data format and flushes that data to the client.
	 *
	 * @param string $id Timestamp/id of this connection.
	 * @param string $msg Line of text that should be transmitted.
	 */

	function sendMsg($id , $msg) {
		echo "id: $id" . PHP_EOL;
		echo "data: {\n";
		echo "data: \"msg\": \"$msg\", \n";
		echo "data: \"id\": $id\n";
		echo "data: }\n";
		echo PHP_EOL;
		ob_flush();
		flush();
	}



	if($id_persona=$_GET['id_persona']){

	}
	else{
		$id_persona=$_POST['id_persona'];
	}
	$query_notificacion = "SELECT * FROM gn_notificacion WHERE id_persona=".$_GET['id_persona']." AND estado=0";
	$stmt_notificacion = $bd->ejecutar($query_notificacion);
	if($notificacion = $bd->obtener_fila($stmt_notificacion, 0)){
		sendMsg($notificacion['id'], $notificacion['mensaje']);
	}
	else{
		//sendMsg($query_notificacion, 'hola');
	}




	$startedAt = time();

	do {
  // Cap connections at 10 seconds. The browser will reopen the connection on close
		if ((time() - $startedAt) > 5) {
			die();
		}

  //sendMsg($_GET['id_per'], "hola");
		sleep(15);

  // If we didn't use a while loop, the browser would essentially do polling
  // every ~3seconds. Using the while, we keep the connection open and only make
  // one request.
	} while(true);
}
?>
