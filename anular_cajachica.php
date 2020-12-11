<?php

require("conexion.inc");
require('function_formatofecha.php');
require('funciones.php');

$fechaCajaChica=$_GET['codigo_registro'];

//anulamos el registro

$sql="delete from cajachica_inicio where fecha_cajachica='$fechaCajaChica'";
//echo $sql;
$resp=mysql_query($sql);

echo "<script>
	alert('Se elimino el registro.');
	location.href='navegador_aperturacajachica.php';
</script>";
?>

