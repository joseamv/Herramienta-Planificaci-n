<?php
//echo "<META HTTP-EQUIV='Refresh' CONTENT='0;URL=https://accounts.google.com/o/oauth2/auth?response_type=code&redirect_uri=http%3A%2F%2Fpepe.safa.edu%2Fapigoogle%2Fsrc%2Fuser_authentication.php&client_id=458941052008.apps.googleusercontent.com&scope=https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.email&access_type=online&approval_prompt=auto'>";

define('CLIENTID','507350918740-02rel6f6sk3eqs7hs6l82c85l8uoprbo.apps.googleusercontent.com');
define('CLIENTSECRET','a-1AoJCmaBMq6FLuuLzi_oCG');
define('URLCALLBACK', 'http://www.fundacionsafa.es/apigoogle/src/user_authentication.php');
define('URL','http://www.fundacionsafa.es');
 
if (isset($_GET['error']) && $_GET['error']){
	$error = $_GET['error'];
 
	header('Location: '.URL.'error.php?errorg='.$error);
	die();
} elseif (isset($_GET['code']) && $_GET['code']) {
	$code = $_GET['code'];
 
	header('Location: '.URL.'googgettoken.php?code='.$code);
	die();
} else {
	$scope = 'https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.email+';
	$scope .= 'https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.profile+';
	$scope .= 'https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fanalytics.readonly+';
 
	$url = "https://accounts.google.com/o/oauth2/auth?scope=$scope&state=%2Fprofile&redirect_uri=".URLCALLBACK."&response_type=code&client_id=".CLIENTID;
 
	header('Location: '.$url);
	die();
}
?>
