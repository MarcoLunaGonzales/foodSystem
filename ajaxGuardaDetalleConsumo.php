<?php 
require("conexion.inc");

$numeroMesa=$_POST["numero_mesa"];
$clienteMesa=$_POST["cliente_mesa"];

$itemConsumo=$_POST["item_consumo"];
$cantidadConsumo=$_POST["cantidad_consumo"];
$precioConsumo=$_POST["precio_consumo"];

$montoConsumo=$cantidadConsumo*$precioConsumo;

//VERIFICA EL NUMERO DE PEDIDO DEL DIA
$fechaActual=date("Y-m-d");
$sqlVerificaNumero="select IFNULL(MAX(nro_pedido)+1,1) from consumo_sala c where fecha_pedido='$fechaActual'";
$respVerificaNumero=mysql_query($sqlVerificaNumero);
$nroPedido=mysql_result($respVerificaNumero,0,0);


$sqlVerifica="select IFNULL(MAX(codigo),0) from consumo_sala c where c.cod_mesa='$numeroMesa' and c.cod_estado=1";
$respVerifica=mysql_query($sqlVerifica);
$codigoConsumo=mysql_result($respVerifica,0,0);

if($codigoConsumo==0){
	$sqlInsert="insert into consumo_sala(nombre_cliente, cod_mesa, cod_estado, nro_pedido, fecha_pedido) 
	values('cliente$numeroMesa','$numeroMesa',1,'$nroPedido','$fechaActual')";
	$respInsert=mysql_query($sqlInsert);
	
	$sqlVerifica="select IFNULL(MAX(codigo),0) from consumo_sala c where c.cod_mesa='$numeroMesa'  and c.cod_estado=1";
	$respVerifica=mysql_query($sqlVerifica);
	$codConsumoNuevo=mysql_result($respVerifica,0,0);
}
if($codigoConsumo>0){
	$codConsumoNuevo=$codigoConsumo;
	//VOLVEMOS A PONER EL NUMERO DE PEDIDO EN 0 PARA QUE NO SE ACTUALICE
	$nroPedido=0;
}

//HACEMOS EL INSERT EN EL DETALLE
$sqlInsertDet="insert into consumo_saladetalle (cod_consumo, cod_material, cantidad, precio, monto) 
values('$codConsumoNuevo','$itemConsumo','$cantidadConsumo','$precioConsumo','$montoConsumo')";
$respInsertDet=mysql_query($sqlInsertDet);

$sqlRecuperaCodigoUnico="select max(codigo) from consumo_saladetalle where cod_consumo='$codConsumoNuevo' and cod_material='$itemConsumo'";
$respRecuperaCodigoUnico=mysql_query($sqlRecuperaCodigoUnico);
$codigoUnico=mysql_result($respRecuperaCodigoUnico,0,0);

echo $codigoUnico."|".$nroPedido;

?>