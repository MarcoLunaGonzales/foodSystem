<?php
require("conexion.inc");
require("estilos_almacenes.inc");

$tipoGasto=$_POST["tipo_gasto"];
$fechaGasto=$_POST["fecha"];
$nombreGasto=$_POST["nombre_gasto"];
$montoGasto=$_POST["monto_gasto"];

$sql="SELECT cod_gasto FROM gastos ORDER BY cod_gasto DESC";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$num_filas=mysql_num_rows($resp);
if($num_filas==0)
{   $codigo=1;
}
else
{   $codigo=$dat[0];
	$codigo++;
}

$sql_inserta="INSERT INTO gastos (cod_gasto, descripcion_gasto, cod_tipogasto, estado, fecha_gasto, monto)
		values ('$codigo', '$nombreGasto', '$tipoGasto', '1', '$fechaGasto', '$montoGasto')";
$sql_inserta=mysql_query($sql_inserta);

echo "<script type='text/javascript' language='javascript'>";
echo "    alert('Los datos fueron insertados correctamente.');";
echo "    location.href='navegador_gastos.php';";
echo "</script>";

?>



