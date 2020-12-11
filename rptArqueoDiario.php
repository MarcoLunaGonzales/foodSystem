<?php
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexion.inc');
require('funcion_nombres.php');
require('funciones.php');

$fecha_ini=$_GET['fecha_ini'];
$rpt_territorio=$_GET['rpt_territorio'];

$variableAdmin=$_GET["variableAdmin"];
if($variableAdmin!=1){
	$variableAdmin=0;
}

//desde esta parte viene el reporte en si
$fecha_iniconsulta=cambia_formatofecha($fecha_ini);

$fecha_reporte=date("d/m/Y");

echo "<h1>Reporte Arqueo Diario de Caja</h1>
	<h2>Fecha: $fecha_ini &nbsp;&nbsp;&nbsp; Fecha Reporte: $fecha_reporte</h2>";

	

echo "<center><table class='textomediano'>";
echo "<tr><th colspan='2'>Saldo Inicial Caja Chica</th></tr>
<tr><th>Fecha</th><th>Monto Apertura de Caja Chica [Bs]</th></tr>";
$consulta = "select DATE_FORMAT(c.fecha_cajachica, '%d/%m/%Y'), c.monto, c.fecha_cajachica from cajachica_inicio c where 
c.fecha_cajachica='$fecha_iniconsulta'";
$resp = mysql_query($consulta);
while ($dat = mysql_fetch_array($resp)) {
	$fechaCajaChica = $dat[0];
	$montoCajaChica = $dat[1];
	$montoCajaChicaF=number_format($montoCajaChica,2,".",",");
	echo "<tr>
	<td align='center'>$fechaCajaChica</td>
	<td align='right'>$montoCajaChicaF</td>
	</tr>";
}
echo "</table></center><br>";


	
$sql="select s.`fecha`,  
	(select c.nombre_cliente from clientes c where c.`cod_cliente`=s.cod_cliente) as cliente, 
	s.`razon_social`, s.`observaciones`, 
	(select t.`abreviatura` from `tipos_docs` t where t.`codigo`=s.cod_tipo_doc),
	s.`nro_correlativo`, s.`monto_final`, s.cod_tipopago, (select tp.nombre_tipopago from tipos_pago tp where tp.cod_tipopago=s.cod_tipopago)
	from `salida_almacenes` s where s.`cod_tiposalida`=1001 and s.salida_anulada=0 and
	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad`='$rpt_territorio')
	and s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_iniconsulta'";

if($variableAdmin==1){
	$sql.=" and s.cod_tipo_doc in (1,2,3)";
}else{
	$sql.=" and s.cod_tipo_doc in (1)";
}
$sql.=" order by s.nro_correlativo";
	
$resp=mysql_query($sql);

echo "<br><table align='center' class='textomediano' width='70%'>
<tr><th colspan='7'>Detalle de Ingresos</th></tr>
<tr>
<th>Fecha</th>
<th>Cliente</th>
<th>Razon Social</th>
<th>Observaciones</th>
<th>TipoPago</th>
<th>Documento</th>
<th>Monto [Bs]</th>
</tr>";

$totalVenta=0;
$totalEfectivo=0;
$totalTarjeta=0;
while($datos=mysql_fetch_array($resp)){	
	$fechaVenta=$datos[0];
	$nombreCliente=$datos[1];
	$razonSocial=$datos[2];
	$obsVenta=$datos[3];
	$datosDoc=$datos[4]."-".$datos[5];
	$montoVenta=$datos[6];
	$totalVenta=$totalVenta+$montoVenta;
	$codTipoPago=$datos[7];
	$nombreTipoPago=$datos[8];
	
	$montoVentaFormat=number_format($montoVenta,2,".",",");
	
	if($codTipoPago==1){
		$totalEfectivo+=$montoVenta;
	}else{
		$totalTarjeta+=$montoVenta;
	}
	$totalEfectivoF=number_format($totalEfectivo,2,".",",");
	$totalTarjetaF=number_format($totalTarjeta,2,".",",");
	
	echo "<tr>
	<td>$fechaVenta</td>
	<td>$nombreCliente</td>
	<td>$razonSocial</td>
	<td>$obsVenta</td>
	<td>$nombreTipoPago</td>
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
	<th>Total Efectivo:</th>
	<th align='right'>$totalEfectivoF</th>
<tr>";
echo "<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<th>Total Tarjeta Deb/Cred:</th>
	<th align='right'>$totalTarjetaF</th>
<tr>";
echo "<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<th>Total Ingresos:</th>
	<th align='right'>$totalVentaFormat</th>
<tr>";
echo "</table></br>";




echo "<br><center><table class='textomediano'>";
echo "<tr><th colspan='4'>Detalle de Gastos</th></tr>";
echo "<tr><th>Fecha</th><th>Tipo</th>
	<th>Descripcion</th><th>Monto [Bs]</th></tr>";

$consulta = "select g.cod_gasto, g.descripcion_gasto, 
	(select nombre_tipogasto from tipos_gasto where cod_tipogasto=g.cod_tipogasto)tipogasto, 
	DATE_FORMAT(g.fecha_gasto, '%d/%m/%Y'), monto, estado from gastos g where fecha_gasto='$fecha_iniconsulta' 
	and g.estado=1 order by g.cod_gasto";
	
$resp = mysql_query($consulta);
$totalGastos=0;
while ($dat = mysql_fetch_array($resp)) {
	$codGasto = $dat[0];
	$descripcionGasto= $dat[1];
	$tipoGasto=$dat[2];
	$fechaGasto = $dat[3];
	$montoGasto = $dat[4];
	$totalGastos=$totalGastos+$montoGasto;
	$codEstado=$dat[5];	
	$montoGasto=redondear2($montoGasto);

	echo "<tr>
	<td align='center'>$fechaGasto</td>
	<td align='center'>$tipoGasto</td>
	<td align='center'>$descripcionGasto</td>
	<td align='right'>$montoGasto</td>
	</tr>";
}
$totalGastos=redondear2($totalGastos);
echo "<tr>
<td align='center'>-</td>
<td align='center'>-</td>
<th>Total Gastos</th>
<th align='right'>$totalGastos</th>
</tr>";
echo "</table></center><br>";

$saldoCajaChica=$montoCajaChica+$totalVenta-$totalGastos;
$saldoCajaChicaF=number_format($saldoCajaChica,2,".",",");

$saldoCajaChica2=$montoCajaChica+$totalEfectivo-$totalGastos;
$saldoCajaChica2F=number_format($saldoCajaChica2,2,".",",");


echo "<br><center><table class='textomediano'>";
echo "<tr><th>Saldo Inicial Caja Chica + Ingresos - Gastos   ---->  </th>
<th align='right'>$saldoCajaChicaF</th>
</tr>";
echo "<tr><th>Saldo Inicial Caja Chica + Ingresos Efectivo - Gastos   ---->  </th>
<th align='right'>$saldoCajaChica2F</th>
</tr>";
echo "</table></center><br>";




include("imprimirInc.php");
?>