<?php
require("conexion.inc");
require("estilos.inc");

$sql="select cod_tipogasto, nombre_tipogasto from tipos_gasto order by 1 desc";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$num_filas=mysql_num_rows($resp);
if($num_filas==0)
{	$codigo=1000;
}
else
{	$codigo=$dat[0];
	$codigo++;
}

$sql_inserta=mysql_query("insert into tipos_gasto (cod_tipogasto, nombre_tipogasto, estado) 
values($codigo,'$nombre','1')");

echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_tiposgasto.php';
			</script>";
?>