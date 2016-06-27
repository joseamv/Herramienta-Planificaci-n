<?php
include "conexion.php";
include "header.php";

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

$materias_totales=0;
$busca_materias="SELECT * FROM HORARIO_CENTRO WHERE CENTRO='$_SESSION[centro]' AND PGE=$pge";
$result_materias=mysql_query($busca_materias,$conexion);
while ($fila_materias=mysql_fetch_row($result_materias)){
	$materias_totales=$materias_totales+1;
}

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
                                <span>Mantenimiento de Tablas</span>
                            </li>
                        </ul>
                        
                    </div>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"> Mantenimiento de Tablas
                    </h3>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
                    <!-- BEGIN DASHBOARD STATS 1-->
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="dashboard-stat blue">
                                <div class="visual">
                                    <i class="fa fa-group"></i>
                                </div>
                                <div class="details">
								<div class="desc"> Mantenimiento de Profesorado </div>
                                    <div class="number">
                                        <span data-counter="counterup" data-value="<?php echo $num_profes; ?>">0</span>
                                    </div>
                                    
                                </div>
                                <a class="more" href="./mantenimiento_profes.php"> Acceder
                                    <i class="m-icon-swapright m-icon-white"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="dashboard-stat red">
                                <div class="visual">
                                    <i class="fa fa-book"></i>
                                </div>
                                <div class="details">
                                    <div class="desc"> Mantenimiento de Materias y Grupos </div>
									<div class="number">
                                        <span data-counter="counterup" data-value="<?php echo $materias_totales; ?>">0</span> </div>
                                    
                                </div>
                                <a class="more" href="./mantenimiento_materias.php"> Acceder
                                    <i class="m-icon-swapright m-icon-white"></i>
                                </a>
                            </div>
                        </div>
                        
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="dashboard-stat green">
                                <div class="visual">
                                    <i class="fa fa-list"></i>
                                </div>
                                <div class="details">
								<div class="desc">  Resumen Centro</div>
                                    <div class="number">
                                        <span data-counter="counterup" data-value=""></span>
                                    </div>
                                    
                                </div>
                                <a class="more" href="./resumen_centro.php"> Acceder
                                    <i class="m-icon-swapright m-icon-white"></i>
                                </a>
                            </div>
                        </div>
						
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="dashboard-stat yellow">
                                <div class="visual">
                                    <i class="fa fa-user"></i>
                                </div>
                                <div class="details">
                                    <div class="number"> 
                                        <span data-counter="counterup" data-value=""></span> </div>
                                    <div class="desc"> Usuarios </div>
                                </div>
                                <a class="more" href="./mantenimiento_tablas.php"> Acceder
                                    <i class="m-icon-swapright m-icon-white"></i>
                                </a>
                            </div>
                        </div>
                        
                    </div>
                    <div class="clearfix"></div>
					  <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="dashboard-stat red-pink">
                                <div class="visual">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                                <div class="details">
								<div class="desc"> Carga Horaria DC </div>
                                    <div class="number">
                                        <span data-counter="counterup" data-value=""></span>
                                    </div>
                                    
                                </div>
                                <a class="more" href="./carga_horaria.php"> Acceder
                                    <i class="m-icon-swapright m-icon-white"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="dashboard-stat purple-soft">
                                <div class="visual">
                                    <i class="fa fa-book"></i>
                                </div>
                                <div class="details">
                                    <div class="desc"> Gestión Concierto</div>
									<div class="number">
                                        <span data-counter="counterup" data-value=""></span> </div>
                                    
                                </div>
                                <a class="more" href="./gestion_concierto.php"> Acceder
                                    <i class="m-icon-swapright m-icon-white"></i>
                                </a>
                            </div>
                        </div>
                        
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="dashboard-stat yellow-gold">
                                <div class="visual">
                                    <i class="fa fa-shopping-cart"></i>
                                </div>
                                <div class="details">
								<div class="desc"> Gestión Materias </div>
                                    <div class="number">
                                        <span data-counter="counterup" data-value=""></span>
                                    </div>
                                    
                                </div>
                                <a class="more" href="./gestion_materias.php"> Acceder
                                    <i class="m-icon-swapright m-icon-white"></i>
                                </a>
                            </div>
                        </div>
						
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="dashboard-stat yellow-mint">
                                <div class="visual">
                                    <i class="fa fa-user"></i>
                                </div>
                                <div class="details">
                                    <div class="number"> 
                                        <span data-counter="counterup" data-value=""></span> </div>
                                    <div class="desc">  </div>
                                </div>
                                <a class="more" href="./mantenimiento_tablas.php"> Acceder
                                    <i class="m-icon-swapright m-icon-white"></i>
                                </a>
                            </div>
                        </div>
                        
                    </div>
                    <div class="clearfix"></div>
                    <!-- END DASHBOARD STATS 1-->

					         


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