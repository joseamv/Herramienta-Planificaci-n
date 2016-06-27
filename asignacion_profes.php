<?php
include "conexion.php";
include "header.php";
$profesor=$_POST[profesor];
if ($profesor==""){
	$profesor=$_GET[profe];
}
ECHO $profesor;



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
                                <span>Asignación de Materias a Profesor</span>
                            </li>
                        </ul>
                        
                    </div>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"> Asignación de Materias a Profesor
                    </h3>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
					<!-- BEGIN PAGE CONTENT-->
					<div class="tab-content">
										<div class="form-group form-md-line-input has-info">
                                                
												<form name="seleccionar_profesor" action='asignacion_profes.php' method='POST'>
												<select class="form-control" id="form_control_1" name="profesor" onchange='javascript:seleccionar_profesor.submit();'>
                                                    <option value=""></option>
													
<?php


if ($titular=='2'){
$sql_personal="SELECT PRIMERAPELLIDO, SEGUNDOAPELLIDO, NOMBRE, DNI, email FROM EMPLEADOS_LOYOLA WHERE nombrecentro='$_SESSION[centro]' ORDER BY PRIMERAPELLIDO, SEGUNDOAPELLIDO";	
}else{

if ($_SESSION[centro]=='11701279'){
	$centro='11003001';
}else{
	$centro=$_SESSION[centro];
}	
	
$sql_personal="SELECT DISTINCT EMPLEADOS.PRIMERAPELLIDO, EMPLEADOS.SEGUNDOAPELLIDO, EMPLEADOS.NOMBRE, EMPLEADOS.DNI, Userfundacionsafa.DNI, Userfundacionsafa.email, CENTROS.codigojunta, EMPLEmovimientos.fechabaja, EMPLEmovimientos.catprofesional
FROM EMPLEmovimientos INNER JOIN
                         EMPLEADOS ON EMPLEmovimientos.reftrabaja = EMPLEADOS.DNI INNER JOIN
                         Userfundacionsafa ON EMPLEADOS.DNI = Userfundacionsafa.DNI INNER JOIN
                         CENTROS ON EMPLEmovimientos.refcentro = CENTROS.ID
WHERE (EMPLEmovimientos.bajarrll=0) and CENTROS.codigojunta=$centro AND (EMPLEmovimientos.catprofesional='PROFESOR' OR EMPLEmovimientos.catprofesional='PROFESOR EDU.ESPEC.INTEGRADA') ORDER BY EMPLEADOS.PRIMERAPELLIDO";
}

if ($_SESSION[centro]=='14000471'){
											$sql_personal="SELECT DISTINCT EMPLEADOS.PRIMERAPELLIDO, EMPLEADOS.SEGUNDOAPELLIDO, EMPLEADOS.NOMBRE, EMPLEADOS.DNI, Userfundacionsafa.DNI, Userfundacionsafa.email, CENTROS.codigojunta, EMPLEmovimientos.fechabaja, EMPLEmovimientos.catprofesional FROM EMPLEmovimientos INNER JOIN
                         EMPLEADOS ON EMPLEmovimientos.reftrabaja = EMPLEADOS.DNI INNER JOIN
                         Userfundacionsafa ON EMPLEADOS.DNI = Userfundacionsafa.DNI INNER JOIN
                         CENTROS ON EMPLEmovimientos.refcentro = CENTROS.ID
						 WHERE (EMPLEmovimientos.bajarrll=0) and CENTROS.codigojunta='14000380' AND EMPLEmovimientos.catprofesional='PROFESOR' ORDER BY EMPLEADOS.PRIMERAPELLIDO";
}

$result_personal=mssql_query($sql_personal);
while ($fila_personal=mssql_fetch_array($result_personal)){
	
	
	$selected="";
	if ($fila_personal[3]==$profesor){
		$selected="SELECTED";
	}
	
		//Busco las horas que tiene por contrato el profesor
	$busca_horas_profe="SELECT HORAS, EVENTUALES FROM HORAS_PROFESOR WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND DNI='$fila_personal[3]'";
	$result_horas_profe=mysql_query($busca_horas_profe,$conexion);
	if (mysql_num_rows($result_horas_profe)>0){
		$fila_horas_profe=mysql_fetch_row($result_horas_profe);
		$horas_profe=$fila_horas_profe[0]+$fila_horas_profe[1];
	}else{
		$horas_profe=0;
	}
	
	$horas_asignadas=0;
	$busca_horas_asignadas="SELECT SUM(HORAS_CONSUMIDAS) FROM HORARIO WHERE CENTRO='$_SESSION[centro]' AND PGE=$pge AND NIF='$fila_personal[3]'";
	$result_horas_asignadas=mysql_query($busca_horas_asignadas,$conexion);
	$fila_horas_asignadas=mysql_fetch_row($result_horas_asignadas);
	$horas_asignadas=$fila_horas_asignadas[0];
	
	$nombre_completo=$fila_personal[0]." ".$fila_personal[1].", ".$fila_personal[2];
	$nombre_completo=iconv("WINDOWS-1252", "UTF-8", $nombre_completo);
	echo "<option value=$fila_personal[3] $selected>$nombre_completo $horas_asignadas/$horas_profe</option>";
}		

//Muestro profes pendientes de contratación:

$sql_personal="SELECT * FROM PROFES_PROVISIONALES WHERE CENTRO='$_SESSION[centro]' AND PGE=$pge";
$result_personal=mysql_query($sql_personal,$conexion);
while ($fila_personal=mysql_fetch_row($result_personal)){
	$selected="";
	if ($fila_personal[0]==$profesor){
		$selected="SELECTED";
	}
	
			//Busco las horas que tiene por contrato el profesor
	$busca_horas_profe="SELECT HORAS, EVENTUALES FROM HORAS_PROFESOR WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND DNI='$fila_personal[0]'";
	$result_horas_profe=mysql_query($busca_horas_profe,$conexion);
	if (mysql_num_rows($result_horas_profe)>0){
		$fila_horas_profe=mysql_fetch_row($result_horas_profe);
		$horas_profe=$fila_horas_profe[0]+$fila_horas_profe[1];
	}else{
		$horas_profe=0;
	}
	
	$horas_asignadas=0;
	$busca_horas_asignadas="SELECT SUM(HORAS_CONSUMIDAS) FROM HORARIO WHERE CENTRO='$_SESSION[centro]' AND PGE=$pge AND NIF='$fila_personal[0]'";
	$result_horas_asignadas=mysql_query($busca_horas_asignadas,$conexion);
	$fila_horas_asignadas=mysql_fetch_row($result_horas_asignadas);
	$horas_asignadas=$fila_horas_asignadas[0];
	
	
	$nombre_completo=$fila_personal[3];
	echo "<option value=$fila_personal[0] $selected>$nombre_completo $horas_asignadas/$horas_profe</option>";
}	
									   
?>		
                                                </select>
												</form>
                                                <label for="form_control_1">Selecciona el profesor</label>
                                            </div>					
								   
<?php
if ($profesor!=""){
	if ($titular=='2'){
		$busca_profe="SELECT PRIMERAPELLIDO, SEGUNDOAPELLIDO, NOMBRE, DNI FROM EMPLEADOS_LOYOLA WHERE DNI='$profesor'";
	}else{
		$busca_profe="SELECT PRIMERAPELLIDO, SEGUNDOAPELLIDO, NOMBRE, DNI FROM EMPLEADOS WHERE DNI='$profesor'";
	}
	$result_profe=mssql_query($busca_profe);
	$fila_profe=mssql_fetch_array($result_profe);
	$nombre_completo=$fila_profe[0]." ".$fila_profe[1].", ".$fila_profe[2];
	$nombre_completo=iconv("WINDOWS-1252", "UTF-8", $nombre_completo);
	
	//Busco las horas que tiene por contrato el profesor
	$busca_horas_profe="SELECT HORAS, EVENTUALES FROM HORAS_PROFESOR WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND DNI='$profesor'";
	$result_horas_profe=mysql_query($busca_horas_profe,$conexion);
	if (mysql_num_rows($result_horas_profe)>0){
		$fila_horas_profe=mysql_fetch_row($result_horas_profe);
		$horas_profe=$fila_horas_profe[0]+$fila_horas_profe[1];
	}else{
		$horas_profe=0;
	}
	
?>								   
						<div class="row">
                        
								    <a class="btn red btn-outline sbold" data-toggle="modal" href="#responsive"> Horas de Docencia Directa </a>
									<?php
										if ($_SESSION[permiso]<=2){
									?>
									<a class="btn red btn-outline sbold" data-toggle="modal" href="#nolectivas"> Añadir otras horas </a>
									<a class="btn red btn-outline sbold" data-toggle="modal" href="#cursoanterior"> Importar curso anterior </a>
									<?php
										}
									?>
						</div><br>
						
	<!--- Importar curso anterior -->
						<div id="cursoanterior" class="modal fade" tabindex="-1" data-width="760">
								<div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                   
                                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-18">
                                            <!-- BEGIN EXAMPLE TABLE PORTLET-->
                                            <div class="portlet light portlet-fit bordered">

                                                <div class="portlet-body">
                                                    <!-- INICIO AÑADIR -->
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="tabbable-line boxless tabbable-reversed">
                                                                <div class="tab-pane" id="tab_1">
                                                                    <div class="portlet box blue">
                                                                        <div class="portlet-title">
                                                                         <div class="caption">Importar horas del curso anterior</div>
                                                                        </div>
                                                                        <div class="portlet-body form">
                                                                            <!-- BEGIN FORM-->
																			
																			<div class="form-body">
																				
																			<div class="form-group">
												<form action="importar_profesor.php" class="horizontal-form" method="POST" id="form1">
									
																			</div>
																			
																			<div class="form-actions">
                                    <div class="row">
                                                                                       


                                                                                           
                                    </div>
									<div class="row">

									</div>

													Las horas del curso anterior correspondientes a este profesor, se copiarán para el curso actual. Por favor, revisa la importación porque es posible que falten datos en algunas o todas las filas. Gracias.									
																																			
									
                                                                                            
                                                                             
																						
																					
                                     </div>
																					
																					<div class="modal-footer">
																							<input type="hidden" name="profesor" value="<?php echo $profesor; ?>">
																							<input type="hidden" name="nolectivas" value="1">
																							<button type="submit" class="btn red" name="guardar" value="Save">Importar</button>
																							<button type="button" data-dismiss="modal" class="btn btn-outline dark">Cerrar</button>

																					</div>
																					
																				</div>
												</form>
                                                                            <!-- END FORM-->
                                                                        </div>
                                                                    </div>
																</div>

                                                            </div>

                                                        </div>
                                                    </div>

												

                                                    <!--FIN AÑADIR-->
                                                </div>
                                            </div>
                                            <!-- END EXAMPLE TABLE PORTLET-->
                                        </div>
									</div>
									</div>
									</div>
                                    </div>
                                

                            </div>
<!--- Fin modal -->								
						

	<!--- Modal Añadir horas no lectivas -->
						<div id="nolectivas" class="modal fade" tabindex="-1" data-width="760">
								<div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                   
                                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-18">
                                            <!-- BEGIN EXAMPLE TABLE PORTLET-->
                                            <div class="portlet light portlet-fit bordered">

                                                <div class="portlet-body">
                                                    <!-- INICIO AÑADIR -->
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="tabbable-line boxless tabbable-reversed">
                                                                <div class="tab-pane" id="tab_1">
                                                                    <div class="portlet box blue">
                                                                        <div class="portlet-title">
                                                                         <div class="caption">Otras horas</div>
                                                                        </div>
                                                                        <div class="portlet-body form">
                                                                            <!-- BEGIN FORM-->
																			
																			<div class="form-body">
																				
																			<div class="form-group">
												<form action="guardar_asignacion_profesor.php" class="horizontal-form" method="POST" id="form1">
									
																			</div>
																			
																			<div class="form-actions">
                                    <div class="row">
                                                                                       
									<div class="col-md-3">													
									<div class="form-group form-md-line-input has-info">													
									<select class="form-control" id="nivel" name="nivel" onchange=''>
                                                    <option value=""></option>
													<?php
														$busca_niveles="SELECT * FROM NIVELES_EDUCATIVOS WHERE ACTIVO=1 ORDER BY ORDEN";
														$result_niveles=mysql_query($busca_niveles,$conexion);
														while ($fila_niveles=mysql_fetch_row($result_niveles)){
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
															echo "<option value=$fila_niveles[0]>$fila_niveles[1]</option>";
										}
														}
													
													?>
                                     </select>
												<label for="nivel">Nivel</label>
									</div>
									</div>
									<div class="col-md-3">													
									<div class="form-group form-md-line-input has-info">
												<select class="form-control" id="origen" name="origen" onchange=''>
                                                    <option value=""></option>
													<?php
														$busca_origenes="SELECT * FROM ORIGENES_HORAS WHERE ACTIVO=1";
														$result_origenes=mysql_query($busca_origenes,$conexion);
														while ($fila_origenes=mysql_fetch_row($result_origenes)){
															echo "<option value=$fila_origenes[0]>$fila_origenes[1]</option>";
														}
													
													?>
                                                </select>
                                                <label for="origen">Origen</label>
                                                
                                    </div>
									</div>
									<div class="col-md-3">													
									<div class="form-group form-md-line-input has-info">
												<select class="form-control" id="actividad_centro" name="actividad_centro" onchange=''>
                                                    <option value=""></option>
													<?php
														$busca_actividades="SELECT * FROM ACTIVIDADES_CENTRO WHERE ACTIVO=1 ORDER BY ACTIVIDAD_CENTRO";
														$result_actividades=mysql_query($busca_actividades,$conexion);
														while ($fila_actividades=mysql_fetch_row($result_actividades)){
															echo "<option value=$fila_actividades[0]>$fila_actividades[1]</option>";
														}
													
													?>
                                                </select>
                                                <label for="origen">Actividad Centro</label>
                                                
                                    </div>
									</div>
									
									<div class="col-md-3">													
									<div class="form-group form-md-line-input has-info">
												<select class="form-control" id="actividad_seneca" name="actividad_seneca" onchange=''>
                                                    <option value=""></option>
													<?php
														$busca_seneca="SELECT * FROM ACTIVIDADES_SENECA WHERE ACTIVO=1 ORDER BY ACTIVIDAD";
														$result_seneca=mysql_query($busca_seneca,$conexion);
														while ($fila_seneca=mysql_fetch_row($result_seneca)){
															echo "<option value=$fila_seneca[0]>$fila_seneca[1]</option>";
														}
													
													?>
                                                </select>
                                                <label for="origen">Actividad Séneca</label>
                                                
                                    </div>
									</div>
                                                                                           
                                    </div>
									<div class="row">
									<div class="col-md-5">	
									</div>
									<div class="col-md-2">
										<div class="form-group form-md-line-input has-info">
											<div class="input-icon">
												<input type="text" class="form-control" name="horas" size=1 >
												<span class="help-block">Nº horas</span>
													<i class="fa fa-clock-o"></i>
											</div>
										</div>
									</div>
									</div>
									<div class="row">
									<div class="col-md-2">
										</div>
										<div class="col-md-8">
										<div class="form-group form-md-line-input has-info">
                                                <input type="text" class="form-control" id="observaciones" name="observaciones" placeholder="Observaciones" value="<?php echo $fila_asignaturas[16]; ?>">
                                                <label for="form_control_1">Observaciones</label>
                                          </div>
										 </div>
										  <div class="col-md-2">
										</div>
									</div>
																						
																																			
									
                                                                                            
                                                                             
																						
																					
                                     </div>
																					
																					<div class="modal-footer">
																							<input type="hidden" name="profesor" value="<?php echo $profesor; ?>">
																							<input type="hidden" name="nolectivas" value="1">
																							<button type="submit" class="btn red" name="guardar" value="Save">Guardar</button>
																							<button type="button" data-dismiss="modal" class="btn btn-outline dark">Cerrar</button>

																					</div>
																					
																				</div>
												</form>
                                                                            <!-- END FORM-->
                                                                        </div>
                                                                    </div>
																</div>

                                                            </div>

                                                        </div>
                                                    </div>

												

                                                    <!--FIN AÑADIR-->
                                                </div>
                                            </div>
                                            <!-- END EXAMPLE TABLE PORTLET-->
                                        </div>
									</div>
									</div>
									</div>
                                    </div>
                                </div>

                            </div>
							<!--- Fin modal -->						
						
						
								   
				<!--- Modal Añadir asignatura -->
						<div id="responsive" class="modal fade" tabindex="-1" data-width="760">
								<div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                   
                                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-18">
                                            <!-- BEGIN EXAMPLE TABLE PORTLET-->
                                            <div class="portlet light portlet-fit bordered">

                                                <div class="portlet-body">
                                                    <!-- INICIO AÑADIR -->
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="tabbable-line boxless tabbable-reversed">
                                                                <div class="tab-pane" id="tab_1">
                                                                    <div class="portlet box blue">
                                                                        <div class="portlet-title">
                                                                         
                                                                        </div>
                                                                        <div class="portlet-body form">
                                                                            <!-- BEGIN FORM-->
																			
																			<div class="form-body">
																				
																			<div class="form-group">
																				<form action="guardar_asignacion_profesor.php" class="horizontal-form" method="POST" id="form2">
																			<?php
											$busca_niveles="SELECT * FROM NIVELES_EDUCATIVOS WHERE ACTIVO=1 ORDER BY ORDEN+0";
											$result_niveles=mysql_query($busca_niveles,$conexion);
											while ($fila_niveles=mysql_fetch_row($result_niveles)){
												$busca_profe_nivel="SELECT ETAPA FROM ETAPAS_PROFESOR WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND NIF='$profesor' AND ETAPA=$fila_niveles[0]";
												$result_profe_nivel=mysql_query($busca_profe_nivel,$conexion);
												if (mysql_num_rows($result_profe_nivel)>0){
													
												
										?>
										
										<div class="portlet box <?php echo $fila_niveles[3]; ?>">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="fa fa-mortar-board"></i><?php echo $fila_niveles[1]; ?> </div>
												<div class="tools">
                                                    <a href="javascript:;" class="expand"> </a>
                                                </div>
                                            </div>
										<div class="portlet-body form"  style="display:none;">
											<br>
											
											 <div class="form-group">
                                                
                                                <div class="col-md-18">

													
													<?php
													
														$busca_cursos="SELECT CURSO, COD_SENECA FROM CURSOS WHERE NIVEL=$fila_niveles[0]";
															$result_cursos=mysql_query($busca_cursos,$conexion);
															while ($fila_cursos=mysql_fetch_row($result_cursos)){
																//Miro si el centro tiene el curso en cuestión 
																$muestra=0;
																$busca_curso="SELECT GRUPOS FROM GRUPOS_CENTRO WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND CURSO=$fila_cursos[1]";
																$result_curso=mysql_query($busca_curso,$conexion);
																$fila_curso=mysql_fetch_row($result_curso);
																if ($fila_curso[0]>0){
																	$muestra=1;
																}
																
																if ($muestra==1){
																
																echo "<table class='table table-hover table-light'>";
																echo "<th colspan=7>$fila_cursos[0]</th>";
																echo "<tr>";
																
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
																	$busca_letra="SELECT GRUPO FROM GRUPOS WHERE ID='$j'";
																	$result_letra=mysql_query($busca_letra,$conexion);
																	$fila_letra=mysql_fetch_row($result_letra);
																	echo "<td>Grupo $fila_letra[0]</td>";
																	$i=$i+1;
																}
																
																echo "</tr>";
																echo "<tr>";
																
																$result_grupos=mysql_query($busca_grupos,$conexion);
																$fila_grupos=mysql_fetch_row($result_grupos);
																$i=1;
																while ($i<=$fila_grupos[0]){
																	if ($fila_grupos[0]==1){
																		$j=0;
																	}else{
																		$j=$i;
																	}
																	
																$nombre_select=$fila_cursos[1]."-".$j."[]";	
																echo "<td>";
																echo "<select class='bs-select form-control' multiple data-show-subtext='true' data-selected-text-format='count' name='$nombre_select' id='elige_materias'>";
																$busca_asignatura2="SELECT COD_MATERIA FROM CURSOS_ASIGNATURAS WHERE COD_CURSO=$fila_cursos[1]";
																//echo $busca_asignatura2;
																$result_asignatura2=mysql_query($busca_asignatura2,$conexion);
																while ($fila_asignatura2=mysql_fetch_row($result_asignatura2)){
																	$busca_nombre_asignatura="SELECT MATERIA FROM MATERIAS_SENECA WHERE ID=$fila_asignatura2[0]";
																	$result_nombre_asignatura=mysql_query($busca_nombre_asignatura,$conexion);
																	$fila_nombre_asignatura=mysql_fetch_row($result_nombre_asignatura);
																	$disabled="";
																	//Busco si la asignatura está en el horario del centro y el número de horas que le corresponden
																	$busca_horario_centro="SELECT HORAS FROM HORARIO_CENTRO WHERE CENTRO='$_SESSION[centro]' AND PGE=$pge AND CURSO=$fila_cursos[1] AND MATERIA=$fila_asignatura2[0] AND GRUPO=$j";
																	$result_horario_centro=mysql_query($busca_horario_centro,$conexion);
																	if (mysql_num_rows($result_horario_centro)>0){
																		$fila_horario_centro=mysql_fetch_row($result_horario_centro);
																		$horas=$fila_horario_centro[0];
																		if ($fila_niveles[0]==5 OR $fila_niveles[0]==6){
																			$horas=round($horas/60,2);
																		}
																		
																		//Busco si la asignatura ya la tiene otro profesor y, en ese caso, aparece deshabilitada
																		$horas_consumidas=0;
																		$busca_horario="SELECT HORAS_CONSUMIDAS FROM HORARIO WHERE CENTRO='$_SESSION[centro]' AND PGE=$pge AND CURSO=$fila_cursos[1] AND GRUPO=$j AND CODIGO_MATERIA=$fila_asignatura2[0]";
																		$result_horario=mysql_query($busca_horario,$conexion);
																		$horas_consumidas;
																		while ($fila_horario=mysql_fetch_row($result_horario)){
																			$horas_consumidas=$horas_consumidas+$fila_horario[0];	
																		}
																		$horas_disponibles=$horas-$horas_consumidas;
																		
																		$etiqueta="<span class='label lable-sm label-danger'>$horas_disponibles</span>";
																		$valor=$fila_asignatura2[0];
																		
																		if ($horas_consumidas>=$horas AND $horas>0){
																			$disabled="disabled";
																			$horas="";
																			$etiqueta="";
																		}
																	?>
																	<option <?php echo $disabled; ?> data-content="<?php echo $fila_nombre_asignatura[0]; ?> <?php echo $etiqueta; ?>" value="<?php echo $valor; ?>"><?php echo $fila_nombre_asignatura[0]; ?></option>
																	<?php
																	}
																}
																echo "</select>";
																echo "</td>";
																$i=$i+1;															
																}
																echo "</tr>";
																
																echo "</table>";
																}
															}
													
											
											
                                                    ?>   

                                                </div>

												
                                            </div>

										<br>
										</div>
											
										</div>
									
									<?php
												}
											}
									?>	
																			</div>
																			
																			<div class="form-actions">
                                                                                    <div class="row">
                                                                                        <div class="col-md-offset-4 col-md-4">
																						<input type="hidden" name="curso" value="<?php echo $modal_curso; ?>">
                                                                                            
                                                                                            <!--<button type="submit" class="btn green" data-toggle="modal" href="#responsive"  data-dismiss="modal">Añadir</button>
                                                                                            -->
                                                                                        </div>
                                                                                    </div>
																					
																					<div class="modal-footer">
																							<input type="hidden" name="profesor" value="<?php echo $profesor; ?>">
																							<button type="submit" class="btn red" name="guardar" value="Save">Guardar</button>
																							<button type="button" data-dismiss="modal" class="btn btn-outline dark">Cerrar</button>

																					</div>
																					
																				</div>
												</form>
                                                                            <!-- END FORM-->
                                                                        </div>
                                                                    </div>
																</div>

                                                            </div>

                                                        </div>
                                                    </div>

												

                                                    <!--FIN AÑADIR-->
                                                </div>
                                            </div>
                                            <!-- END EXAMPLE TABLE PORTLET-->
                                        </div>
									</div>
									</div>
									</div>
									





                                    </div>
                                </div>

                            </div>
							<!--- Fin modal -->
								   
								   
								   
								   
					<div class="portlet box red">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-globe"></i>Horas asignadas para el profesor: <?php echo $nombre_completo; ?> </div>
                                    <div class="tools"> </div>
                                </div>
                                <div class="portlet-body">
								
                                    <div class="table-responsive">
                                         <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="sample_4">
                                            <thead>
                                                <tr>
                                                    <th> Origen </th>
													<th> Etapa </th>
                                                    <th> Curso </th>
                                                    <th> Materia </th>
                                                    <th> Grupo </th>
                                                    <th> Horas </th>
													<th> Acciones </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
													//Busco todas las apariciones del profesor en el horario
													$suma_horas=0;
													$busca_asignaturas="SELECT * FROM HORARIO WHERE PGE='$pge' AND NIF='$profesor' ORDER BY NIVEL_EDUCATIVO, CURSO, GRUPO";
													$result_asignaturas=mysql_query($busca_asignaturas,$conexion);
													while ($fila_asignaturas=mysql_fetch_row($result_asignaturas)){
														echo "<tr>";
														$busca_origen="SELECT ORIGEN FROM ORIGENES_HORAS WHERE ID=$fila_asignaturas[4]";
														$result_origen=mysql_query($busca_origen,$conexion);
														$fila_origen=mysql_fetch_row($result_origen);
														echo "<td>$fila_origen[0]</td>";
														$busca_etapa="SELECT NIVEL_EDUCATIVO FROM NIVELES_EDUCATIVOS WHERE ID=$fila_asignaturas[5]";
														$result_etapa=mysql_query($busca_etapa,$conexion);
														$fila_etapa=mysql_fetch_row($result_etapa);
														echo "<td>$fila_etapa[0]</td>";
														$busca_curso="SELECT CURSO FROM CURSOS WHERE COD_SENECA='$fila_asignaturas[10]'";
														$result_curso=mysql_query($busca_curso,$conexion);
														$fila_curso=mysql_fetch_row($result_curso);
														echo "<td>$fila_curso[0]</td>";
														$busca_materia="SELECT MATERIA FROM MATERIAS_SENECA WHERE ID=$fila_asignaturas[9]";
														$result_materia=mysql_query($busca_materia,$conexion);
														$fila_materia=mysql_fetch_row($result_materia);
														if ($fila_materia[0]==""){
															$busca_actividad_centro="SELECT * FROM ACTIVIDADES_CENTRO WHERE ID=$fila_asignaturas[8]";
															$result_actividad_centro=mysql_query($busca_actividad_centro,$conexion);
															$fila_actividad_centro=mysql_fetch_row($result_actividad_centro);
															echo "<td><p class='font-blue-madison'>$fila_actividad_centro[1]</p></td>";
														}else{
															echo "<td>$fila_materia[0]</td>";
														}
														
														$busca_grupo="SELECT GRUPO FROM GRUPOS WHERE ID=$fila_asignaturas[11]";
														$result_grupo=mysql_query($busca_grupo,$conexion);
														$fila_grupo=mysql_fetch_row($result_grupo);
														echo "<td>$fila_grupo[0]</td>";
														echo "<td>$fila_asignaturas[14]</td>";
														echo "<td>";

														if ($fila_origen[0]=="Concierto" OR $_SESSION[permiso]<=2){
														 
                                                        echo "<a class='btn blue' data-toggle='modal' href='#$fila_asignaturas[0]'>
                                                                                    <i class='fa fa-pencil'></i> Editar </a>
                                                                                <a href='borrar_asignatura_profesor.php?id=$fila_asignaturas[0]&profe=$profesor'><button class='btn red btn-large' data-toggle='confirmation' data-original-title='¿Estás seguro?' data-btn-ok-label='Sí' data-btn-cancel-label='No'
                                    title=''><i class='fa fa-trash-o'></i>Borrar</button></a>";	
														}

                                                                    echo "</td>";
														
														echo "</tr>";
														$suma_horas=$suma_horas+$fila_asignaturas[14];
														
?>														





				
				
				<!--- Modal Editar -->
						<div id="<?php echo $fila_asignaturas[0]; ?>" class="modal fade" tabindex="-1" data-width="760">
								<div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-18">
                                            <!-- BEGIN EXAMPLE TABLE PORTLET-->
                                            <div class="portlet light portlet-fit bordered">

                                                <div class="portlet-body">
                                                    <!-- INICIO AÑADIR -->
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="tabbable-line boxless tabbable-reversed">
                                                                <div class="tab-pane" id="tab_1">
                                                                    <div class="portlet box blue">
                                                                        <div class="portlet-title">
                                                                          <label><?php echo $fila_materia[0]."-".$nombre_completo; ?></label>
                                                                        </div>
                                                                        <div class="portlet-body form">
                                                                            <!-- BEGIN FORM-->
																			
																			<div class="form-body">
																				
																			<div class="form-group">
												<form action="guardar_asignacion_profesor.php" class="horizontal-form" method="POST" id="form3">
																				
																
																			</div>																
									<div class="form-actions">
                                    <div class="row">
																					
																					
									<div class="col-md-3">													
									<div class="form-group form-md-line-input has-info">
												<select class="form-control" id="origen" name="origen" onchange=''>
                                                    <option value=""></option>
													<?php
														$busca_origenes="SELECT * FROM ORIGENES_HORAS WHERE ACTIVO=1";
														$result_origenes=mysql_query($busca_origenes,$conexion);
														while ($fila_origenes=mysql_fetch_row($result_origenes)){
															$selected="";
															if ($fila_asignaturas[4]==$fila_origenes[0]){
																$selected="SELECTED";
															}
															echo "<option value=$fila_origenes[0] $selected>$fila_origenes[1]</option>";
														}
													
													?>
                                                </select>
                                                <label for="origen">Origen</label>
                                                
                                    </div>
									</div>
									
									<?php
										//Si la línea corresponde a un curso, lo muestro y, si no, muestro el desplegable de orígenes.
										$tipo_linea=1;
										if ($fila_asignaturas[9]>0){
											$tipo_linea=2;
									
									?>
									<div class="col-md-3">													
									<div class="form-group form-md-line-input has-info">													
									<select class="form-control" id="curso" name="curso">
                                                    <option value=""></option>
													<?php
														$curso_marcado=0;
														$busca_cursos="SELECT * FROM CURSOS WHERE NIVEL=$fila_asignaturas[5] ORDER BY CURSO";
														$result_cursos=mysql_query($busca_cursos,$conexion);
														while ($fila_cursos=mysql_fetch_row($result_cursos)){
															$selected="";
															if ($fila_cursos[2]==$fila_asignaturas[10]){
																$selected="SELECTED";
																$curso_marcado=1;
															}
															
															//Busca en horariocentro para ver si se muestra el curso o no
															$busca_curso="SELECT GRUPOS FROM GRUPOS_CENTRO WHERE CENTRO='$_SESSION[centro]' AND PGE=$pge AND CURSO=$fila_cursos[2]";
															$result_curso=mysql_query($busca_curso,$conexion);
															$fila_curso=mysql_fetch_row($result_curso);
															if ($fila_curso[0]>0){
																echo "<option value=$fila_cursos[2] $selected>$fila_cursos[0]</option>";
															}
														}
													
													?>
                                                </select>
												<label for="curso">Curso</label>
									</div>
									</div>
									<?php 
										}else{
									?>
									<div class="col-md-3">													
									<div class="form-group form-md-line-input has-info">													
									<select class="form-control" id="nivel" name="nivel">
                                                    <option value=""></option>
													<?php
														$busca_niveles="SELECT * FROM NIVELES_EDUCATIVOS WHERE ACTIVO=1 ORDER BY ORDEN";
														$result_niveles=mysql_query($busca_niveles,$conexion);
														while ($fila_niveles=mysql_fetch_row($result_niveles)){
															$selected="";
															if ($fila_niveles[0]==$fila_asignaturas[5]){
																$selected="SELECTED";
															}
															echo "<option value=$fila_niveles[0] $selected>$fila_niveles[1]</option>";
														}
													
													?>
                                                </select>
												<label for="nivel">Nivel</label>
									</div>
									</div>
									<?php
										}
									?>
									
									<div class="col-md-3">													
									<div class="form-group form-md-line-input has-info">
												<select class="form-control" id="actividad_centro" name="actividad_centro" onchange=''>
                                                    <option value=""></option>
													<?php
														$busca_actividades="SELECT * FROM ACTIVIDADES_CENTRO WHERE ACTIVO=1 ORDER BY ACTIVIDAD_CENTRO";
														$result_actividades=mysql_query($busca_actividades,$conexion);
														while ($fila_actividades=mysql_fetch_row($result_actividades)){
															$selected="";
															if ($fila_actividades[0]==$fila_asignaturas[8]){
																$selected="SELECTED";
															}
															echo "<option value=$fila_actividades[0] $selected>$fila_actividades[1]</option>";
														}
													
													?>
                                                </select>
                                                <label for="origen">Actividad Centro</label>
                                                
                                    </div>
									</div>
									
									<div class="col-md-3">													
									<div class="form-group form-md-line-input has-info">
												<select class="form-control" id="actividad_seneca" name="actividad_seneca" onchange=''>
                                                    <option value=""></option>
													<?php
														$busca_seneca="SELECT * FROM ACTIVIDADES_SENECA WHERE ACTIVO=1 ORDER BY ACTIVIDAD";
														$result_seneca=mysql_query($busca_seneca,$conexion);
														while ($fila_seneca=mysql_fetch_row($result_seneca)){
															$selected="";
															if ($fila_seneca[0]==$fila_asignaturas[7]){
																$selected="SELECTED";
															}
															echo "<option value=$fila_seneca[0] $selected>$fila_seneca[1]</option>";
														}
													
													?>
                                                </select>
                                                <label for="origen">Actividad Séneca</label>
                                                
                                    </div>
									</div>
                                                                                           
                                    </div>
									<div class="row">
									<div class="col-md-2">	
									</div>
									<?php
									if ($fila_asignaturas[9]>0){
									?>
									<div class="col-md-3">
										<div class="form-group form-md-line-input has-info">
											<select class="form-control" id="materia" name="materia">
													<?php

														$busca_curso="SELECT COD_SENECA FROM CURSOS WHERE NIVEL=$fila_asignaturas[5]";
														$result_curso=mysql_query($busca_curso,$conexion);
														$fila_curso=mysql_fetch_row($result_curso);
													
														$busca_grupos="SELECT GRUPOS FROM GRUPOS_CENTRO WHERE CURSO=$fila_curso[0]";
														
														$busca_materias="SELECT * FROM HORARIO_CENTRO WHERE CENTRO='$_SESSION[centro]' AND PGE=$pge AND CURSO=$fila_curso[0]";
														
														if ($curso_marcado==1){
															$busca_materias="SELECT * FROM HORARIO_CENTRO WHERE CENTRO='$_SESSION[centro]' AND PGE=$pge AND CURSO=$fila_asignaturas[10]";
														}
														$result_materias=mysql_query($busca_materias,$conexion);

														while ($fila_materias=mysql_fetch_row($result_materias)){
																$busca_nombre="SELECT MATERIA FROM MATERIAS_SENECA WHERE ID=$fila_materias[4]";
																$result_nombre=mysql_query($busca_nombre,$conexion);
																$fila_nombre=mysql_fetch_row($result_nombre);
																$selected="";
																if ($fila_materias[4]==$fila_asignaturas[9]){
																	$selected="SELECTED";
																}
																echo "<option value=$fila_materias[4] $selected>$fila_nombre[0]</option>";
														}
												?>
												   </select>
                                                <label for="materia">Materia</label>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group form-md-line-input has-info">
													<?php
													
														$busca_curso="SELECT COD_SENECA FROM CURSOS WHERE NIVEL=$fila_asignaturas[5]";
														$result_curso=mysql_query($busca_curso,$conexion);
														$fila_curso=mysql_fetch_row($result_curso);
													
														if ($curso_marcado==1){
															$busca_grupos="SELECT GRUPOS FROM GRUPOS_CENTRO WHERE CURSO=$fila_asignaturas[10] AND CENTRO='$_SESSION[centro]' AND PGE=$pge";
														}else{
															$busca_grupos="SELECT GRUPOS FROM GRUPOS_CENTRO WHERE CURSO=$fila_curso[0] AND CENTRO='$_SESSION[centro]' AND PGE=$pge";
														}
														//echo $busca_grupos;
														$result_grupos=mysql_query($busca_grupos,$conexion);
														$fila_grupos=mysql_fetch_row($result_grupos);
														$num_grupos=$fila_grupos[0];
														if ($num_grupos==""){
															$num_grupos=1;
														}
														if ($num_grupos==1){
															echo "<input type='text' class='form-control' name='grupo_u' readonly value='U' size=1 >";
															echo "<input type='hidden' class='form-control' name='grupo' readonly value='0' size=1 >";
														}else{
															?>
															<select class="form-control" id="grupos" name="grupo">
															<?php
															$i=1;
															while ($i<=$num_grupos){
																$busca_nombre="SELECT GRUPO FROM GRUPOS WHERE ID=$i";
																$result_nombre=mysql_query($busca_nombre,$conexion);
																$fila_nombre=mysql_fetch_row($result_nombre);
																$selected="";
																if ($i==$fila_asignaturas[11]){
																	$selected="SELECTED";
																}
																echo "<option value=$i $selected>$fila_nombre[0]</option>";
																$i=$i+1;
															}
														
													
													?>
                                                </select>
                                                <label for="origen">Grupo</label>
												<?php
														}
												?>
										</div>
									</div>
									<?php
										}
									$readonly="";	
									if ($tipo_linea==2){
										$readonly="";
									}
									?>
									<div class="col-md-2">
										<div class="form-group form-md-line-input has-info">
											<div class="input-icon">
												<input type="text" class="form-control" name="horas" size=1 <?php echo $readonly; ?> value="<?php echo $fila_asignaturas[14]; ?>">
												<span class="help-block">Nº horas</span>
													<i class="fa fa-clock-o"></i>
											</div>
										</div>
									</div>
									</div>
									<div class="row">
									<div class="col-md-2">
										</div>
										<div class="col-md-8">
										<div class="form-group form-md-line-input has-info">
                                                <input type="text" class="form-control" id="observaciones" name="observaciones" placeholder="Observaciones" value="<?php echo $fila_asignaturas[16]; ?>">
                                                <label for="form_control_1">Observaciones</label>
                                          </div>
										 </div>
										  <div class="col-md-2">
										</div>
									</div>

									</div>
																					
																					<div class="modal-footer">
																							<input type="hidden" name="profesor" value="<?php echo $profesor; ?>">
																							<input type="hidden" name="id" value="<?php echo $fila_asignaturas[0]; ?>">
																							<input type="hidden" name="editar" value="1">
																							<input type="hidden" name="tipo_linea" value="<?php echo $tipo_linea; ?>">
																							<button type="submit" class="btn red" name="guardar" value="Save">Guardar</button>
																							<button type="button" data-dismiss="modal" class="btn btn-outline dark">Cerrar</button>

																					</div>
																					
																				</div>
												</form>
                                                                            <!-- END FORM-->
                                                                        </div>
                                                                    </div>
																</div>

                                                            </div>

                                                        </div>
                                                    </div>

												

                                                    <!--FIN AÑADIR-->
                                                </div>
                                            </div>
                                            <!-- END EXAMPLE TABLE PORTLET-->
                                        </div>
									</div>
									</div>
									</div>
									





                                    </div>
                                </div>
					<!--- Fin modal -->
                            </div>
							
														
														

														
													<?php	
													}
												
												?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
					<br>
					<!--- Resumen de horas -->
					<div class="pricing-content-1">
					<div class="col-md-2">
					</div>
					<div class="col-md-4">
                                        <div class="price-column-container border-active">
                                            <div class="price-table-head bg-red">
                                                <h2 class="no-margin">Horas asignadas</h2>
                                            </div>
                                            <div class="arrow-down border-top-red"></div>
                                            <div class="price-table-pricing">
                                                <h3>
                                                    <?php echo $suma_horas; ?>/<strong><?php echo $horas_profe; ?></strong></h3>
                                                <p>horas</p>
                                            </div>
                                               <div class="price-table-content">
                                                <?php
													$busca_etapas="SELECT ID, NIVEL_EDUCATIVO FROM NIVELES_EDUCATIVOS WHERE ACTIVO=1 ORDER BY ORDEN";													
													$result_etapas=mysql_query($busca_etapas, $conexion);
													while ($fila_etapas=mysql_fetch_row($result_etapas)){
														$busca_origenes="SELECT * FROM ORIGENES_HORAS WHERE ACTIVO=1";
														$result_origenes=mysql_query($busca_origenes,$conexion);
														while ($fila_origenes=mysql_fetch_row($result_origenes)){
															$busca_horas_etapa="SELECT HORAS_CONSUMIDAS, ORIGEN FROM HORARIO WHERE PGE='$pge' AND NIF='$profesor' AND CENTRO='$_SESSION[centro]' AND NIVEL_EDUCATIVO=$fila_etapas[0] AND ORIGEN=$fila_origenes[0]";
															$result_horas_etapa=mysql_query($busca_horas_etapa,$conexion);
															$horas=0;
															while ($fila_horas_etapa=mysql_fetch_row($result_horas_etapa)){
															
																$horas=$horas+$fila_horas_etapa[0];
															}
															if ($horas>0){
												?>
												<div class="row mobile-padding">
                                                    <div class="col-xs-3 text-right mobile-padding">
                                                        <i class="icon-screen-smartphone"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding"><b><?php echo $fila_etapas[1]."-".$fila_origenes[1]; ?></b>: <?php echo $horas; ?></div>
                                                </div>
												<?php
														}
														}
														
													}
												?>
                                            <div class="arrow-down arrow-grey"></div>

                                        </div>
                                    </div>
					</div>
					
					
					<!--- Fin resumen horas -->
					
					
				<!--- Resumen de horas AÑO ANTERIOR-->
					<div class="pricing-content-1">
					<div class="col-md-4">
                                        <div class="price-column-container border-active">
                                            <div class="price-table-head bg-blue">
                                                <h2 class="no-margin">Horas curso anterior</h2>
                                            </div>
                                            <div class="arrow-down border-top-blue"></div>
                                            <div class="price-table-pricing">
                                                
                                            </div>
                                            <div class="price-table-content">
                                                <?php
													$busca_etapas="SELECT ID, NIVEL_EDUCATIVO FROM NIVELES_EDUCATIVOS WHERE ACTIVO=1 ORDER BY ORDEN";													
													$result_etapas=mysql_query($busca_etapas, $conexion);
													while ($fila_etapas=mysql_fetch_row($result_etapas)){
														$busca_origenes="SELECT * FROM ORIGENES_HORAS WHERE ACTIVO=1";
														$result_origenes=mysql_query($busca_origenes,$conexion);
														while ($fila_origenes=mysql_fetch_row($result_origenes)){
															$busca_horas_etapa="SELECT * FROM HORARIO WHERE PGE='$pge_anterior' AND NIF='$profesor' AND CENTRO='$_SESSION[centro]' AND NIVEL_EDUCATIVO=$fila_etapas[0] AND ORIGEN=$fila_origenes[0]";
															$result_horas_etapa=mysql_query($busca_horas_etapa,$conexion);
															$horas=0;
															
															while ($fila_horas_etapa=mysql_fetch_row($result_horas_etapa)){
																echo $fila_horas[9].$fila_horas[10].$fila_horas[11];
																$horas=$horas+$fila_horas_etapa[14];
															}
															
															if ($horas>0){
												?>
												<div class="row mobile-padding">
                                                    <div class="col-xs-3 text-right mobile-padding">
                                                        <i class="icon-screen-smartphone"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding"><b><?php echo $fila_etapas[1]."-".$fila_origenes[1]; ?></b>: <?php echo $horas; ?></div>
                                                </div>
												<?php
														}
														}
														
													}
												?>
                                            <div class="col-xs-9 text-left mobile-padding"><b><a target="blank" href="./sabana.php?profesor=<?php echo $profesor; ?>">Ver distribución completa</a></div>
											<div class="arrow-down arrow-grey"></div>
											
	
                                        </div>
                                    </div>
					</div>
					
					</div>
					<!--- Fin resumen horas año anterior-->
                    
					 <?php
					 }else{
						 echo "<div class='alert alert-danger'><strong>Nada que mostrar</strong> Selecciona un profesor de la lista</div>";
					 }
					 ?>
					
					
					
					
					
					
					
					
					
					
					
					
                    <!-- END PAGE CONTENT-->


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