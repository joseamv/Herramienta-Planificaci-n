<?php

	include "conexion.php";

	$profesor=$_POST[profesor];

if ($_POST[editar]==1){
	if ($_POST[tipo_linea]==2){
				//Compruebo ahora que la materia aparece, para el centro, en el curso en el que se quiere asignar
		$busca_horario_centro="SELECT HORAS FROM HORARIO_CENTRO WHERE CENTRO='$_SESSION[centro]' AND PGE=$pge AND CURSO='$_POST[curso]' AND MATERIA=$_POST[materia]";
		//echo $busca_horario_centro;
		$result_horario_centro=mysql_query($busca_horario_centro,$conexion);
		if (mysql_num_rows($result_horario_centro)==0){
			$noesposible=1;
		}
			$fila_horario_centro=mysql_fetch_row($result_horario_centro);
			$horas=$fila_horario_centro[0];
			if ($fila_niveles[0]==5 OR $fila_niveles[0]==6){
				$horas=round($horas/60,2);
			}
		
		
		//Miro si es posible la asignación, por si está la materia asginada a otro profesor.
		$busca_linea="SELECT HORAS_CONSUMIDAS FROM HORARIO WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND CURSO=$_POST[curso] AND GRUPO=$_POST[grupo] AND CODIGO_MATERIA=$_POST[materia] AND NIF!=$_POST[profesor]";
		//echo $busca_linea;
		$result_linea=mysql_query($busca_linea,$conexion);
		$horas_consumidas=0;
		while ($fila_horario=mysql_fetch_row($result_linea)){
			$horas_consumidas=$horas_consumidas+$fila_horario[0];
		}
		
		$noesposible=0;
		if ($horas_consumidas>=$horas){
			$noesposible=1;
		}

		$horas_disponibles=$horas-$horas_consumidas;
		//echo $horas_disponibles;
		if ($_POST[horas]>$horas_disponibles){
			$noesposible=1;
		}
		//echo "horas:".$horas;
		//echo "consumidas:".$horas_consumidas;
		//echo "no es posible".$noesposible;
		if ($noesposible==0){	
		$busca_nivel="SELECT NIVEL FROM CURSOS WHERE COD_SENECA=$_POST[curso]";
		$result_nivel=mysql_query($busca_nivel,$conexion);
		$fila_nivel=mysql_fetch_row($result_nivel);
		$nivel=$fila_nivel[0];
		$update_horario="UPDATE HORARIO SET ORIGEN=$_POST[origen], CURSO=$_POST[curso], CODIGO_MATERIA=$_POST[materia], NIVEL_EDUCATIVO=$nivel, ACTIVIDAD_SENECA=$_POST[actividad_seneca], GRUPO=$_POST[grupo], ACTIVIDAD_CENTRO=$_POST[actividad_centro], OBSERVACIONES='$_POST[observaciones]', HORAS_CONSUMIDAS=$_POST[horas] WHERE ID=$_POST[id]";		
		//echo $update_horario;
		}
	}else{
		$update_horario="UPDATE HORARIO SET ORIGEN=$_POST[origen], NIVEL_EDUCATIVO=$_POST[nivel], ACTIVIDAD_SENECA=$_POST[actividad_seneca], ACTIVIDAD_CENTRO=$_POST[actividad_centro], HORAS_CONSUMIDAS=$_POST[horas], OBSERVACIONES='$_POST[observaciones]' WHERE ID=$_POST[id]";		
		
	}

	//echo $update_horario;
	mysql_query($update_horario,$conexion);
	
}else{

	
if ($_POST[nolectivas]==1){
	$inserta_nolectiva="INSERT INTO HORARIO (PGE, CENTRO, ORIGEN, NIVEL_EDUCATIVO, ACTIVIDAD_SENECA, ACTIVIDAD_CENTRO, NIF, HORAS_CONSUMIDAS, OBSERVACIONES) VALUES ($pge, '$_SESSION[centro]', $_POST[origen], $_POST[nivel], $_POST[actividad_seneca], $_POST[actividad_centro], '$profesor', $_POST[horas], '$_POST[observaciones]')";
	//echo $inserta_nolectiva;
	mysql_query($inserta_nolectiva,$conexion);
}else{
	
$busca_niveles="SELECT * FROM NIVELES_EDUCATIVOS WHERE ACTIVO=1 ORDER BY ORDEN+0";
$result_niveles=mysql_query($busca_niveles,$conexion);
while ($fila_niveles=mysql_fetch_row($result_niveles)){	
	$busca_cursos="SELECT CURSO, COD_SENECA FROM CURSOS WHERE NIVEL=$fila_niveles[0] ORDER BY CURSO";
	$result_cursos=mysql_query($busca_cursos,$conexion);
	while ($fila_cursos=mysql_fetch_row($result_cursos)){
		$busca_grupos="SELECT GRUPOS FROM GRUPOS_CENTRO WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND CURSO=$fila_cursos[1]";
		$result_grupos=mysql_query($busca_grupos,$conexion);
		$fila_grupos=mysql_fetch_row($result_grupos);
		$i=1;
		while ($i<=$fila_grupos[0]){
			if ($fila_grupos[0]==1){
				$j=0;
			}else{
				$j=$i;
			}
			//Muestro todas las asignaturas que vengan de este curso y grupo
			$nombre_variable=$fila_cursos[1]."-".$j;
			$materias=$_POST[$nombre_variable];
			$p=0;
			while ($p<count($materias)){
				//Busco si la tiene asignada, para insertarla si no
				$busca_asignacion="SELECT ID FROM HORARIO WHERE PGE=$pge, CENTRO='$_SESSION[centro]' AND CURSO=$fila_cursos[1] AND CODIGO_MATERIA=$materias[$p] AND GRUPO='$j' AND NIF='$profesor'";
				$result_asignacion=mysql_query($busca_asignacion,$conexion);
				if (mysql_num_rows($result_asignacion)==0){
					$busca_horas="SELECT HORAS FROM HORARIO_CENTRO WHERE CENTRO='$_SESSION[centro]' AND PGE=$pge AND CURSO=$fila_cursos[1] AND MATERIA=$materias[$p] AND GRUPO='$j'";
					//echo $busca_horas;
					$result_horas=mysql_query($busca_horas,$conexion);
					$fila_horas=mysql_fetch_row($result_horas);
					
					$horas=$fila_horas[0];
					if ($fila_niveles[0]==6 OR $fila_niveles[0]==5){
						$horas=round($horas/60,2);
					}
					
					
					//Sólo le asigno el número de horas disponibles que queden en la asignatura.
					$busca_linea="SELECT HORAS_CONSUMIDAS FROM HORARIO WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND CURSO=$fila_cursos[1] AND GRUPO='$j' AND CODIGO_MATERIA=$materias[$p]";
					//echo $busca_linea;
					$result_linea=mysql_query($busca_linea,$conexion);
					$fila_horario=mysql_fetch_row($result_linea);
					$horas_consumidas=$fila_horario[0];
					$horas_disponibles=$horas-$horas_consumidas;
					
					$inserta_asignacion="INSERT INTO HORARIO (CENTRO, CURSO, CODIGO_MATERIA, NIVEL_EDUCATIVO, PGE, GRUPO, HORAS_CONSUMIDAS, NIF, ORIGEN, ACTIVIDAD_CENTRO, ACTIVIDAD_SENECA) VALUES ('$_SESSION[centro]', $fila_cursos[1], $materias[$p], $fila_niveles[0], $pge, $j, $horas_disponibles, '$profesor', 1, 1, 9)";
					//echo $inserta_asignacion;
					mysql_query($inserta_asignacion,$conexion);
				}

				
				//echo $materias[$p]."<br>";
				$p=$p+1;
			}
			
			$i=$i+1;
		}
	}
}
}
}
	
	
	
	
	echo "<meta HTTP-EQUIV='REFRESH' content='0; url=asignacion_profes.php?no=$noesposible&profe=$profesor'>";
	
	


?>
