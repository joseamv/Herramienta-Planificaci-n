<?php
include "conexion.php";
include "header.php";


?>
<div class="page-container">
<?php
	include "sidebar.php";

if ($_POST[profe_provisional]=="1"){
	$inserta_profe="INSERT INTO PROFES_PROVISIONALES (PGE, CENTRO, NOMBRE, OBSERVACIONES) VALUES ($pge, '$_SESSION[centro]', '$_POST[nombre_profe_nuevo]', '$_POST[observaciones_profe_nuevo]')";
	mysql_query($inserta_profe,$conexion);
}
	
	
if ($_POST[guardar]=="1"){
	if ($_POST[etapas]=="1"){
		$busca_niveles="SELECT * FROM NIVELES_EDUCATIVOS WHERE ACTIVO=1 ORDER BY ORDEN";
		$result_niveles=mysql_query($busca_niveles,$conexion);
		while ($fila_niveles=mysql_fetch_row($result_niveles)){
			
			$busca_checked="SELECT ETAPA FROM ETAPAS_PROFESOR WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND NIF='$_POST[profesor]' AND ETAPA=$fila_niveles[0]";
			$result_checked=mysql_query($busca_checked,$conexion);
			if (mysql_num_rows($result_checked)==0){
				//Miro si viene marcado el checkbox correspondiente o no
				if ($_POST[$fila_niveles[0]]=='on'){
					$inserta_check="INSERT INTO ETAPAS_PROFESOR (PGE, CENTRO, NIF, ETAPA) VALUES ($pge, '$_SESSION[centro]', '$_POST[profesor]', $fila_niveles[0])";
					//echo $inserta_check;
					mysql_query($inserta_check,$conexion);
				}
			}else{
				if ($_POST[$fila_niveles[0]]!='on'){
					$delete_check="DELETE FROM ETAPAS_PROFESOR WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND NIF='$_POST[profesor]' AND ETAPA=$fila_niveles[0]";
					mysql_query($delete_check,$conexion);
				}
			}
		
		}
		//Actualizo horas fijas y eventuales
		$busca_horas="SELECT HORAS FROM HORAS_PROFESOR WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND DNI='$_POST[profesor]'";
		//echo $busca_horas;
		$result_horas=mysql_query($busca_horas,$conexion);
		if (mysql_num_rows($result_horas)>0){
			$update_horas="UPDATE HORAS_PROFESOR SET HORAS=$_POST[horas], EVENTUALES=$_POST[eventuales] WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND DNI='$_POST[profesor]'";
			//echo $update_horas;
			mysql_query($update_horas,$conexion);
		}else{
			$inserta_horas="INSERT INTO HORAS_PROFESOR (PGE, CENTRO, DNI, HORAS, EVENTUALES) VALUES ($pge, '$_SESSION[centro]', '$_POST[profesor]', $_POST[horas], $_POST[eventuales])";
			//echo $inserta_horas;
			mysql_query($inserta_horas,$conexion);
		}
		
		//Actualizo observaciones
		$busca_observaciones="SELECT OBSERVACIONES FROM OBSERVACIONES_PROFESOR WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND NIF='$_POST[profesor]'";
		//echo $busca_observaciones;
		$result_observaciones=mysql_query($busca_observaciones,$conexion);
		if (mysql_num_rows($result_observaciones)>0){
			$update_observaciones="UPDATE OBSERVACIONES_PROFESOR SET OBSERVACIONES='$_POST[observaciones]' WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND NIF='$_POST[profesor]'";
			//echo $update_observaciones;
			mysql_query($update_observaciones,$conexion);
		}else{
			$inserta_observaciones="INSERT INTO OBSERVACIONES_PROFESOR (PGE, CENTRO, NIF, OBSERVACIONES) VALUES ($pge, '$_SESSION[centro]', '$_POST[profesor]', '$_POST[observaciones]')";
			//echo $inserta_observaciones;
			mysql_query($inserta_observaciones,$conexion);
		}
		
	}else{
	
	$busca_horas="SELECT HORAS FROM HORAS_PROFESOR WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND DNI='$_POST[profesor]'";
	//echo $busca_horas;
	$result_horas=mysql_query($busca_horas,$conexion);
	if (mysql_num_rows($result_horas)>0){
		$update_horas="UPDATE HORAS_PROFESOR SET HORAS=$_POST[horas], EVENTUALES=$_POST[eventuales] WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND DNI='$_POST[profesor]'";
		mysql_query($update_horas,$conexion);
	}else{
		$inserta_horas="INSERT INTO HORAS_PROFESOR (PGE, CENTRO, DNI, HORAS, EVENTUALES) VALUES ($pge, '$_SESSION[centro]', '$_POST[profesor]', $_POST[horas], $_POST[eventuales])";
		//echo $inserta_horas;
		mysql_query($inserta_horas,$conexion);
	}
	}
}	
	
	
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
                                <span>Mantenimiento de Profesorado</span>
                            </li>
                        </ul>
                        
                    </div>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"> Mantenimiento de Profesorado
                    </h3>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
					<!-- BEGIN PAGE CONTENT-->

					<div class="tab-content">

					<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
									<a class="btn red btn-outline sbold" data-toggle="modal" href="#nuevo_profe"> Añadir profesor </a>
						</div></div>
					
					
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption font-dark">
                                        <i class="icon-settings font-dark"></i>
                                        <span class="caption-subject bold uppercase">Profesores</span>
                                    </div>
                                    <div class="tools"> </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-striped table-bordered table-hover" id="sample_1">
                                        <thead>
                                            <tr>
                                                
                                                <th> Apellidos </th>
                                                <th> Nombre </th>
												<th> NIF </th>
                                                <th> Email </th>
												<th> Etapas </th>
                                                <th> Horas </th>
												<th> Editar </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
											<?php
											
											if ($titular=='2'){
$sql_personal="SELECT PRIMERAPELLIDO, SEGUNDOAPELLIDO, NOMBRE, DNI, email FROM EMPLEADOS_LOYOLA WHERE nombrecentro='$_SESSION[centro]'";	
}else{
if ($_SESSION[centro]=='11701279'){
	$centro='11003001';
}else{
	$centro=$_SESSION[centro];
}	
	
						$sql_personal="SELECT DISTINCT EMPLEADOS.PRIMERAPELLIDO, EMPLEADOS.SEGUNDOAPELLIDO, EMPLEADOS.NOMBRE, EMPLEADOS.DNI, Userfundacionsafa.DNI, Userfundacionsafa.email, CENTROS.codigojunta, EMPLEmovimientos.fechabaja, EMPLEmovimientos.catprofesional FROM EMPLEmovimientos INNER JOIN
                         EMPLEADOS ON EMPLEmovimientos.reftrabaja = EMPLEADOS.DNI INNER JOIN
                         Userfundacionsafa ON EMPLEADOS.DNI = Userfundacionsafa.DNI INNER JOIN
                         CENTROS ON EMPLEmovimientos.refcentro = CENTROS.ID
						 WHERE (EMPLEmovimientos.bajarrll=0) and CENTROS.codigojunta=$centro AND (EMPLEmovimientos.catprofesional='PROFESOR' OR EMPLEmovimientos.catprofesional='PROFESOR EDU.ESPEC.INTEGRADA') ORDER BY EMPLEADOS.PRIMERAPELLIDO";
}

//Si el centro es La Milagrosa, muestro todos los profes de Baena

if ($_SESSION[centro]=='14000471'){
											$sql_personal="SELECT DISTINCT EMPLEADOS.PRIMERAPELLIDO, EMPLEADOS.SEGUNDOAPELLIDO, EMPLEADOS.NOMBRE, EMPLEADOS.DNI, Userfundacionsafa.DNI, Userfundacionsafa.email, CENTROS.codigojunta, EMPLEmovimientos.fechabaja, EMPLEmovimientos.catprofesional FROM EMPLEmovimientos INNER JOIN
                         EMPLEADOS ON EMPLEmovimientos.reftrabaja = EMPLEADOS.DNI INNER JOIN
                         Userfundacionsafa ON EMPLEADOS.DNI = Userfundacionsafa.DNI INNER JOIN
                         CENTROS ON EMPLEmovimientos.refcentro = CENTROS.ID
						 WHERE (EMPLEmovimientos.bajarrll=0) and CENTROS.codigojunta='14000380' AND (EMPLEmovimientos.catprofesional='PROFESOR' OR EMPLEmovimientos.catprofesional='PROFESOR EDU.ESPEC.INTEGRADA') ORDER BY EMPLEADOS.PRIMERAPELLIDO";
}

						$result_personal=mssql_query($sql_personal);
						while ($fila_personal=mssql_fetch_array($result_personal)){
							$horas=0;
							$eventuales=0;
							$busca_horas="SELECT HORAS, EVENTUALES FROM HORAS_PROFESOR WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND DNI='$fila_personal[3]'";
							$result_horas=mysql_query($busca_horas,$conexion);
							if (mysql_num_rows($result_horas)>0){
								$fila_horas=mysql_fetch_row($result_horas);
								$horas=$fila_horas[0];
								$eventuales=$fila_horas[1];
							}
							echo "<tr>";
							
							echo "<td>".iconv("WINDOWS-1252", "UTF-8", $fila_personal[0])." ".iconv("WINDOWS-1252", "UTF-8", $fila_personal[1])."</td>";
							echo "<td>".iconv("WINDOWS-1252", "UTF-8", $fila_personal[2])."</td>";
							echo "<td>$fila_personal[3]</td>";
							echo "<td>$fila_personal[5]</td>";
							echo "<td>";
							$busca_etapas="SELECT DISTINCT ETAPA FROM ETAPAS_PROFESOR WHERE PGE=$pge AND NIF='$fila_personal[3]' AND CENTRO='$_SESSION[centro]'";
							$result_etapas=mysql_query($busca_etapas,$conexion);
							while ($fila_etapas=mysql_fetch_row($result_etapas)){
								$busca_nombre_etapa="SELECT NIVEL_EDUCATIVO FROM NIVELES_EDUCATIVOS WHERE ID=$fila_etapas[0]";
								$result_nombre_etapa=mysql_query($busca_nombre_etapa,$conexion);
								$fila_nombre_etapa=mysql_fetch_row($result_nombre_etapa);
								echo $fila_nombre_etapa[0]." | ";
							}
							echo "</td>";
							echo "<td>";
							?>
							<div class="btn-group">
                                                                        <a class="btn red" href="javascript:;" data-toggle="dropdown">
                                                                            <i class="fa fa-clock-o"></i> <?php echo $horas+$eventuales; ?>
                                                                            <i class="fa fa-angle-down"></i>
                                                                        </a>
                                                                        <ul class="dropdown-menu">
                                                                            <li>
                                                                            <form action='mantenimiento_profes.php' name='1' method='POST'>
																				<div class="form-group form-md-line-input has-success">
																					<div class="input-icon">
																						<input type="text" class="form-control" name="horas" value="<?php echo $horas; ?>">
																						<i class="fa fa-clock-o"></i>
																					</div>
																				</div>
																				
                                                                            </li>
                                                                            <li>
																			<div class="form-group form-md-line-input has-success">
																					<div class="input-icon">
																						<input type="text" class="form-control" name="eventuales" value="<?php echo $eventuales; ?>">
																						<i class="fa fa-clock-o"></i>
																					</div>
																			</div>
                                                                                <input type="hidden" name="profesor" value="<?php echo $fila_personal[3]; ?>">
																				<input type="hidden" name="guardar" value="1">
																				<center><input type='submit' class="btn blue" value='Guardar'></center>
																				<br>
                                                                            </li>
																			</form>
                                                                        </ul>
                                                                    </div>
							<?php
							echo "</td>";
							echo "<td><a class='btn red btn-outline sbold' data-toggle='modal' href='#$fila_personal[3]'> Etapas </a></td>";
							echo "</tr>";
							?>
							
							<!--- Modal etapas y horas -->
						<div id="<?php echo $fila_personal[3]; ?>" class="modal fade" tabindex="-1" data-width="760">
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
                                                                         <div class="caption"><?php echo iconv("WINDOWS-1252", "UTF-8", $fila_personal[0])." ".iconv("WINDOWS-1252", "UTF-8", $fila_personal[1]).", ".$fila_personal[2]; ?></div>
                                                                        </div>
                                                                        <div class="portlet-body form">
                                                                            <!-- BEGIN FORM-->
																			
																			<div class="form-body">
																				
																			<div class="form-group">
												<form action="mantenimiento_profes.php" class="horizontal-form" method="POST" id="form">
									
																			</div>
																			
																			<div class="form-actions">
                                    <div class="row">
                                                                                       
									<div class="form-group form-md-line-input">
                                                <label class="col-md-2 control-label" for="form_control_1">Etapas</label>
                                                <div class="col-md-10">
                                                    <div class="md-checkbox">
                                                        <?php
															$busca_niveles="SELECT * FROM NIVELES_EDUCATIVOS WHERE ACTIVO=1 ORDER BY ORDEN";
															$result_niveles=mysql_query($busca_niveles,$conexion);
															while ($fila_niveles=mysql_fetch_row($result_niveles)){
																$checked="";
																$busca_checked="SELECT ETAPA FROM ETAPAS_PROFESOR WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND NIF='$fila_personal[3]' AND ETAPA=$fila_niveles[0]";
																//echo $busca_checked;
																$result_checked=mysql_query($busca_checked,$conexion);
																if (mysql_num_rows($result_checked)>0){
																	$checked="CHECKED";
																}
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
														<div class="md-checkbox">
                                                            <input type="checkbox" id="<?php echo $fila_niveles[0].$fila_personal[3]; ?>" <?php echo $checked; ?> name="<?php echo $fila_niveles[0]; ?>" class="md-check">
                                                            <label for="<?php echo $fila_niveles[0].$fila_personal[3]; ?>">
                                                                <span></span>
                                                                <span class="check"></span>
                                                                <span class="box"></span> <?php echo $fila_niveles[1]; ?> </label>
                                                        </div>
														<?php
										}
															}

														?>
														
                                                    </div>
                                                </div>
                                    </div>
									


                                                                                           
                                    </div>
									<div class="row">
									<div class="col-md-3">	
									</div>
									<div class="col-md-2">
										<div class="form-group form-md-line-input has-info">
											<div class="input-icon">
												<input type="text" class="form-control" name="horas" size=1 value="<?php echo $horas; ?>">
												
													<i class="fa fa-clock-o"></i>
											</div>
										<label for="horas">Horas fijas</label>
										</div>
										
									</div>
									<div class="col-md-2">
										<div class="form-group form-md-line-input has-info">
											<div class="input-icon">
												<input type="text" class="form-control" name="eventuales" size=1 value="<?php echo $eventuales; ?>">
												
													<i class="fa fa-clock-o"></i>
											</div>
										<label for="horas">Horas eventuales</label>
										</div>
										
									</div>
									</div>
									
									<?php
										$observaciones="";
										$busca_observaciones="SELECT OBSERVACIONES FROM OBSERVACIONES_PROFESOR WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND NIF='$fila_personal[3]'";
										$result_observaciones=mysql_query($busca_observaciones,$conexion);
										$fila_observaciones=mysql_fetch_row($result_observaciones);
										$observaciones=$fila_observaciones[0];
									
									?>
									
									<div class="row">
										<div class="col-md-2">
										</div>
										<div class="col-md-8">
										<div class="form-group form-md-line-input has-info">
                                                <input type="text" class="form-control" id="observaciones" name="observaciones" placeholder="Observaciones" value="<?php echo $observaciones; ?>">
                                                <label for="form_control_1">Observaciones</label>
                                          </div>
										 </div>
										  <div class="col-md-2">
										</div>
									</div>
																					
                                     </div>
																					
																					<div class="modal-footer">
																							<input type="hidden" name="profesor" value="<?php echo $fila_personal[3]; ?>">
																							<input type="hidden" name="guardar" value="1">
																							<input type="hidden" name="etapas" value="1">
																							<button type="submit" class="btn red" value="Save">Guardar</button>
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
							<?php
							
						}
						
						//Ahora busco los profesores pendientes de contratación y los muestro de la misma forma:
						
						$sql_personal="SELECT * FROM PROFES_PROVISIONALES WHERE CENTRO='$_SESSION[centro]' AND PGE=$pge";
						$result_personal=mysql_query($sql_personal, $conexion);
						while ($fila_personal=mysql_fetch_row($result_personal)){
							$horas=0;
							$eventuales=0;
							$busca_horas="SELECT HORAS, EVENTUALES FROM HORAS_PROFESOR WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND DNI='$fila_personal[0]'";
							$result_horas=mysql_query($busca_horas,$conexion);
							if (mysql_num_rows($result_horas)>0){
								$fila_horas=mysql_fetch_row($result_horas);
								$horas=$fila_horas[0];
								$eventuales=$fila_horas[1];
							}
							echo "<tr>";
							
							echo "<td>$fila_personal[3]</td>";
							echo "<td>$fila_personal[4]</td>";
							echo "<td>$fila_personal[0]</td>";
							echo "<td>-</td>";
							echo "<td>";
							$busca_etapas="SELECT DISTINCT ETAPA FROM ETAPAS_PROFESOR WHERE PGE=$pge AND NIF='$fila_personal[0]' AND CENTRO='$_SESSION[centro]'";
							$result_etapas=mysql_query($busca_etapas,$conexion);
							while ($fila_etapas=mysql_fetch_row($result_etapas)){
								$busca_nombre_etapa="SELECT NIVEL_EDUCATIVO FROM NIVELES_EDUCATIVOS WHERE ID=$fila_etapas[0]";
								$result_nombre_etapa=mysql_query($busca_nombre_etapa,$conexion);
								$fila_nombre_etapa=mysql_fetch_row($result_nombre_etapa);
								echo $fila_nombre_etapa[0]." | ";
							}
							echo "</td>";
							echo "<td>";
							?>
							<div class="btn-group">
                                                                        <a class="btn red" href="javascript:;" data-toggle="dropdown">
                                                                            <i class="fa fa-clock-o"></i> <?php echo $horas+$eventuales; ?>
                                                                            <i class="fa fa-angle-down"></i>
                                                                        </a>
                                                                        <ul class="dropdown-menu">
                                                                            <li>
                                                                            <form action='mantenimiento_profes.php' name='1' method='POST'>
																				<div class="form-group form-md-line-input has-success">
																					<div class="input-icon">
																						<input type="text" class="form-control" name="horas" value="<?php echo $horas; ?>">
																						<i class="fa fa-clock-o"></i>
																					</div>
																				</div>
																				
                                                                            </li>
                                                                            <li>
																			<div class="form-group form-md-line-input has-success">
																					<div class="input-icon">
																						<input type="text" class="form-control" name="eventuales" value="<?php echo $eventuales; ?>">
																						<i class="fa fa-clock-o"></i>
																					</div>
																			</div>
                                                                                <input type="hidden" name="profesor" value="<?php echo $fila_personal[0]; ?>">
																				<input type="hidden" name="guardar" value="1">
																				<center><input type='submit' class="btn blue" value='Guardar'></center>
																				<br>
                                                                            </li>
																			</form>
                                                                        </ul>
                                                                    </div>
							<?php
							echo "</td>";
							echo "<td><a class='btn red btn-outline sbold' data-toggle='modal' href='#$fila_personal[0]'> Etapas </a></td>";
							echo "</tr>";
							?>
							
							<!--- Modal etapas y horas -->
						<div id="<?php echo $fila_personal[0]; ?>" class="modal fade" tabindex="-1" data-width="760">
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
                                                                         <div class="caption"><?php echo $fila_personal[1]; ?></div>
                                                                        </div>
                                                                        <div class="portlet-body form">
                                                                            <!-- BEGIN FORM-->
																			
																			<div class="form-body">
																				
																			<div class="form-group">
												<form action="mantenimiento_profes.php" class="horizontal-form" method="POST" id="form">
									
																			</div>
																			
																			<div class="form-actions">
                                    <div class="row">
                                                                                       
									<div class="form-group form-md-line-input">
                                                <label class="col-md-2 control-label" for="form_control_1">Etapas</label>
                                                <div class="col-md-10">
                                                    <div class="md-checkbox">
                                                        <?php
															$busca_niveles="SELECT * FROM NIVELES_EDUCATIVOS WHERE ACTIVO=1 ORDER BY ORDEN";
															$result_niveles=mysql_query($busca_niveles,$conexion);
															while ($fila_niveles=mysql_fetch_row($result_niveles)){
																$checked="";
																$busca_checked="SELECT ETAPA FROM ETAPAS_PROFESOR WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND NIF='$fila_personal[0]' AND ETAPA=$fila_niveles[0]";
																$result_checked=mysql_query($busca_checked,$conexion);
																if (mysql_num_rows($result_checked)>0){
																	$checked="CHECKED";
																}
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
														<div class="md-checkbox">
                                                            <input type="checkbox" id="<?php echo $fila_niveles[0].$fila_personal[0]; ?>" <?php echo $checked; ?> name="<?php echo $fila_niveles[0]; ?>" class="md-check">
                                                            <label for="<?php echo $fila_niveles[0].$fila_personal[0]; ?>">
                                                                <span></span>
                                                                <span class="check"></span>
                                                                <span class="box"></span> <?php echo $fila_niveles[1]; ?> </label>
                                                        </div>
														<?php
										}
														}

														?>
														
                                                    </div>
                                                </div>
                                    </div>
									


                                                                                           
                                    </div>
									<div class="row">
									<div class="col-md-3">	
									</div>
									<div class="col-md-2">
										<div class="form-group form-md-line-input has-info">
											<div class="input-icon">
												<input type="text" class="form-control" name="horas" size=1 value="<?php echo $horas; ?>">
												
													<i class="fa fa-clock-o"></i>
											</div>
										<label for="horas">Horas fijas</label>
										</div>
										
									</div>
									<div class="col-md-2">
										<div class="form-group form-md-line-input has-info">
											<div class="input-icon">
												<input type="text" class="form-control" name="eventuales" size=1 value="<?php echo $eventuales; ?>">
												
													<i class="fa fa-clock-o"></i>
											</div>
										<label for="horas">Horas eventuales</label>
										</div>
										
									</div>
									</div>
									
									<?php
										$observaciones="";
										$busca_observaciones="SELECT OBSERVACIONES FROM OBSERVACIONES_PROFESOR WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND NIF='$fila_personal[0]'";
										$result_observaciones=mysql_query($busca_observaciones,$conexion);
										$fila_observaciones=mysql_fetch_row($result_observaciones);
										$observaciones=$fila_observaciones[0];
									
									?>
									
									<div class="row">
										<div class="col-md-2">
										</div>
										<div class="col-md-8">
										<div class="form-group form-md-line-input has-info">
                                                <input type="text" class="form-control" id="observaciones" name="observaciones" placeholder="Observaciones" value="<?php echo $observaciones; ?>">
                                                <label for="form_control_1">Observaciones</label>
                                          </div>
										 </div>
										  <div class="col-md-2">
										</div>
									</div>
																					
                                     </div>
																					
																					<div class="modal-footer">
																							<input type="hidden" name="profesor" value="<?php echo $fila_personal[0]; ?>">
																							<input type="hidden" name="guardar" value="1">
																							<input type="hidden" name="etapas" value="1">
																							<button type="submit" class="btn red" value="Save">Guardar</button>
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
							<?php
							
						}
											
											
											?>
											
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- END EXAMPLE TABLE PORTLET-->
					
					
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


<!--- Modal Añadir Profesor -->
						
						<div id="nuevo_profe" class="modal fade" tabindex="-1" data-width="760">
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
                                                                         <div class="caption">Profesor pendiente de contratación</div>
                                                                        </div>
                                                                        <div class="portlet-body form">
                                                                            <!-- BEGIN FORM-->
																			
																			<div class="form-body">
																				
																			<div class="form-group">
												<form action="mantenimiento_profes.php" class="horizontal-form" method="POST" id="form">
									
																			</div>
																			
									<div class="form-actions">
                                    <div class="row">
                                                                                       
									<div class="col-md-6">													
									<div class="form-group form-md-line-input has-info">
												<input type="text" class="form-control" id="nombre_profe_nuevo" name="nombre_profe_nuevo">
                                                <label for="nombre_profe_nuevo">Nombre</label>
                                                
                                    </div>
									</div>
									
									<div class="col-md-6">													
									<div class="form-group form-md-line-input has-info">
												<input type="text" class="form-control" id="observaciones_profe_nuevo" name="observaciones_profe_nuevo">
                                                <label for="observaciones_profe_nuevo">Observaciones</label>
                                                
                                    </div>
									</div>
                                                   
                                    </div>
                                    </div>
																					
																					<div class="modal-footer">
																							<input type="hidden" name="profe_provisional" value="1">
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