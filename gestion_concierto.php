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
                                <span>Gestión de unidades concertadas</span>
                            </li>
                        </ul>
                        
                    </div>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"> Gestión de unidades concertadas
                    </h3>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
					<!-- BEGIN PAGE CONTENT-->

					<?php 
					
						if ($_SESSION[permiso]<2){
					?>
					
					<div class="tab-content">
                                    <div class="tab-pane active" id="tab_0">
                                        <?php
											$busca_niveles="SELECT * FROM NIVELES_EDUCATIVOS WHERE ACTIVO=1 ORDER BY ORDEN+0";
											$result_niveles=mysql_query($busca_niveles,$conexion);
											while ($fila_niveles=mysql_fetch_row($result_niveles)){
										
										
										
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
														$modal_curso=$fila_cursos[2];
														
														
														
														$busca_grupos="SELECT GRUPOS FROM UNIDADES_CONCERTADAS WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND CURSO=$modal_curso";
														$result_grupos=mysql_query($busca_grupos,$conexion);
														if (mysql_num_rows($result_grupos)>0){
															$fila_grupos=mysql_fetch_row($result_grupos);
															$grupos=$fila_grupos[0];
														}else{
															$grupos=0;
														}
														
											   
											   ?>
											   
                                                    <div class="form-body">
                                                       <a class="btn red btn-outline sbold" data-toggle="modal" href="#<?php echo $modal_curso; ?>"> <?php echo $fila_cursos[0]; ?> </a>&nbsp;&nbsp; 
													   
													   
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
                                                                            <div class="caption">Grupos de <?php echo $fila_cursos[0]; ?>
																			
                                                                            </div>
                                                                            <div class="tools">

                                                                            </div>
                                                                        </div>
                                                                        <div class="portlet-body form">
                                                                            <!-- BEGIN FORM-->
														<form action="guardar_concierto_centro.php" class="horizontal-form" method="POST" id="formulario">
																			<div class="form-body">
																				
																			<div class="form-group">
																				
																			
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
										}
							 
							 ?>
												
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
										?>
								</div>
						</div>
						
					<?PHP
					 }else{
						 echo "<div class='alert alert-danger'><strong>Sin acceso</strong> Tu rol actual no tiene permisos para acceder a esta página</div>";
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