<?php
include "conexion.php";
include "header.php";


$total_concierto=0;


?>
<div class="page-container">
<?php
	include "sidebar.php";
	
	
if ($_POST[guardar]=="1"){
	$busca_horas="SELECT HORAS FROM HORAS_PROFESOR WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND DNI='$_POST[profesor]'";
	//echo $busca_horas;
	$result_horas=mysql_query($busca_horas,$conexion);
	if (mysql_num_rows($result_horas)>0){
		$update_horas="UPDATE HORAS_PROFESOR SET HORAS=$_POST[horas] WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND DNI='$_POST[profesor]'";
		mysql_query($update_horas,$conexion);
	}else{
		$inserta_horas="INSERT INTO HORAS_PROFESOR (PGE, CENTRO, DNI, HORAS) VALUES ($pge, '$_SESSION[centro]', '$_POST[profesor]', $_POST[horas])";
		//echo $inserta_horas;
		mysql_query($inserta_horas,$conexion);
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
                                <a href="informes.php">Informes</a>
                                <i class="fa fa-circle"></i>
                            </li>
							<li>
                                <span>Listado Concierto SAFA</span>
                            </li>
                        </ul>
                        
                    </div>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"> Listado Concierto SAFA
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
                                        <span class="caption-subject bold uppercase">Listado Concierto SAFA</span>
                                    </div>
                                    <div class="tools"> </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-striped table-bordered table-hover" id="sample_1">
                                        <thead>
                                            <tr>
                                                <th> AÑO </th>
                                                <th> CENTRO </th>
                                                <th> NIVEL </th>
												<th> ORIGEN </th>
												<th> HORAS </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
											<?php
					$busca_centros_safa="SELECT CODIGO, CENTRO FROM CENTROS WHERE TITULAR=1 AND ACTIVO=1";
					$result_centros_safa=mysql_query($busca_centros_safa,$conexion);
					while ($fila_centros_safa=mysql_fetch_row($result_centros_safa)){
						$sql_niveles="SELECT * FROM NIVELES_EDUCATIVOS WHERE ACTIVO=1 ORDER BY ORDEN";
						$result_niveles=mysql_query($sql_niveles,$conexion);
						while ($fila_niveles=mysql_fetch_row($result_niveles)){
							
							//Miro si el centro tiene algún grupo en ese nivel, si no, no lo muestro
							
							$busca_cursos="SELECT COD_SENECA FROM CURSOS WHERE NIVEL=$fila_niveles[0]";
							$result_cursos=mysql_query($busca_cursos,$conexion);
							while ($fila_cursos=mysql_fetch_row($result_cursos)){
								$mostrar=1;
								$busca_grupos="SELECT GRUPOS FROM UNIDADES_CONCERTADAS WHERE CURSO=$fila_cursos[0] AND PGE=$pge AND CENTRO='$fila_centros_safa[0]'";
								//echo $busca_grupos;
								$result_grupos=mysql_query($busca_grupos,$conexion);
								while ($fila_grupos=mysql_fetch_row($result_grupos)){
									if ($fila_grupos[0]>0){
										$mostrar=1;
									}
								}
							}
							
							if ($mostrar==1){
							
							$busca_origenes="SELECT * FROM ORIGENES_HORAS WHERE ACTIVO=1";
							$result_origenes=mysql_query($busca_origenes,$conexion);
							while ($fila_origenes=mysql_fetch_row($result_origenes)){
								$horas_nivel=0;

								
								$busca_cursos="SELECT COD_SENECA FROM CURSOS WHERE NIVEL=$fila_niveles[0]";
								$result_cursos=mysql_query($busca_cursos,$conexion);
									while ($fila_cursos=mysql_fetch_row($result_cursos)){
										$busca_grupos="SELECT GRUPOS FROM UNIDADES_CONCERTADAS WHERE PGE=$pge AND CENTRO='$fila_centros_safa[0]' AND CURSO=$fila_cursos[0]";
										$result_grupos=mysql_query($busca_grupos,$conexion);
										$fila_grupos=mysql_fetch_row($result_grupos);
										if ($fila_origenes[3]=="CO"){
											$horas_nivel=$horas_nivel+$fila_grupos[0]*$fila_niveles[5];
										}elseif($fila_origenes[3]=="AP"){
											
											if ($fila_niveles[0]==7 OR $fila_niveles[0]==8){
												$horas_nivel=($fila_grupos[0]*$fila_niveles[6])/2; //Es un profesor por línea para la eso completa, se divide por 2 para que aparezca la mitad en eso1 y la otra en eso2
											}elseif ($fila_niveles[0]==1 OR $fila_niveles[0]==2 OR $fila_niveles[0]==3 OR $fila_niveles[0]==11){ //En bachillerato y ciclos, el AP es por grupo
												$horas_nivel=$horas_nivel+$fila_grupos[0]*$fila_niveles[6];
											}else{
												$horas_nivel=$fila_grupos[0]*$fila_niveles[6];
											}
											
											
											
											
											
											if ($fila_niveles[0]==5){
												//En Ed. Infantil, hay 9 horas para centros de una línea y 25 para el resto.
												if ($fila_grupos[0]==1){
													$horas_nivel=9;
												}elseif($fila_grupos[0]>1){
													$horas_nivel=25;
												}
											}
										
										}elseif($fila_origenes[3]=="OR"){
											$horas_nivel=$horas_nivel+$fila_grupos[0]*$fila_niveles[7];
										}elseif($fila_origenes[3]=="LEA"){
											$horas_nivel=$horas_nivel+$fila_grupos[0]*$fila_niveles[8];
										}
										
										

									}
									
									//Busco si tiene horas cargadas desde DC, al margen de las de concierto
										
										$busca_carga="SELECT HORAS FROM CARGA_HORARIA WHERE PGE=$pge AND CENTRO='$fila_centros_safa[0]' AND NIVEL_EDUCATIVO=$fila_niveles[0] AND ORIGEN=$fila_origenes[0]";
										$result_carga=mysql_query($busca_carga,$conexion);
										$fila_carga=mysql_fetch_row($result_carga);
										if ($fila_carga[0]>0){
											$horas_nivel=$horas_nivel+$fila_carga[0];
										}
									
									//Si las horas son de AP, miro si hay ajustes particulares para ciclos y FPB
									if($fila_origenes[3]=="AP"){
									$busca_ajuste="SELECT AJUSTE FROM AJUSTE_AP_CICLOS WHERE PGE=$pge AND NIVEL_EDUCATIVO=$fila_niveles[0] AND CENTRO='$fila_centros_safa[0]'";
									//echo $busca_ajuste;
									$result_ajuste=mysql_query($busca_ajuste,$conexion);
									if (mysql_num_rows($result_ajuste)>0){
										$fila_ajuste=mysql_fetch_row($result_ajuste);
										$horas_nivel=$horas_nivel+$fila_ajuste[0];
									}
									}
									

								
						if ($horas_nivel>0){
							echo "<tr>";
							echo "<td>$pge</td>";
							echo "<td>$fila_centros_safa[1]</td>";
							echo "<td>$fila_niveles[1]</td>";
							echo "<td>$fila_origenes[1]</td>";
							echo "<td>$horas_nivel</td>";
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