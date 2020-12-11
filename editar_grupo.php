<?php

require("conexion.inc");
require("estilos.inc");

$sql=mysql_query("select nombre_grupo from grupos where cod_grupo=$codigo_registro");
$dat=mysql_fetch_array($sql);

$nombreGrupo=$dat[0];

echo "<form action='guarda_editargrupo.php' method='post'>";

echo "<h1>Editar Grupo</h1>";

echo "<center><table class='texto'>";
echo "<tr><th align='left'>Nombre de Grupo</th>";

echo "<input type='hidden' name='codigo' value='$codigo_registro'>";
echo "<td align='center'><input type='text' class='texto' name='nombre_grupo' value='$nombreGrupo' size='30' onKeyUp='javascript:this.value=this.value.toUpperCase();' required></td></tr>";

echo "<tr><th align='left'>Estado</th>";
echo "<td align='center'>
<select name='estado'>
	<option value='1'>Activo</option>
	<option value='2'>Inactivo</option>
</select></td></tr>";
echo "</table></center>";

echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_grupos.php\"'>
</div>";

echo "</form>";
?>