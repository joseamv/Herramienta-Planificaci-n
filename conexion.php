<?php
session_start();

if ($_GET[flac]==1){
	$_SESSION[k_user]=$_GET[email];
}


ini_set('mssql.charset', 'WINDOWS-1252');

    $dbhost='localhost';
    $dbusuario="safa";
    $dbpassword="SaFa1999";
    $db="planificacion";

$conexion = mysql_connect($dbhost, $dbusuario, $dbpassword);

mysql_select_db($db, $conexion);


mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $conexion);

$conn = mssql_connect('217.116.2.232', 'DirCentral', 'SaFa1999');
mssql_select_db('safa.MDF', $conn);


$pge='2016';
$pge_anterior='2015';

if ($_SESSION[k_user]=='mrivas.ext@fundacionsafa.es' OR $_SESSION[k_user]=='correo@fundacionsafa.es'){
	$pge='2015';
	$pge_anterior='2014';
}

//Si no ha entrado a través del brocal o no tiene permisos en la tabla de usuarios de planificación
$busca_planificacion="SELECT * FROM planificacion.USUARIOS WHERE EMAIL='$_SESSION[k_user]'";
$result_planificacion=mysql_query($busca_planificacion,$conexion);



if ($_SESSION[k_user]=="" OR mysql_num_rows($result_planificacion)==0){
	//echo $_SESSION[k_user];
	echo "<META HTTP-EQUIV='Refresh' CONTENT='0;URL=../conexiongoogle.php?pla=1'>";
	exit;
}

$fila_planificacion=mysql_fetch_row($result_planificacion);
if ($fila_planificacion[4]=='99999999' OR $fila_planificacion[4]=='11003001' OR $_SESSION[k_user]=='rmartin@fundacionsafa.es'){

}else{
	$_SESSION[centro]=$fila_planificacion[4];
	
}

$_SESSION[permiso]=$fila_planificacion[3];

//$_SESSION[centro]="41005075";
//$_SESSION[permiso]=1;

$busca_centro="SELECT CENTRO, TITULAR FROM CENTROS WHERE CODIGO='$_SESSION[centro]'";
$result_centro=mysql_query($busca_centro,$conexion);
$fila_centro=mysql_fetch_row($result_centro);
$nombre_centro=$fila_centro[0];
$titular=$fila_centro[1];

$pge_siguiente=$pge+1;

?>