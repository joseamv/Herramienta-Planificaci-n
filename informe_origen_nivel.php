<?php
include "conexion.php";
include "header.php";


$total_concierto=0;


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
                                <span>Informe por Profesor, Origen y Nivel</span>
                            </li>
                        </ul>
                        
                    </div>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"> Informe por Profesor, Origen y Nivel
                    </h3>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
					<!-- BEGIN PAGE CONTENT-->

					
					<?php 
					
						if ($_SESSION[permiso]==1){
					?>
					
					<div class="tab-content">

					<!-- BEGIN EXAMPLE TABLE PORTLET-->
    
						 
						 <div class="col-md-12 col-sm-12">
							<div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption font-dark">
                                        <i class="icon-settings font-dark"></i>
                                        <span class="caption-subject bold uppercase">Informe por Profesor, Origen y Nivel</span>
                                    </div>
                                    <div class="tools"> </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-striped table-bordered table-hover" id="sample_1">
                                        <thead>
                                            <tr>
                                                <th> CENTRO </th>
												<th> NIF </th>
                                                <th> PROFESOR </th>                                               
												<th> NIVEL </th>
												<th> ORIGEN </th>
												<th> HORAS </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
											<?php
					$busca_centros_safa="SELECT CODIGO, CENTRO FROM CENTROS WHERE TITULAR=1 AND ACTIVO=1 AND CODIGO=$_SESSION[centro]";
					$result_centros_safa=mysql_query($busca_centros_safa,$conexion);
					while ($fila_centros_safa=mysql_fetch_row($result_centros_safa)){
						
if ($fila_centros_safa[0]=='11701279'){
	$centro='11003001';
}else{
	$centro=$fila_centros_safa[0];
}	
	
$sql_personal="SELECT DISTINCT EMPLEADOS.PRIMERAPELLIDO, EMPLEADOS.SEGUNDOAPELLIDO, EMPLEADOS.NOMBRE, EMPLEADOS.DNI, Userfundacionsafa.DNI, Userfundacionsafa.email, CENTROS.codigojunta, EMPLEmovimientos.fechabaja, EMPLEmovimientos.catprofesional
FROM EMPLEmovimientos INNER JOIN
                         EMPLEADOS ON EMPLEmovimientos.reftrabaja = EMPLEADOS.DNI INNER JOIN
                         Userfundacionsafa ON EMPLEADOS.DNI = Userfundacionsafa.DNI INNER JOIN
                         CENTROS ON EMPLEmovimientos.refcentro = CENTROS.ID
WHERE (EMPLEmovimientos.bajarrll=0) and CENTROS.codigojunta=$centro AND (EMPLEmovimientos.catprofesional='PROFESOR' OR EMPLEmovimientos.catprofesional='PROFESOR EDU.ESPEC.INTEGRADA') ORDER BY EMPLEADOS.PRIMERAPELLIDO";


if ($fila_centros_safa[0]=='14000471'){
											$sql_personal="SELECT DISTINCT EMPLEADOS.PRIMERAPELLIDO, EMPLEADOS.SEGUNDOAPELLIDO, EMPLEADOS.NOMBRE, EMPLEADOS.DNI, Userfundacionsafa.DNI, Userfundacionsafa.email, CENTROS.codigojunta, EMPLEmovimientos.fechabaja, EMPLEmovimientos.catprofesional FROM EMPLEmovimientos INNER JOIN
                         EMPLEADOS ON EMPLEmovimientos.reftrabaja = EMPLEADOS.DNI INNER JOIN
                         Userfundacionsafa ON EMPLEADOS.DNI = Userfundacionsafa.DNI INNER JOIN
                         CENTROS ON EMPLEmovimientos.refcentro = CENTROS.ID
						 WHERE (EMPLEmovimientos.bajarrll=0) and CENTROS.codigojunta='14000380' AND (EMPLEmovimientos.catprofesional='PROFESOR' OR EMPLEmovimientos.catprofesional='PROFESOR EDU.ESPEC.INTEGRADA') ORDER BY EMPLEADOS.PRIMERAPELLIDO";
}

$result_personal=mssql_query($sql_personal);
while ($fila_personal=mssql_fetch_row($result_personal)){		

													$busca_etapas="SELECT ID, NIVEL_EDUCATIVO FROM NIVELES_EDUCATIVOS WHERE ACTIVO=1 ORDER BY ORDEN";													
													$result_etapas=mysql_query($busca_etapas, $conexion);
													while ($fila_etapas=mysql_fetch_row($result_etapas)){
														$busca_origenes="SELECT * FROM ORIGENES_HORAS WHERE ACTIVO=1";
														$result_origenes=mysql_query($busca_origenes,$conexion);
														while ($fila_origenes=mysql_fetch_row($result_origenes)){
															$busca_horas_etapa="SELECT HORAS_CONSUMIDAS, ORIGEN FROM HORARIO WHERE PGE='$pge' AND NIF='$fila_personal[3]' AND CENTRO='$_SESSION[centro]' AND NIVEL_EDUCATIVO=$fila_etapas[0] AND ORIGEN=$fila_origenes[0]";
															$result_horas_etapa=mysql_query($busca_horas_etapa,$conexion);
															$horas=0;
															while ($fila_horas_etapa=mysql_fetch_row($result_horas_etapa)){
															
																$horas=$horas+$fila_horas_etapa[0];
															}
															if ($horas>0){
												
																	$nombre_completo=$fila_personal[0]." ".$fila_personal[1].", ".$fila_personal[2];
					$nombre_completo=iconv("WINDOWS-1252", "UTF-8", $nombre_completo);
							echo "<tr>";
							echo "<td>$fila_centros_safa[1]</td>";
							echo "<td>$fila_personal[3]</td>";
							echo "<td>$nombre_completo</td>";
							echo "<td>$fila_etapas[1]</td>";
							echo "<td>$fila_origenes[1]</td>";
							echo "<td>$horas</td>";
							echo "</tr>";
							
												
												
														}
														}
														
													}
												


}				
					}
							
							

							

											
											
											?>
											
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- END EXAMPLE TABLE PORTLET-->
					
						</div>
						
						

						
						
						
					</div>
                    
					 <?php echo "Total horas disponibles: ".$total_autorizado; ?>
					 <br>
					 <?php echo "Total horas consumidas: ".$total_consumido; ?>
					
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