<?php

	include "conexion.php";
	
	$curso=$_GET[curso];
	
	$busca_grupos="SELECT GRUPOS FROM GRUPOS_CENTRO WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND CURSO=$curso";
	//echo $busca_grupos;
	$result_grupos=mysql_query($busca_grupos,$conexion);
	$fila_grupos=mysql_fetch_row($result_grupos);
	$grupos=$fila_grupos[0];
	
	$i=1;
	
	//Borro las asignaturas anteriores de los grupos (no del grupo 0)
	$borra_materias="DELETE FROM HORARIO_CENTRO WHERE CENTRO='$_SESSION[centro]' AND PGE=$pge AND CURSO=$curso AND GRUPO>0";
	mysql_query($borra_materias);
	
	while ($i<=$grupos){
		$busca_materias="SELECT * FROM HORARIO_CENTRO WHERE CENTRO='$_SESSION[centro]' AND PGE=$pge AND CURSO=$curso AND GRUPO=0";
		$result_materias=mysql_query($busca_materias,$conexion);
		while ($fila_materias=mysql_fetch_row($result_materias)){
			$inserta_materia="INSERT INTO HORARIO_CENTRO (CENTRO, PGE, CURSO, MATERIA, HORAS, SESIONES, GRUPO) VALUES ('$_SESSION[centro]', $pge, $curso, $fila_materias[4], $fila_materias[5], $fila_materias[6], $i)";
			//echo $inserta_materia;
			mysql_query($inserta_materia,$conexion);
		}
		
		$i=$i+1;
	}
	
	echo "<META HTTP-EQUIV='Refresh' CONTENT='0;URL=./mantenimiento_materias.php'>";
	
?>