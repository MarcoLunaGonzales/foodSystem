<?php
require("conexion.inc");
require("estilos.inc");

//recogemos variables
$codProducto=$_POST['codProducto'];
$nombreProducto=$_POST['material'];
$nombreProducto = strtoupper($nombreProducto);

$codLinea=$_POST['codLinea'];
$codGrupo=$_POST['cod_grupo'];
$codTipo=$_POST['cod_tipo'];
$observaciones=$_POST['observaciones'];
$codUnidad=$_POST['cod_unidad'];
$precioProducto=$_POST['precio_producto'];


$sql_inserta="update material_apoyo set descripcion_material='$nombreProducto', cod_linea_proveedor='$codLinea', 
cod_grupo='$codGrupo', cod_tipomaterial='$codTipo', observaciones='$observaciones', 
cod_unidad='$codUnidad'  where codigo_material='$codProducto'";
$resp_inserta=mysql_query($sql_inserta);

//insertamos los precios
$sqlDel="delete from precios where codigo_material=$codProducto";
$respDel=mysql_query($sqlDel);
$sqlInsertPrecio="insert into precios values($codProducto, 1,$precioProducto)";
$respInsertPrecio=mysql_query($sqlInsertPrecio);

if($resp_inserta){
		echo "<script language='Javascript'>
			alert('Los datos fueron guardados correctamente.');
			location.href='navegador_material.php';
			</script>";
}else{
	echo "<script language='Javascript'>
			alert('ERROR EN LA TRANSACCION. COMUNIQUESE CON EL ADMIN.');
			history.back();
			</script>";
}
	

?>