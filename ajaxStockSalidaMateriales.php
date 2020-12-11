<?php
require("funciones.php");

$codMaterial = $_GET["codmat"];
$codAlmacen = $_GET["codalm"];
$indice = $_GET["indice"];

//
require("conexion.inc");

//SACAMOS LA CONFIGURACION PARA LA  VALIDACION DE STOCKS
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=4";
$respConf=mysql_query($sqlConf);
$banderaValidacionStock=mysql_result($respConf,0,0);

$cadRespuesta="";
$consulta="
    SELECT SUM(id.cantidad_restante) as total
    FROM ingreso_detalle_almacenes id, ingreso_almacenes i
    WHERE id.cod_material='$codMaterial' AND i.cod_ingreso_almacen=id.cod_ingreso_almacen AND i.ingreso_anulado=0 
	AND i.cod_almacen='$codAlmacen'";
$rs=mysql_query($consulta);
$registro=mysql_fetch_array($rs);
$cadRespuesta=$registro[0];
if($cadRespuesta=="")
{   $cadRespuesta=0;
}

$cadRespuesta=redondear2($cadRespuesta);

//PREGUNTAMOS SI EL ITEM ES UNA PREPARACION SI ES ASI PONEMOS EL STOCK EN (-) PARA DIFERENCIARLO
$sqlVeri="select cod_tipomaterial from material_apoyo where codigo_material='$codMaterial'";
$respVeri=mysql_query($sqlVeri);
$tipoMaterial=mysql_result($respVeri,0,0);
if(($tipoMaterial==3 || $tipoMaterial==4) || $banderaValidacionStock!=1){
	echo "<input type='text' id='stock$indice' name='stock$indice' value='-' readonly size='4'>";
}else{
	echo "<input type='text' id='stock$indice' name='stock$indice' value='$cadRespuesta' readonly size='4'>";
}
//echo "$cadRespuesta -> ".rand(0, 10);
//

?>
