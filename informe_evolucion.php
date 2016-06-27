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
                                <span>Evolución de la Jornada</span>
                            </li>
                        </ul>
                        
                    </div>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"> Evolución de la Jornada
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
                                        <span class="caption-subject bold uppercase">Profesores</span>
                                    </div>
                                    <div class="tools"> </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-striped table-bordered table-hover" id="sample_1">
                                        <thead>
                                            <tr>
                                                
                                                <th> Fijo/Even </th>
												<th> Apellidos </th>
                                                <th> Nombre </th>
												<th> NIF </th>
                                                <th> Email </th>
												<th> Horas <?echo $pge_anterior; ?> </th>
                                                <th> Horas <?echo $pge; ?></th>
												<th> Var </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
											<?php
											
											if ($titular=='2'){
$sql_personal="SELECT PRIMERAPELLIDO, SEGUNDOAPELLIDO, NOMBRE, DNI, email FROM EMPLEADOS_LOYOLA WHERE nombrecentro='$_SESSION[centro]' ORDER BY PRIMERAPELLIDO, SEGUNDOAPELLIDO";	
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
							$fijo_even="";
							if ($eventuales>0){
								$fijo_even="E";
							}else{
								$fijo_even="F";
							}
							echo "<tr>";
							echo "<td>$fijo_even</td>";
							echo "<td>".iconv("WINDOWS-1252", "UTF-8", $fila_personal[0])." ".iconv("WINDOWS-1252", "UTF-8", $fila_personal[1])."</td>";
							echo "<td>".iconv("WINDOWS-1252", "UTF-8", $fila_personal[2])."</td>";
							echo "<td>$fila_personal[3]</td>";
							echo "<td>$fila_personal[5]</td>";
							$busca_horas_anterior="SELECT SUM(HORAS_CONSUMIDAS) FROM HORARIO WHERE PGE=$pge_anterior AND CENTRO='$_SESSION[centro]' AND NIF='$fila_personal[3]'";
							$result_horas_anterior=mysql_query($busca_horas_anterior,$conexion);
							$fila_horas_anterior=mysql_fetch_row($result_horas_anterior);
							
							echo "<td>";
							echo $fila_horas_anterior[0];
							echo "</td>";
							$busca_horas="SELECT SUM(HORAS_CONSUMIDAS) FROM HORARIO WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND NIF='$fila_personal[3]'";
							$result_horas=mysql_query($busca_horas,$conexion);
							$fila_horas=mysql_fetch_row($result_horas);
							echo "<td>";
							echo $fila_horas[0];
							echo "</td>";
							$variacion=$fila_horas[0]-$fila_horas_anterior[0];
							$signo="";
							if ($variacion>0){
								$signo="+";
							}
							if ($variacion==0){
								$variacion="";
							}
							echo "<td>$signo$variacion</td>";
							echo "</tr>";
							?>
							

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