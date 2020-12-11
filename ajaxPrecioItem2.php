
<?php
require("funciones.php");

$codMaterial = $_GET["codmat"];
require("conexion.inc");
$cadRespuesta="";
$consulta="
    select p.`precio` from precios p where p.`codigo_material`='$codMaterial' and p.`cod_precio`='1'";
$rs=mysql_query($consulta);
$registro=mysql_fetch_array($rs);
$cadRespuesta=$registro[0];
if($cadRespuesta=="")
{   $cadRespuesta=0;
}

$cadRespuesta=redondear2($cadRespuesta);
echo "<input class='form-control' type='number' min='0.1' step='0.01' name='precio_consumo' id='precio_consumo' onChange='calculaMontoConsumo();' onKeyUp='calculaMontoConsumo();' value='$cadRespuesta' readonly='true'/>";

?>
