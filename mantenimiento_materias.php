	<?php
include "conexion.php";
include "header.php";

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
							<li>
                                <span>Mantenimiento de Materias</span>
                            </li>
                        </ul>
                        
                    </div>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"> Mantenimiento de Materias y Grupos
                    </h3>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
					<!-- BEGIN PAGE CONTENT-->

					<div class="tab-content">
                                    <div class="tab-pane active" id="tab_0">
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
										
										<div class="portlet box <?php echo $fila_niveles[3]; ?>">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="fa fa-mortar-board"></i><?php echo $fila_niveles[1]; ?> </div>
                                                <div class="tools">
                                                    <a href="javascript:;" class="expand"> </a>
                                                </div>
                                            </div>
											<?php
												if ($_GET[nivelguardado]==$fila_niveles[0]){
													$display="iline";
												}else{
													$display="none";
												}
											?>
                                            <div class="portlet-body form"  style="display:<?php echo $display; ?>;">
                                                <!-- BEGIN FORM-->
                                               <?php
													$busca_cursos="SELECT * FROM CURSOS WHERE NIVEL=$fila_niveles[0] ORDER BY CURSO";
													$result_cursos=mysql_query($busca_cursos,$conexion);
													while ($fila_cursos=mysql_fetch_row($result_cursos)){
														$muestra_curso=0;
														$busca_concierto="SELECT GRUPOS FROM UNIDADES_CONCERTADAS WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND CURSO=$fila_cursos[2]";
														$result_concierto=mysql_query($busca_concierto,$conexion);
														$fila_concierto=mysql_fetch_row($result_concierto);
														
														
														
														
														$modal_curso=$fila_cursos[2];
														
														//Sumo las horas que se han asignado al curso hasta el momento
														$horas_curso=0;
														$busca_horas_curso="SELECT HORAS FROM HORARIO_CENTRO WHERE CENTRO='$_SESSION[centro]' AND PGE=$pge AND CURSO=$modal_curso AND GRUPO=0";
														$result_horas_curso=mysql_query($busca_horas_curso,$conexion);
														while ($fila_horas_curso=mysql_fetch_row($result_horas_curso)){
															$horas_curso=$horas_curso+$fila_horas_curso[0];
														}
														
														if ($fila_niveles[0]==5 OR $fila_niveles[0]==6){
															$horas_curso=round($horas_curso/60,2);
														}
														
														$busca_grupos="SELECT GRUPOS FROM GRUPOS_CENTRO WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND CURSO=$modal_curso";
														$result_grupos=mysql_query($busca_grupos,$conexion);
														if (mysql_num_rows($result_grupos)>0){
															$fila_grupos=mysql_fetch_row($result_grupos);
															$grupos=$fila_grupos[0];
														}else{
															$grupos=0;
														}
													if ($fila_concierto[0]>0){	
											   
											   ?>
											   
                                                    <div class="form-body">
                                                       <a class="btn red btn-outline sbold" data-toggle="modal" href="#<?php echo $modal_curso; ?>"> <?php echo $fila_cursos[0]; ?> </a>&nbsp;&nbsp; 
													   <a data-toggle="modal" href="#<?php echo $modal_curso; ?>" class="icon-btn">
                                                            <i class="fa fa-calendar"></i>
                                                            <div> Horas </div>
                                                            <span class="badge badge-danger"> <?php echo round($horas_curso,2); ?> </span>
                                                        </a>
													   
													   <?php
														$i=1;
														
														while ($i<=$grupos){
															$modal_grupo=$modal_curso.$i;
															
															$horas_grupo=0;
															$busca_nombre_grupo="SELECT GRUPO FROM GRUPOS WHERE ID=$i";
															$result_nombre_grupo=mysql_query($busca_nombre_grupo,$conexion);
															$fila_nombre_grupo=mysql_fetch_row($result_nombre_grupo);
															$nombre_grupo=$fila_nombre_grupo[0];
															if ($grupos==1){
																$nombre_grupo="U";
																$j=0;
															}else{
																$j=$i;
															}
															
														//Sumo las horas que se han asignado al grupo hasta el momento
														
														$busca_horas_grupo="SELECT HORAS FROM HORARIO_CENTRO WHERE CENTRO='$_SESSION[centro]' AND PGE=$pge AND CURSO=$modal_curso AND GRUPO=$j";
														$result_horas_grupo=mysql_query($busca_horas_grupo,$conexion);
														while ($fila_horas_grupo=mysql_fetch_row($result_horas_grupo)){
															$horas_grupo=$horas_grupo+$fila_horas_grupo[0];
														}
														
														if ($fila_niveles[0]==5 OR $fila_niveles[0]==6){
															$horas_grupo=round($horas_grupo/60,2);
														}
														
															
															
														?>
													   <a data-toggle="modal" href="#<?php echo $modal_grupo; ?>"	 class="icon-btn">
                                                            <i class="fa fa-group"></i>
                                                            <div> Grupo <?php echo $nombre_grupo; ?></div>
                                                            <span class="badge badge-danger"> <? echo round($horas_grupo,2); ?> </span>
                                                        </a>
														<?php
														$i=$i+1;
														}
														?>
                                                    </div>
													<?php 
													
													}
?>
													
				<!--Modal del curso-->
				             
                            <div id="<?php echo $modal_curso; ?>" class="modal fade" tabindex="-1" data-width="760">
								<div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                   
                                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
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
                                                                            <div class="caption">Asignaturas de <?php echo $fila_cursos[0]; ?>
																			
                                                                            </div>
                                                                            <div class="tools">

                                                                            </div>
                                                                        </div>
                                                                        <div class="portlet-body form">
                                                                            <!-- BEGIN FORM-->
														<form action="guardar_horario_centro.php" class="horizontal-form" method="POST" id="formulario">
																			<div class="form-body">
																				
																			<div class="form-group">
																				
																			<?php 
																$busca_asignaturas="SELECT COD_MATERIA FROM CURSOS_ASIGNATURAS WHERE COD_CURSO=$fila_cursos[2]";
																$result_asignaturas=mysql_query($busca_asignaturas,$conexion);
																echo "<table class='table table-striped table-bordered table-hover order-column' id='sample_1' >";
																while ($fila_asignaturas=mysql_fetch_row($result_asignaturas)){
																	$busca_nombre_asignatura="SELECT * FROM MATERIAS_SENECA WHERE ID=$fila_asignaturas[0]";
																	$result_nombre_asignatura=mysql_query($busca_nombre_asignatura,$conexion);
																	$fila_nombre_asignatura=mysql_fetch_row($result_nombre_asignatura);
																	
																	//Miro si existe en horario_centro para que aparezca aquí seleccionada o no 
																	$checked="";
																	$horas=0;
																	$sesiones=0;
																	$observaciones="";
																	$busca_horario_centro="SELECT HORAS, SESIONES, OBSERVACIONES FROM HORARIO_CENTRO WHERE CENTRO='$_SESSION[centro]' AND PGE=$pge AND CURSO=$modal_curso AND MATERIA=$fila_asignaturas[0] AND GRUPO=0";
																	$result_horario_centro=mysql_query($busca_horario_centro,$conexion);
																	
																	if (mysql_num_rows($result_horario_centro)>0){
																		$checked="CHECKED";
																		$fila_horario_centro=mysql_fetch_row($result_horario_centro);
																		$horas=$fila_horario_centro[0];
																		$sesiones=$fila_horario_centro[1];
																		$observaciones=$fila_horario_centro[2];
																	}
																	
																	
																	echo "<tr><td>".$fila_nombre_asignatura[1];
																	?>
																		</td><td>
																		<input type="checkbox" <?php echo $checked; ?> name="<?php echo $fila_asignaturas[0]; ?>" class="make-switch" data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>">
																		</td>
																		
																		<?php 
																			if ($fila_niveles[0]==5 OR $fila_niveles[0]==6){
																				//Aparte de mostrar la columna del número de sesiones, también pongo la etiquetas de horas en minutos
																				$texto_horas="Minutos";
																		?>
																		<td>
																		<div class="form-group form-md-line-input has-success">
																			<div class="input-icon">
																				<input type="text" class="form-control" name="<?php echo "sesiones".$fila_asignaturas[0]; ?>" size=1 value="<?php echo $sesiones; ?>">
																				<span class="help-block">Sesiones</span>
																				<i class="fa fa-calendar"></i>
																			</div>
																		</div>
																		</td>
																		<?php
																			}else{
																				$texto_horas="Horas";
																			}
																		?>
																		<td>
																		<div class="form-group form-md-line-input has-success">
																			<div class="input-icon">
																				<input type="text" class="form-control" name="<?php echo "horas".$fila_asignaturas[0]; ?>" size=5 value="<?php echo $horas; ?>">
																				<span class="help-block"><?php echo $texto_horas; ?></span>
																				<i class="fa fa-clock-o"></i>
																			</div>
																		</div>
																		</td>
																		<td>
																		<div class="form-group form-md-line-input has-info">
																			<input type="text" class="form-control" id="observaciones" name="<?php echo "observaciones".$fila_asignaturas[0]; ?>" value="<?php echo $observaciones; ?>">
																			<label for="form_control_1">Observaciones</label>
																		</div>
																		</td>
																		</tr>
																	<?php
																}
																echo "</table>";
															
															?>	
																		<div class="form-group form-md-line-input has-info">
																			<div class="input-icon">
																				<input type="text" class="form-control" name="grupos" size=3 value="<?php echo $grupos; ?>" id="form_control_grupos">
																				<span class="help-block">Grupos</span>
																				<label for="form_control_grupos">Nº de grupos</label>
																				<i class="fa fa-group "></i>
																			</div>
																		</div>		
																				
																				
																			</div>
																			
																			
																			
																			
																			<div class="form-actions">
                                                                                    
																					
																					<div class="modal-footer">
																							<input type="hidden" name="curso" value="<?php echo $modal_curso; ?>">
																							<input type="hidden" name="nivel" value="<?php echo $fila_niveles[0]; ?>">
																							<input type="hidden" name="grupo" value="0">
																							<?php if ($grupos>1){ echo "<a href='traspasa_materias.php?curso=$modal_curso' class='btn btn-lg red'> Traspasar
																														<i class='fa fa-hand-o-right'></i>
																														</a>";} ?>
																							<button type="submit" class="btn blue" name="guardar" value="Save">Guardar</button>
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
													
							<!-- Fin modal curso -->						
                                             
												
							<!--Modal de los grupos-->
				             
							  <?php
									$i=1;
									while ($i<=$grupos){
										$modal_grupo=$modal_curso.$i;
										$busca_nombre_grupo="SELECT GRUPO FROM GRUPOS WHERE ID=$i";
										$result_nombre_grupo=mysql_query($busca_nombre_grupo,$conexion);
										$fila_nombre_grupo=mysql_fetch_row($result_nombre_grupo);
										$nombre_grupo=$fila_nombre_grupo[0];
										if ($grupos==1){
											$nombre_grupo="U";
											$j=0;
										}else{
											$j=$i;
										}
							 
							 ?>
                            <div id="<?php echo $modal_grupo; ?>" class="modal fade" tabindex="-1" data-width="760">
								<div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                   
                                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
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
                                                                            <div class="caption">Asignaturas de <?php echo $fila_cursos[0]." - Grupo".$nombre_grupo; ?>
																			
                                                                            </div>
                                                                            <div class="tools">

                                                                            </div>
                                                                        </div>
                                                                        <div class="portlet-body form">
                                                                            <!-- BEGIN FORM-->
														<form action="guardar_horario_centro.php" class="horizontal-form" method="POST" id="formulario">
																			<div class="form-body">
																				
																			<div class="form-group">
																				
																			<?php 
																$busca_asignaturas="SELECT COD_MATERIA FROM CURSOS_ASIGNATURAS WHERE COD_CURSO=$fila_cursos[2]";
																$result_asignaturas=mysql_query($busca_asignaturas,$conexion);
																echo "<table align='center' style='border-collapse: separate; border-spacing: 10px;'>";
																while ($fila_asignaturas=mysql_fetch_row($result_asignaturas)){
																	$busca_nombre_asignatura="SELECT * FROM MATERIAS_SENECA WHERE ID=$fila_asignaturas[0]";
																	$result_nombre_asignatura=mysql_query($busca_nombre_asignatura,$conexion);
																	$fila_nombre_asignatura=mysql_fetch_row($result_nombre_asignatura);
																	
																	//Miro si existe en horario_centro para que aparezca aquí seleccionada o no 
																	$checked="";
																	$horas=0;
																	$sesiones=0;
																	$observaciones="";
																	$busca_horario_centro="SELECT HORAS, SESIONES, OBSERVACIONES FROM HORARIO_CENTRO WHERE CENTRO='$_SESSION[centro]' AND PGE=$pge AND CURSO=$modal_curso AND MATERIA=$fila_asignaturas[0] AND GRUPO=$j";
																	$result_horario_centro=mysql_query($busca_horario_centro,$conexion);
																	
																	if (mysql_num_rows($result_horario_centro)>0){
																		$checked="CHECKED";
																		$fila_horario_centro=mysql_fetch_row($result_horario_centro);
																		$horas=$fila_horario_centro[0];
																		$sesiones=$fila_horario_centro[1];
																		$observaciones=$fila_horario_centro[2];
																	}
																	
																	
																	echo "<tr><td>".$fila_nombre_asignatura[1];
																	?>
																		</td><td>
																		<input type="checkbox" <?php echo $checked; ?> name="<?php echo $fila_asignaturas[0]; ?>" class="make-switch" data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>">
																		</td>
																		
																		<?php 
																			if ($fila_niveles[0]==5 OR $fila_niveles[0]==6){
																				//Aparte de mostrar la columna del número de sesiones, también pongo la etiquetas de horas en minutos
																				$texto_horas="Minutos";
																		?>
																		<td>
																		<div class="form-group form-md-line-input has-success">
																			<div class="input-icon">
																				<input type="text" class="form-control" name="<?php echo "sesiones".$fila_asignaturas[0]; ?>" size=1 value="<?php echo $sesiones; ?>">
																				<span class="help-block">Sesiones</span>
																				<i class="fa fa-calendar"></i>
																			</div>
																		</div>
																		</td>
																		<?php
																			}else{
																				$texto_horas="Horas";
																			}
																		?>
																		<td>
																		<div class="form-group form-md-line-input has-success">
																			<div class="input-icon">
																				<input type="text" class="form-control" name="<?php echo "horas".$fila_asignaturas[0]; ?>" size=5 value="<?php echo $horas; ?>">
																				<span class="help-block"><?php echo $texto_horas; ?></span>
																				<i class="fa fa-clock-o"></i>
																			</div>
																		</div>
																		</td>
																		<td>
																		<div class="form-group form-md-line-input has-info">
																			<input type="text" class="form-control" id="observaciones" name="<?php echo "observaciones".$fila_asignaturas[0]; ?>" value="<?php echo $observaciones; ?>">
																			<label for="form_control_1">Observaciones</label>
																		</div>
																		</td>
																		</tr>
																	<?php
																}
																echo "</table>";
															
															?>	
																				
																			</div>
																			
																			
																			
																			
																			<div class="form-actions">
                                                                                    
																					
																					<div class="modal-footer">
																							<input type="hidden" name="curso" value="<?php echo $modal_curso; ?>">
																							<input type="hidden" name="nivel" value="<?php echo $fila_niveles[0]; ?>">
																							<input type="hidden" name="grupo" value="<?php echo $j; ?>">
																							<button type="submit" class="btn blue" name="guardar" value="Save">Guardar</button>
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
							<?php
								$i=$i+1;
									}
							?>
							<!-- Fin modal grupos -->					
												
												
												

									<?php
											}
									
									?>
												
                                                <!-- END FORM-->
                                            </div>
                                        </div>
										<?php
											}
											}
										?>
								</div>
						</div>
                    
					 
					
					
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