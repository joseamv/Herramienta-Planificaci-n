<?php
include "conexion.php";
include "header.php";

if ($_SESSION[centro]==""){
	echo "<META HTTP-EQUIV='Refresh' CONTENT='0;URL=./seleccion_centro.php'>";
	exit;
}

//Para las estadísticas

//Busco el número de profes
if ($titular=='2'){
$sql_personal="SELECT PRIMERAPELLIDO, SEGUNDOAPELLIDO, NOMBRE, DNI, email FROM EMPLEADOS_LOYOLA WHERE nombrecentro='$_SESSION[centro]'";	
}else{
$sql_personal="SELECT DISTINCT EMPLEADOS.PRIMERAPELLIDO, EMPLEADOS.SEGUNDOAPELLIDO, EMPLEADOS.NOMBRE, EMPLEADOS.DNI, Userfundacionsafa.DNI, Userfundacionsafa.email, CENTROS.codigojunta, EMPLEmovimientos.fechabaja, EMPLEmovimientos.catprofesional
FROM EMPLEmovimientos INNER JOIN
                         EMPLEADOS ON EMPLEmovimientos.reftrabaja = EMPLEADOS.DNI INNER JOIN
                         Userfundacionsafa ON EMPLEADOS.DNI = Userfundacionsafa.DNI INNER JOIN
                         CENTROS ON EMPLEmovimientos.refcentro = CENTROS.ID
WHERE (EMPLEmovimientos.bajarrll=0) and CENTROS.codigojunta=$_SESSION[centro] AND EMPLEmovimientos.catprofesional='PROFESOR' ORDER BY EMPLEADOS.PRIMERAPELLIDO";
}
$result_personal=mssql_query($sql_personal);
$num_profes=mssql_num_rows($result_personal);

//Busco el número total de grupos
$num_grupos=0;
$busca_grupos="SELECT SUM(GRUPOS) FROM GRUPOS_CENTRO WHERE PGE=$pge AND CENTRO='$_SESSION[centro]'";
$result_grupos=mysql_query($busca_grupos,$conexion);
$fila_grupos=mysql_fetch_row($result_grupos);
$num_grupos=$fila_grupos[0];



$porcentaje_horas=0;
$suma_horas_totales=0;
$suma_horas_ocupadas=0;


						$sql_niveles="SELECT * FROM NIVELES_EDUCATIVOS WHERE ACTIVO=1 ORDER BY ORDEN";
						$result_niveles=mysql_query($sql_niveles,$conexion);
						while ($fila_niveles=mysql_fetch_row($result_niveles)){

								$total_fila_autorizado=0;
								$total_fila_consumido=0;
							
							
							if ($titular==2){
														$busca_origenes="SELECT * FROM ORIGENES_HORAS WHERE ACTIVO=1";
													}else{
														$busca_origenes="SELECT * FROM ORIGENES_HORAS WHERE ACTIVO=1 AND ABREVIATURA!='SR' AND ABREVIATURA!='PR'";
													}
							$result_origenes=mysql_query($busca_origenes,$conexion);
							$i=0;
							while ($fila_origenes=mysql_fetch_row($result_origenes)){
								
								$horas_nivel=0;
								$horas_consumidas_nivel=0;
								
								$busca_cursos="SELECT COD_SENECA FROM CURSOS WHERE NIVEL=$fila_niveles[0]";
								$result_cursos=mysql_query($busca_cursos,$conexion);
									while ($fila_cursos=mysql_fetch_row($result_cursos)){
										$busca_grupos="SELECT GRUPOS FROM UNIDADES_CONCERTADAS WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND CURSO=$fila_cursos[0]";
										$result_grupos=mysql_query($busca_grupos,$conexion);
										$fila_grupos=mysql_fetch_row($result_grupos);
										if ($fila_origenes[3]=="CO"){
											if ($fila_niveles[0]==1 AND ($_SESSION[centro]=='FLAC02' OR $_SESSION[centro]=='29004225')){
												$horas_nivel=$horas_nivel+$fila_grupos[0]*37;
											}else{
												$horas_nivel=$horas_nivel+$fila_grupos[0]*$fila_niveles[5];
											}
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
									
																			
										$busca_horas_consumidas="SELECT HORAS_CONSUMIDAS FROM HORARIO WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND NIVEL_EDUCATIVO=$fila_niveles[0] AND ORIGEN=$fila_origenes[0]";								
										
										$result_horas_consumidas=mysql_query($busca_horas_consumidas,$conexion);
										while ($fila_horas_consumidas=mysql_fetch_row($result_horas_consumidas)){
										
										
										$horas_consumidas_nivel=$horas_consumidas_nivel+$fila_horas_consumidas[0];
										}
									
									
									
									//Busco si tiene horas cargadas desde DC, al margen de las de concierto
										
										$busca_carga="SELECT HORAS FROM CARGA_HORARIA WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND NIVEL_EDUCATIVO=$fila_niveles[0] AND ORIGEN=$fila_origenes[0]";
										$result_carga=mysql_query($busca_carga,$conexion);
										$fila_carga=mysql_fetch_row($result_carga);
										if ($fila_carga[0]!=0){
											$horas_nivel=$horas_nivel+$fila_carga[0];
										}
									
									//Pongo horas de SR para FLAC, todas en bachillerato
									if($fila_origenes[3]=="SR" AND $fila_niveles[0]==1){
										$horas_nivel=25;
									}
									
									//Si las horas son de AP, miro si hay ajustes particulares para ciclos y FPB
									if($fila_origenes[3]=="AP"){
									$busca_ajuste="SELECT AJUSTE FROM AJUSTE_AP_CICLOS WHERE PGE=$pge AND NIVEL_EDUCATIVO=$fila_niveles[0] AND CENTRO='$_SESSION[centro]'";
									//echo $busca_ajuste;
									$result_ajuste=mysql_query($busca_ajuste,$conexion);
									if (mysql_num_rows($result_ajuste)>0){
										$fila_ajuste=mysql_fetch_row($result_ajuste);
										$horas_nivel=$horas_nivel+$fila_ajuste[0];
									}
									}
									
								$color="";
								if ($horas_consumidas_nivel>$horas_nivel){
									$color="red";
								}
								
								
								$total_autorizado=$total_autorizado+$horas_nivel;
							}
							
						}




$busca_horas_ocupadas="SELECT SUM(HORAS_CONSUMIDAS) FROM HORARIO WHERE CENTRO='$_SESSION[centro]' AND PGE=$pge";
$result_horas_ocupadas=mysql_query($busca_horas_ocupadas,$conexion);
$fila_horas_ocupadas=mysql_fetch_row($result_horas_ocupadas);
$suma_horas_ocupadas=$fila_horas_ocupadas[0];


$porcentaje_horas=round($suma_horas_ocupadas*100/$total_autorizado,2);
echo $suma_horas_ocupadas."|".$suma_horas_totales.$porcentaje_horas;


$porcentaje_materias=0;
$materias_completas=0;
$materias_totales=0;

$busca_materias="SELECT * FROM HORARIO_CENTRO WHERE CENTRO='$_SESSION[centro]' AND PGE=$pge";
$result_materias=mysql_query($busca_materias,$conexion);
if (mysql_num_rows($result_materias)<500){
while ($fila_materias=mysql_fetch_row($result_materias)){
	$materias_totales=$materias_totales+1;
	$busca_horario="SELECT HORAS_CONSUMIDAS FROM HORARIO WHERE CENTRO='$_SESSION[centro]' AND PGE=$pge AND CURSO=$fila_materias[3] AND CODIGO_MATERIA=$fila_materias[4]";
	$result_horario=mysql_query($busca_horario,$conexion);
	if (mysql_num_rows($result_horario)>0){
		$materias_completas=$materias_completas+1;
	}
}
$porcentaje_materias=round($materias_completas*100/$materias_totales,2);
}else{
	$porcentaje_materias="N/D";
}



$porcentaje_profes=0;
$profes_completos=0;

if ($titular=='2'){
$sql_personal="SELECT PRIMERAPELLIDO, SEGUNDOAPELLIDO, NOMBRE, DNI, email FROM EMPLEADOS_LOYOLA WHERE nombrecentro='$_SESSION[centro]'";	
}else{
$sql_personal="SELECT DISTINCT EMPLEADOS.PRIMERAPELLIDO, EMPLEADOS.SEGUNDOAPELLIDO, EMPLEADOS.NOMBRE, EMPLEADOS.DNI, Userfundacionsafa.DNI, Userfundacionsafa.email, CENTROS.codigojunta, EMPLEmovimientos.fechabaja, EMPLEmovimientos.catprofesional
FROM EMPLEmovimientos INNER JOIN
                         EMPLEADOS ON EMPLEmovimientos.reftrabaja = EMPLEADOS.DNI INNER JOIN
                         Userfundacionsafa ON EMPLEADOS.DNI = Userfundacionsafa.DNI INNER JOIN
                         CENTROS ON EMPLEmovimientos.refcentro = CENTROS.ID
WHERE (EMPLEmovimientos.bajarrll=0) and CENTROS.codigojunta=$_SESSION[centro] AND EMPLEmovimientos.catprofesional='PROFESOR' ORDER BY EMPLEADOS.PRIMERAPELLIDO";
}
$result_personal=mssql_query($sql_personal);
while ($fila_personal=mssql_fetch_array($result_personal)){
	$busca_horas="SELECT HORAS, EVENTUALES FROM HORAS_PROFESOR WHERE DNI='$fila_personal[3]' AND PGE=$pge AND CENTRO='$_SESSION[centro]'";
	$result_horas=mysql_query($busca_horas,$conexion);
	$fila_horas=mysql_fetch_row($result_horas);
	$horas_profe=$fila_horas[0]+$fila_horas[1];
	
	$busca_horario="SELECT HORAS_CONSUMIDAS FROM HORARIO WHERE PGE=$pge AND CENTRO='$_SESSION[centro]' AND NIF='$fila_personal[3]'";
	$result_horario=mysql_query($busca_horario,$conexion);
	$horas_asignadas=0;
	while ($fila_horario=mysql_fetch_row($result_horario)){
		$horas_asignadas=$horas_asignadas+$fila_horario[0];
	}
	if ($horas_asignadas==$horas_profe AND $horas_profe>0){
		$profes_completos=$profes_completos+1;
	}
}


$porcentaje_profes=round($profes_completos*100/$num_profes);


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

                        </ul>
                        
                    </div>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"> Panel de Control
                        <small>Perfil dirección colegios</small>
                    </h3>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
                    <!-- BEGIN DASHBOARD STATS 1-->
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="dashboard-stat blue">
                                <div class="visual">
                                    <i class="fa fa-comments"></i>
                                </div>
                                <div class="details">
								<div class="desc"> Asignación por Profesorado </div>
                                    <div class="number">
                                        <span data-counter="counterup" data-value="<?php echo $num_profes; ?>">0</span>
                                    </div>
                                    
                                </div>
                                <a class="more" href="./asignacion_profes.php"> Acceder
                                    <i class="m-icon-swapright m-icon-white"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="dashboard-stat red">
                                <div class="visual">
                                    <i class="fa fa-bar-chart-o"></i>
                                </div>
                                <div class="details">
                                    <div class="desc"> Asignación por Grupos </div>
									<div class="number">
                                        <span data-counter="counterup" data-value="<?php echo $num_grupos; ?>">0</span> </div>
                                    
                                </div>
                                <a class="more" href="./asignacion_materias.php"> Acceder
                                    <i class="m-icon-swapright m-icon-white"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="dashboard-stat purple">
                                <div class="visual">
                                    <i class="fa fa-globe"></i>
                                </div>
                                <div class="details">
                                    <div class="number"> 
                                        <span data-counter="counterup" data-value=""></span> </div>
                                    <div class="desc"> Mantenimiento tablas </div>
                                </div>
                                <a class="more" href="./mantenimiento_tablas.php"> Acceder
                                    <i class="m-icon-swapright m-icon-white"></i>
                                </a>
                            </div>
                        </div>
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="dashboard-stat green">
                                <div class="visual">
                                    <i class="fa fa-shopping-cart"></i>
                                </div>
                                <div class="details">
								<div class="desc"> Informes </div>
                                    <div class="number">
                                        <span data-counter="counterup" data-value=""></span>
                                    </div>
                                    
                                </div>
                                <a class="more" href="informes.php"> Acceder
                                    <i class="m-icon-swapright m-icon-white"></i>
                                </a>
                            </div>
                        </div>
                        
                    </div>
                    <div class="clearfix"></div>
                    <!-- END DASHBOARD STATS 1-->

					                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon-cursor font-purple"></i>
                                        <span class="caption-subject font-purple bold uppercase">Estadísticas</span>
                                    </div>
                                    <div class="actions">

                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="easy-pie-chart">
                                                <div class="number transactions" data-percent="<?php echo $porcentaje_horas; ?>">
                                                    <span><?php echo $porcentaje_horas; ?></span>% </div>
                                                <a class="title" href="javascript:;"> Horas
                                                </a>
                                            </div>
                                        </div>
                                        <div class="margin-bottom-10 visible-sm"> </div>
                                        <div class="col-md-4">
                                            <div class="easy-pie-chart">
                                                <div class="number visits" data-percent="<? echo $porcentaje_materias; ?>">
                                                    <span><? echo $porcentaje_materias; ?></span>% </div>
                                                <a class="title" href="javascript:;"> Materias
                                                </a>
                                            </div>
                                        </div>
                                        <div class="margin-bottom-10 visible-sm"> </div>
                                        <div class="col-md-4">
                                            <div class="easy-pie-chart">
                                                <div class="number bounce" data-percent="<? echo $porcentaje_profes; ?>">
                                                    <span><? echo $porcentaje_profes; ?></span>% </div>
                                                <a class="title" href="javascript:;"> Profesorado
                                                    
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
					
                    
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon-share font-blue"></i>
                                        <span class="caption-subject font-blue bold uppercase">Actividad Reciente</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="scroller" style="height: 300px;" data-always-visible="1" data-rail-visible="0">
                                        <ul class="feeds">
											<?php
												$busca_ultimas="SELECT * FROM HORARIO WHERE CENTRO='$_SESSION[centro]' AND PGE=$pge ORDER BY ID DESC LIMIT 10";
												$result_ultimas=mysql_query($busca_ultimas,$conexion);
												while ($fila_ultimas=mysql_fetch_row($result_ultimas)){
													if ($titular=='2'){
														$busca_profe="SELECT PRIMERAPELLIDO, SEGUNDOAPELLIDO, NOMBRE, DNI FROM EMPLEADOS_LOYOLA WHERE DNI='$fila_ultimas[15]'";
													}else{
														$busca_profe="SELECT PRIMERAPELLIDO, SEGUNDOAPELLIDO, NOMBRE, DNI FROM EMPLEADOS WHERE DNI='$fila_ultimas[15]'";
													}
													$result_profe=mssql_query($busca_profe);
													$fila_profe=mssql_fetch_array($result_profe);
													$nombre_completo=$fila_profe[0]." ".$fila_profe[1].", ".$fila_profe[2];
													$nombre_completo=iconv("WINDOWS-1252", "UTF-8", $nombre_completo);
													
													$busca_nivel="SELECT NIVEL_EDUCATIVO FROM NIVELES_EDUCATIVOS WHERE ID=$fila_ultimas[5]";
													$result_nivel=mysql_query($busca_nivel,$conexion);
													$fila_nivel=mysql_fetch_row($result_nivel);
											?>
										   <li>
                                                <div class="col1">
                                                    <div class="cont">
                                                        <div class="cont-col1">
                                                            <div class="label label-sm label-info">
                                                                <i class="fa fa-check"></i>
                                                            </div>
                                                        </div>
                                                        <div class="cont-col2">
                                                            <div class="desc"> Asignación de <?php echo $fila_ultimas[14]; ?> horas para <?php echo $nombre_completo; ?>, nivel <?php echo $fila_nivel[0]; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col2">
                                                    <div class="date">  </div>
                                                </div>
                                            </li>
											<?php
												}
											?>
                                           
                                            
                                            
                                            
                                        </ul>
                                    </div>
                                    <div class="scroller-footer">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="portlet light tasks-widget bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon-share font-green-haze hide"></i>
                                        <span class="caption-subject font-green bold uppercase">Tareas Pendientes</span>
                                    </div>

                                </div>
                                <div class="portlet-body">
                                    <div class="task-content">
                                        <div class="scroller" style="height: 312px;" data-always-visible="1" data-rail-visible1="1">
                                            <!-- START TASK LIST -->
                                            <ul class="task-list">
                                                <li>
                                                    <div class="task-checkbox">
                                                        <input type="checkbox" class="liChild" value="" /> </div>
                                                    <div class="task-title">
                                                        <span class="task-title-sp">  </span>
                                                        <span class="task-bell">
                                                            <i class="fa fa-bell-o"></i>
                                                        </span>
                                                    </div>
                                                    <div class="task-config">
                                                        <div class="task-config-btn btn-group">
                                                            <a class="btn btn-sm default" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                                                <i class="fa fa-cog"></i>
                                                                <i class="fa fa-angle-down"></i>
                                                            </a>
                                                            <ul class="dropdown-menu pull-right">
                                                                <li>
                                                                    <a href="javascript:;">
                                                                        <i class="fa fa-check"></i> Completada </a>
                                                                </li>
                                                                <li>
                                                                    <a href="javascript:;">
                                                                        <i class="fa fa-pencil"></i> Retrasar </a>
                                                                </li>
                                                                <li>
                                                                    <a href="javascript:;">
                                                                        <i class="fa fa-trash-o"></i> Borrar </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </li>
                                                  <li>
                                                    <div class="task-checkbox">
                                                        <input type="checkbox" class="liChild" value="" /> </div>
                                                    <div class="task-title">
                                                        <span class="task-title-sp"> </span>
                                                        <span class="task-bell">
                                                            <i class="fa fa-bell-o"></i>
                                                        </span>
                                                    </div>
                                                    <div class="task-config">
                                                        <div class="task-config-btn btn-group">
                                                            <a class="btn btn-sm default" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                                                <i class="fa fa-cog"></i>
                                                                <i class="fa fa-angle-down"></i>
                                                            </a>
                                                            <ul class="dropdown-menu pull-right">
                                                                <li>
                                                                    <a href="javascript:;">
                                                                        <i class="fa fa-check"></i> Completada </a>
                                                                </li>
                                                                <li>
                                                                    <a href="javascript:;">
                                                                        <i class="fa fa-pencil"></i> Retrasar </a>
                                                                </li>
                                                                <li>
                                                                    <a href="javascript:;">
                                                                        <i class="fa fa-trash-o"></i> Borrar </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                            <!-- END START TASK LIST -->
                                        </div>
                                    </div>
                                    <div class="task-footer">
                                        <div class="btn-arrow-link pull-right">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


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