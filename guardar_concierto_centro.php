<?php

	include "conexion.php";

	
	// Actualizo o inserto el número de grupos para el curso
	$busca_grupos="SELECT GRUPOS FROM UNIDADES_CONCERTADAS WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND CURSO=$_POST[curso]";
	//echo $busca_grupos; 
	$result_grupos=mysql_query($busca_grupos,$conexion);
	if (mysql_num_rows($result_grupos)==0){
		$inserta_grupos="INSERT INTO UNIDADES_CONCERTADAS (PGE, CENTRO, CURSO, GRUPOS) VALUES ($pge, '$_SESSION[centro]', $_POST[curso], $_POST[grupos])";
		//echo $inserta_grupos;
		mysql_query($inserta_grupos,$conexion);
	}else{
		$update_grupos="UPDATE UNIDADES_CONCERTADAS SET GRUPOS=$_POST[grupos] WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND CURSO=$_POST[curso]";
		//echo $update_grupos; 
		mysql_query($update_grupos,$conexion);
	}
	
	
	echo "<meta HTTP-EQUIV='REFRESH' content='0; url=gestion_concierto.php?nivelguardado=$_POST[nivel]'>";
	


?>
