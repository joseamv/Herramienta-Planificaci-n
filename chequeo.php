<?php
include "conexion.php";
include "header.php";

if ($_SESSION[centro]==""){
	echo "<META HTTP-EQUIV='Refresh' CONTENT='0;URL=./seleccion_centro.php'>";
	exit;
}



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

                    <!-- BEGIN PAGE BAR -->
                    <div class="page-bar">
                        <ul class="page-breadcrumb">
                            <li>
                                <a href="index.php">Inicio</a>
                                <i class="fa fa-circle"></i>
                            </li>

                        </ul>
                        
                    </div>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"> Chequeo
                    </h3>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
                    <!-- BEGIN DASHBOARD STATS 1-->

                    <!-- END DASHBOARD STATS 1-->


					
                    
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon-user font-blue"></i>
                                        <span class="caption-subject font-blue bold uppercase">PROFESORES</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="scroller" style="height: 300px;" data-always-visible="1" data-rail-visible="0">
                                        <ul class="feeds">
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
											while ($fila_personal=mssql_fetch_array($result_personal)){

													$nombre_completo=$fila_personal[0]." ".$fila_personal[1].", ".$fila_personal[2];
													$nombre_completo=iconv("WINDOWS-1252", "UTF-8", $nombre_completo);
													
													$busca_horas="SELECT HORAS, EVENTUALES FROM HORAS_PROFESOR WHERE PGE=$pge AND DNI='$fila_personal[3]'";
													$result_horas=mysql_query($busca_horas,$conexion);
													$fila_horas=mysql_fetch_row($result_horas);
													$horas_profe=$fila_horas[0]+$fila_horas[1];
													
													$horas_asignadas=0;
													$busca_asignadas="SELECT HORAS_CONSUMIDAS FROM HORARIO WHERE PGE=$pge AND NIF='$fila_personal[3]'";
													$result_asignadas=mysql_query($busca_asignadas,$conexion);
													while ($fila_asignadas=mysql_fetch_row($result_asignadas)){
														$horas_asignadas=$horas_asignadas+$fila_asignadas[0];
													}
													
													if ($horas_asignadas!=$horas_profe){
											?>
										   <li>
                                                <div class="col1">
                                                    <div class="cont">
                                                        <div class="cont-col1">
                                                            <div class="label label-sm label-info">
                                                                <i class="fa fa-warning"></i>
                                                            </div>
                                                        </div>
                                                        <div class="cont-col2">
                                                            <div class="desc"> <?php echo $nombre_completo; ?>, <?php echo $horas_asignadas; ?> horas asignadas de <?php echo $horas_profe; ?> disponibles
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col2">
                                                    <div class="date">  </div>
                                                </div>
                                            </li>
											<?php
													}
												}
											
											//Profesores pendientes de contratación
											$sql_personal="SELECT * FROM PROFES_PROVISIONALES WHERE CENTRO='$_SESSION[centro]' AND PGE=$pge";
											$result_personal=mysql_query($sql_personal,$conexion);
											while ($fila_personal=mysql_fetch_row($result_personal)){

													$nombre_completo=$fila_personal[3].", ".$fila_personal[4];
																										
													$busca_horas="SELECT HORAS, EVENTUALES FROM HORAS_PROFESOR WHERE PGE=$pge AND DNI='$fila_personal[0]'";
													$result_horas=mysql_query($busca_horas,$conexion);
													$fila_horas=mysql_fetch_row($result_horas);
													$horas_profe=$fila_horas[0]+$fila_horas[1];
													
													$horas_asignadas=0;
													$busca_asignadas="SELECT HORAS_CONSUMIDAS FROM HORARIO WHERE PGE=$pge AND NIF='$fila_personal[0]'";
													$result_asignadas=mysql_query($busca_asignadas,$conexion);
													while ($fila_asignadas=mysql_fetch_row($result_asignadas)){
														$horas_asignadas=$horas_asignadas+$fila_asignadas[0];
													}
													
													if ($horas_asignadas!=$horas_profe){
											?>
										   <li>
                                                <div class="col1">
                                                    <div class="cont">
                                                        <div class="cont-col1">
                                                            <div class="label label-sm label-info">
                                                                <i class="fa fa-warning"></i>
                                                            </div>
                                                        </div>
                                                        <div class="cont-col2">
                                                            <div class="desc"> <?php echo $nombre_completo; ?>, <?php echo $horas_asignadas; ?> horas asignadas de <?php echo $horas_profe; ?> disponibles
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col2">
                                                    <div class="date">  </div>
                                                </div>
                                            </li>
											<?php
													}
												}
											?>										   
										   
                                            
                                            
                                            
                                        </ul>
                                    </div>
                                    <div class="scroller-footer">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="portlet light bordered ">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon-notebook font-blue"></i>
                                        <span class="caption-subject font-blue bold uppercase">GRUPOS Y MATERIAS</span>
									</div>
                                </div>
                                    <div class="scroller" style="height: 300px;" data-always-visible="1" data-rail-visible="0">
                                        <div class="portlet-body">
                                    <div class="panel-group accordion scrollable" id="accordion2">
											<?php
												
											
											$busca_niveles="SELECT * FROM NIVELES_EDUCATIVOS WHERE ACTIVO=1 ORDER BY ORDEN+0";
											$result_niveles=mysql_query($busca_niveles,$conexion);
											while ($fila_niveles=mysql_fetch_row($result_niveles)){
										
										//Miro si tiene concierto en algún curso de este nivel, si no, no se muestra.
										$muestra=0;
										$busca_cursos="SELECT COD_SENECA FROM CURSOS WHERE NIVEL=$fila_niveles[0]";
										$result_cursos=mysql_query($busca_cursos,$conexion);
										while ($fila_cursos=mysql_fetch_row($result_cursos)){
											$busca_concierto="SELECT GRUPOS FROM UNIDADES_CONCERTADAS WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND CURSO=$fila_cursos[0]";
											//echo $busca_concierto;
											$result_concierto=mysql_query($busca_concierto,$conexion);
											if (mysql_num_rows($result_concierto)>0){
												$fila_concierto=mysql_fetch_row($result_concierto);
												if ($fila_concierto[0]>0){
													$muestra=1;
												}
											}
										}
										if ($muestra==1){
											?>
											<div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#<?php echo $fila_niveles[0]; ?>"> <?php echo $fila_niveles[1]; ?> </a>
                                                </h4>
                                            </div>
                                            <div id="<?php echo $fila_niveles[0]; ?>" class="panel-collapse collapse">
											<div class="panel-body">
											<ul class="feeds">
                                                <?php
												$busca_cursos="SELECT COD_SENECA FROM CURSOS WHERE NIVEL=$fila_niveles[0]";
												$result_cursos=mysql_query($busca_cursos,$conexion);
												while ($fila_cursos=mysql_fetch_row($result_cursos)){
													$busca_nombre_curso="SELECT CURSO FROM CURSOS WHERE COD_SENECA=$fila_cursos[0]";
													$result_nombre_curso=mysql_query($busca_nombre_curso,$conexion);
													$fila_nombre_curso=mysql_fetch_row($result_nombre_curso);
													
													$busca_grupo="SELECT GRUPOS FROM GRUPOS_CENTRO WHERE PGE=$pge AND CENTRO=$_SESSION[centro] AND CURSO=$fila_cursos[0]";
													$result_grupo=mysql_query($busca_grupo,$conexion);
													$fila_grupo=mysql_fetch_row($result_grupo);
													$i=1;
													while ($i<=$fila_grupo[0]){
														
													if ($fila_grupo[0]==1){
														$j=0;
													}else{
														$j=$i;
													}
													
													$busca_nombre_grupo="SELECT GRUPO FROM GRUPOS WHERE ID=$j";
													$result_nombre_grupo=mysql_query($busca_nombre_grupo,$conexion);
													$fila_nombre_grupo=mysql_fetch_row($result_nombre_grupo);
													
													$busca_horario_centro="SELECT MATERIA, HORAS FROM HORARIO_CENTRO WHERE PGE=$pge and CENTRO='$_SESSION[centro]' AND CURSO=$fila_cursos[0] AND GRUPO=$j";
													//echo $busca_horario_centro;
													$result_horario_centro=mysql_query($busca_horario_centro,$conexion);
													while ($fila_horario_centro=mysql_fetch_row($result_horario_centro)){
													if ($fila_horario_centro[1]>0){
														$horas_centro=$fila_horario_centro[1];
														if ($fila_niveles[0]==5 OR $fila_niveles[0]==6){
															$horas_centro=round($horas_centro/60,2);
														}
														$busca_horario="SELECT HORAS_CONSUMIDAS FROM HORARIO WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND CURSO=$fila_cursos[0] AND CODIGO_MATERIA=$fila_horario_centro[0] AND GRUPO=$j";
														$result_horario=mysql_query($busca_horario,$conexion);
														$horas_asignadas=0;
														while ($fila_horario=mysql_fetch_row($result_horario)){
															$horas_asignadas=$horas_asignadas+$fila_horario[0];
														}
														$busca_nombre_materia="SELECT MATERIA FROM MATERIAS_SENECA WHERE ID=$fila_horario_centro[0]";
														$result_nombre_materia=mysql_query($busca_nombre_materia,$conexion);
														$fila_nombre_materia=mysql_fetch_row($result_nombre_materia);
														
														if ($horas_asignadas!=$horas_centro){
															echo "<li><br>$fila_nombre_curso[0] $fila_nombre_grupo[0] - $fila_nombre_materia[0], repartidas $horas_asignadas de un total de $horas_centro</li>";
														}
													}
													}
													$i=$i+1;
													}
												}
												?>
												
												</ul>
                                                </div>
                                            </div>
                                        </div>
											
										<?php	
										}
											}
											?>
                          
                                
                                        
  
                                    </div>
                                </div>
                            </div>
	
									   
										   
                                            
                                            
                                            
                                        
                                    </div>
                                    <div class="scroller-footer">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
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