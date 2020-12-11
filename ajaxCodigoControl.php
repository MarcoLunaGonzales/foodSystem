<?php
require("conexion.inc");

$nro_autorizacion=$_POST["nro_autorizacion"];
$nro_factura=$_POST["nro_factura"];
$llave=$_POST["llave"];
$fecha=$_POST["fecha"];
$nit_cliente=$_POST["nit_cliente"];
$monto=$_POST["monto"];

include 'controlcode/sin/ControlCode.php';
$controlCode = new ControlCode();
$code = $controlCode->generate($nro_autorizacion,//Numero de autorizacion
							   $nro_factura,//Numero de factura
							   $nit_cliente,//Número de Identificación Tributaria o Carnet de Identidad
							   str_replace('-','',$fecha),//fecha de transaccion de la forma AAAAMMDD
							   $monto,//Monto de la transacción
							   $llave//Llave de dosificación
							   );
echo $code;
?>
