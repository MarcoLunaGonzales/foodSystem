<script type='text/javascript' language='javascript'>
function nuevoAjax()
{	var xmlhttp=false;
	try {
			xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
	} catch (e) {
	try {
		xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	} catch (E) {
		xmlhttp = false;
	}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
 	xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}

function codigoControl(f){
	var contenedor;
	contenedor = document.getElementById('divCodigoControl');
	var nro_autorizacion=f.nro_autorizacion.value;
	var nro_factura=f.nro_factura.value;
	var llave=f.llave.value;
	var fecha=f.fecha.value;
	var nit_cliente=f.nit_cliente.value;
	var monto=f.monto.value;
	
	ajax=nuevoAjax();
	ajax.open("POST", "ajaxCodigoControl.php?nro_autorizacion="+nro_autorizacion+"&nro_factura="+nro_factura+"&fecha="+fecha+"&nit_cliente="+nit_cliente+"&monto="+monto+"&llave="+llave,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}
</script>

<?php

echo "<form action='verificarCodigoControl.php' method='post'>";

echo "<h1>CODIGO CONTROL IMPUESTOS</h1>";

echo "<center><table class='texto'>";
echo "<tr>
	<td>Nro. Autorizacion</td>";
echo "<td>
	<input type='text' class='texto' name='nro_autorizacion' size='40' required>
</td></tr>";

echo "<tr>
	<td>Nro. Factura</td>";
echo "<td>
	<input type='text' class='texto' name='nro_factura' size='40' required>
</td></tr>";

echo "<tr>
	<td>NIT CLIENTE</td>";
echo "<td>
	<input type='text' class='texto' name='nit_cliente' size='40' required>
</td></tr>";

echo "<tr>
	<td>Fecha (AAAAMMDD)</td>";
echo "<td>
	<input type='text' class='texto' name='fecha' size='40' required>
</td></tr>";

echo "<tr>
	<td>Monto Factura</td>";
echo "<td>
	<input type='text' class='texto' name='monto' size='40' required>
</td></tr>";

echo "<tr>
	<td>Llave de Dosificacion</td>";
echo "<td>
	<input type='text' class='texto' name='llave' size='100' required>
</td></tr>";



echo "</table></center>";

echo "<div class='divBotones'>
<input type='submit' class='boton' value='VER'>
</div>";


echo "<div id='divCodigoControl'></div>";

echo "</form>";
?>