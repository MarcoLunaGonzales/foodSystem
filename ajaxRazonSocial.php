<?php
require("conexion.inc");
$nitCliente=$_GET['nitCliente'];

$sql="select s.razon_social, s.telefono_cliente from salida_almacenes s 
	where s.nit='$nitCliente' order by s.fecha desc limit 0,1";
$resp=mysql_query($sql);

$nombre="";
while($dat=mysql_fetch_array($resp)){
	$nombre=$dat[0];
	$telefono=$dat[1];
}

//VERIFICA SI ES UN CLIENTE VIP
$sqlVIP="select count(*), c.nombre_factura from clientes c where c.nit_cliente='$nitCliente' and c.cod_tipocliente=2";
$respVIP=mysql_query($sqlVIP);
$numeroFilas=mysql_result($respVIP,0,0);
$nombreFactura=mysql_result($respVIP,0,1);
$imagenVIP="";
if($numeroFilas>0){
	$imagenVIP="<img src='imagenes/vip.png' width='30'>";
	if($nombre==""){
		$nombre=$nombreFactura;
	}
}
echo "<input type='text' value='$nombre' name='razonSocial' id='razonSocial' style='text-transform:uppercase;' required>
<input type='text' value='$telefono' name='telefono_cliente' id='telefono_cliente'>$imagenVIP";

?>
