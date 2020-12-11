<?php
require("conexion.inc");
require('estilos.inc');
require('funciones.php');

$codProducto=$_GET['cod_material'];
$tipo=$_GET['tipo'];

$sql="select IFNULL((max(codigo_material)+1),1) as codigo from material_apoyo m";
$resp=mysql_query($sql);
$codigo=mysql_result($resp,0,0);


$sqlCopia="insert into material_apoyo (codigo_material, descripcion_material, estado, cod_linea_proveedor, cod_grupo, cod_tipomaterial, cantidad_presentacion, 
observaciones, imagen, cod_unidad) select $codigo, concat(descripcion_material,' (COPIA)'), estado, cod_linea_proveedor, cod_grupo, cod_tipomaterial, cantidad_presentacion, 
observaciones, imagen, cod_unidad from material_apoyo where codigo_material='$codProducto'";
$respCopia=mysql_query($sqlCopia);

$sqlCopia="insert into material_insumos (cod_material, cod_insumo, cantidad_insumo) 
select $codigo, cod_insumo, cantidad_insumo from material_insumos where cod_material='$codProducto'";
$respCopia=mysql_query($sqlCopia);

$sqlCopia="insert into precios(codigo_material, cod_precio, precio) 
select $codigo, cod_precio, precio from precios where codigo_material='$codProducto'";
$respCopia=mysql_query($sqlCopia);


if($tipo==1){
		echo "<script language='Javascript'>
		alert('Los datos fueron duplicados correctamente.');
		location.href='navegador_material.php';
		</script>";
}

if($tipo==2){
		echo "<script language='Javascript'>
		alert('Los datos fueron duplicados correctamente.');
		location.href='navegador_recetas.php';
		</script>";
}


if($tipo==3){
		echo "<script language='Javascript'>
		alert('Los datos fueron duplicados correctamente.');
		location.href='navegador_combos.php';
		</script>";
}


?>
