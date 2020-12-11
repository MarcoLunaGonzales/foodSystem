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

$cantidad_material=$_POST["cantidad_material"];

$cantidad_material2=$_POST["cantidad_material2"];//PARA EL SEGUNDO DETALLE

$precioProducto=$_POST['precio_producto'];

$sql_inserta="update material_apoyo set descripcion_material='$nombreProducto', cod_linea_proveedor='$codLinea', 
cod_grupo='$codGrupo', cod_tipomaterial='$codTipo', observaciones='$observaciones', 
cod_unidad='$codUnidad'  where codigo_material='$codProducto'";
$resp_inserta=mysql_query($sql_inserta);

$sqlDeleteDet="delete from material_insumos where cod_material='$codProducto'";
$respDeleteDet=mysql_query($sqlDeleteDet);
for($i=1;$i<=$cantidad_material;$i++)
{   	
	$codInsumo=$_POST["material$i"];
	$cantidadInsumo=$_POST["cantidad_unitaria$i"];
	if($codInsumo!=0){
		$sqlDet="insert into material_insumos (cod_material, cod_insumo, cantidad_insumo) values ('$codProducto','$codInsumo','$cantidadInsumo')";
		$respDet=mysql_query($sqlDet);
	}
}

for($i=1;$i<=$cantidad_material2;$i++)
{   	
	$codGrupo=$_POST["grupo$i"];
	$codTamanio=$_POST["tamanio$i"];
	
	$sqlDet="insert into material_comodines (cod_material, cod_grupo, cod_tamanio) values ('$codProducto','$codGrupo','$codTamanio')";
	$respDet=mysql_query($sqlDet);
}


//insertamos los precios
$sqlDel="delete from precios where codigo_material=$codProducto";
$respDel=mysql_query($sqlDel);
$sqlInsertPrecio="insert into precios values($codProducto, 1,$precioProducto)";
$respInsertPrecio=mysql_query($sqlInsertPrecio);

if($resp_inserta){
		echo "<script language='Javascript'>
			alert('Los datos fueron guardados correctamente.');
			location.href='navegador_combos.php';
			</script>";
}else{
	echo "<script language='Javascript'>
			alert('ERROR EN LA TRANSACCION. COMUNIQUESE CON EL ADMIN.');
			history.back();
			</script>";
}
	

?>