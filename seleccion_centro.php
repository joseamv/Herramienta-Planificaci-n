<?php
include "conexion.php";
include "header.php";

if ($_POST[seleccionado]=="1"){
	$_SESSION[centro]=$_POST[centro];
	echo "<META HTTP-EQUIV='Refresh' CONTENT='0;URL=./index.php'>";
	exit;
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
                                <span>Selección de Centro</span>
                            </li>
                        </ul>
                        
                    </div>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"> Selección de Centro
                    </h3>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
					<!-- BEGIN PAGE CONTENT-->
					<div class="tab-content">
										<div class="form-group form-md-line-input has-info">
                                                
												<form name="seleccionar_centro" action='seleccion_centro.php' method='POST'>
												<select class="form-control" id="form_control_1" name="centro" onchange='javascript:seleccionar_centro.submit();'>
                                                    <option value=""></option>
													
<?php


if ($fila_planificacion[4]=='99999999'){
$sql_centro="SELECT CENTRO, CODIGO FROM CENTROS WHERE ACTIVO=1 ORDER BY CENTRO";
$result_centro=mysql_query($sql_centro, $conexion);
while ($fila_centro=mysql_fetch_row($result_centro)){
	echo "<option value=$fila_centro[1] $selected>$fila_centro[0]</option>";
}		
}elseif($fila_planificacion[4]=='11003001'){
	echo "<option value='11003001'>Jerez - Dr. Arruga</option>";
	echo "<option value='11701279'>Jerez - Andalucía</option>";
}elseif ($_SESSION[k_user]='rmartin@fundacionsafa.es'){
	echo "<option value='14000380'>Baena</option>";
	echo "<option value='14000471'>La Milagrosa</option>";
}

?>
                                                </select>
												<input type="hidden" name="seleccionado" value="1">
												</form>
                                                <label for="form_control_1">Selecciona el centro</label>
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