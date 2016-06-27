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
                                <span>Informe de Liberaciones</span>
                            </li>
                        </ul>
                        
                    </div>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"> Informe de Liberaciones
                    </h3>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
					<!-- BEGIN PAGE CONTENT-->

					<div class="tab-content">

					<!-- BEGIN EXAMPLE TABLE PORTLET-->

					
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption font-dark">
                                        <i class="icon-settings font-dark"></i>
                                        <span class="caption-subject bold uppercase">Liberaciones</span>
                                    </div>
                                    <div class="tools"> </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-striped table-bordered table-hover" id="sample_1">
                                        <thead>
                                            <tr>
                                                
                                                <th> Profesor </th>
                                                <th> Actividad Centro </th>
												<th> Nivel </th>
												<th> Origen </th>
												<th> Observaciones </th>
                                                <th> Horas </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
<?php
									$total_horas=0;
										$busca_actividades="SELECT ID, ACTIVIDAD_CENTRO FROM ACTIVIDADES_CENTRO WHERE ACTIVO=1 AND LIBERACION=1 ORDER BY ACTIVIDAD_CENTRO";		
										$result_actividades=mysql_query($busca_actividades,$conexion);
										while ($fila_actividades=mysql_fetch_row($result_actividades)){
											$busca_horas="SELECT NIF, OBSERVACIONES, SUM(HORAS_CONSUMIDAS), NIVEL_EDUCATIVO, ORIGEN FROM HORARIO WHERE CENTRO='$_SESSION[centro]' AND ACTIVIDAD_CENTRO=$fila_actividades[0] AND PGE=$pge GROUP BY NIF";
											//echo $busca_horas;
											$result_horas=mysql_query($busca_horas,$conexion);
											while ($fila_horas=mysql_fetch_row($result_horas)){
												if ($titular=='2'){
								$busca_profe="SELECT PRIMERAPELLIDO, SEGUNDOAPELLIDO, NOMBRE, DNI FROM EMPLEADOS_LOYOLA WHERE DNI='$fila_horas[0]'";
							}else{
								$busca_profe="SELECT PRIMERAPELLIDO, SEGUNDOAPELLIDO, NOMBRE, DNI FROM EMPLEADOS WHERE DNI='$fila_horas[0]'";
							}
							$result_profe=mssql_query($busca_profe);
							$fila_profe=mssql_fetch_array($result_profe);
							$nombre_completo=$fila_profe[0]." ".$fila_profe[1].", ".$fila_profe[2];
							$nombre_completo=iconv("WINDOWS-1252", "UTF-8", $nombre_completo);
							
							$busca_nivel="SELECT NIVEL_EDUCATIVO FROM NIVELES_EDUCATIVOS WHERE ID=$fila_horas[3]";
							$result_nivel=mysql_query($busca_nivel,$conexion);
							$fila_nivel=mysql_fetch_row($result_nivel);
							
							$busca_origen="SELECT ORIGEN FROM ORIGENES_HORAS WHERE ID=$fila_horas[4]";
							$result_origen=mysql_query($busca_origen,$conexion);
							$fila_origen=mysql_fetch_row($result_origen);
												
												echo "<tr><td><a href='./asignacion_profes.php?profe=$fila_profe[3]'>$nombre_completo</a></td><td>$fila_actividades[1]</td><td>$fila_nivel[0]</td><td>$fila_origen[0]</td><td>$fila_horas[1]</td><td>$fila_horas[2]</td></tr>";
												$total_horas=$total_horas+$fila_horas[2];
											}
										}
											
?>
											
                                        </tbody>
                                    </table>
                                </div>
								Total Horas: <?php echo $total_horas; ?>
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