<?php
require("conexion.inc");
require("estilos.inc");
$codigo=$_POST['codigo'];
$nombreGrupo=$_POST['nombre_grupo'];
$estado=$_POST['estado'];

$sql_upd=mysql_query("update grupos set nombre_grupo='$nombreGrupo', 
estado='$estado' where cod_grupo='$codigo'");

echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='navegador_grupos.php';
			</script>";
?>