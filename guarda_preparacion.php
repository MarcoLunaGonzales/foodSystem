<?php
require("conexion.inc");
require("estilos.inc");

//recogemos variables
$nombreProducto=$_POST['material'];
$nombreProducto = strtoupper($nombreProducto);

$codLinea=$_POST['codLinea'];
$codGrupo=$_POST['cod_grupo'];
$codTipo=$_POST['cod_tipo'];
$observaciones=$_POST['observaciones'];
$codUnidad=$_POST['cod_unidad'];
$codTamanio=$_POST['cod_tamanio'];


$cantidad_material=$_POST["cantidad_material"];

$precioProducto=$_POST['precio_producto'];


$fechahora=date("dmy.Hi");
$archivoName=$fechahora.$_FILES['archivo']['name'];
if ($_FILES['archivo']["error"] > 0){
	echo "Error: " . $_FILES['archivo']['error'] . "<br>";
}
move_uploaded_file($_FILES['archivo']['tmp_name'], "imagenesprod/".$archivoName);	


$sql="select IFNULL((max(codigo_material)+1),1) as codigo from material_apoyo m";
$resp=mysql_query($sql);
$codigo=mysql_result($resp,0,0);

$sql_inserta="insert into material_apoyo(codigo_material, descripcion_material, estado, cod_linea_proveedor, cod_grupo, cod_tipomaterial,
cantidad_presentacion, observaciones, imagen, cod_unidad, cod_tamanio) values ($codigo,'$nombreProducto','1','$codLinea','$codGrupo','$codTipo','1','$observaciones','$archivoName','$codUnidad','$codTamanio')";
//echo $sql_inserta;
$resp_inserta=mysql_query($sql_inserta);

for($i=1;$i<=$cantidad_material;$i++)
{   	
	$codInsumo=$_POST["material$i"];
	$cantidadInsumo=$_POST["cantidad_unitaria$i"];
	
	$sqlDet="insert into material_insumos (cod_material, cod_insumo, cantidad_insumo) values ('$codigo','$codInsumo','$cantidadInsumo')";
	$respDet=mysql_query($sqlDet);
}


//insertamos los precios
$sqlDel="delete from precios where codigo_material=$codigo";
$respDel=mysql_query($sqlDel);
$sqlInsertPrecio="insert into precios values($codigo, 1,$precioProducto)";
$respInsertPrecio=mysql_query($sqlInsertPrecio);


if($resp_inserta){
		echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_recetas.php';
			</script>";
}else{
	echo "<script language='Javascript'>
			alert('ERROR EN LA TRANSACCION. COMUNIQUESE CON EL ADMIN.');
			history.back();
			</script>";
}
	

?>