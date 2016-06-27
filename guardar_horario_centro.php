<?php

	include "conexion.php";

	//Recorro todas las materias del curso correspondiente, para ver si vienen marcadas o no
	$busca_materias="SELECT COD_MATERIA FROM CURSOS_ASIGNATURAS WHERE COD_CURSO=$_POST[curso]";
	//echo $busca_materias;
	$result_materias=mysql_query($busca_materias,$conexion);
	while ($fila_materias=mysql_fetch_row($result_materias)){
		if ($_POST[$fila_materias[0]]=='on'){
			$variable_horas="horas".$fila_materias[0];
			$variable_sesiones="sesiones".$fila_materias[0];
			$variable_observaciones="observaciones".$fila_materias[0];
			$horas=$_POST[$variable_horas];
			$sesiones=$_POST[$variable_sesiones];
			$observaciones=$_POST[$variable_observaciones];
			if ($horas==""){
				$horas=0;
			}
			if ($sesiones==""){
				$sesiones=0;
			}
			//Miro si no existía antes y, en ese caso, lo inserto. Si ya existía, actualizo el número de horas.
			$busca_horario="SELECT * FROM HORARIO_CENTRO WHERE CENTRO='$_SESSION[centro]' AND PGE=$pge AND CURSO=$_POST[curso] AND MATERIA=$fila_materias[0] AND GRUPO=$_POST[grupo]";
			//echo $busca_horario;
			$result_horario=mysql_query($busca_horario,$conexion);
			if (mysql_num_rows($result_horario)==0){
				//Inserto
				$inserta_horario="INSERT INTO HORARIO_CENTRO (CENTRO, PGE, CURSO, MATERIA, HORAS, SESIONES, OBSERVACIONES, GRUPO) VALUES ('$_SESSION[centro]', $pge, $_POST[curso], $fila_materias[0], $horas, $sesiones, '$observaciones', $_POST[grupo])";
				//echo $inserta_horario;
				mysql_query($inserta_horario,$conexion);
			}else{
				//Actualizo
				$update_horario="UPDATE HORARIO_CENTRO SET HORAS=$horas, SESIONES=$sesiones, OBSERVACIONES='$observaciones' WHERE CENTRO='$_SESSION[centro]' AND PGE=$pge AND CURSO=$_POST[curso] AND MATERIA=$fila_materias[0] AND GRUPO=$_POST[grupo]";
				//echo $update_horario;
				mysql_query($update_horario,$conexion);
			}
		}else{
			//Busco si existía antes para darlo de baja
			$busca_horario="SELECT * FROM HORARIO_CENTRO WHERE CENTRO='$_SESSION[centro]' AND PGE=$pge AND CURSO=$_POST[curso] AND MATERIA=$fila_materias[0] AND GRUPO=$_POST[grupo]";
			//echo $busca_horario;
			$result_horario=mysql_query($busca_horario,$conexion);
			if (mysql_num_rows($result_horario)>0){
				$borrar_horario="DELETE FROM HORARIO_CENTRO WHERE CENTRO='$_SESSION[centro]' AND PGE=$pge AND CURSO=$_POST[curso] AND MATERIA=$fila_materias[0] AND GRUPO=$_POST[grupo]";
				mysql_query($borrar_horario,$conexion);
			}
		}
	}
	
	// Actualizo o inserto el número de grupos para el curso
	$busca_grupos="SELECT GRUPOS FROM GRUPOS_CENTRO WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND CURSO=$_POST[curso]";
	//echo $busca_grupos; 
	$result_grupos=mysql_query($busca_grupos,$conexion);
	if (mysql_num_rows($result_grupos)==0){
		$inserta_grupos="INSERT INTO GRUPOS_CENTRO (PGE, CENTRO, CURSO, GRUPOS) VALUES ($pge, '$_SESSION[centro]', $_POST[curso], $_POST[grupos])";
		//echo $inserta_grupos;
		mysql_query($inserta_grupos,$conexion);
	}else{
		$update_grupos="UPDATE GRUPOS_CENTRO SET GRUPOS=$_POST[grupos] WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND CURSO=$_POST[curso]";
		//echo $update_grupos; 
		mysql_query($update_grupos,$conexion);
	}
	
	
	echo "<meta HTTP-EQUIV='REFRESH' content='0; url=mantenimiento_materias.php?nivelguardado=$_POST[nivel]'>";
	


?>
