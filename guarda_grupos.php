<?php
require("conexion.inc");
require("estilos.inc");

$sql="select cod_grupo, nombre_grupo from grupos order by 1 desc";
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

$sql_inserta=mysql_query("insert into grupos (cod_grupo, nombre_grupo, estado) 
values($codigo,'$nombre_grupo','1')");

echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_grupos.php';
			</script>";
?>