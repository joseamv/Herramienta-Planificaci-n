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
                                <span>Informes</span>
                            </li>
                        </ul>
                        
                    </div>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"> Informes
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
								<div class="desc"> Sábana horaria </div>
                                    <div class="number">
                                        <span data-counter="counterup" data-value=""></span>
                                    </div>
                                    
                                </div>
                                <a class="more" href="./sabana.php"> Acceder
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
								<div class="desc"> Resumen Centro  </div>
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
                            <div class="dashboard-stat red">
                                <div class="visual">
                                    <i class="fa fa-book"></i>
                                </div>
                                <div class="details">
                                    <div class="desc"> Resumen Concierto SAFA </div>
									<div class="number">
                                        <span data-counter="counterup" data-value=""></span> </div>
                                    
                                </div>
                                <a class="more" href="./resumen_concierto_safa.php"> Acceder
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
                                    <div class="desc"> Listado profesorado </div>
                                </div>
                                <a class="more" href="./listado_profes.php"> Acceder
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
								<div class="desc"> Listado cursos y grupos </div>
                                    <div class="number">
                                        <span data-counter="counterup" data-value=""></span>
                                    </div>
                                    
                                </div>
                                <a class="more" href="./listado_grupos.php"> Acceder
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
                                    <div class="desc"> Listado Concierto SAFA </div>
									<div class="number">
                                        <span data-counter="counterup" data-value=""></span> </div>
                                    
                                </div>
                                <a class="more" href="./concierto_safa.php"> Acceder
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
								<div class="desc"> Resumen Concierto FLAC </div>
                                    <div class="number">
                                        <span data-counter="counterup" data-value=""></span>
                                    </div>
                                    
                                </div>
                                <a class="more" href="resumen_concierto_flac.php"> Acceder
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
                                    <div class="desc"> Tutores </div>
									<div class="number"> 
                                        <span data-counter="counterup" data-value=""></span> </div>
                                    
                                </div>
                                <a class="more" href="./informe_tutores.php"> Acceder
                                    <i class="m-icon-swapright m-icon-white"></i>
                                </a>
                            </div>
                        </div>
                        
                    </div>
                    <div class="clearfix"></div>
					 
					<div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="dashboard-stat grey-steel">
                                <div class="visual">
                                    <i class="fa fa-calculator"></i>
                                </div>
                                <div class="details">
								<div class="desc"> Liberaciones </div>
                                    <div class="number">
                                        <span data-counter="counterup" data-value=""></span>
                                    </div>
                                    
                                </div>
                                <a class="more" href="./informe_liberaciones.php"> Acceder
                                    <i class="m-icon-swapright m-icon-white"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="dashboard-stat yellow-soft">
                                <div class="visual">
                                    <i class="fa fa-external-link"></i>
                                </div>
                                <div class="details">
                                    <div class="desc"> Evolución Jornada </div>
									<div class="number">
                                        <span data-counter="counterup" data-value=""></span> </div>
                                    
                                </div>
                                <a class="more" href="./informe_evolucion.php"> Acceder
                                    <i class="m-icon-swapright m-icon-white"></i>
                                </a>
                            </div>
                        </div>
						 <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="dashboard-stat red-soft">
                                <div class="visual">
                                    <i class="fa fa-link"></i>
                                </div>
                                <div class="details">
                                    <div class="desc"> Consumo por centro </div>
									<div class="number">
                                        <span data-counter="counterup" data-value=""></span> </div>
                                    
                                </div>
                                <a class="more" href="./informe_consumo.php"> Acceder
                                    <i class="m-icon-swapright m-icon-white"></i>
                                </a>
                            </div>
                        </div>
						
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="dashboard-stat blue-soft">
                                <div class="visual">
                                    <i class="fa fa-"></i>
                                </div>
                                <div class="details">
                                    <div class="desc"> Consumo por profesor, origen y nivel </div>
									<div class="number">
                                        <span data-counter="counterup" data-value=""></span> </div>
                                    
                                </div>
                                <a class="more" href="./informe_origen_nivel.php"> Acceder
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