<?php

########## Google Settings.. Client ID, Client Secret #############
$google_client_id 		= '129468258832-kneba1um3150s0cf4jhk3bns42p3hhvn.apps.googleusercontent.com';
$google_client_secret 	= '-Z4KIkwqKB1j9zf3zNME2bAz';
$google_redirect_url 	= 'http://funsepa.net/suni/app/src/google/index.php';
$google_developer_key 	= '506d52893fa916525d368fdfcd684d1768e5e02f';

//include google api files
require_once 'src/Google_Client.php';
require_once 'src/contrib/Google_Oauth2Service.php';
require_once 'src/contrib/Google_CalendarService.php';

//start session
if(!isset($_SESSION)){
	session_start();
}

$gClient = new Google_Client();
$gClient->setApplicationName('Login to SUNI');
$gClient->setClientId($google_client_id);
$gClient->setClientSecret($google_client_secret);
$gClient->setRedirectUri($google_redirect_url);
$gClient->setDeveloperKey($google_developer_key);

$google_oauthV2 = new Google_Oauth2Service($gClient);
$cal = new Google_CalendarService($gClient);

//If user wish to log out, we just unset Session variable
if (isset($_REQUEST['reset'])) 
{
  unset($_SESSION['token']);
  $gClient->revokeToken();
  header('Location: ' . filter_var($google_redirect_url, FILTER_SANITIZE_URL));
}

//Redirect user to google authentication page for code, if code is empty.
//Code is required to aquire Access Token from google
//Once we have access token, assign token to session variable
//and we can redirect user back to page and login.
if (isset($_GET['code'])) 
{ 
	$gClient->authenticate($_GET['code']);
	$_SESSION['token'] = $gClient->getAccessToken();
	header('Location: ' . filter_var($google_redirect_url, FILTER_SANITIZE_URL));
	return;
}


if (isset($_SESSION['token'])) 
{ 
		$gClient->setAccessToken($_SESSION['token']);
}


if ($gClient->getAccessToken()) 
{
	  //Get user details if user is logged in
	  $user 				= $google_oauthV2->userinfo->get();
	  $user_id 				= $user['id'];
	  $user_name 			= filter_var($user['name'], FILTER_SANITIZE_SPECIAL_CHARS);
	  $email 				= filter_var($user['email'], FILTER_SANITIZE_EMAIL);
	  $profile_url 			= filter_var($user['link'], FILTER_VALIDATE_URL);
	  $profile_image_url 	= filter_var($user['picture'], FILTER_VALIDATE_URL);
	  $personMarkup 		= "$email<div><img src='$profile_image_url?sz=50'></div>";
	  $_SESSION['token'] 	= $gClient->getAccessToken();
}
else 
{
	//get google login url
	$authUrl = $gClient->createAuthUrl();
}

//HTML page start


if(isset($authUrl)) //user is not logged in, show login button
{
	echo '<a class="login" href="'.$authUrl.'"><img src="app/src/google/images/google-login-button.png" /></a>';
} 
else // user logged in 
{
	//$cal = new Google_CalendarService($gClient);
	$patron = "/^[a-z|A-Z|\.]+@(funsepa)+\.(org)$/";
	if(preg_match($patron, $user["email"])){
		header('Location: ../../../includes/auth/sesion.login.php?mail='.$user["email"]);
	}
	else{
		unset($_SESSION['token']);
  		$gClient->revokeToken();
		//header('Location: http://funsepa.net/suni/cerrarsesion.php');
		return;
	}
	/*require_once('../../../includes/auth/Conf.class.php');
	require_once('../../../includes/auth/Db.class.php');
	$bd = Db::getInstance();
	$consulta = "select * from gn_persona where mail='".$user["email"]."'";
	$stmt=$bd->ejecutar($consulta);
	if($result=$bd->obtener_fila($stmt,0)){
		require_once('../../../includes/auth/validar.php');
		validarUsuario($result["id_usr"],$user["pass"],"");
	}
	else{
		echo "no1";
	}*/

	
	//google_login();
   /* connect to mysql 
    $connecDB = mysql_connect($hostname, $db_username, $db_password)or die("Unable to connect to MySQL");
    mysql_select_db($db_name,$connecDB);
	
    //compare user id in our database
    $result = mysql_query("SELECT COUNT(google_id) FROM google_users WHERE google_id=$user_id");
	if($result === false) { 
		die(mysql_error()); //result is false show db error and exit.
	}
	
	$UserCount = mysql_fetch_array($result);
 
    if($UserCount[0]) //user id exist in database
    {
		echo 'Welcome back '.$user_name.'!';
    }else{ //user is new
		echo 'Hi '.$user_name.', Thanks for Registering!';
		@mysql_query("INSERT INTO google_users (google_id, google_name, google_email, google_link, google_picture_link) VALUES ($user_id, '$user_name','$email','$profile_url','$profile_image_url')");
	}
*/
	
	//echo '<br /><a href="'.$profile_url.'" target="_blank"><img src="'.$profile_image_url.'?sz=50" /></a>';
	//echo '<br /><a class="logout" href="?reset=1">Logout</a>';
	
	//list all user details
	
	//print_r($user);
	
}
if(isset($_GET['error'])){
	header('Location: ../../../');
}
?>

