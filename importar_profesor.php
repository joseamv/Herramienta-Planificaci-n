<?php
	include "conexion.php";
	
	$busca_lineas="SELECT * FROM HORARIO WHERE PGE=$pge_anterior AND CENTRO='$_SESSION[centro]' AND NIF='$_POST[profesor]'";
	//ECHO $busca_lineas;
	$result_lineas=mysql_query($busca_lineas,$conexion);
	while ($fila_lineas=mysql_fetch_row($result_lineas)){
		//echo $fila_lineas[0]."<br>";
		$inserta_linea="INSERT INTO HORARIO (`PGE`, `TITULAR`, `CENTRO`, `ORIGEN`, `NIVEL_EDUCATIVO`, `NOMBRE_ENSENANZA`, `ACTIVIDAD_SENECA`, `ACTIVIDAD_CENTRO`, `CODIGO_MATERIA`, `CURSO`, `GRUPO`, `FECHA_INICIO`, `FECHA_FIN`, `HORAS_CONSUMIDAS`, `NIF`, `OBSERVACIONES`) 
		VALUES ('$pge', '$titular', '$_SESSION[centro]', '$fila_lineas[4]', '$fila_lineas[5]', '$fila_lineas[6]', '$fila_lineas[7]', '$fila_lineas[8]', '$fila_lineas[9]', '$fila_lineas[10]', '$fila_lineas[11]', '$fila_lineas[12]', '$fila_lineas[13]', '$fila_lineas[14]', '$_POST[profesor]', '$fila_lineas[16]')";
		//echo $inserta_linea."<br>";
		mysql_query($inserta_linea,$conexion);
	}
	
	echo "<meta HTTP-EQUIV='REFRESH' content='0; url=asignacion_profes.php?profe=$_POST[profesor]'>";


?>