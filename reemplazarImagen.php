<?php
require("conexion.inc");
require('estilos.inc');

$codProducto=$_GET['codigo'];
$nombreProducto=$_GET['nombre'];

echo "<form enctype='multipart/form-data' action='guarda_reemplazar_imagen.php' method='post' name='form1'>";

echo "<h1>Reemplazar Imagen</h1>";


echo "<input type='hidden' name='codProducto' id='codProducto' value='$codProducto'>";

echo "<center><table class='texto'>";
echo "<tr><th align='left'>Nombre</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='material' size='40' style='text-transform:uppercase;' value='$nombreProducto' readOnly>
	</td></tr>";

echo "<tr><th>Imagen</th>";
echo "<td> <input name='archivo' id='archivo' required type='file' class='boton2'/> </td>";
echo "</tr>";	

	echo "</table></center>";
echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_material.php\"'>
</div>";
echo "</form>";
?>
