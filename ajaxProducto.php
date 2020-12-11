<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
	<select name="material<?php echo $num;?>" id="material<?php echo $num;?>" onChange="ajaxPreciosProducto(this.form, this, <?php echo $num;?>);" required>
		<option value=""></option>
		<?php
			$sqlInsumo="select m.codigo_material, m.descripcion_material from material_apoyo m
					where m.cod_tipomaterial in (2,3) and m.estado=1 ORDER BY 2";
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

<td align="center" width="10%">
<input type="number" class="inputnumber" min="1" max="100" id="cantidad_unitaria<?php echo $num;?>" name="cantidad_unitaria<?php echo $num;?>" size="5"  value="1" required>
</td>

<td align="center" width="10%">
<div id="div_precioproducto<?php echo $num;?>">
</div>
</td>

<td align="center"  width="10%" ><input class="boton2peque" type="button" value="-" onclick="menos(<?php echo $num;?>)" size="5"/></td>

</tr>
</table>

</head>
</html>