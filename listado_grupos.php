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
                                <a href="informes.php">Informes</a>
                                <i class="fa fa-circle"></i>
                            </li>
							<li>
                                <span>Listado de grupos y materias</span>
                            </li>
                        </ul>
                        
                    </div>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"> Listado de grupos y materias
                    </h3>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
					<!-- BEGIN PAGE CONTENT-->

					

						<div class="tab-content">
										<div class="form-group form-md-line-input has-info">
                                                
												<form name="seleccionar_nivel" action='#' method='POST'>
												<select class="form-control" id="form_control_1" name="nivel" onchange='javascript:seleccionar_nivel.submit();'>
                                                    <option value=""></option>
												
													
<?php


$sql_nivel="SELECT ID, NIVEL_EDUCATIVO, ORDEN FROM NIVELES_EDUCATIVOS WHERE ACTIVO=1 ORDER BY ORDEN";
$result_nivel=mysql_query($sql_nivel,$conexion);
while ($fila_nivel=mysql_fetch_row($result_nivel)){
	$selected="";
	if ($fila_nivel[0]==$_POST[nivel]){
		$selected="SELECTED";
	}
	
										$muestra=0;
										$busca_cursos="SELECT COD_SENECA FROM CURSOS WHERE NIVEL=$fila_nivel[0]";
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
	
	echo "<option value=$fila_nivel[0] $selected>$fila_nivel[1]</option>";
										}
}											   
?>		
                                                </select>
												</form>
                                                <label for="form_control_1">Selecciona el nivel educativo</label>
                                            </div>	

	<div class="tab-content">
                                    <div class="tab-pane active" id="tab_0">
                                        <?php
											$busca_niveles="SELECT * FROM NIVELES_EDUCATIVOS WHERE ID=$_POST[nivel] ORDER BY ORDEN+0";
											$result_niveles=mysql_query($busca_niveles,$conexion);
											while ($fila_niveles=mysql_fetch_row($result_niveles)){
										
										
										?>
										
										<div class="portlet box <?php echo $fila_niveles[3]; ?>">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="fa fa-mortar-board"></i><?php echo $fila_niveles[1]; ?> 
												</div>
                                          
                                            </div>
                                            <div class="portlet-body form">
                                                <!-- BEGIN FORM-->
                                               <?php
													$busca_cursos="SELECT * FROM CURSOS WHERE NIVEL=$fila_niveles[0]";
													$result_cursos=mysql_query($busca_cursos,$conexion);
													while ($fila_cursos=mysql_fetch_row($result_cursos)){
														
														$busca_grupos="SELECT GRUPOS FROM GRUPOS_CENTRO WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND CURSO='$fila_cursos[2]'";
														//echo $busca_grupos;
														$result_grupos=mysql_query($busca_grupos,$conexion);
														if (mysql_num_rows($result_grupos)>0){
															$fila_grupos=mysql_fetch_row($result_grupos);
															$num_grupos=$fila_grupos[0];
														}else{
															$num_grupos=0;
														}
														
														$i=1;
														
													while ($i<=$num_grupos){
														
														if ($num_grupos==1){
															$j=0;
														}else{
															$j=$i;
														}
														

											   
											   ?>
											   

				
				            <div style="display:block; page-break-before:always;"></div> 
                           

                                
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
																		<?php 
																			$busca_nombre_grupo="SELECT GRUPO FROM GRUPOS WHERE ID=$j";
																			$result_nombre_grupo=mysql_query($busca_nombre_grupo,$conexion);
																			$fila_nombre_grupo=mysql_fetch_row($result_nombre_grupo);
																			$nombre_grupo=$fila_nombre_grupo[0];
																		?>
																	   
                                                                            <div class="caption">Asignaturas de <?php echo $fila_cursos[0]."-".$nombre_grupo; ?>
																			
                                                                            </div>
                                                                            <div class="tools">

                                                                            </div>
                                                                        </div>
                                                                        <div class="portlet-body form">
                                                        
																			<div class="form-body">
																				
																			<div class="form-group">
																				
																			<?php 
																$busca_asignaturas="SELECT MATERIA, HORAS FROM HORARIO_CENTRO WHERE CURSO=$fila_cursos[2] AND PGE=$pge AND CENTRO='$_SESSION[centro]' AND GRUPO=$j";
																//echo $busca_asignaturas;
																$result_asignaturas=mysql_query($busca_asignaturas,$conexion);
																echo "<table class='table table-striped table-bordered table-hover' id='sample_1'>";
																while ($fila_asignaturas=mysql_fetch_row($result_asignaturas)){
																	$busca_nombre_asignatura="SELECT * FROM MATERIAS_SENECA WHERE ID=$fila_asignaturas[0]";
																	$result_nombre_asignatura=mysql_query($busca_nombre_asignatura,$conexion);
																	$fila_nombre_asignatura=mysql_fetch_row($result_nombre_asignatura);
																	
																	//Busco las horas que tiene en el centro
																	
																	$horas=$fila_asignaturas[1];
																	if ($fila_niveles[0]==5 OR $fila_niveles[0]==6){
																		$horas=round($horas/60,2);
																	}
																	
																	
																	echo "<tr><td>".$fila_nombre_asignatura[1];
																	?>
																		</td><td align="right"><span class="label label-danger"> <?php echo $horas; horas ?> </span></td><td>
																														
<?php
	$busca_seleccion="SELECT HORAS_CONSUMIDAS, NIF FROM HORARIO WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND CURSO=$fila_cursos[2] AND GRUPO=$j AND CODIGO_MATERIA=$fila_asignaturas[0]";
	$result_seleccion=mysql_query($busca_seleccion,$conexion);
	while ($fila_seleccion=mysql_fetch_row($result_seleccion)){
		
if ($titular=='2'){
$sql_personal="SELECT PRIMERAPELLIDO, SEGUNDOAPELLIDO, NOMBRE, DNI, email FROM EMPLEADOS_LOYOLA WHERE DNI='$fila_seleccion[1]'";	
}else{

$sql_personal="SELECT DISTINCT EMPLEADOS.PRIMERAPELLIDO, EMPLEADOS.SEGUNDOAPELLIDO, EMPLEADOS.NOMBRE, EMPLEADOS.DNI FROM EMPLEADOS WHERE EMPLEADOS.DNI='$fila_seleccion[1]'";
}
$result_personal=mssql_query($sql_personal);
$fila_personal=mssql_fetch_array($result_personal);	

$nombre_completo="";
	
//Si no he encontrado el profe, busco entre los pendientes de contratarç
if (mssql_num_rows($result_personal)==0){
	$sql_personal="SELECT * FROM PROFES_PROVISIONALES WHERE ID=$fila_seleccion[1]";	
	$result_personal=mysql_query($sql_personal,$conexion);
	if (mysql_num_rows($result_personal)>0){
		$fila_personal=mysql_fetch_row($result_personal);
		$nombre_completo=$fila_personal[3].", ".$fila_personal[4];
	}
}else{
		$nombre_completo=$fila_personal[0]." ".$fila_personal[1].", ".$fila_personal[2];
	$nombre_completo=iconv("WINDOWS-1252", "UTF-8", $nombre_completo);
}


echo $nombre_completo." | ".$fila_seleccion[0]."<br>";
	}
										   
?>		

																		
																		
													
																		</td></tr>
																	<?php
																}
																echo "</table>";
															
															?>	
																				
																				
																				
																			</div>
																			

																				</div>
														
                                                                            
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
													
													
                                             
												

									<?php
													$i=$i+1;
													
												}
												echo "<hr>";
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