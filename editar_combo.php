<?php

require("conexion.inc");
require('estilos.inc');
require('funciones.php');

$codProducto=$_GET['cod_material'];
	
$sql="select count(*) from material_insumos where cod_material='$codProducto'";	
$resp=mysql_query($sql);				
$num_materiales=0;
while($dat=mysql_fetch_array($resp)){	
	$num_materiales=$dat[0];
}

$sql="select count(*) from material_comodines where cod_material='$codProducto'";	
$resp=mysql_query($sql);				
$num_materiales2=0;
while($dat=mysql_fetch_array($resp)){	
	$num_materiales2=$dat[0];
}

?>
<script>
num=<?php echo $num_materiales;?>;
num2=<?php echo $num_materiales2;?>;

console.log("iniciamos con: "+num);

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
function mas(obj) {
	num++;
	console.log("MAS: "+num);
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


</script>

<head>

</head>
<?php

$sqlEdit="select m.codigo_material, m.descripcion_material, m.estado, m.cod_linea_proveedor, m.cod_grupo, m.cod_tipomaterial, 
	m.observaciones, m.cod_unidad from material_apoyo m where m.codigo_material='$codProducto'";
$respEdit=mysql_query($sqlEdit);
while($datEdit=mysql_fetch_array($respEdit)){
	$nombreProductoX=$datEdit[1];
	$codLineaX=$datEdit[3];
	$codGrupoX=$datEdit[4];
	$codTipoX=$datEdit[5];
	$observacionesX=$datEdit[6];
	$codUnidadX=$datEdit[7];
}

$sqlPrecio="select p.`precio` from `precios` p where p.`cod_precio`=1 and p.`codigo_material`=$codProducto";
$respPrecio=mysql_query($sqlPrecio);
$numFilas=mysql_num_rows($respPrecio);
if($numFilas==1){
	$precio1=mysql_result($respPrecio,0,0);
	$precio1=redondear2($precio1);
}else{
	$precio1=0;
	$precio1=redondear2($precio1);
}

echo "<form action='guarda_editarcombo.php' method='post' name='form1'>";

echo "<h1>Editar Combo</h1>";


echo "<input type='hidden' name='codProducto' id='codProducto' value='$codProducto'>";

echo "<center><table class='texto'>";
echo "<tr><th align='left'>Nombre</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='material' size='40' style='text-transform:uppercase;' value='$nombreProductoX'>
	</td>";
	
echo "<th align='left'>Linea</th>";
$sql1="select pl.cod_linea_proveedor, CONCAT(p.nombre_proveedor,' - ',pl.nombre_linea_proveedor) from proveedores p, proveedores_lineas pl 
where p.cod_proveedor=pl.cod_proveedor and pl.estado=1 order by 2;";
$resp1=mysql_query($sql1);
echo "<td>
		<select name='codLinea' id='codLinea' required>
		<option value=''></option>";
		while($dat1=mysql_fetch_array($resp1))
		{	$codLinea=$dat1[0];
			$nombreLinea=$dat1[1];
			if($codLinea==$codLineaX){
				echo "<option value='$codLinea' selected>$nombreLinea</option>";
			}else{
				echo "<option value='$codLinea'>$nombreLinea</option>";
			}
		}
		echo "</select>
</td>";
echo "</tr>";

echo "<tr><th>Tipo</th>";
$sql1="select e.cod_tipomaterial, e.nombre_tipomaterial from tipos_material e where e.cod_tipomaterial=4 order by 2;";
$resp1=mysql_query($sql1);
echo "<td>
			<select name='cod_tipo' id='cod_tipo' required>";
			while($dat1=mysql_fetch_array($resp1))
			{	$codigo=$dat1[0];
				$nombre=$dat1[1];
				if($codigo==$codGrupoX){
					echo "<option value='$codigo' selected>$nombre</option>";
				}else{
					echo "<option value='$codigo'>$nombre</option>";
				}
			}
			echo "</select>
</td>";
//echo "</tr>";

echo "<th>Grupo</th>";
$sql1="select f.cod_grupo, f.nombre_grupo from grupos f  where f.estado=1 order by 2;";
$resp1=mysql_query($sql1);
echo "<td>
			<select name='cod_grupo' id='cod_grupo' required>
			<option value=''></option>";
			while($dat1=mysql_fetch_array($resp1))
			{	$codigo=$dat1[0];
				$nombre=$dat1[1];
				if($codigo==$codGrupoX){
					echo "<option value='$codigo' selected>$nombre</option>";
				}else{
					echo "<option value='$codigo'>$nombre</option>";
				}
			}
			echo "</select>
	</div>
	</div>
</td>";
echo "</tr>";

echo "<tr><th align='left'>Descripcion</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='observaciones' id='observaciones' size='80' style='text-transform:uppercase;' value='$observacionesX'>
	</td>";


echo "<th>Unidad de Manejo</th>";
$sql1="select u.codigo, u.nombre, u.abreviatura from unidades_medida u order by 1;";
$resp1=mysql_query($sql1);
echo "<td>
			<select name='cod_unidad' id='cod_unidad' required>
			<option value=''></option>";
			while($dat1=mysql_fetch_array($resp1))
			{	$codigo=$dat1[0];
				$nombre=$dat1[1];
				$abreviatura=$dat1[2];
				if($codigo==$codUnidadX){
					echo "<option value='$codigo' selected>$nombre $abreviatura</option>";
				}else{
					echo "<option value='$codigo'>$nombre $abreviatura</option>";
				}
			}
			echo "</select>
</td>";
echo "</tr>";

echo "<tr><th align='left'>Precio</th>";
echo "<td align='left'>
	<input type='number' class='texto' name='precio_producto' id='precio_producto' value='$precio1'>
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

	<?php
	$sqlDetalle="select cod_insumo, cantidad_insumo from material_insumos where cod_material='$codProducto'";
	//echo $sqlDetalle;
	$respDetalle=mysql_query($sqlDetalle);
	$num=1;
	while($datDetalle=mysql_fetch_array($respDetalle)){
		$codInsumo=$datDetalle[0];
		$cantidadInsumo=$datDetalle[1];
		$consulta="select p.`precio` from precios p where p.`codigo_material`='$codInsumo' and p.`cod_precio`='1'";
		$rs=mysql_query($consulta);
		$registro=mysql_fetch_array($rs);
		$precioDetalle=$registro[0];
		if($precioDetalle=="")
		{   $precioDetalle=0;
		}
		$precioDetalle=redondear2($precioDetalle);
	?>
	<div id="div<?php echo $num;?>">
	<table border="0" align="center" cellSpacing="1" cellPadding="1" width="50%" style="border:#ccc 1px solid;" id="data<?php echo $num?>" >
		<tr bgcolor="#FFFFFF">

		<td width="40%" align="center">
			<select name="material<?php echo $num;?>" id="material<?php echo $num;?>" required>
				<option value=""></option>
				<?php
					$sqlInsumo="select m.codigo_material, m.descripcion_material from material_apoyo m
							where m.cod_tipomaterial in (2,3) and m.estado=1 ORDER BY 2";
					$respInsumo=mysql_query($sqlInsumo);
					while($datInsumo=mysql_fetch_array($respInsumo)){
						$codigo=$datInsumo[0];
						$nombre=$datInsumo[1];
						if($codigo==$codInsumo){
						?>
						<option value="<?php echo $codigo;?>" selected><?php echo $nombre;?></option>
						<?php
						}else{
						?>
						<option value="<?php echo $codigo;?>"><?php echo $nombre;?></option>
						<?php	
						}
					}
				?>
			</select>
		</td>

		<td align="center" width="10%">
		<input type="number" class="inputnumber" min="1" max="100" id="cantidad_unitaria<?php echo $num;?>" name="cantidad_unitaria<?php echo $num;?>" size="5"  value="<?php echo $cantidadInsumo;?>" required>
		</td>

		<td align="center" width="10%">
		<div id="div_precioproducto<?php echo $num;?>">
		<?php echo $precioDetalle; ?>
		</div>
		</td>

		<td align="center"  width="10%" ><input class="boton2peque" type="button" value="-" onclick="menos(<?php echo $num;?>)" size="5"/></td>

		</tr>
	</table>
	</div>
	<?php
		$num++;
	}
	?>
		
</fieldset>


<fieldset id="fiel2" style="width:50%;border:0;">
	<table align="center"class="text" cellSpacing="1" cellPadding="2" width="50%" border="0" id="data0" style="border:#ccc 1px solid;">
		<tr>
			<td align="center" colspan="3">
				<input class="boton" type="button" value="Comodines (+)" onclick="mas2(this)" accesskey="N"/>
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

	<?php
	$sqlDetalle="select cod_grupo, cod_tamanio from material_comodines where cod_material='$codProducto'";
	//echo $sqlDetalle;
	$respDetalle=mysql_query($sqlDetalle);
	$num=1;
	while($datDetalle=mysql_fetch_array($respDetalle)){
		$codGrupoX=$datDetalle[0];
		$codTamanioX=$datDetalle[1];
	?>
	<div id="div<?php echo $num;?>">
	<table border="0" align="center" cellSpacing="1" cellPadding="1" width="50%" style="border:#ccc 1px solid;" id="data<?php echo $num?>" >
		<tr bgcolor="#FFFFFF">

		<td width="40%" align="center">
			<select name="grupo<?php echo $num;?>" id="grupo<?php echo $num;?>" required>
				<option value=""></option>
				<?php
					$sqlInsumo="select g.cod_grupo, g.nombre_grupo from grupos g
							where g.estado=1 ORDER BY 2";
					$respInsumo=mysql_query($sqlInsumo);
					while($datInsumo=mysql_fetch_array($respInsumo)){
						$codigo=$datInsumo[0];
						$nombre=$datInsumo[1];
						if($codigo==$codGrupoX){
						?>
						<option value="<?php echo $codigo;?>" selected><?php echo $nombre;?></option>
						<?php
						}else{
						?>
						<option value="<?php echo $codigo;?>"><?php echo $nombre;?></option>
						<?php	
						}
					}
				?>
			</select>
		</td>

		<td width="40%" align="center">
			<select name="tamanio<?php echo $num;?>" id="tamanio<?php echo $num;?>" required>
				<option value=""></option>
				<?php
					$sqlX="select t.codigo, t.nombre from tamanios t where t.estado=1 ORDER BY 2";
					$respX=mysql_query($sqlX);
					while($datX=mysql_fetch_array($respX)){
						$codigo=$datX[0];
						$nombre=$datX[1];
						if($codigo==$codTamanioX){
						?>
						<option value="<?php echo $codigo;?>" selected><?php echo $nombre;?></option>
						<?php
						}else{
						?>
						<option value="<?php echo $codigo;?>"><?php echo $nombre;?></option>
						<?php	
						}
					}
				?>
			</select>
		</td>

		<td align="center"  width="10%" ><input class="boton2peque" type="button" value="-" onclick="menos2(<?php echo $num;?>)" size="5"/></td>

		</tr>
	</table>
	</div>
	<?php
		$num++;
	}
	?>
		
</fieldset>
</center>






<?php
echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar' onClick='return validar(this.form);'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_combos.php\"'>
</div>";
?>

	<input type='hidden' name='materialActivo' value="0">
	<input type='hidden' name='cantidad_material' value="0">
	<input type='hidden' name='cantidad_material2' value="0">
</form>