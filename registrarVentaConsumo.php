<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script type="text/javascript" src="lib/js/xlibPrototipoSimple-v0.1.js"></script>
		
<?php
echo "<body onLoad='funcionInicio();'>";
require("conexion.inc");
require("estilos_almacenes.inc");
require("funciones.php");

if($fecha==""){   
	$fecha=date("d/m/Y");
}

$codConsumo=$_GET["codConsumo"];

//SACAMOS LA CANTIDAD DE ITEMS PARA INICIALIZAR ALGUNAS VARIABLES JAVASCRIPT
$sqlDetalle="select count(distinct(m.codigo_material)) from consumo_sala c, consumo_saladetalle cd, material_apoyo m where c.codigo=cd.cod_consumo and cd.cod_material=m.codigo_material and 
	c.cod_mesa='$codConsumo' and c.cod_estado=1";
$respDetalle=mysql_query($sqlDetalle);
$cantidadItems=mysql_result($respDetalle,0,0);
?>

<script type='text/javascript' language='javascript'>
	num=<?=$cantidadItems;?>;
	cantidad_items=<?=$cantidadItems;?>;	
	
function funcionInicio(){
	document.getElementById('nitCliente').focus();
	totales();
}
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

function listaMateriales(f){
	var contenedor;
	var nombreItem=f.itemNombreMaterial.value;
	contenedor = document.getElementById('divListaMateriales');
	var arrayItemsUtilizados=new Array();	
	var i=0;
	for(var j=1; j<=num; j++){
		if(document.getElementById('materiales'+j)!=null){
			console.log("codmaterial: "+document.getElementById('materiales'+j).value);
			arrayItemsUtilizados[i]=document.getElementById('materiales'+j).value;
			i++;
		}
	}
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxListaMateriales.php?nombreItem="+nombreItem+"&arrayItemsUtilizados="+arrayItemsUtilizados,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}

function ajaxTipoDoc(f){
	var contenedor;
	contenedor=document.getElementById("divTipoDoc");
	ajax=nuevoAjax();
	var codTipoSalida=(f.tipoSalida.value);
	ajax.open("GET", "ajaxTipoDoc.php?codTipoSalida="+codTipoSalida,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null);
}

function ajaxPesoMaximo(codVehiculo){
	var contenedor;
	contenedor=document.getElementById("divPesoMax");
	ajax=nuevoAjax();
	var codVehiculo=codVehiculo;
	ajax.open("GET", "ajaxPesoMaximo.php?codVehiculo="+codVehiculo,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null);
}

function ajaxNroDoc(f){
	var contenedor;
	contenedor=document.getElementById("divNroDoc");
	ajax=nuevoAjax();
	var codTipoDoc=(f.tipoDoc.value);
	ajax.open("GET", "ajaxNroDoc.php?codTipoDoc="+codTipoDoc,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null);
}

function actStock(indice){
	var contenedor;
	contenedor=document.getElementById("idstock"+indice);
	var codmat=document.getElementById("materiales"+indice).value;
    var codalm=document.getElementById("global_almacen").value;
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxStockSalidaMateriales.php?codmat="+codmat+"&codalm="+codalm+"&indice="+indice,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
			ajaxPrecioItem(indice);
		}
	}
	ajax.send(null);
}

function ajaxPrecioItem(indice){
	var contenedor;
	contenedor=document.getElementById("idprecio"+indice);
	var codmat=document.getElementById("materiales"+indice).value;
	var tipoPrecio=document.getElementById("tipoPrecio").value;
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxPrecioItem.php?codmat="+codmat+"&indice="+indice+"&tipoPrecio="+tipoPrecio,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
			calculaMontoMaterial(indice);
		}
	}
	ajax.send(null);
}

function ajaxRazonSocial(f){
	var contenedor;
	contenedor=document.getElementById("divRazonSocial");
	var nitCliente=document.getElementById("nitCliente").value;
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxRazonSocial.php?nitCliente="+nitCliente,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
			document.getElementById('razonSocial').focus();
		}
	}
	ajax.send(null);
	
}

function calculaMontoMaterial(indice){

	var cantidadUnitaria=document.getElementById("cantidad_unitaria"+indice).value;
	var precioUnitario=document.getElementById("precio_unitario"+indice).value;
	//var descuentoUnitario=document.getElementById("descuentoProducto"+indice).value;
	
	//var montoUnitario=(parseFloat(cantidadUnitaria)*parseFloat(precioUnitario)) * (1-(descuentoUnitario/100));
	var montoUnitario=(parseFloat(cantidadUnitaria)*parseFloat(precioUnitario));
	montoUnitario=Math.round(montoUnitario*100)/100
		
	document.getElementById("montoMaterial"+indice).value=montoUnitario;
	
	totales();
}

function totales(){
	var subtotal=0;
    for(var ii=1;ii<=num;ii++){
		if(document.getElementById("montoMaterial"+ii)!=null){
			var monto=document.getElementById("montoMaterial"+ii).value;
			subtotal=subtotal+parseFloat(monto);
		}
    }
	
	subtotal=Math.round(subtotal*100)/100;
    document.getElementById("totalVenta").value=subtotal;
	document.getElementById("totalFinal").value=subtotal;
}

function aplicarDescuento(f){
	var total=document.getElementById("totalVenta").value;
	var descuento=document.getElementById("descuentoVenta").value;
	
	descuento=Math.round(descuento*100)/100;
	
	document.getElementById("totalFinal").value=parseFloat(total)-parseFloat(descuento);
	
}
function buscarMaterial(f, numMaterial){
	f.materialActivo.value=numMaterial;
	document.getElementById('divRecuadroExt').style.visibility='visible';
	document.getElementById('divProfileData').style.visibility='visible';
	document.getElementById('divProfileDetail').style.visibility='visible';
	document.getElementById('divboton').style.visibility='visible';
	
	document.getElementById('divListaMateriales').innerHTML='';
	document.getElementById('itemNombreMaterial').value='';	
	document.getElementById('itemNombreMaterial').focus();	
}
function Hidden(){
	document.getElementById('divRecuadroExt').style.visibility='hidden';
	document.getElementById('divProfileData').style.visibility='hidden';
	document.getElementById('divProfileDetail').style.visibility='hidden';
	document.getElementById('divboton').style.visibility='hidden';

}
function setMateriales(f, cod, nombreMat){
	var numRegistro=f.materialActivo.value;
	
	document.getElementById('materiales'+numRegistro).value=cod;
	document.getElementById('cod_material'+numRegistro).innerHTML=nombreMat;
	
	document.getElementById('divRecuadroExt').style.visibility='hidden';
	document.getElementById('divProfileData').style.visibility='hidden';
	document.getElementById('divProfileDetail').style.visibility='hidden';
	document.getElementById('divboton').style.visibility='hidden';
	
	actStock(numRegistro);
}

function mas(obj) {
	if(num>=15){
		alert("No puede registrar mas de 15 items en una nota.");
	}else{
		//aca validamos que el item este seleccionado antes de adicionar nueva fila de datos
		var banderaItems0=0;
		for(var j=1; j<=num; j++){
			if(document.getElementById('materiales'+j)!=null){
				if(document.getElementById('materiales'+j).value==0){
					banderaItems0=1;
				}
			}
		}
		//fin validacion
		console.log("bandera: "+banderaItems0);

		if(banderaItems0==0){
			num++;
			cantidad_items++;
			console.log("num: "+num);
			console.log("cantidadItems: "+cantidad_items);
			fi = document.getElementById('fiel');
			contenedor = document.createElement('div');
			contenedor.id = 'div'+num;  
			fi.type="style";
			fi.appendChild(contenedor);
			var div_material;
			div_material=document.getElementById("div"+num);			
			ajax=nuevoAjax();
			ajax.open("GET","ajaxMaterialVentas.php?codigo="+num,true);
			ajax.onreadystatechange=function(){
				if (ajax.readyState==4) {
					div_material.innerHTML=ajax.responseText;
					buscarMaterial(form1, num);
				}
			}		
			ajax.send(null);
		}

	}
	
}	
	
function menos(numero) {
	cantidad_items--;
	console.log("TOTAL ITEMS: "+num);
	console.log("NUMERO A DISMINUIR: "+numero);
	if(numero==num){
		num=parseInt(num)-1;
 	}
	fi = document.getElementById('fiel');
	fi.removeChild(document.getElementById('div'+numero));
	totales();
}

function pressEnter(e, f){
	tecla = (document.all) ? e.keyCode : e.which;
	if (tecla==13){
		document.getElementById('itemNombreMaterial').focus();
		listaMateriales(f);
		return false;
	}
}

function validar(f)
{   
	f.cantidad_material.value=num;
	var cantidadItems=num;
	
	if(cantidadItems>0){
		
		var item="";
		var cantidad="";
		var stock="";
		var descuento="";
		
		console.log("CANTIDAD DE ITEMS: "+cantidadItems);		
		for(var i=1; i<=cantidadItems; i++){
			if(document.getElementById("materiales"+i)!=null){
				item=parseFloat(document.getElementById("materiales"+i).value);
				cantidad=parseFloat(document.getElementById("cantidad_unitaria"+i).value);
				//realizamos una validacion adicional para los preparados no tienen stock y los ponemos en 10000
				stock=document.getElementById("stock"+i).value;
				if(stock=="-"){
					stock=10000;
				}else{
					stock=parseFloat(document.getElementById("stock"+i).value);
				}
				console.log("material: "+item);
				console.log("cantidad: "+cantidad);
				console.log("stock: "+stock);
				
				if(item==0){
					alert("Debe escoger un item en la fila "+i);
					return(false);
				}			
				if(stock<cantidad){
					alert("No puede sacar cantidades mayores a las existencias.");
					return(false);
				}			
				return(true);	
			}
		}
		
	}else{
		alert("La transaccion debe tener al menos 1 item.");
		return(false);
	}
}
	
	
</script>

		
<?php



$usuarioVentas=$_COOKIE['global_usuario'];
$globalAgencia=$_COOKIE['global_agencia'];

//SACAMOS LA CONFIGURACION PARA EL DOCUMENTO POR DEFECTO
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=1";
$respConf=mysql_query($sqlConf);
$tipoDocDefault=mysql_result($respConf,0,0);

//SACAMOS LA CONFIGURACION PARA EL CLIENTE POR DEFECTO
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=2";
$respConf=mysql_query($sqlConf);
$clienteDefault=mysql_result($respConf,0,0);

//SACAMOS LA CONFIGURACION PARA CONOCER SI LA FACTURACION ESTA ACTIVADA
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=3";
$respConf=mysql_query($sqlConf);
$facturacionActivada=mysql_result($respConf,0,0);

?>

<form action='guardarSalidaMaterial.php' method='POST' name='form1'>

<h1>Registrar Venta</h1>

<table class='texto' align='center' width='100%'>
<tr>
<th>Tipo de Documento</th>
<th>Nro.Factura</th>
<th>Fecha</th>
<th>Cliente</th>
<th>Precio</th>
<th align="center"><span class="textogranderojo">Mesa</span></th>
</tr>
<tr>
<input type="hidden" name="tipoSalida" id="tipoSalida" value="1001">
<input type="hidden" name="codConsumo" id="codConsumo" value="<?=$codConsumo;?>">
<input type="hidden" name="tipoOrigen" id="tipoOrigen" value="1"><!--TIPO ORIGEN ES CANDY RICE-->

<td align='center'>
	<?php
		
		if($facturacionActivada==1){
			$sql="select codigo, nombre, abreviatura from tipos_docs where codigo in (1,2) order by 2 desc";
		}else{
			$sql="select codigo, nombre, abreviatura from tipos_docs where codigo in (2) order by 2 desc";
		}
		$resp=mysql_query($sql);

		echo "<select name='tipoDoc' id='tipoDoc' onChange='ajaxNroDoc(form1)' required>";
		echo "<option value=''>-</option>";
		while($dat=mysql_fetch_array($resp)){
			$codigo=$dat[0];
			$nombre=$dat[1];
			if($codigo==$tipoDocDefault){
				echo "<option value='$codigo' selected>$nombre</option>";
			}else{
				echo "<option value='$codigo'>$nombre</option>";
			}
		}
		echo "</select>";
		?>
</td>
<td align='center'>
	<div id='divNroDoc'>
		<?php
		
		$vectorNroCorrelativo=numeroCorrelativo($tipoDocDefault);
		$nroCorrelativo=$vectorNroCorrelativo[0];
		$banderaErrorFacturacion=$vectorNroCorrelativo[1];
	
		echo "<span class='textogranderojo'>$nroCorrelativo</span>";
	
		?>
	</div>
</td>

<td align='center'>
	<input type='text' class='texto' value='<?php echo $fecha?>' id='fecha' size='10' name='fecha' readonly>
	<img id='imagenFecha' src='imagenes/fecha.bmp'>
</td>

<td align='center'>
	<select name='cliente' class='texto' id='cliente' onChange='ajaxTipoPrecio(form1);' required>
		<option value=''>----</option>
<?php
$sql2="select c.`cod_cliente`, c.`nombre_cliente` from clientes c order by 2";
$resp2=mysql_query($sql2);

while($dat2=mysql_fetch_array($resp2)){
   $codCliente=$dat2[0];
	$nombreCliente=$dat2[1];
	if($codCliente==$clienteDefault){
?>		
	<option value='<?php echo $codCliente?>' selected><?php echo $nombreCliente?></option>
<?php			
	}else{
?>		
	<option value='<?php echo $codCliente?>'><?php echo $nombreCliente?></option>
<?php			
	}

}
?>
	</select>
</td>
<td>
	<div id='divTipoPrecio'>
		<?php
			$sql1="select codigo, nombre from tipos_precio order by 1";
			$resp1=mysql_query($sql1);
			echo "<select name='tipoPrecio' class='texto' id='tipoPrecio'>";
			while($dat=mysql_fetch_array($resp1)){
				$codigo=$dat[0];
				$nombre=$dat[1];
				echo "<option value='$codigo'>$nombre</option>";
			}
			echo "</select>";
			?>

	</div>
</td>

<td align="center"><span class="textogranderojo"><?=$codConsumo;?></span></td>

</tr>

<tr>
	<th>NIT</th>
	<th colspan="2">Nombre/RazonSocial &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Telefono</th>
	<th>TipoPago</th>
	<th colspan="2">Observaciones</th>
</tr>
<tr>	
	<td>
		<div id='divNIT'>
			<input type='number' value='' name='nitCliente' id='nitCliente'  onChange='ajaxRazonSocial(this.form);' required>
		</div>
	</td>
	
	<td colspan='2'>
		<div id='divRazonSocial'>
			<input type='text' name='razonSocial' id='razonSocial' value='' style="text-transform:uppercase;"  required>
			<input type='text' name='telefono_cliente' id='telefono_cliente' value=''>
		</div>
	</td>
	<td>
	<?php
		$sql1="select cod_tipopago, nombre_tipopago from tipos_pago order by 1";
		$resp1=mysql_query($sql1);
		echo "<select name='tipo_pago' class='texto' id='tipo_pago'>";
		while($dat=mysql_fetch_array($resp1)){
			$codigo=$dat[0];
			$nombre=$dat[1];
			echo "<option value='$codigo'>$nombre</option>";
		}
		echo "</select>";
		?>

	</td>
	<th align='center' colspan="2">
		<input type='text' class='texto' name='observaciones' value='' size='40' rows="3">
	</th>
</tr>

</table>


<fieldset id="fiel" style="width:100%;border:0;">
	<table align="center" class="texto" width="100%" id="data0">
	<tr>
		<td align="center" colspan="8">
			<b>Detalle de la Venta    </b><input class="boton" type="button" value="Adicionar Item (Alt+A) " onclick="mas(this)" accesskey="a"/>
		</td>
	</tr>

	<tr align="center">
		<td width="8%">&nbsp;</td>
		<td width="42%">Item</td>
		<td width="10%">Stock</td>
		<td width="10%">Cantidad</td>
		<td width="10%">Precio </td>
		<td width="10%">Monto</td>
		<td width="10%">&nbsp;</td>
	</tr>
	</table>
	
	<?php
	$sqlDetalle="select m.codigo_material, sum(cd.cantidad), m.descripcion_material, cd.precio, sum(cd.monto) from consumo_sala c, consumo_saladetalle cd, material_apoyo m where c.codigo=cd.cod_consumo and cd.cod_material=m.codigo_material and 
	c.cod_mesa='$codConsumo' and c.cod_estado=1 group by m.codigo_material, m.descripcion_material order by 3";
	//echo $sqlDetalle;
	$respDetalle=mysql_query($sqlDetalle);
	$num=1;
	while($datDetalle=mysql_fetch_array($respDetalle)){
	$codInterno=$datDetalle[0];
	$cantUnit=$datDetalle[1];
	$cantUnit=redondear2($cantUnit);
	$nombreMat=$datDetalle[2];
	$precioUnit=$datDetalle[3];
	$precioUnit=redondear2($precioUnit);
	$montoUnit=$datDetalle[4];
	$montoUnit=redondear2($montoUnit);
	?>
	<div id="div<?=$num;?>">
	<table border="0" align="center" width="100%"  class="texto" id="data<?php echo $num?>" >
		<tr bgcolor="#FFFFFF">
			<td width="8%" align="center">
				<a href="javascript:buscarMaterial(form1, <?php echo $num;?>)"><img src='imagenes/buscar2.png' title="Buscar Producto" width="30"></a>
			</td>
			<td width="42%" align="center">	
				<input type="hidden" name="materiales<?php echo $num;?>" id="materiales<?php echo $num;?>" value="<?=$codInterno;?>">
					<div id="cod_material<?php echo $num;?>" class='textograndenegro'><?=$nombreMat;?></div>
			</td>
			<td width="10%" align="center">
				<div id='idstock<?php echo $num;?>'>
					<input type='hidden' id='stock<?php echo $num;?>' name='stock<?php echo $num;?>' value='-'>
				</div>
			</td>
			<td align="center" width="10%">
				<input class="inputnumber" type="number" value="<?=$cantUnit;?>" min="1" id="cantidad_unitaria<?php echo $num;?>" onKeyUp='calculaMontoMaterial(<?php echo $num;?>);'  onChange='calculaMontoMaterial(<?php echo $num;?>);' name="cantidad_unitaria<?php echo $num;?>" required> 
			</td>
			<td align="center" width="10%">
				<div id='idprecio<?php echo $num;?>'>
					<input class="inputnumber" type="number" id="precio_unitario<?php echo $num;?>" name="precio_unitario<?php echo $num;?>" value="<?=$precioUnit;?>" readonly>
				</div>
			</td>
			<td align="center" width="10%">
				<input class="inputnumber" type="number" id="montoMaterial<?php echo $num;?>" name="montoMaterial<?php echo $num;?>" value="<?=$montoUnit;?>" readonly> 
			</td>
			<td align="center"  width="10%" ><input class="boton2peque" type="button" value="-" onclick="menos(<?php echo $num;?>)" />
			</td>
		</tr>
	</table>
	</div>	
	<?php
		$num++;
	}
	?>
	
</fieldset>
	<table id='pieNota' width='100%' border="0">
		<tr>
			<td align='right' width='90%'>Monto Nota</td><td><input type='number' name='totalVenta' id='totalVenta' readonly></td>
		</tr>
		<tr>
			<td align='right' width='90%'>Descuento Bs.</td><td><input type='number' name='descuentoVenta' id='descuentoVenta' onChange='aplicarDescuento(form1);' value="0" required></td>
		</tr>
		<tr>
			<td align='right' width='90%'>Monto Final</td><td><input type='number' name='totalFinal' id='totalFinal' readonly></td>
		</tr>

	</table>


<?php

if($banderaErrorFacturacion==0){
	echo "<div class='divBotones'><input type='submit' class='boton' value='Guardar' onClick='return validar(this.form)'>
			<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegadorVentas.php\"';></div>";
	echo "</div>";	
}else{
	echo "";
}


?>


<div id="divRecuadroExt" style="background-color:#666; position:absolute; width:800px; height: 400px; top:30px; left:150px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); -webkit-border-radius: 20px; -moz-border-radius: 20px; z-index:2; overflow: auto;">
</div>

<div id="divboton" style="position: absolute; top:20px; left:920px;visibility:hidden; text-align:center; z-index:3">
	<a href="javascript:Hidden();"><img src="imagenes/cerrar4.png" height="45px" width="45px"></a>
</div>

<div id="divProfileData" style="background-color:#FFF; width:750px; height:350px; position:absolute; top:50px; left:170px; -webkit-border-radius: 20px; 	-moz-border-radius: 20px; visibility: hidden; z-index:2; overflow: auto;">
  	<div id="divProfileDetail" style="visibility:hidden; text-align:center">
		<table align='center'>
			<tr><th>Producto a vender</th><th>&nbsp;</th></tr>
			<tr>
			<td>
				<input type='text' name='itemNombreMaterial' id='itemNombreMaterial' size="40" class="textogranderojo" onkeypress="return pressEnter(event, this.form);">
			</td>
			<td>
				<input type='button' class='boton' value='Buscar' onClick="listaMateriales(this.form)">
			</td>
			</tr>
			
		</table>
		<div id="divListaMateriales">
		</div>
	
	</div>
</div>

<input type='hidden' name='materialActivo' value="0">
<input type='hidden' name='cantidad_material' value="0">

</form>
</body>