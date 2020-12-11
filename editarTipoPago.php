<?php

require("conexion.inc");
require("estilos_almacenes.inc");
require("funciones.php");


$codigoRegistro=$_GET['codigo_registro'];

$sqlSelect="select s.fecha,
(select t.abreviatura from tipos_docs t where t.codigo=s.cod_tipo_doc),
s.nro_correlativo, s.nit, s.razon_social, s.telefono_cliente, s.cod_tipopago, s.cod_cliente
 from salida_almacenes s where s.cod_salida_almacenes=$codigoRegistro";		
$respSelect=mysql_query($sqlSelect);
while($datSelect=mysql_fetch_array($respSelect)){
	$fechaSalida=$datSelect[0];
	$nroDocumento=$datSelect[1]."-".$datSelect[2];
	$nit=$datSelect[3];
	$razonSocial=$datSelect[4];
	$telefono=$datSelect[5];	
	$codTipoPago=$datSelect[6];
	$codCliente=$datSelect[7];
} 	    

?>
<form action='guardaEditarTipoPago.php' method='POST' name='form1'>

<input type="hidden" name="codigoVenta" id="codigoVenta" value="<?=$codigoRegistro;?>">

<h1>Editar Tipo de Pago</h1>
<center>
<table class='texto'>
<tr><th style="text-align: center">Fecha</th>
<th style="text-align: center">Nro</th>
<th style="text-align: center">Razon Social</th><th style="text-align: center">NIT</th><th>Telefono</th></tr>
<tr>
<td style="text-align: center">
<?=$fechaSalida;?>
</td>
<td style="text-align: center">
<?=$nroDocumento;?>
</td>
<td style="text-align: center">
<?=$nit;?>
</td>
<td style="text-align: center">
<?=$razonSocial?>
</td>
<td style="text-align: center">
<?=$telefono;?>
</td>
</tr>
<tr>
	<th colspan="2" style="text-align: center">Cliente</th>
	<th colspan="3" style="text-align: center">Tipo de Pago</th>
</tr>
<tr>
	<td colspan='2' align="center">
	<?php
		$sql1="select cod_cliente, nombre_cliente from clientes order by 2";
		$resp1=mysql_query($sql1);
		echo "<select name='cliente' class='textograndenegro' id='cliente'>";
		while($dat=mysql_fetch_array($resp1)){
			$codigo=$dat[0];
			$nombre=$dat[1];
			if($codigo==$codCliente){
				echo "<option value='$codigo' selected>$nombre</option>";
			}else{
				echo "<option value='$codigo'>$nombre</option>";
			}
		}
		echo "</select>";
		?>

	</td>

	<td colspan='3' align="center">
	<?php
		$sql1="select cod_tipopago, nombre_tipopago from tipos_pago order by 1";
		$resp1=mysql_query($sql1);
		echo "<select name='tipo_pago' class='textograndenegro' id='tipo_pago'>";
		while($dat=mysql_fetch_array($resp1)){
			$codigo=$dat[0];
			$nombre=$dat[1];
			if($codigo==$codTipoPago){
				echo "<option value='$codigo' selected>$nombre</option>";
			}else{
				echo "<option value='$codigo'>$nombre</option>";
			}
		}
		echo "</select>";
		?>

	</td>
</tr>
</table></center>
	<div class='divBotones'>
	<input type='submit' class='boton' value='Guardar'>
	<input type='button' class='boton2' value='Cancelar' onClick="location.href='navegadorVentas.php'";>
	</div>
</form>
</body>