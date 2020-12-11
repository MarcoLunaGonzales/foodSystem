<html>
        <script type='text/javascript' language='javascript'>
	</script>
<?php
require("conexion.inc");
require("estilos_almacenes.inc");
$codCajaChica=$_GET["cod_cajachica"];
$sql="select c.monto from cajachica_inicio c where c.fecha_cajachica='$codCajaChica'";
$resp=mysql_query($sql);
$montoCaja=mysql_result($resp,0,0);
?>

<body>
<form action='guardar_editarcajachica.php' method='post' name='form1'>
<h3 align="center">Editar Caja Chica</h3>

<table border='0' class='texto' cellspacing='0' align='center' width='80%' style='border:#ccc 1px solid;'>
<tr><th>Fecha</th><th>Monto</th></tr>

<?php	
$fecha=date("Y-m-d");
?>

<td>
<input type='date' class='texto' min='<?php echo $codCajaChica; ?>' max='<?php echo $codCajaChica; ?>' value='<?php echo $codCajaChica; ?>' id='fecha' name='fecha' required>
</td>

<td>
<input type='number' class='texto' id='monto_gasto' name='monto_gasto' step='0.1' value="<?=$montoCaja;?>" required>
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