<?php
if($_GET['mail']){
	include '../../../includes/auth/sesion.login.php';
	$google = new Sesion_google($_GET['mail']);
}
else{
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
	if ($client->getAccessToken()) {
		header('Location: http://funsepa.net/suni/');
	}else{
		$authUrl = $client->createAuthUrl();
		//print "<a class='login' target='_blank' href='$authUrl'>Iniciar sesión</a>";
			//$respuesta = $authUrl;
		$respuesta = array();
			//echo $authUrl."\n";
			//array_push($respuesta, $authUrl);
			//echo json_encode($authUrl);
			//$authUrl);
		header('Location: '.$authUrl);
	}	
}

?>