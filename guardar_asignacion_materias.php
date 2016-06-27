<?php

	include "conexion.php";
	
	$curso=$_POST[curso];
	$grupo=$_POST[grupo];
	
	//Borro todo lo que hubiera previamente, porque desde esta pantalla vienen todos las materias y profes actuales
	$borra_lineas="DELETE FROM HORARIO WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND CURSO=$curso AND GRUPO=$grupo";
	mysql_query($borra_lineas,$conexion);
	
	$busca_asignaturas="SELECT MATERIA, HORAS FROM HORARIO_CENTRO WHERE CURSO=$curso AND PGE=$pge AND CENTRO='$_SESSION[centro]' AND GRUPO=$grupo";
	//echo $busca_asignaturas;
	$result_asignaturas=mysql_query($busca_asignaturas,$conexion);
	while ($fila_asignaturas=mysql_fetch_row($result_asignaturas)){
		if ($_POST[$fila_asignaturas[0]]!=""){
			$horas=$fila_asignaturas[1];
			
			$busca_nivel="SELECT NIVEL FROM CURSOS WHERE COD_SENECA=$curso";
			$result_nivel=mysql_query($busca_nivel,$conexion);
			$fila_nivel=mysql_fetch_row($result_nivel);
			if ($fila_nivel[0]==5 OR $fila_nivel[0]==6){
				$horas=round($horas/60,2);
			}
			
			$profesor=$_POST[$fila_asignaturas[0]];
			$busca_asignacion="SELECT HORAS_CONSUMIDAS FROM HORARIO WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND CURSO=$curso AND GRUPO=$grupo AND CODIGO_MATERIA=$fila_asignaturas[0]";
			$result_asignacion=mysql_query($busca_asignacion,$conexion);
			if (mysql_num_rows($result_asignacion)>0){
				$update_asignacion="UPDATE HORARIO SET HORAS_CONSUMIDAS=$horas, NIF='$profesor' WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND GRUPO=$grupo AND CURSO=$curso AND CODIGO_MATERIA=$fila_asignaturas[0]";
				//echo $update_asignacion;
				mysql_query($update_asignacion,$conexion);
			}else{
				$inserta_asignacion="INSERT INTO HORARIO (PGE, CENTRO, CURSO, GRUPO, NIF, HORAS_CONSUMIDAS, CODIGO_MATERIA, ORIGEN, ACTIVIDAD_CENTRO, ACTIVIDAD_SENECA, NIVEL_EDUCATIVO) VALUES ($pge, '$_SESSION[centro]', $curso, $grupo, '$profesor', $horas, $fila_asignaturas[0], 1, 1, 9, $fila_nivel[0])";
				mysql_query($inserta_asignacion,$conexion);
				//echo $inserta_asignacion;
			}
		}
	}
	echo "<meta HTTP-EQUIV='REFRESH' content='0; url=asignacion_materias.php?curso=$curso'>";

?>