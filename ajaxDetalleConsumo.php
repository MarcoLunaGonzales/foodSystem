<?php 
require("conexion.inc");
	$card=$_GET["card"];
	$num=$_GET["codigo"];
?>
<div id="<?=$card;?>div<?=$num;?>">
<table class="table table-condensed bordered" width="100%">
	<tr>
		<input type="hidden" name="<?=$card;?>item<?=$num;?>" id="<?=$card;?>item<?=$num;?>">
		<input type="hidden" name="<?=$card;?>cantidad<?=$num;?>" id="<?=$card;?>cantidad<?=$num;?>">
		<input type="hidden" name="<?=$card;?>precio<?=$num;?>" id="<?=$card;?>precio<?=$num;?>">
		<input type="hidden" name="<?=$card;?>monto<?=$num;?>" id="<?=$card;?>monto<?=$num;?>">
		<input type="hidden" name="<?=$card;?>codigo_unico<?=$num;?>" id="<?=$card;?>codigo_unico<?=$num;?>">
		
		<td width="50%"><div id="div<?=$card;?>item<?=$num;?>" class="text-left">-</div><td>
		<td width="15%"><div id="div<?=$card;?>cantidad<?=$num;?>" class="text-center">-</div></td>
		<td width="15%"><div id="div<?=$card;?>monto<?=$num;?>" class="text-center">-</div></td>
		<td width="5%" align="center"><input class="boton2peque" type="button" value="-" onclick="menos(<?=$card;?>,<?=$num;?>)" size="5"/></td>
	</tr>
</table>
</div
