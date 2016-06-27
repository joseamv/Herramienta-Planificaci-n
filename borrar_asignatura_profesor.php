<?php

	include "conexion.php";

	$id=$_GET[id];
	$profesor=$_GET[profe];
	
	$borrar_asignatura="DELETE FROM HORARIO WHERE ID=$id";
	mysql_query($borrar_asignatura,$conexion);
	

	//echo $profesor;
	
	
	
	echo "<meta HTTP-EQUIV='REFRESH' content='0; url=asignacion_profes.php?profe=$profesor'>";
	


?>