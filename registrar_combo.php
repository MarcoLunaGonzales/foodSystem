<script>
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

num=0;
num2=0;

function mas(obj) {
	num++;
	fi = document.getElementById('fiel');
	contenedor = document.createElement('div');
	contenedor.id = 'div'+num;  
	fi.type="style";
	fi.appendChild(contenedor);
	var div_material;
	div_material=document.getElementById("div"+num);			
	ajax=nuevoAjax();
	ajax.open("GET","ajaxProducto.php?codigo="+num,true);
	ajax.onreadystatechange=function(){
		if (ajax.readyState==4) {
			div_material.innerHTML=ajax.responseText;
		}
	}		
	ajax.send(null);
}	

function mas2(obj) {
	num2++;
	fi = document.getElementById('fiel2');
	contenedor = document.createElement('div2');
	contenedor.id = 'div2'+num2;  
	fi.type="style";
	fi.appendChild(contenedor);
	var div_material;
	div_material=document.getElementById("div2"+num2);			
	ajax=nuevoAjax();
	ajax.open("GET","ajaxComodin.php?codigo="+num2,true);
	ajax.onreadystatechange=function(){
		if (ajax.readyState==4) {
			div_material.innerHTML=ajax.responseText;
		}
	}		
	ajax.send(null);
}	


function menos(numero) {
	console.log("TOTAL ITEMS: "+num);
	console.log("NUMERO A DISMINUIR: "+numero);
	if(numero==num){
		num=parseInt(num)-1;
 	}
	fi = document.getElementById('fiel');
	fi.removeChild(document.getElementById('div'+numero));		
	//calcularTotal();	 
}

function menos2(numero) {
	console.log("TOTAL ITEMS: "+num2);
	console.log("NUMERO A DISMINUIR: "+numero);
	if(numero==num2){
		num2=parseInt(num2)-1;
 	}
	fi = document.getElementById('fiel2');
	fi.removeChild(document.getElementById('div2'+numero));		
	//calcularTotal();	 
}

function validar(f){   
	f.cantidad_material.value=num;
	var cantidadItems=num;
	
	f.cantidad_material2.value=num2;
	
	if(cantidadItems>0){
	}else{
		alert("El registro debe tener al menos 1 detalle.");
		return(false);
	}
}

function ajaxPreciosProducto(f, combo, indice){
	var contenedor;
	contenedor=document.getElementById("div_precioproducto"+indice);
	ajax=nuevoAjax();
	var codProducto=combo.value;
	ajax.open("GET", "ajaxPrecioProducto.php?cod_producto="+codProducto,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null);
}

</script>

<?php
require("conexion.inc");
require('estilos.inc');

echo "<form enctype='multipart/form-data' action='guarda_combo.php' method='post' name='form1'>";

echo "<h1>Adicionar Combo de Productos</h1>";


echo "<center><table class='texto' width='80'>";

echo "<tr><th align='left'>Nombre</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='material' size='40' style='text-transform:uppercase;' required>
	</td>";
	
echo "<th align='left'>Proveedor</th>";
$sql1="select pl.cod_linea_proveedor, CONCAT(p.nombre_proveedor,' - ',pl.nombre_linea_proveedor) from proveedores p, proveedores_lineas pl 
where p.cod_proveedor=pl.cod_proveedor and pl.estado=1 order by 2;";
$resp1=mysql_query($sql1);
echo "<td>
		<select name='codLinea' id='codLinea' required>
		<option value=''></option>";
		while($dat1=mysql_fetch_array($resp1))
		{	$codLinea=$dat1[0];
		$nombreLinea=$dat1[1];
		echo "<option value='$codLinea'>$nombreLinea</option>";
		}
		echo "</select>
</td>";
echo "</tr>";

echo "<tr><th>Tipo</th>";
$sql1="select f.cod_tipomaterial, f.nombre_tipomaterial from tipos_material f where f.cod_tipomaterial=4 order by 2;";
$resp1=mysql_query($sql1);
echo "<td>
			<select name='cod_tipo' id='cod_tipo' required>";
			
			while($dat1=mysql_fetch_array($resp1))
			{	$codTipo=$dat1[0];
				$nombreTipo=$dat1[1];
				echo "<option value='$codTipo'>$nombreTipo</option>";
			}
echo "</select>
</td>";

echo "<th>Grupo</th>";
$sql1="select f.cod_grupo, f.nombre_grupo from grupos f 
where f.estado=1 order by 2;";
$resp1=mysql_query($sql1);
echo "<td>
			<select name='cod_grupo' id='cod_grupo' required>
			<option value=''></option>";
			while($dat1=mysql_fetch_array($resp1))
			{	$codGrupo=$dat1[0];
				$nombreGrupo=$dat1[1];
				echo "<option value='$codGrupo'>$nombreGrupo</option>";
			}
			echo "</select>
</td>";
echo "</tr>";

echo "<tr><th align='left'>Descripcion</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='observaciones' id='observaciones' size='80' style='text-transform:uppercase;'>
	</td>";

echo "<th>Unidad de Manejo</th>";
$sql1="select u.codigo, u.nombre, u.abreviatura from unidades_medida u order by 1;";
$resp1=mysql_query($sql1);
echo "<td>
			<select name='cod_unidad' id='cod_unidad' required>
			<option value=''></option>";
			while($dat1=mysql_fetch_array($resp1))
			{	$codUnidad=$dat1[0];
				$nombreUnidad=$dat1[1];
				$abreviatura=$dat1[2];
				echo "<option value='$codUnidad'>$nombreUnidad $abreviatura</option>";
			}
			echo "</select>
</td></tr>";

echo "<tr><th>Imagen</th>";
echo "<td> <input name='archivo' id='archivo' required type='file' class='boton2'/> </td>";
echo "";

echo "<th align='left'>Precio</th>";
echo "<td align='left'>
	<input type='number' class='texto' name='precio_producto' id='precio_producto'>
	</td></tr>";


echo "</table></center>";

?>	

<center>
<fieldset id="fiel" style="width:50%;border:0;">
	<table align="center"class="text" cellSpacing="1" cellPadding="2" width="50%" border="0" id="data0" style="border:#ccc 1px solid;">
		<tr>
			<td align="center" colspan="3">
				<input class="boton" type="button" value="Productos (+)" onclick="mas(this)" accesskey="N"/>
			</td>
		</tr>
		<tr>
			<td align="center" colspan="3">
			<div style="width:50%;" align="center"><b>Detalle de Productos</b></div>
			</td>				
		</tr>				
		<tr class="titulo_tabla" align="center">
			<td width="40%" align="center">Producto</td>
			<td width="10%" align="center">Cantidad</td>
			<td width="10%" align="center">Precio</td>
			<td width="10%" align="center">&nbsp;</td>
		</tr>
	</table>
</fieldset>
</center>

<center>
<fieldset id="fiel2" style="width:50%;border:0;">
	<table align="center"class="text" cellSpacing="1" cellPadding="2" width="50%" border="0" id="data0" style="border:#ccc 1px solid;">
		<tr>
			<td align="center" colspan="3">
				<input class="boton" type="button" value="Comodin (+)" onclick="mas2(this)" accesskey="N"/>
			</td>
		</tr>
		<tr>
			<td align="center" colspan="3">
			<div style="width:50%;" align="center"><b>Detalle de Comodines</b></div>
			</td>				
		</tr>				
		<tr class="titulo_tabla" align="center">
			<td width="40%" align="center">Grupo</td>
			<td width="10%" align="center">Tamaño</td>
		</tr>
	</table>
</fieldset>
</center>

	
<?php
echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar' onClick='return validar(this.form);'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_material.php\"'>
</div>";

?>

<input type='hidden' name='materialActivo' value="0">
<input type='hidden' name='cantidad_material' value="0">
<input type='hidden' name='cantidad_material2' value="0">

</form>
