<?php
include "conexion.php";
include "header.php";


if ($_POST[nueva]=="1"){
	$inserta_materia="INSERT INTO MATERIAS_SENECA (ID, MATERIA, ACTIVO) VALUES ($_POST[codigo], '$_POST[materia]', 1)";
	mysql_query($inserta_materia,$conexion);
	
	$inserta_materia_curso="INSERT INTO CURSOS_ASIGNATURAS (COD_CURSO, COD_MATERIA, HORAS) VALUES ($_POST[curso], '$_POST[codigo]', $_POST[horas])";
	//echo $inserta_materia_curso;
	mysql_query($inserta_materia_curso,$conexion);
	
}


?>
<div class="page-container">
<?php
	include "sidebar.php";
	

	
?>
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <?php 
					
						if ($_SESSION[permiso]<2){
					?>
				
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
                                <span>Mantenimiento de materias</span>
                            </li>
                        </ul>
                        
                    </div>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"> Mantenimiento de materias
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
	
										$muestra=1;
										
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

if ($_POST[nivel]!="" OR $_POST[curso]!=""){
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

		echo "<option value=$fila_curso[1] $selected>$fila_curso[0] - $fila_curso[1]</option>";

										
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

											   ?>
										<div class="table-responsive">
                                         <table class="table table-striped table-bordered table-hover" width="100%" id="sample_1">
                                            <thead>
                                                <tr>
                                                    <th> Materia </th>
													<th> Código </th>
													<th> Horas </th>
                                                    <th> Borrar </th>
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
												$busca_materias="SELECT * FROM CURSOS_ASIGNATURAS WHERE COD_CURSO=$_POST[curso]";
												$result_materias=mysql_query($busca_materias,$conexion);
												while ($fila_materias=mysql_fetch_row($result_materias)){
													echo "<tr>";
													$busca_nombre_materia="SELECT MATERIA FROM MATERIAS_SENECA WHERE ID=$fila_materias[1]";
													$result_nombre_materia=mysql_query($busca_nombre_materia,$conexion);
													$fila_nombre_materia=mysql_fetch_row($result_nombre_materia);
													echo "<td>$fila_nombre_materia[0]</td>";
													echo "<td>$fila_materias[1]</td>";
													echo "<td>$fila_materias[2]</td>";
													echo "<td><a href='borrar_materia_curso.php?id=$fila_asignaturas[0]&profe=$profesor'><button class='btn red btn-large' data-toggle='confirmation' data-original-title='¿Estás seguro?' data-btn-ok-label='Sí' data-btn-cancel-label='No' title=''><i class='fa fa-trash-o'></i>Borrar</button></a></td>";
													echo "</tr>";
												}
											?>
											</tbody>
											</table>
										</div>
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
						
					<div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
								    <a class="btn red btn-outline sbold" data-toggle="modal" href="#nueva_materia"> Añadir Materia </a>
						</div></div>
                    
			
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
		<?php
						}else{
						 echo "<div class='alert alert-danger'><strong>Sin acceso</strong> Tu rol actual no tiene permisos para acceder a esta página</div>";
					 }
				?>
        <!-- END CONTAINER -->

	<!--- Modal Añadir materias -->
						<div id="nueva_materia" class="modal fade" tabindex="-1" data-width="760">
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
                                                                         <div class="caption">Nueva Materia</div>
                                                                        </div>
                                                                        <div class="portlet-body form">
                                                                            <!-- BEGIN FORM-->
																			
																			<div class="form-body">
																				
																			<div class="form-group">
												<form action="gestion_materias.php" class="horizontal-form" method="POST" id="form1">
									
																			</div>
																			
																			<div class="form-actions">
									<div class="row">
									<div class="col-md-2">
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
									<div class="col-md-2">
									</div>
									<div class="col-md-2">
										<div class="form-group form-md-line-input has-info">
												<input type="text" class="form-control" name="codigo" size=6 id="codigo">
												<span class="help-block">Código</span>
										</div>
									</div>
									</div>
									<div class="row">
									<div class="col-md-2">
										</div>
										<div class="col-md-8">
										<div class="form-group form-md-line-input has-info">
                                                <input type="text" class="form-control" id="materia" name="materia" placeholder="Observaciones" >
                                                <label for="form_control_1">Materia</label>
                                          </div>
										 </div>
										  <div class="col-md-2">
										</div>
									</div>
																						
																																			
									
                                                                                            
                                                                             
																						
																					
                                     </div>
																					
																					<div class="modal-footer">
																							<input type="hidden" name="curso" value="<?php echo $_POST[curso]; ?>">
																							<input type="hidden" name="nivel" value="<?php echo $_POST[nivel]; ?>">
																							<input type="hidden" name="nueva" value="1">
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
		

<?php
	include "footer.php";
?>