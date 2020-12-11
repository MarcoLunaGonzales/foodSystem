<script language='JavaScript'>
function envia_formulario(f, variableAdmin)
{	var rpt_territorio,fecha_ini, fecha_fin, rpt_ver;
	rpt_territorio=f.rpt_territorio.value;
	fecha_ini=f.exafinicial.value;
	fecha_fin=f.exaffinal.value;
	window.open('rptVentasxItem.php?rpt_territorio='+rpt_territorio+'&fecha_ini='+fecha_ini+'&fecha_fin='+fecha_fin+'&variableAdmin='+variableAdmin,'','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
	return(true);
}
</script>
<?php

require("conexion.inc");
require("estilos_almacenes.inc");

$variableAdmin=$_GET["variableAdmin"];
if($variableAdmin!=1){
	$variableAdmin=0;
}


$fecha_rptdefault=date("Y-m-d");
echo "<table align='center' class='textotit'><tr><th>Reporte Ventas x Item</th></tr></table><br>";
echo"<form method='get' action='rptVentasxItem.php' target='_blank'>";

	echo "<input type='hidden' name='variableAdmin' id='variableAdmin' value='$variableAdmin'>";

	echo"\n<table class='texto' align='center' cellSpacing='0' width='50%'>\n";
	echo "<tr><th align='left'>Territorio</th><td><select name='rpt_territorio' class='texto'>";
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
	}
	echo "</select></td></tr>";
	
	echo "<tr><th align='left'>Fecha inicio:</th>";
			echo" <TD bgcolor='#ffffff'>
			<INPUT type='date' class='texto' value='$fecha_rptdefault' id='fecha_ini' size='10' name='fecha_ini' required>";
    		echo"  </TD>";
	echo "</tr>";
	echo "<tr><th align='left'>Fecha final:</th>";
		echo" <TD bgcolor='#ffffff'>
		<INPUT type='date' class='texto' value='$fecha_rptdefault' id='fecha_fin' size='10' name='fecha_fin' required>";
		echo"  </TD>";
	echo "</tr>";
	
	echo "<tr><th align='left'>Cliente</th>
	<td>
	<select class='texto' name='cliente[]' id='cliente' multiple size='4' required>";
	$sql="select cod_cliente, nombre_cliente from clientes order by 2";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$codigo=$dat[0];
		$nombre=$dat[1];
		echo "<option value='$codigo' selected>$nombre</option>";
	}
	echo "</select>
	</td>
	</tr>";
	
	echo "<tr><th align='left'>Tipo Pago</th>
	<td>
	<select class='texto' name='tipo_pago[]' id='tipo_pago' multiple size='4' required>";
	$sql="select cod_tipopago, nombre_tipopago from tipos_pago order by 2";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$codigo=$dat[0];
		$nombre=$dat[1];
		echo "<option value='$codigo' selected>$nombre</option>";
	}
	echo "</select>
	</td>
	</tr>";
	
	echo "<tr><th align='left'>Origen Pedido</th>
	<td>
	<select class='texto' name='origen_pedido[]' id='origen_pedido' multiple size='3' required>";
	$sql="select codigo, nombre from tipos_origenpedido order by 2";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$codigo=$dat[0];
		$nombre=$dat[1];
		echo "<option value='$codigo' selected>$nombre</option>";
	}
	echo "</select>
	</td>
	</tr>";
	
	echo"\n </table><br>";
	require('home_almacen.php');
	echo "<center><input type='submit' name='reporte' value='Ver Reporte' class='boton'>
	</center><br>";
	echo"</form>";
	echo "</div>";
	echo"<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";

?>