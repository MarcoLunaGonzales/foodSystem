<?php
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexion.inc');
require('funcion_nombres.php');

$fecha_ini=$_GET['fecha_ini'];
$fecha_fin=$_GET['fecha_fin'];

$variableAdmin=$_GET["variableAdmin"];
if($variableAdmin!=1){
	$variableAdmin=0;
}

$fecha_iniconsulta=$fecha_ini;
$fecha_finconsulta=$fecha_fin;

$rpt_territorio=$_GET['rpt_territorio'];

$clienteRpt=$_GET['cliente'];
$clienteString=implode(",", $clienteRpt);

$tipoPagoRpt=$_GET['tipo_pago'];
$tipoPagoString=implode(",",$tipoPagoRpt);

$origenPedidoRpt=$_GET['origen_pedido'];
$origenPedidoString=implode(",",$origenPedidoRpt);

$fecha_reporte=date("d/m/Y");

$nombre_territorio=nombreTerritorio($rpt_territorio);
$nombreClientes=nameClientes($clienteString);
$nombreOrigenPedidos=nameOrigenPedido($origenPedidoString);
$nombreTiposPago=nameTiposPago($tipoPagoString);

echo "<table align='center' class='textotit' width='100%'><tr><td align='center'>Reporte Ventas x Item
	<br>Territorio: $nombre_territorio <br> De: $fecha_ini A: $fecha_fin    Fecha Reporte: $fecha_reporte
	<br>Cliente(s): $nombreClientes  Origen: $nombreOrigenPedidos
	<br>Tipo Pago: $nombreTiposPago
	</tr>
	</table>";
	
$sql="select m.`codigo_material`, m.`descripcion_material`, 
	sum(sd.monto_unitario)montoVenta, sum(sd.cantidad_unitaria),
	(select nombre_grupo from grupos where cod_grupo=m.cod_grupo)
	from `salida_almacenes` s, `salida_detalle_almacenes` sd, `material_apoyo` m 
	where s.`cod_salida_almacenes`=sd.`cod_salida_almacen` and s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta'
	and s.`salida_anulada`=0 and sd.`cod_material`=m.`codigo_material` and
	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad`='$rpt_territorio') 
	and sd.estado=1 and s.cod_cliente in ($clienteString) 
	and s.cod_tipopago in ($tipoPagoString) and s.cod_tipoorigenpedido in ($origenPedidoString)";
	
if($variableAdmin==1){
	$sql.=" and s.cod_tipo_doc in (1,2,3)";
}else{
	$sql.=" and s.cod_tipo_doc in (1)";
}

$sql.=" group by m.`codigo_material` order by 5,2";

$resp=mysql_query($sql);
echo "<br><table align='center' class='texto' width='100%'>
<tr>
<th>Codigo</th>
<th>Grupo</th>
<th>Item</th>
<th>Cantidad</th>
<th>Monto Venta</th>
</tr>";

$totalVenta=0;
$totalVentaGrupo=0;
$codGrupoPivote=mysql_result($resp,0,4);
$resp=mysql_query($sql);
while($datos=mysql_fetch_array($resp)){	
	$codItem=$datos[0];
	$nombreItem=$datos[1];
	$montoVenta=$datos[2];
	$cantidad=$datos[3];
	$grupoItem=$datos[4];
	
	$montoPtr=number_format($montoVenta,2,".",",");
	$cantidadFormat=number_format($cantidad,2,".",",");	
	$totalVenta=$totalVenta+$montoVenta;
	$totalVentaGrupo=$totalVentaGrupo+$montoVenta;
	
	if($codGrupoPivote!=$grupoItem){
		$totalVentaGrupo=$totalVentaGrupo-$montoVenta;
		echo "<tr>
		<td>-</td>
		<th>Total $codGrupoPivote</th>
		<td></td>
		<td></td>
		<th>$totalVentaGrupo</th>
		</tr>";
		$codGrupoPivote=$grupoItem;
		$totalVentaGrupo=$montoVenta;
	}

	echo "<tr>
	<td>$codItem</td>
	<td>$grupoItem</td>
	<td>$nombreItem</td>
	<td>$cantidadFormat</td>
	<td>$montoPtr</td>
	</tr>";
	
}
	$totalVentaGrupo=$totalVentaGrupo;
	echo "<tr>
	<td>-</td>
	<th>Total $codGrupoPivote</th>
	<td></td>
	<td></td>
	<th>$totalVentaGrupo</th>
	</tr>";
	$codGrupoPivote=$grupoItem;

	
$totalPtr=number_format($totalVenta,2,".",",");
echo "<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<th>Total:</th>
	<th>$totalPtr</th>
<tr>";

echo "</table>";
include("imprimirInc.php");
?>