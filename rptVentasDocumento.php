<?php
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexion.inc');
require('funcion_nombres.php');
require('funciones.php');

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

echo "<h1>Reporte Ventas x Documento</h1>
	<h2>Territorio: $nombre_territorio <br> De: $fecha_ini A: $fecha_fin
	<br>Fecha Reporte: $fecha_reporte</h2>";

$sql="select s.`fecha`,  
	(select c.nombre_cliente from clientes c where c.`cod_cliente`=s.cod_cliente) as cliente, 
	s.`razon_social`, s.`observaciones`, 
	(select t.`abreviatura` from `tipos_docs` t where t.`codigo`=s.cod_tipo_doc),
	s.`nro_correlativo`, s.`monto_final`, s.telefono_cliente, s.nit,
	(select tp.nombre_tipopago from tipos_pago tp where tp.cod_tipopago=s.cod_tipopago)tipopago,
(select tp.nombre from tipos_origenpedido tp where tp.codigo=s.cod_tipoorigenpedido)origenpedido
	from `salida_almacenes` s where s.`cod_tiposalida`=1001 and s.salida_anulada=0 and
	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad`='$rpt_territorio')
	and s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta' and s.cod_cliente in ($clienteString) 
	and s.cod_tipopago in ($tipoPagoString) and s.cod_tipoorigenpedido in ($origenPedidoString) ";

if($variableAdmin==1){
	$sql.=" and s.cod_tipo_doc in (1,2,3)";
}else{
	$sql.=" and s.cod_tipo_doc in (1)";
}

$sql.=" order by s.fecha, s.nro_correlativo";
	
//	echo $sql;
$resp=mysql_query($sql);

echo "<br><table align='center' class='textomediano' width='70%'>
<tr>
<th>Fecha</th>
<th>Cliente</th>
<th>Razon Social</th>
<th>Telefono</th>
<th>Observaciones</th>
<th>TipoPago</th>
<th>OrigenPedido</th>
<th>Documento</th>
<th>Monto</th>
</tr>";

$totalVenta=0;
while($datos=mysql_fetch_array($resp)){	
	$fechaVenta=$datos[0];
	$nombreCliente=$datos[1];
	$razonSocial=$datos[2];
	$obsVenta=$datos[3];
	$datosDoc=$datos[4]."-".$datos[5];
	$montoVenta=$datos[6];
	$totalVenta=$totalVenta+$montoVenta;
	$telefonoCliente=$datos[7];
	$nitCliente=$datos[8];
	$tipoPago=$datos[9];
	$origenPedido=$datos[10];
	
	if($telefonoCliente==""){
		$telefonoCliente=buscarTelefono($nitCliente);
	}
	
	$montoVentaFormat=number_format($montoVenta,2,".",",");
	echo "<tr>
	<td>$fechaVenta</td>
	<td>$nombreCliente</td>
	<td>$razonSocial</td>
	<td>$telefonoCliente</td>
	<td>$obsVenta</td>
	<td>$tipoPago</td>
	<td>$origenPedido</td>
	<td>$datosDoc</td>
	<td align='right'>$montoVentaFormat</td>
	</tr>";
}
$totalVentaFormat=number_format($totalVenta,2,".",",");
echo "<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>Total:</td>
	<td align='right'>$totalVentaFormat</td>
<tr>";

echo "</table></br>";
include("imprimirInc.php");
?>