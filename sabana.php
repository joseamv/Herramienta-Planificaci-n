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
                                <span>Sábana Horaria</span>
                            </li>
                        </ul>
                        
                    </div>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"> Sábana Horaria
                    </h3>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
					<!-- BEGIN PAGE CONTENT-->

					<div class="tab-content">

					<!-- BEGIN EXAMPLE TABLE PORTLET-->
    
						 
						 <div class="col-md-12 col-sm-12">
							<div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption font-dark">
                                        <i class="icon-settings font-dark"></i>
                                        <span class="caption-subject bold uppercase">Planificación horaria del centro</span>
                                    </div>
                                    <div class="tools"> </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-striped table-bordered table-hover" id="sample_1">
                                        <thead>
                                            <tr>
                                                
                                                <th> PROFESOR </th>
												<th> NIF </th>
												<th> ETAPA </th>
												<th> CURSO </th>
												<th> MATERIA </th>
												<th> GRUPO </th>
												<th> HORAS </th>
												<th> ORIGEN </th>
												<th> ACT CENTRO </th>
												<th> ACT SÉNECA </th>
												<th> OBSERVACIONES </th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
											<?php
												$busca_lineas="SELECT * FROM HORARIO WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' ORDER BY NIF, NIVEL_EDUCATIVO, CURSO, GRUPO";
												
												if ($_GET[profesor]!=""){
													$busca_lineas="SELECT * FROM HORARIO WHERE PGE=$pge_anterior AND CENTRO='$_SESSION[centro]' AND NIF='$_GET[profesor]'ORDER BY NIF, NIVEL_EDUCATIVO, CURSO, GRUPO";
												}
												
												$result_lineas=mysql_query($busca_lineas,$conexion);
												while ($fila_lineas=mysql_fetch_row($result_lineas)){
													echo "<tr>";
													
													//Nombre del profesor
													if ($titular=='2'){
														$sql_personal="SELECT PRIMERAPELLIDO, SEGUNDOAPELLIDO, NOMBRE, DNI FROM EMPLEADOS_LOYOLA WHERE DNI='$fila_lineas[15]'";
													}else{
														$sql_personal="SELECT DISTINCT EMPLEADOS.PRIMERAPELLIDO, EMPLEADOS.SEGUNDOAPELLIDO, EMPLEADOS.NOMBRE, EMPLEADOS.DNI FROM EMPLEADOS WHERE EMPLEADOS.DNI='$fila_lineas[15]'";
													}
													$result_personal=mssql_query($sql_personal);
													$fila_personal=mssql_fetch_array($result_personal);
													if (mssql_num_rows($result_personal)==0){
														$sql_personal="SELECT NOMBRE, OBSERVACIONES FROM PROFES_PROVISIONALES WHERE ID=$fila_lineas[15]";
														$result_personal=mysql_query($sql_personal,$conexion);
														$fila_personal=mysql_fetch_row($result_personal);
														echo "<td>$fila_personal[0] - $fila_personal[1]</td>";
													}else{
													echo "<td>".iconv("WINDOWS-1252", "UTF-8", $fila_personal[0])." ".iconv("WINDOWS-1252", "UTF-8", $fila_personal[1]).", ".iconv("WINDOWS-1252", "UTF-8", $fila_personal[2])."</td>";	
													}
													
													
													//NIF
													echo "<td>$fila_lineas[15]";
													
													//ETAPA
													$busca_etapa="SELECT NIVEL_EDUCATIVO FROM NIVELES_EDUCATIVOS WHERE ID=$fila_lineas[5]";
													$result_etapa=mysql_query($busca_etapa,$conexion);
													$fila_etapa=mysql_fetch_row($result_etapa);
													echo "<td>$fila_etapa[0]</td>";
													
													//CURSO
													$busca_curso="SELECT CURSO FROM CURSOS WHERE COD_SENECA=$fila_lineas[10]";
													$result_curso=mysql_query($busca_curso,$conexion);
													$fila_curso=mysql_fetch_row($result_curso);
													echo "<td>$fila_curso[0]</td>";
													
													
													//MATERIA
													$busca_materia="SELECT MATERIA FROM MATERIAS_SENECA WHERE ID=$fila_lineas[9]";
													$result_materia=mysql_query($busca_materia,$conexion);
													$fila_materia=mysql_fetch_row($result_materia);
													echo "<td>$fila_materia[0]</td>";
													
													//GRUPO
													$busca_grupo="SELECT GRUPO FROM GRUPOS WHERE ID=$fila_lineas[11]";
													$result_grupo=mysql_query($busca_grupo,$conexion);
													$fila_grupo=mysql_fetch_row($result_grupo);
													echo "<td>$fila_grupo[0]</td>";
													
													//HORAS
													echo "<td>$fila_lineas[14]";
													
													//ORIGEN
													$busca_origen="SELECT ORIGEN FROM ORIGENES_HORAS WHERE ID=$fila_lineas[4]";
													$result_origen=mysql_query($busca_origen,$conexion);
													$fila_origen=mysql_fetch_row($result_origen);
													echo "<td>$fila_origen[0]</td>";
													
													//ACT. CENTRO
													$busca_origen="SELECT ACTIVIDAD_CENTRO FROM ACTIVIDADES_CENTRO WHERE ID=$fila_lineas[8]";
													$result_origen=mysql_query($busca_origen,$conexion);
													$fila_origen=mysql_fetch_row($result_origen);
													echo "<td>$fila_origen[0]</td>";
													
													//ACT. SÉNECA
													$busca_origen="SELECT ACTIVIDAD FROM ACTIVIDADES_SENECA WHERE ID=$fila_lineas[7]";
													$result_origen=mysql_query($busca_origen,$conexion);
													$fila_origen=mysql_fetch_row($result_origen);
													echo "<td>$fila_origen[0]</td>";
													
													echo "<td>$fila_lineas[16]";
													
													echo "</tr>";
												}
											
											
											?>
											
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- END EXAMPLE TABLE PORTLET-->
					
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