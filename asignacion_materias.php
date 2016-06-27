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
                                <span>Asignación por grupos y materias</span>
                            </li>
                        </ul>
                        
                    </div>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"> Asignación por grupos y materias
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
<?php

if ($_POST[nivel]!=""){
?>
						<div class="tab-content">
										<div class="form-group form-md-line-input has-info">
                                                
												<form name="seleccionar_curso" action='#' method='POST'>
												<select class="form-control" id="form_control_1" name="curso" onchange='javascript:seleccionar_curso.submit();'>
                                                    <option value=""></option>
	
<?php	
$sql_curso="SELECT CURSO, COD_SENECA FROM CURSOS WHERE NIVEL=$_POST[nivel] ORDER BY CURSO";
$result_curso=mysql_query($sql_curso,$conexion);
while ($fila_curso=mysql_fetch_row($result_curso)){
	$selected="";
	if ($fila_curso[1]==$_POST[curso]){
		$selected="SELECTED";
	}
	
	$busca_curso2="SELECT GRUPOS FROM GRUPOS_CENTRO WHERE CENTRO='$_SESSION[centro]' AND PGE=$pge AND CURSO=$fila_curso[1]";
	$result_curso2=mysql_query($busca_curso2,$conexion);
	$fila_curso2=mysql_fetch_row($result_curso2);
	if ($fila_curso2[0]>0){
		echo "<option value=$fila_curso[1] $selected>$fila_curso[0]</option>";
	}
										
}											   
?>		
                                                </select>
												<input type="hidden" name="nivel" value="<?php echo $_POST[nivel]; ?>">
												</form>
                                                <label for="form_control_1">Selecciona el curso</label>
                                            </div>	

<?php	
}


if ($_POST[curso]!=""){
	
//Hago la consulta del profesorado aquí y la guardo en un result, para no tener que hacerla cada vez que accedía a una asignatura (ralentizaba)
if ($titular=='2'){
$sql_personal="SELECT PRIMERAPELLIDO, SEGUNDOAPELLIDO, NOMBRE, DNI, email FROM EMPLEADOS_LOYOLA WHERE nombrecentro='$_SESSION[centro]'";	
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

while( $row = mssql_fetch_array( $result_personal)){
    $busca_nivel="SELECT ETAPA FROM ETAPAS_PROFESOR WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND NIF='$row[3]' AND ETAPA=$_POST[nivel]";
	$result_nivel=mysql_query($busca_nivel,$conexion);
	if (mysql_num_rows($result_nivel)>0){
		$new_array[] = $row; // Inside while loop
	}
}

	
	
?>	
					
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
													$busca_cursos="SELECT * FROM CURSOS WHERE COD_SENECA=$_POST[curso]";
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
														
														$modal_curso=$fila_cursos[2].$j;
														
														$busca_grupo="SELECT GRUPO FROM GRUPOS WHERE ID=$j";
														$result_grupo=mysql_query($busca_grupo,$conexion);
														$fila_grupo=mysql_fetch_row($result_grupo);
														$nombre_grupo=$fila_grupo[0];
													
													$horas_grupo=0;
													$busca_horas_grupo="SELECT HORAS_CONSUMIDAS FROM HORARIO WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND GRUPO=$j AND CURSO=$fila_cursos[2]";
													//echo $busca_horas_grupo;
													$result_horas_grupo=mysql_query($busca_horas_grupo,$conexion);
													while ($fila_horas_grupo=mysql_fetch_row($result_horas_grupo)){
														$horas_grupo=$horas_grupo+$fila_horas_grupo[0];
													}
													

											   
											   ?>
											   
                                                    <div class="form-body">
                                                       <a class="btn red btn-outline sbold" data-toggle="modal" href="#<?php echo $modal_curso; ?>"> <?php echo $fila_cursos[0]."-".$nombre_grupo; ?> </a>&nbsp;&nbsp; 
													   <a href="#<?php echo $modal_curso; ?>" data-toggle="modal" class="icon-btn">
                                                            <i class="fa fa-calendar"></i>
                                                            <div> Horas </div>
                                                            <span class="badge badge-danger"> <?php echo round($horas_grupo,2); ?> </span>
                                                        </a>

                                                    </div>

													
				<!--Modal-->
				             
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
                                                                            <div class="caption">Asignaturas de <?php echo $fila_cursos[0]."-".$nombre_grupo; ?>
																			
                                                                            </div>
                                                                            <div class="tools">

                                                                            </div>
                                                                        </div>
                                                                        <div class="portlet-body form">
                                                        <!-- BEGIN FORM-->
														<form action="guardar_asignacion_materias.php" class="horizontal-form" method="POST" id="formulario">
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
																		

												<div class="form-group form-md-line-input has-info">
                                                
												
												<select class="form-control" id="form_control_1" name="<?php echo $fila_asignaturas[0]; ?>" >
                                                    <option value=""></option>
													
<?php

	
$p=0;
foreach($new_array as $array){

	
	//Busco las horas que tiene por contrato el profesor
	$busca_horas_profe="SELECT HORAS, EVENTUALES FROM HORAS_PROFESOR WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND DNI='$array[3]'";
	$result_horas_profe=mysql_query($busca_horas_profe,$conexion);
	if (mysql_num_rows($result_horas_profe)>0){
		$fila_horas_profe=mysql_fetch_row($result_horas_profe);
		$horas_profe=$fila_horas_profe[0]+$fila_horas_profe[1];
	}else{
		$horas_profe=0;
	}
	

													$suma_horas=0;
													$busca_asignaturas2="SELECT HORAS_CONSUMIDAS FROM HORARIO WHERE PGE='$pge' AND NIF='$array[3]'";
													$result_asignaturas2=mysql_query($busca_asignaturas2,$conexion);
													while ($fila_asignaturas2=mysql_fetch_row($result_asignaturas2)){
															$suma_horas=$suma_horas+$fila_asignaturas2[0];
													}

	
	$horas_libres=$horas_profe-$suma_horas;
	$selected="";
	$busca_seleccion="SELECT HORAS_CONSUMIDAS FROM HORARIO WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND NIF='$array[3]' AND CURSO=$fila_cursos[2] AND GRUPO=$j AND CODIGO_MATERIA=$fila_asignaturas[0]";
	$result_seleccion=mysql_query($busca_seleccion,$conexion);
	if (mysql_num_rows($result_seleccion)>0){
		$selected="SELECTED";
	}
	

	
	$etiqueta="<span class='label lable-sm label-danger'>$horas_libres</span>";

	$nombre_completo=$array[0]." ".$array[1].", ".$array[2];
	$nombre_completo=iconv("WINDOWS-1252", "UTF-8", $nombre_completo);
	echo "<option $selected value=$array[3] $selected>$nombre_completo | $horas_libres</option>";

	$p=$p+1;
}	

//Muestros los profes pendientes de contratación

$sql_personal="SELECT * FROM PROFES_PROVISIONALES WHERE CENTRO='$_SESSION[centro]' AND PGE=$pge";
$result_personal=mysql_query($sql_personal, $conexion);
while ($fila_personal=mysql_fetch_row($result_personal)){

	
	//Busco las horas que tiene por contrato el profesor
	$busca_horas_profe="SELECT HORAS, EVENTUALES FROM HORAS_PROFESOR WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND DNI='$fila_personal[0]'";
	$result_horas_profe=mysql_query($busca_horas_profe,$conexion);
	if (mysql_num_rows($result_horas_profe)>0){
		$fila_horas_profe=mysql_fetch_row($result_horas_profe);
		$horas_profe=$fila_horas_profe[0]+$fila_horas_profe[1];
	}else{
		$horas_profe=0;
	}
	

													$suma_horas=0;
													$busca_asignaturas2="SELECT HORAS_CONSUMIDAS FROM HORARIO WHERE PGE='$pge' AND NIF='$fila_personal[0]'";
													$result_asignaturas2=mysql_query($busca_asignaturas2,$conexion);
													while ($fila_asignaturas2=mysql_fetch_row($result_asignaturas2)){
															$suma_horas=$suma_horas+$fila_asignaturas2[0];
													}

	
	$horas_libres=$horas_profe-$suma_horas;
	$selected="";
	$busca_seleccion="SELECT HORAS_CONSUMIDAS FROM HORARIO WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND NIF='$fila_personal[0]' AND CURSO=$fila_cursos[2] AND GRUPO=$j AND CODIGO_MATERIA=$fila_asignaturas[0]";
	$result_seleccion=mysql_query($busca_seleccion,$conexion);
	if (mysql_num_rows($result_seleccion)>0){
		$selected="SELECTED";
	}
	

	
	$etiqueta="<span class='label lable-sm label-danger'>$horas_libres</span>";

	$nombre_completo=$fila_personal[3];
	echo "<option $selected value=$fila_personal[0] $selected>$nombre_completo | $horas_libres</option>";

	$p=$p+1;
}
										   
?>		
                                                </select>
												
                                                <label for="form_control_1">Selecciona el profesor</label>
                                            </div>	
																		
																		
													
																		</td></tr>
																	<?php
																}
																echo "</table>";
															
															?>	
																				
																				
																				
																			</div>
																			
																			
																			
																			
																			<div class="form-actions">
                                                                                    
																					
																					<div class="modal-footer">
																							<input type="hidden" name="curso" value="<?php echo $fila_cursos[2]; ?>">
																							<input type="hidden" name="grupo" value="<?php echo $j; ?>">
																							<div class="alert alert-info">
                                        <strong>Atención!</strong> Si tienes asignaturas compartidas por varios profesores, perderás esa división. La asignación quedará tal y como se muestra en esta pantalla. </div>
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
                    
			
<?php 

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