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
                                <span>Informe de Tutores</span>
                            </li>
                        </ul>
                        
                    </div>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"> Informe de Tutores
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
                                        <span class="caption-subject bold uppercase">Tutores</span>
                                    </div>
                                    <div class="tools"> </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-striped table-bordered table-hover" id="sample_1">
                                        <thead>
                                            <tr>
                                                <th> Id </th>
                                                <th> Etapa </th>
                                                <th> Curso </th>
												<th> Grupo </th>
                                                <th> Tutor </th>
												<th> DNI </th>
                                                <th> Horas </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
											<?php
											
											

						$busca_niveles="SELECT ID, ORDEN, NIVEL_EDUCATIVO FROM NIVELES_EDUCATIVOS WHERE ACTIVO=1 AND ID!=4";
						$result_niveles=mysql_query($busca_niveles,$conexion);
						while ($fila_niveles=mysql_fetch_row($result_niveles)){
							$busca_cursos="SELECT CURSO, COD_SENECA FROM CURSOS WHERE NIVEL=$fila_niveles[0]";
							$result_cursos=mysql_query($busca_cursos,$conexion);
							while ($fila_cursos=mysql_fetch_row($result_cursos)){
								$busca_grupos="SELECT GRUPOS FROM GRUPOS_CENTRO WHERE CURSO=$fila_cursos[1] AND CENTRO='$_SESSION[centro]' AND PGE=$pge";
								$result_grupos=mysql_query($busca_grupos,$conexion);
								$fila_grupos=mysql_fetch_row($result_grupos);
								$i=1;
								while ($i<=$fila_grupos[0]){
									if ($fila_grupos[0]==1){
										$j=0;
									}else{
										$j=$i;
									}
								
							$busca_nombre_grupo="SELECT GRUPO FROM GRUPOS WHERE ID=$j";
							$result_nombre_grupo=mysql_query($busca_nombre_grupo,$conexion);
							$fila_nombre_grupo=mysql_fetch_row($result_nombre_grupo);
								
						
						
							
							echo "<tr>";
							echo "<td>$fila_niveles[1]</td>";
							echo "<td>$fila_niveles[2]</td>";
							echo "<td>$fila_cursos[0]</td>";
							echo "<td>$fila_nombre_grupo[0]</td>";
							
							$busca_tutor="SELECT NIF, HORAS_CONSUMIDAS FROM HORARIO WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND CURSO=$fila_cursos[1] AND GRUPO=$j AND CODIGO_MATERIA=10000";
							$result_tutor=mysql_query($busca_tutor,$conexion);
							$fila_tutor=mysql_fetch_row($result_tutor);
							
							if ($titular=='2'){
								$busca_profe="SELECT PRIMERAPELLIDO, SEGUNDOAPELLIDO, NOMBRE, DNI FROM EMPLEADOS_LOYOLA WHERE DNI='$fila_tutor[0]'";
							}else{
								$busca_profe="SELECT PRIMERAPELLIDO, SEGUNDOAPELLIDO, NOMBRE, DNI FROM EMPLEADOS WHERE DNI='$fila_tutor[0]'";
							}
							$result_profe=mssql_query($busca_profe);
							$fila_profe=mssql_fetch_array($result_profe);
							
							if (mssql_num_rows($result_profe)==0){
								$busca_provisional="SELECT NOMBRE, OBSERVACIONES FROM PROFES_PROVISIONALES WHERE ID='$fila_tutor[0]'";
								$result_provisional=mysql_query($result_provisional);
								$fila_profe=mysql_query($result_provisional);
							}
							
							$nombre_completo=$fila_profe[0]." ".$fila_profe[1].", ".$fila_profe[2];
							$nombre_completo=iconv("WINDOWS-1252", "UTF-8", $nombre_completo);
							
							
							
							echo "<td>$nombre_completo</td>";
							echo "<td>$fila_profe[3]</td>";
							echo "<td>$fila_tutor[1]</td>";

							echo "</tr>";
							?>
							


                            </div>

							<?php
								$i=$i+1;
								}//Cierra grupos
							} // Cierra cursos
						} // Cierra niveles
						
		
											
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