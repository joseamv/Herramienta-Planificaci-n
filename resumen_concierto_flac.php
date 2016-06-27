<?php
include "conexion.php";
include "header.php";


$total_concierto=0;
$total_autorizado=0;
$total_consumido=0;


?>
<div class="page-container">
<?php
	include "sidebar.php";
	
	
	
	
?>
            <!-- BEGIN CONTENT -->
			<?php 
					
						if ($_SESSION[permiso]==1){
					?>
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
                                <span>Resumen Horario FLAC</span>
                            </li>
                        </ul>
                        
                    </div>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"> Resumen Horario FLAC
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
                                        <span class="caption-subject bold uppercase">Horas consumidas / Consumo autorizado</span>
                                    </div>
                                    <div class="tools"> </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-striped table-bordered table-hover" id="sample_1">
                                        <thead>
                                            <tr>
                                                <th> ID </th>
                                                <th> NIVEL </th>
                                                <?php
													$busca_origenes="SELECT * FROM ORIGENES_HORAS WHERE ACTIVO=1";
													$result_origenes=mysql_query($busca_origenes,$conexion);
													while ($fila_origenes=mysql_fetch_row($result_origenes)){
														echo "<th>$fila_origenes[3]</th>";
													}
													
												
												?>
												
												<th> TOTAL </th>
											</tr>
                                        </thead>
                                        <tbody>
                                            
											<?php
						$sql_niveles="SELECT * FROM NIVELES_EDUCATIVOS WHERE ACTIVO=1 ORDER BY ORDEN";
						$result_niveles=mysql_query($sql_niveles,$conexion);
						while ($fila_niveles=mysql_fetch_row($result_niveles)){
							
							//Miro si el centro tiene algún grupo en ese nivel, si no, no lo muestro
							$mostrar=0;
							$busca_cursos="SELECT COD_SENECA FROM CURSOS WHERE NIVEL=$fila_niveles[0]";
							$result_cursos=mysql_query($busca_cursos,$conexion);
							while ($fila_cursos=mysql_fetch_row($result_cursos)){
								$busca_grupos="SELECT GRUPOS FROM UNIDADES_CONCERTADAS WHERE CURSO=$fila_cursos[0] AND (CENTRO='FLAC02' OR CENTRO='29004225' OR CENTRO='29004705') AND PGE=$pge";
								//echo $busca_grupos;
								$result_grupos=mysql_query($busca_grupos,$conexion);
								while ($fila_grupos=mysql_fetch_row($result_grupos)){
									if ($fila_grupos[0]>0){
										$mostrar=1;
									}
								}
							}
							
							if ($mostrar==1){
								
								$total_fila_autorizado=0;
								$total_fila_consumido=0;
							
							echo "<tr>";
							echo "<td>$fila_niveles[4]</td>"; 
							echo "<td>$fila_niveles[1]</td>";
							$busca_origenes="SELECT * FROM ORIGENES_HORAS WHERE ACTIVO=1";
							$result_origenes=mysql_query($busca_origenes,$conexion);
							$i=0;
							while ($fila_origenes=mysql_fetch_row($result_origenes)){
								
								$horas_nivel=0;
								$horas_consumidas_nivel=0;
								
								$busca_cursos="SELECT COD_SENECA FROM CURSOS WHERE NIVEL=$fila_niveles[0]";
								$result_cursos=mysql_query($busca_cursos,$conexion);
									while ($fila_cursos=mysql_fetch_row($result_cursos)){
										$busca_grupos="SELECT GRUPOS FROM UNIDADES_CONCERTADAS WHERE PGE=$pge AND (CENTRO='FLAC02' OR CENTRO='29004225' OR CENTRO='29004705') AND CURSO=$fila_cursos[0]";
										//echo $busca_grupos;
										$result_grupos=mysql_query($busca_grupos,$conexion);
										while ($fila_grupos=mysql_fetch_row($result_grupos)){
										
										if ($fila_origenes[3]=="CO"){
											$horas_nivel=$horas_nivel+$fila_grupos[0]*$fila_niveles[5];
										}elseif($fila_origenes[3]=="AP"){
											
											
										
										}elseif($fila_origenes[3]=="OR"){
											$horas_nivel=$horas_nivel+$fila_grupos[0]*$fila_niveles[7];
										}elseif($fila_origenes[3]=="LEA"){
											$horas_nivel=$horas_nivel+$fila_grupos[0]*$fila_niveles[8];
										}
										}
										

									}
									
																			
										$busca_horas_consumidas="SELECT HORAS_CONSUMIDAS FROM HORARIO WHERE PGE=$pge AND (CENTRO='FLAC02' OR CENTRO='29004225' OR CENTRO='29004705') AND NIVEL_EDUCATIVO=$fila_niveles[0] AND ORIGEN=$fila_origenes[0]";								
										
										$result_horas_consumidas=mysql_query($busca_horas_consumidas,$conexion);
										while ($fila_horas_consumidas=mysql_fetch_row($result_horas_consumidas)){
										
										
										$horas_consumidas_nivel=$horas_consumidas_nivel+$fila_horas_consumidas[0];
										}
									
									
									
									//Busco si tiene horas cargadas desde DC, al margen de las de concierto
										
										$busca_carga="SELECT HORAS FROM CARGA_HORARIA WHERE PGE=$pge AND (CENTRO='FLAC02' OR CENTRO='29004225' OR CENTRO='29004705') AND NIVEL_EDUCATIVO=$fila_niveles[0] AND ORIGEN=$fila_origenes[0]";
										$result_carga=mysql_query($busca_carga,$conexion);
										$fila_carga=mysql_fetch_row($result_carga);
										if ($fila_carga[0]>0){
											$horas_nivel=$horas_nivel+$fila_carga[0];
										}
									

									
								
								if ($fila_origenes[3]=="AP"){
									if ($fila_niveles[0]==1){
										$horas_nivel=77;
									}elseif ($fila_niveles[0]==2){
										$horas_nivel=50.5;
									}elseif ($fila_niveles[0]==3){
										$horas_nivel=35;
									}elseif ($fila_niveles[0]==5){
										$horas_nivel=75;
									}elseif ($fila_niveles[0]==6){
										$horas_nivel=225;
									}elseif ($fila_niveles[0]==7){
										$horas_nivel=162.50;
									}elseif ($fila_niveles[0]==8){
										$horas_nivel=187.50;
									}elseif ($fila_niveles[0]==11){
										$horas_nivel=14;
									}
								}
								
								if ($fila_origenes[3]=="CE" AND $fila_niveles[0]==4){
									$horas_nivel=0;
								}
								
								if ($fila_origenes[3]=="SR" AND $fila_niveles[0]==1){
									$horas_nivel=75;
								}
								
								if ($fila_origenes[3]=="PR" AND $fila_niveles[0]==1){
									$horas_nivel=396;
								}
								
								$color="";
								if ($horas_consumidas_nivel>$horas_nivel){
									$color="red";
								}
								
								echo "<td><font color=$color>$horas_consumidas_nivel/$horas_nivel</font></td>";
								$total_autorizado=$total_autorizado+$horas_nivel;
								$total_consumido=$total_consumido+$horas_consumidas_nivel;
								$total_fila_autorizado=$total_fila_autorizado+$horas_nivel;

								$total_fila_consumido=$total_fila_consumido+$horas_consumidas_nivel;
								$array[$i]=$array[$i]+$horas_nivel;
								$consumidas[$i]=$consumidas[$i]+$horas_consumidas_nivel;
								$i=$i+1;
							}
							

							
							
							echo "<td><strong>$total_fila_consumido / $total_fila_autorizado</strong></td>";
							echo "</tr>";
							}

							
						}
						    echo "<tr><td>TOT</td><td><strong>TOTAL</strong></td>";
							$j=0;
							while ($j<$i){
								echo "<td><strong>$consumidas[$j] / $array[$j]</strong></td>";
								$j=$j+1;
							}
							
							echo "<td><strong>$total_consumido / $total_autorizado</strong></td>";				
											
											?>
											
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- END EXAMPLE TABLE PORTLET-->
					
						</div>
						
						

						
						
						
					</div>
                    
					<?php
						$porcentaje_horas=0;
						$porcentaje_horas=round($total_consumido*100/$total_autorizado,2);
					?>
						<div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon-cursor font-purple"></i>
                                        <span class="caption-subject font-purple bold uppercase">TOTAL HORAS</span>
                                    </div>
                                    <div class="actions">

                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="easy-pie-chart">
                                                <div class="number bounce" data-percent="<?php echo $total_autorizado; ?>">
                                                    <span><strong><?php echo $total_autorizado; ?></strong></span> </div>
                                                <a class="title" href="javascript:;"> Consumo autorizado
                                                </a>
                                            </div>
                                        </div>
                                        <div class="margin-bottom-10 visible-sm"> </div>
                                        <div class="col-md-4">
                                            <div class="easy-pie-chart">
                                                <div class="number bounce" data-percent="<? echo $porcentaje_horas; ?>">
                                                    <span><strong><? echo $total_consumido; ?></strong></span> </div>
                                                <a class="title" href="javascript:;"> Horas consumidas
                                                </a>
                                            </div>
                                        </div>
                                        <div class="margin-bottom-10 visible-sm"> </div>
                                        <div class="col-md-4">
                                            <div class="easy-pie-chart">
                                                <div class="number bounce" data-percent="<? echo $porcentaje_horas; ?>">
                                                    <span><strong><? echo $porcentaje_horas; ?></span>% </strong></div>
                                                <a class="title" href="javascript:;"> % Asignado
                                                    
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
					
					
					
                    <!-- END PAGE CONTENT-->
	

	
	
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
            <?php
						}
				//include "quick_sidebar.php";
			?>
        </div>
        <!-- END CONTAINER -->
		

<?php
	include "footer.php";
?>