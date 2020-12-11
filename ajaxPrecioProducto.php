
<?php
require("funciones.php");

$codProducto = $_GET["cod_producto"];
//
require("conexion.inc");
$cadRespuesta="";
$consulta="
    select p.`precio` from precios p where p.`codigo_material`='$codProducto' and p.`cod_precio`='1'";
$rs=mysql_query($consulta);
$registro=mysql_fetch_array($rs);
$cadRespuesta=$registro[0];
if($cadRespuesta=="")
{   $cadRespuesta=0;
}

$cadRespuesta=redondear2($cadRespuesta);

echo "$cadRespuesta";

?>
