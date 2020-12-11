<?php
require("conexion.inc");
require("estilos.inc");
$codigoVenta=$_POST['codigoVenta'];
$tipoPago=$_POST['tipo_pago'];
$cliente=$_POST['cliente'];

$sql_upd=mysql_query("update salida_almacenes set cod_cliente='$cliente', cod_tipopago='$tipoPago' where cod_salida_almacenes='$codigoVenta'");
echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='navegadorVentas.php';
			</script>";
?>