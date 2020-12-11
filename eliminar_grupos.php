<?php
	require("conexion.inc");
	require("estilos.inc");

	$vector=explode(",",$datos);
	$n=sizeof($vector);
	for($i=0;$i<$n;$i++)
	{
		$sql="update grupos set estado=2 where cod_grupo=$vector[$i]";
		//echo $sql;
		$resp=mysql_query($sql);
	}
	echo "<script language='Javascript'>
			alert('Los datos fueron eliminados.');
			location.href='navegador_grupos.php';
			</script>";

?>