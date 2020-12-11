<?php
require('fpdf.php');
require('conexion.inc');
require('funciones.php');
require('NumeroALetras.php');

$codConsumo=$_GET["codConsumo"];

//consulta cuantos items tiene el detalle
$sqlNro="select count(*), c.nro_pedido from consumo_sala c, consumo_saladetalle cd where c.codigo=cd.cod_consumo and 
c.cod_mesa='$codConsumo' and c.cod_estado=1";
$respNro=mysql_query($sqlNro);
$nroItems=mysql_result($respNro,0,0);
$numeroPedido=mysql_result($respNro,0,0);

$tamanoLargo=100+($nroItems*3)-3;

$pdf=new FPDF('P','mm',array(76,$tamanoLargo));
$pdf->SetMargins(0,0,0);
$pdf->AddPage(); 
$pdf->SetFont('Arial','',8);


$sqlConf="select id, valor from configuracion_facturas where id=1";
$respConf=mysql_query($sqlConf);
$nombreTxt=mysql_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=2";
$respConf=mysql_query($sqlConf);
$sucursalTxt=mysql_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=3";
$respConf=mysql_query($sqlConf);
$direccionTxt=mysql_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=4";
$respConf=mysql_query($sqlConf);
$telefonoTxt=mysql_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=5";
$respConf=mysql_query($sqlConf);
$ciudadTxt=mysql_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=6";
$respConf=mysql_query($sqlConf);
$txt1=mysql_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=7";
$respConf=mysql_query($sqlConf);
$txt2=mysql_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=8";
$respConf=mysql_query($sqlConf);
$txt3=mysql_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=9";
$respConf=mysql_query($sqlConf);
$nitTxt=mysql_result($respConf,0,1);

//datos documento
	$mesa="MESA $codConsumo";
	$fechaConsumo=date("d-m-Y");

$y=5;
$incremento=3;

$pdf->SetXY(0,$y+3);		$pdf->Cell(0,0,$nombreTxt,0,0,"C");
$pdf->SetXY(0,$y+6);		$pdf->Cell(0,0,$sucursalTxt,0,0,"C");
$y=$y+0;
$pdf->SetXY(0,$y+12);		$pdf->Cell(0,0,"CONSUMO EN SALA", 0,0,"C");
$pdf->SetXY(0,$y+15);		$pdf->Cell(0,0,$ciudadTxt,0,0,"C");
$pdf->SetXY(0,$y+18);		$pdf->Cell(0,0,"-------------------------------------------------------------------------------", 0,0,"C");

$pdf->SetXY(0,$y+23);		$pdf->Cell(0,0,"$mesa  -  Pedido: $numeroPedido",0,0,"C");
$pdf->SetXY(0,$y+27);		$pdf->Cell(0,0,"Fecha: $fechaConsumo",0,0,"C");

$y=$y-15;
$pdf->SetXY(0,$y+45);		$pdf->Cell(0,0,"=================================================================================",0,0,"C");
$pdf->SetXY(15,$y+48);		$pdf->Cell(0,0,"ITEM");
$pdf->SetXY(50,$y+48);		$pdf->Cell(0,0,"Cant.");
$pdf->SetXY(58,$y+48);		$pdf->Cell(0,0,"Importe");
$pdf->SetXY(0,$y+52);		$pdf->Cell(0,0,"=================================================================================",0,0,"C");


$sqlDetalle="select m.codigo_material, sum(cd.cantidad), m.descripcion_material, cd.precio, 0, sum(cd.monto) from consumo_sala c, consumo_saladetalle cd, material_apoyo m where c.codigo=cd.cod_consumo and cd.cod_material=m.codigo_material and 
c.cod_mesa='$codConsumo' and c.cod_estado=1 group by m.codigo_material, m.descripcion_material order by 3";
$respDetalle=mysql_query($sqlDetalle);

$yyy=55;

$montoTotal=0;
while($datDetalle=mysql_fetch_array($respDetalle)){
	$codInterno=$datDetalle[0];
	$cantUnit=$datDetalle[1];
	$cantUnit=redondear2($cantUnit);
	$nombreMat=$datDetalle[2];
	$precioUnit=$datDetalle[3];
	$precioUnit=redondear2($precioUnit);
	$descUnit=$datDetalle[4];
	$montoUnit=$datDetalle[5];
	$montoUnit=redondear2($montoUnit);
	
	$pdf->SetXY(5,$y+$yyy);		$pdf->MultiCell(45,3,"$nombreMat",0,"L");
	$pdf->SetXY(50,$y+$yyy+1);		$pdf->Cell(7,0,"$cantUnit",0,0,"R");
	$pdf->SetXY(61,$y+$yyy+1);		$pdf->Cell(10,0,"$montoUnit",0,0,"R");
	$montoTotal=$montoTotal+$montoUnit;
	
	$yyy=$yyy+6;
}
$pdf->SetXY(0,$y+$yyy+2);		$pdf->Cell(0,0,"=================================================================================",0,0,"C");		
$yyy=$yyy+5;


$pdf->SetXY(40,$y+$yyy);		$pdf->Cell(0,0,"Total Consumo:",0,0);
$pdf->SetXY(61,$y+$yyy);		$pdf->Cell(10,0,"$montoTotal",0,0,"R");

$pdf->Output();
?>