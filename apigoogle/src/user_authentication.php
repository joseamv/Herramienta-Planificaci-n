<?php
session_start();

//require_once 'dbcontroller.php';

//Google API PHP Library includes
require_once 'Google/Client.php';
require_once 'Google/Service/Oauth2.php';

// Fill CLIENT ID, CLIENT SECRET ID, REDIRECT URI from Google Developer Console
 $client_id = '507350918740-02rel6f6sk3eqs7hs6l82c85l8uoprbo.apps.googleusercontent.com';
 $client_secret = 'a-1AoJCmaBMq6FLuuLzi_oCG';
 $redirect_uri = 'http://www.fundacionsafa.es/apigoogle/src/user_authentication.php';
 $simple_api_key = '<Your-API-Key>';
 

//Create Client Request to access Google API
$client = new Google_Client();
//$client->setApplicationName("PHP Google OAuth Login Example");
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_uri);
//$client->setDeveloperKey($simple_api_key);
$client->addScope("https://www.googleapis.com/auth/userinfo.email");

//Send Client Request
$objOAuthService = new Google_Service_Oauth2($client);


/*
//Logout
if (isset($_REQUEST['logout'])) {
  unset($_SESSION['access_token']);
  $client->revokeToken();
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL)); //redirect user back to page
}*/


//Authenticate code from Google OAuth Flow
//Add Access Token to Session
if (isset($_GET['code'])) {
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}


//Set Access Token to make Request
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
  $client->setAccessToken($_SESSION['access_token']);
}

/*
//Get User Data from Google Plus
//If New, Insert to Database
if ($client->getAccessToken()) {
  $userData = $objOAuthService->userinfo->get();
  if(!empty($userData)) {
	//$objDBController = new DBController();
	//$existing_member = $objDBController->getUserByOAuthId($userData->id);
	//if(empty($existing_member)) {
	//	$objDBController->insertOAuthUser($userData);
	//}
  }
  $_SESSION['access_token'] = $client->getAccessToken();
} else {
  $authUrl = $client->createAuthUrl();
}
*/


$userData = $objOAuthService->userinfo->get();

//require_once("viewlogin.php")
$_SESSION['k_email']=$userData["email"];
$_SESSION['k_name']=$userData["name"];
//echo $_SESSION['k_email'];
//echo $userData["name"];

//Busco el centro al que pertenece
$email = $userData["email"];
$eva = $_GET[eva];
$doblecentro=0;
$centro_seleccionado=$_GET[centro_seleccionado];



//Busco la posición de la @ en la dirección de email

$i=0;
$long=strlen($email);
$caracter="";
$j=0;
while ($j<1 AND $i<=$long){
	$caracter=substr($email,$i,1); /* Voy sacando uno a uno los caracteres de la url */
	$i=$i+1;
	
	if ($caracter=="@"){ /* Si el caracter es "@"*/
		$j=1;
	}
}

//$dominio=substr($email,$i,16);
//echo $dominio;


//echo $email;


$dominio = strstr($email, '@');
$safanet=substr($dominio,5,10);

if ($dominio=="@fundacionsafa.es" OR $dominio=="@safareyes.es" OR $dominio=="@osu.safanet.es" OR $dominio=="@mal.safanet.es" OR $dominio=="@ban.safanet.es" OR $safanet=="safanet.es" OR $dominio=="@safa.edu"){





$conn2 = mssql_connect('217.116.2.232', 'DirCentral', 'SaFa1999');

// Seleccionar la base de datos 'php'
mssql_select_db('safa.MDF', $conn2);

if( $conn2 ) {
     //echo "conexión realizada";
}else{
     echo "Conexión con SQL Server no se pudo establecer.<br />";
     die( print_r( sqlsrv_errors(), true));
}


$sql_personal="SELECT DISTINCT EMPLEADOS.PRIMERAPELLIDO, EMPLEADOS.SEGUNDOAPELLIDO, EMPLEADOS.NOMBRE, Userfundacionsafa.DNI, Userfundacionsafa.email, CENTROS.codigojunta, EMPLEmovimientos.fechabaja
FROM EMPLEmovimientos INNER JOIN
                         EMPLEADOS ON EMPLEmovimientos.reftrabaja = EMPLEADOS.DNI INNER JOIN
                         Userfundacionsafa ON EMPLEADOS.DNI = Userfundacionsafa.DNI INNER JOIN
                         CENTROS ON EMPLEmovimientos.refcentro = CENTROS.ID
WHERE (EMPLEmovimientos.bajarrll=0) and userfundacionsafa.email='$email'";

$result_personal = mssql_query($sql_personal);
/*while ($fila_personal=mssql_fetch_array($result_personal)){
	echo "<br>".$fila_personal[0]." ".$fila_personal[1]." ".$fila_personal[2]." ".$fila_personal[3]." ".$fila_personal[4]." ".$fila_personal[5]." ".$fila_personal[6]." ".$fila_personal[7]	;
}*/


if (mssql_num_rows($result_personal)==0){ //No lo he encontrando en SGI, miro en la BD antigua
	$dbhost='localhost';
	$dbusuario="safa";
	$dbpassword="SaFa1999";
	$db="usuariosfundacionsafa";

	$conexion = mysql_connect($dbhost, $dbusuario, $dbpassword);

	mysql_connect($dbhost,$dbusuario,$dbpassword);
	mysql_select_db($db);
	
	$busca_usuario="SELECT CODIGO_CENTRO FROM usuariosfundacionsafa WHERE EMAIL='$email'";
	$result_usuario=mysql_query($busca_usuario,$conexion);
	$fila_usuario=mysql_fetch_row($result_usuario);
	$id_centro=$fila_usuario[0];

}elseif (mssql_num_rows($result_personal)==1){ //Sólo tiene un centro, redirijo directamente a privada.php
	$fila_personal=mssql_fetch_row($result_personal);
	$id_centro=$fila_personal[5];
	
	//Miro si es de Baena, para asignarle la Milagrosa si es del otro centro de trabajo
	if ($id_centro=='14000380'){
		$busca_milagrosa="SELECT DISTINCT EMPLEmovimientos.refempresa
FROM            EMPLEmovimientos INNER JOIN
                         EMPLEADOS ON EMPLEmovimientos.reftrabaja = EMPLEADOS.DNI INNER JOIN
                         Userfundacionsafa ON EMPLEADOS.DNI = Userfundacionsafa.DNI INNER JOIN
                         CENTROS ON EMPLEmovimientos.refcentro = CENTROS.ID
WHERE        (EMPLEmovimientos.bajarrll = 0) AND (Userfundacionsafa.email = '$email')";
//echo $busca_milagrosa;
		$result_milagrosa=mssql_query($busca_milagrosa);
		$fila_milagrosa=mssql_fetch_row($result_milagrosa);
		if ($fila_milagrosa[0]=='21' OR $fila_milagrosa[0]=='22'){
			$id_centro="milagrosa";
			//echo $id_centro;
		}
	}
	
}else{ //Tiene más de un centro, muestro el desplegable para que elija
	$doblecentro=1;
	if ($_GET["centro_seleccionado"]!=""){
		$doblecentro=0;
		$id_centro=$_GET[centro_seleccionado];
	}
	echo "Elige el centro con el que quieres trabajar";
	echo "<form action=user_authentication.php method=GET>";
	echo "<select name='centro_seleccionado'>";
	while ($fila_personal=mssql_fetch_row($result_personal)){
		$busca_nombre_centro="SELECT NOMBRE FROM CENTROS WHERE codigojunta='$fila_personal[5]'";
		$result_nombre_centro=mssql_query($busca_nombre_centro);
		$fila_nombre_centro=mssql_fetch_array($result_nombre_centro);
		
		echo "<option value='$fila_personal[5]'>$fila_nombre_centro[0]</option>";
	}
	echo "</select>";
	echo "<input type=hidden name='email' value=$email>";
	echo "<input type=hidden name='eva' value=$eva>";
	echo "<input type=hidden name='doble_centro' value='1'>";
	echo "<input type=submit name='Aceptar' value='Enviar'>";
	echo "</form>";
}



mssql_free_result($result_personal);

if ($dominio=="@safareyes.es" OR $safanet=="safanet.es"){ //Alumno o familia, busco su centro y activo la variable correspondiente
	$_SESSION[esalumno]=1;
	$codigo_safanet=substr($dominio,1,3);
	$busca_centro="SELECT codigojunta FROM CENTROS WHERE SAFANET='$codigo_safanet'";
	echo $busca_centro;
	$result_centro=mssql_query($busca_centro);
	$fila_centro=mssql_fetch_row($result_centro);
	$id_centro=$fila_centro[0];
	//echo $dominio;
	if ($dominio=="@safareyes.es"){
		$id_centro="41005075";
	}
}

if ($doblecentro==0){
	
	echo "<META HTTP-EQUIV='Refresh' CONTENT='0;URL=../../privada.php?centro_id=$id_centro'>";
}


}else
{
	echo "No está autorizado a entrar en la aplicación, debe acceder con una cuenta de fundacionsafa.es";

}




?>