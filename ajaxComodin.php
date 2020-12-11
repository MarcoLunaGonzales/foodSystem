<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="STYLESHEET" type="text/css" href="stilos.css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php 

require("conexion.inc");
$num=$_GET['codigo'];

?>

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

				?>
				<option value="<?php echo $codigo;?>"><?php echo $nombre;?></option>
				<?php
			}
		?>
	</select>
</td>

<td width="40%" align="center">
	<select name="tamanio<?php echo $num;?>" id="tamanio<?php echo $num;?>" required>
		<option value=""></option>
		<?php
			$sqlInsumo="select t.codigo, t.nombre from tamanios t
					where t.estado=1 ORDER BY 2";
			$respInsumo=mysql_query($sqlInsumo);
			while($datInsumo=mysql_fetch_array($respInsumo)){
				$codigo=$datInsumo[0];
				$nombre=$datInsumo[1];

				?>
				<option value="<?php echo $codigo;?>"><?php echo $nombre;?></option>
				<?php
			}
		?>
	</select>
</td>

<td align="center"  width="10%" ><input class="boton2peque" type="button" value="-" onclick="menos2(<?php echo $num;?>)" size="5"/></td>

</tr>
</table>

</head>
</html>