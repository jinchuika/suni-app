<?php
/**
* -> Buscador de grupos
*/
function setOverrides(/* array(Google_EventReminder) */ $overrides) {
	$this->assertIsArray($overrides, 'Google_EventReminder', __METHOD__);
	$this->overrides = $overrides;
}
$respuesta = array();
function nuevo_evento($summary, $lugar, $fecha, $hora_ini, $hora_fin, $notificacion)
{
	require_once $_SERVER['DOCUMENT_ROOT'].'/suni/includes/libs/google-api-php-client/src/Google_Client.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/suni/includes/libs/google-api-php-client/src/contrib/Google_CalendarService.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/suni/includes/libs/google-api-php-client/src/contrib/Google_Oauth2Service.php';
	session_start();

	$client = new Google_Client();
	$client->setApplicationName("Google Calendar PHP Starter Application");

	$client->setClientId('481495743176.apps.googleusercontent.com');
	$client->setClientSecret('rGTqKPFOcRglVa_jwl3wROTx');
	$client->setRedirectUri('http://funsepa.net/suni/app/src/test/prueba.php');
	$client->setDeveloperKey('481495743176@developer.gserviceaccount.com');
	$client->setAccessType('offline');

	$client->setUseObjects(false);

	$cal = new Google_CalendarService($client);
	if (isset($_GET['logout'])) {
		unset($_SESSION['token']);
	}

	if (isset($_GET['code'])) {
		$client->authenticate($_GET['code']);
		$_SESSION['token'] = $client->getAccessToken();
		$redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
		header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
	}

	if (isset($_SESSION['token'])) {
		$client->setAccessToken($_SESSION['token']);
	}
	$authUrl = $client->createAuthUrl();
	if ($client->getAccessToken()) {
		

		/*function convertir_fecha($fecha)
		{
			$fechaF = explode("/", $fecha);
			$dia = $fechaF[0];
			$mes = $fechaF[1];
			$anno = $fechaF[2];
			$fecha = $anno."-".$mes."-".$dia;
			return $fecha;
		}*/
		$event = new Google_Event();
		$event->setSummary($summary);
		$event->setLocation($lugar);
		$start = new Google_EventDateTime();
		$start->setDateTime($fecha.'T'.$hora_ini.'-07:00');

		$event->setStart($start);
		$end = new Google_EventDateTime();
		$end->setDateTime($fecha.'T'.$hora_fin.'-07:00');

		$reminder = new Google_EventReminders("email", "sms", "popup");
		$reminder->setUseDefault(false);
		$overrides = array("method"=> "email","minutes" => "15");
		$overrides2 = array("method"=> "popup","minutes" => "5");
		$overrides3 = array("method"=> "sms","minutes" => "3");
		$reminder->setOverrides(array($overrides, $overrides2, $overrides3));
		$event->setReminders($reminder);
		$event->setEnd($end);
		$createdEvent = $cal->events->insert('primary', $event);
		return "si";
	}
	else{
		$authUrl = $client->createAuthUrl();

		header('Location: '.$authUrl);
	}

}
if($array_entrada = $_POST['eventos']){
	//nuevo_evento("Descripción", "sede", "21/11/2013", "10:00", "12:00", "");
	//$array_entrada = json_decode($array_entrada);
	foreach ($array_entrada as $key => $evento) {
		if(nuevo_evento("Grupo ".$evento[4]." de ".$evento[7], $evento[5], $evento[1], $evento[2], $evento[3], "")=="si"){
			$respuesta['msj'] = "si";
		}
	}
	echo json_encode($respuesta);
}
if($_GET["prueba"]){
	nuevo_evento("Descripción", "sede", "2013-11-11", "10:00", "12:00", "");
}
if($_GET["login"]){
	require_once $_SERVER['DOCUMENT_ROOT'].'/suni/includes/libs/google-api-php-client/src/Google_Client.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/suni/includes/libs/google-api-php-client/src/contrib/Google_CalendarService.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/suni/includes/libs/google-api-php-client/src/contrib/Google_Oauth2Service.php';
	session_start();

	$client = new Google_Client();
	$client->setApplicationName("Google Calendar PHP Starter Application");

	$client->setClientId('129468258832-kneba1um3150s0cf4jhk3bns42p3hhvn.apps.googleusercontent.com');
	$client->setClientSecret('-Z4KIkwqKB1j9zf3zNME2bAz');
	$client->setRedirectUri('http://funsepa.net/suni/app/src/libs/google_login.php');
	$client->setDeveloperKey('129468258832-4hljhasquar5ujj09cftct08lg992ks3@developer.gserviceaccount.com');
	$client->setAccessType('offline');

	$client->setUseObjects(false);

	$cal = new Google_CalendarService($client);
	if (isset($_GET['logout'])) {
		unset($_SESSION['token']);
	}

	if (isset($_GET['code'])) {
		$client->authenticate($_GET['code']);
		$_SESSION['token'] = $client->getAccessToken();
		$redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
		header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
	}

	if (isset($_SESSION['token'])) {
		$client->setAccessToken($_SESSION['token']);
	}
	$authUrl = $client->createAuthUrl();
	$authUrl = $client->createAuthUrl();

	header('Location: '.$authUrl);
}

?>