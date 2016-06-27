<?php
session_start();
//Si venimos de elegir centros de la pantalla anterior
$viene_de_form=$_POST[viene_de_form];



$email=$_SESSION['k_email'];

if ($email==""){
	//echo "<META HTTP-EQUIV='Refresh' CONTENT='0;URL=http://www.fundacionsafa.es'>";
	echo "hola";
}

echo $email;







?>

