<?php
include "conexion.php";
include "header.php";

?>
<div class="page-container">
<?php
	include "sidebar.php";
	
	
if ($_POST[guardar]=="1"){
	//Recorro niveles y orígenes para comprobar si existía el registro y actualizarlo o añadirlo
	$busca_origenes="SELECT * FROM ORIGENES_HORAS WHERE ACTIVO=1 AND CARGA_DC=1";
	$result_origenes=mysql_query($busca_origenes,$conexion);
	while ($fila_origenes=mysql_fetch_row($result_origenes)){
		$busca_niveles="SELECT * FROM NIVELES_EDUCATIVOS WHERE ACTIVO=1";
		$result_niveles=mysql_query($busca_niveles,$conexion);
		while ($fila_niveles=mysql_fetch_row($result_niveles)){
			$nombre_variable=$fila_niveles[0]."-".$fila_origenes[0];
			$busca_carga="SELECT HORAS FROM CARGA_HORARIA WHERE PGE=$pge AND CENTRO=$_POST[centro] AND NIVEL_EDUCATIVO=$fila_niveles[0] AND ORIGEN=$fila_origenes[0]";
			//ECHO $busca_carga;
			$result_carga=mysql_query($busca_carga,$conexion);
			if (mysql_num_rows($result_carga)>0){
				$update_carga="UPDATE CARGA_HORARIA SET HORAS=$_POST[$nombre_variable] WHERE PGE=$pge AND CENTRO=$_POST[centro] AND NIVEL_EDUCATIVO=$fila_niveles[0] AND ORIGEN=$fila_origenes[0]";
				mysql_query($update_carga,$conexion);
			}else{
				$inserta_carga="INSERT INTO CARGA_HORARIA (PGE, CENTRO, NIVEL_EDUCATIVO, ORIGEN, HORAS) VALUES ($pge, '$_POST[centro]', $fila_niveles[0], $fila_origenes[0], $_POST[$nombre_variable])";
				//echo $inserta_carga;
				mysql_query($inserta_carga,$conexion);
			}
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
                                <span>Carga Horaria</span>
                            </li>
                        </ul>
                        
                    </div>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"> Carga Horaria
                    </h3>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
					<!-- BEGIN PAGE CONTENT-->

					<?php 
					
						if ($_SESSION[permiso]==1){
					?>
					
					<div class="tab-content">

					<!-- BEGIN EXAMPLE TABLE PORTLET-->
						<div class="row">
                                                                                       
									<div class="col-md-12">													
									<div class="form-group form-md-line-input has-info">	
									<form name="seleccionar_profesor" action='#' method='POST'>									
									<select class="form-control" id="centro" name="centro" onchange='javascript:seleccionar_profesor.submit();'>
                                                    <option value=""></option>
													<?php
														$busca_centros="SELECT CODIGO, CENTRO  FROM CENTROS WHERE ACTIVO=1 ORDER BY CENTRO";
														$result_centros=mysql_query($busca_centros,$conexion);
														while ($fila_centros=mysql_fetch_row($result_centros)){
															$selected="";
															if ($fila_centros[0]==$_POST[centro]){
																$selected="SELECTED";
															}
															echo "<option value=$fila_centros[0] $selected>$fila_centros[1]</option>";
														}
													
													?>
                                                </select>
												<label for="centro">Selecciona un centro</label>
									</form>
									</div>
									</div>
						</div>
					
					
					
                         <div class="col-md-12 col-sm-12">
							<div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption font-dark">
                                        <i class="icon-settings font-dark"></i>
                                        <span class="caption-subject bold uppercase">Carga de horas desde DC</span>
                                    </div>
                                    <div class="tools"> </div>
                                </div>
                                <div class="portlet-body">
									<form action="#" method="POST" name="carga_horaria">
                                    <table class="table table-striped table-bordered table-hover" id="sample_1">
                                        <thead>
                                            <tr>
                                                <th> ID </th>
                                                <th> NIVEL </th>
                                                <?php
													$busca_origenes="SELECT * FROM ORIGENES_HORAS WHERE ACTIVO=1 AND CARGA_DC=1";
													$result_origenes=mysql_query($busca_origenes,$conexion);
													while ($fila_origenes=mysql_fetch_row($result_origenes)){
														echo "<th>$fila_origenes[3]</th>";
													}
													
												
												?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
											<?php
						$sql_niveles="SELECT * FROM NIVELES_EDUCATIVOS WHERE ACTIVO=1 ORDER BY ORDEN";
						$result_niveles=mysql_query($sql_niveles,$conexion);
						while ($fila_niveles=mysql_fetch_row($result_niveles)){
							
							echo "<tr>";
							echo "<td>$fila_niveles[4]</td>";
							echo "<td>$fila_niveles[1]</td>";
							$busca_origenes="SELECT * FROM ORIGENES_HORAS WHERE ACTIVO=1 AND CARGA_DC=1";
							$result_origenes=mysql_query($busca_origenes,$conexion);
							while ($fila_origenes=mysql_fetch_row($result_origenes)){
								$horas_nivel=0;
								$busca_carga="SELECT HORAS FROM CARGA_HORARIA WHERE PGE=$pge AND CENTRO=$_POST[centro] AND NIVEL_EDUCATIVO=$fila_niveles[0] AND ORIGEN=$fila_origenes[0]";
								$result_carga=mysql_query($busca_carga,$conexion);
								$fila_carga=mysql_fetch_row($result_carga);
								echo "<td>";
								$nombre_variable=$fila_niveles[0]."-".$fila_origenes[0];
								?>
								
										<div class="form-group form-md-line-input has-info">
											<div class="input-icon">
												<input type="text" class="form-control" name="<?php echo $nombre_variable; ?>" value="<? echo $fila_carga[0]; ?>" size=1 >
												<span class="help-block">Horas</span>
													<i class="fa fa-clock-o"></i>
											</div>
										</div>
								
								<?php
								echo "</td>";
							}
							
							echo "</tr>";
						}
											
											
											?>
											
                                        </tbody>
                                    </table>
									<input type="hidden" name="guardar" value="1">
									<input type="hidden" name="centro" value="<?php echo $_POST[centro]; ?>">
									<div align=right><input type="submit" class="btn blue" value="Guardar" name="Guardar"></div>
									</form>
                                </div>
                            </div>
                            <!-- END EXAMPLE TABLE PORTLET-->
					
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