<html>
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
function ajaxCargarDeudas(){
	var contenedor;
	contenedor = document.getElementById('divDetalle');

	var codCliente = document.getElementById('cliente').value;

	ajax=nuevoAjax();

	ajax.open("GET", "ajaxCargarDeudas.php?codCliente="+codCliente,true);

	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
		}else{
			contenedor.innerHTML = "Cargando...";
		}
	}
	ajax.send(null)
}


function validar(f)
{   
	var codCliente=document.getElementById("cliente").value;
	var numRegistros=document.getElementById("nroFilas").value;
	var monto;
	var nroDoc;
	if(codCliente==0){
		alert("Debe seleccionar un Cliente");
	}else{
		if(numRegistros>0){
			for(var i=1; i<=numRegistros; i++){
				monto=parseFloat(document.getElementById("montoPago"+i).value);
				nroDoc=parseFloat(document.getElementById("nroDoc"+i).value);
				//if(monto==0 || nroDoc==0 || monto=="NaN" || nroDoc=="NaN"){
					//alert("Monto de Pago, Nro. Doc. no pueden estar vacios. Fila: "+i);
					//return(false);
				//}
				f.submit();
			}
		}
		
	}	
}

function solonumeros(e)
{
	var key;
	if(window.event) {// IE
		key = e.keyCode;
	}else if(e.which) // Netscape/Firefox/Opera
	{
		key = e.which;
	}
	if (key < 46 || key > 57) 
	{
	  return false;
	}
	return true;
}



	</script>
<?php
require("conexion.inc");
require("estilos_almacenes.inc");
?>

<body>
<form action='guardar_cajachica.php' method='post' name='form1'>
<h3 align="center">Registrar Apertura de Caja Chica</h3>

<table border='0' class='texto' cellspacing='0' align='center' width='80%' style='border:#ccc 1px solid;'>
<tr><th>Fecha</th><th>Monto</th></tr>

<?php	
$fecha=date("Y-m-d");
?>

<td>
<input type='date' class='texto' min='<?php echo $fecha; ?>' max='<?php echo $fecha; ?>' value='<?php echo $fecha; ?>' id='fecha' name='fecha' required>
</td>

<td>
<input type='number' class='texto' value="" id='monto_gasto' name='monto_gasto' step='0.1' required>
</td>

</tr>
</table>

<?php
echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_aperturacajachica.php\"'>
</div>";
?>


</form>
</body>