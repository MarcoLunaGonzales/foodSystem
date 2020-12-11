<?php 
require("conexion.inc");

$codigoEliminar=$_GET["codigo"];
$mesa=$_GET["mesa"];

$sqlCodConsumo="select cod_consumo from consumo_saladetalle where codigo='$codigoEliminar'";
$respCodConsumo=mysql_query($sqlCodConsumo);
$codConsumo=mysql_result($respCodConsumo,0,0);

$sql="delete from consumo_saladetalle where codigo='$codigoEliminar'";
$resp=mysql_query($sql);

$sqlVerifica="select count(*) from consumo_saladetalle where cod_consumo='$codConsumo'";
$respVerifica=mysql_query($sqlVerifica);
$contador=mysql_result($respVerifica,0,0);

if($contador==0){
	$sqlUpd="update consumo_sala set cod_estado=0 where codigo='$codConsumo'";
	$respUpd=mysql_query($sqlUpd);
}

echo "resp:".$resp;