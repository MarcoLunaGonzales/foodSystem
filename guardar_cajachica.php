<?php
require("conexion.inc");
require("estilos_almacenes.inc");

$fechaCajaChica=$_POST["fecha"];
$montoCajaChica=$_POST["monto_gasto"];

$sql_inserta="INSERT INTO cajachica_inicio(fecha_cajachica, monto)
		values ('$fechaCajaChica', '$montoCajaChica')";
$sql_inserta=mysql_query($sql_inserta);

echo "<script type='text/javascript' language='javascript'>";
echo "    alert('Los datos fueron insertados correctamente.');";
echo "    location.href='navegador_aperturacajachica.php';";
echo "</script>";

?>



