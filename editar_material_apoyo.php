<script language='Javascript'>
	

</script>

<head>

</head>
<?php
require("conexion.inc");
require('estilos.inc');
require('funciones.php');

$codProducto=$_GET['cod_material'];

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

echo "<form action='guarda_editarproducto.php' method='post' name='form1'>";

echo "<h1>Editar Producto</h1>";


echo "<input type='hidden' name='codProducto' id='codProducto' value='$codProducto'>";

echo "<center><table class='texto'>";
echo "<tr><th align='left'>Nombre</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='material' size='40' style='text-transform:uppercase;' value='$nombreProductoX'>
	</td>";
	
echo "<tr><th align='left'>Linea</th>";
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
$sql1="select e.cod_tipomaterial, e.nombre_tipomaterial from tipos_material e order by 2;";
$resp1=mysql_query($sql1);
echo "<td>
			<select name='cod_tipo' id='cod_tipo' required>
				<option value=''></option>";
			while($dat1=mysql_fetch_array($resp1))
			{	$codigo=$dat1[0];
				$nombre=$dat1[1];
				if($codigo==$codTipoX){
					echo "<option value='$codigo' selected>$nombre</option>";
				}else{
					echo "<option value='$codigo'>$nombre</option>";
				}
			}
			echo "</select>
</td>";
echo "</tr>";

echo "<tr><th>Grupo</th>";
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


echo "<tr><th>Unidad de Manejo</th>";
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
	<input type='number' class='texto' name='precio_producto' id='precio_producto' value='$precio1' step='0.1'>
	</td></tr>";

echo "</table></center>";
echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_material.php\"'>
</div>";
echo "</form>";
?>
