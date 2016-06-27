<?php
include "conexion.php";
include "header.php";
$profesor=$_POST[profesor];
if ($profesor==""){
	$profesor=$_GET[profe];
}
//ECHO $profesor;



?>
<div class="page-container">
<?php
	include "sidebar.php";
?>
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->
					
<?php
if ($_GET[no]=="1"){
	echo "<div class='alert alert-warning alert-dismissable'>
	<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
	<strong>No permitido</strong> El cambio solicitado no se puede realizar porque la materia está asignada a otro profesor, o no existe en ese grupo. </div>";
}
?>

                    <!-- BEGIN PAGE BAR -->
                    <div class="page-bar">
                        <ul class="page-breadcrumb">
                            <li>
                                <a href="index.php">Inicio</a>
                                <i class="fa fa-circle"></i>
                            </li>
							<li>
								<a href="informes.php">Listados</a>
                                <i class="fa fa-circle"></i>
                            </li>
							<li>
                                <span>Listado de horas por profesor</span>
                            </li>
                        </ul>
                        
                    </div>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE-->

                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
					<!-- BEGIN PAGE CONTENT-->
					<div class="page-content-wrapper">
														
								   
<?php

	if ($titular=='2'){
		$busca_profe="SELECT PRIMERAPELLIDO, SEGUNDOAPELLIDO, NOMBRE, DNI FROM EMPLEADOS_LOYOLA";
	}else{
		$busca_profe="SELECT PRIMERAPELLIDO, SEGUNDOAPELLIDO, NOMBRE, DNI FROM EMPLEADOS ";
	}
	$result_profe=mssql_query($busca_profe);
	
	
	
?>								   
		<div class="note note-info note-bordered">
                        <p> <strong>REPARTO DE HORAS</strong> </p>
         </div>  
		 <div class="portlet light">
	<?php	
	if ($titular=='2'){
$sql_personal="SELECT PRIMERAPELLIDO, SEGUNDOAPELLIDO, NOMBRE, DNI, email FROM EMPLEADOS_LOYOLA WHERE nombrecentro='$_SESSION[centro]'";	
}else{
$sql_personal="SELECT DISTINCT EMPLEADOS.PRIMERAPELLIDO, EMPLEADOS.SEGUNDOAPELLIDO, EMPLEADOS.NOMBRE, EMPLEADOS.DNI, Userfundacionsafa.DNI, Userfundacionsafa.email, CENTROS.codigojunta, EMPLEmovimientos.fechabaja, EMPLEmovimientos.catprofesional
FROM EMPLEmovimientos INNER JOIN
                         EMPLEADOS ON EMPLEmovimientos.reftrabaja = EMPLEADOS.DNI INNER JOIN
                         Userfundacionsafa ON EMPLEADOS.DNI = Userfundacionsafa.DNI INNER JOIN
                         CENTROS ON EMPLEmovimientos.refcentro = CENTROS.ID
WHERE (EMPLEmovimientos.bajarrll=0) and CENTROS.codigojunta=$_SESSION[centro] AND EMPLEmovimientos.catprofesional='PROFESOR' ORDER BY EMPLEADOS.PRIMERAPELLIDO";
}
	$result_personal=mssql_query($sql_personal);
	while ($fila_profe=mssql_fetch_array($result_personal)){
	$nombre_completo=$fila_profe[0]." ".$fila_profe[1].", ".$fila_profe[2];
	$nombre_completo=iconv("WINDOWS-1252", "UTF-8", $nombre_completo);		
	?>	
		
							   <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon-speech"></i>
                                        <span class="caption-subject bold uppercase"> <?php echo $nombre_completo; ?></span>
                                        <span class="caption-helper"></span>
                                    </div>
                                    <div class="actions">
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="panel panel-info">
                                            
											<?php
													//Busco todas las apariciones del profesor en el horario
													$suma_horas=0;
													$busca_asignaturas="SELECT * FROM HORARIO WHERE PGE='$pge' AND NIF='$fila_profe[3]' ORDER BY NIVEL_EDUCATIVO";
													$result_asignaturas=mysql_query($busca_asignaturas,$conexion);
													$etapa_actual=0;
													$total_etapa=0;
													while ($fila_asignaturas=mysql_fetch_row($result_asignaturas)){
														
														if ($etapa_actual!=$fila_asignaturas[5]){
															
															$busca_etapa="SELECT NIVEL_EDUCATIVO FROM NIVELES_EDUCATIVOS WHERE ID=$fila_asignaturas[5]";
																$result_etapa=mysql_query($busca_etapa,$conexion);
																$fila_etapa=mysql_fetch_row($result_etapa);
															if ($etapa_actual>0){														
																echo "</table>";
																
																?>
																<li class="list-group-item list-group-item-info"> Total etapa
                                            <span class="badge badge-warning"> <?php echo $total_etapa; ?> </span>
																</div>
																<?php
																$total_etapa=0;
															}
															?>
															<div class="panel-heading">
																<h3 class="panel-title"><strong>Etapa: <?php echo "$fila_etapa[0]"; ?></strong>

																</h3>
															</div>
															<div class="panel-body">
															<table class="table table-striped table-hover">
															<?php 
														}
														$etapa_actual=$fila_asignaturas[5];
														
														
														echo "<tr>";
														//$busca_origen="SELECT ORIGEN FROM ORIGENES_HORAS WHERE ID=$fila_asignaturas[4]";
														//$result_origen=mysql_query($busca_origen,$conexion);
														//$fila_origen=mysql_fetch_row($result_origen);
														//echo "<td>$fila_origen[0]</td>";
														
														$busca_curso="SELECT CURSO FROM CURSOS WHERE COD_SENECA='$fila_asignaturas[10]'";
														$result_curso=mysql_query($busca_curso,$conexion);
														$fila_curso=mysql_fetch_row($result_curso);
														if ($fila_curso[0]==""){
															$busca_actividad_centro="SELECT * FROM ACTIVIDADES_CENTRO WHERE ID=$fila_asignaturas[8]";
															$result_actividad_centro=mysql_query($busca_actividad_centro,$conexion);
															$fila_actividad_centro=mysql_fetch_row($result_actividad_centro);
															echo "<td witdh='40%'>$fila_actividad_centro[1]</td>";
														}else{
															echo "<td witdh='40%'>$fila_curso[0]</td>";
														}
														$busca_grupo="SELECT GRUPO FROM GRUPOS WHERE ID=$fila_asignaturas[11]";
														$result_grupo=mysql_query($busca_grupo,$conexion);
														$fila_grupo=mysql_fetch_row($result_grupo);
														echo "<td witdh='10%'>$fila_grupo[0]</td>";
														$busca_materia="SELECT MATERIA FROM MATERIAS_SENECA WHERE ID=$fila_asignaturas[9]";
														$result_materia=mysql_query($busca_materia,$conexion);
														$fila_materia=mysql_fetch_row($result_materia);
														echo "<td witdh='35%'>$fila_materia[0]</td>";
													
														
														
														echo "<td witdh='15%'>";
														?>
														
                                            <span class="badge badge-warning"> <?php echo $fila_asignaturas[14]; ?> </span>
														<?php
														//$fila_asignaturas[14] 
														echo "</td>";

														$suma_horas=$suma_horas+$fila_asignaturas[14];
														echo "</tr>";
?>														

												
														
														

														
													<?php	
														$total_etapa=$total_etapa+$fila_asignaturas[14];
													}
													echo "</table>";
													?>
																<li class="list-group-item list-group-item-info"> Total etapa
                                            <span class="badge badge-warning"> <?php echo $total_etapa; ?> </span>
																</div>
													
											</div>
                                       
										<div style="display:block; page-break-before:always;"></div>
										<?php 
	}
	?>
	 </div>
                                </div>
                           </div>
							


                    <!-- END PAGE CONTENT-->


                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
            <?php
				//include "quick_sidebar.php";
			?>
        </div>
        <!-- END CONTAINER -->
		

<?php
	include "footer.php";
?>