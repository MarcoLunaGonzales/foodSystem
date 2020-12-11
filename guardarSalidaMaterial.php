<?php

header('Content-Type: text/html; charset=UTF-8');

//error_reporting(-1);

require("conexion.inc");
//require("estilos_almacenes.inc");
require("funciones.php");
require("funciones_inventarios.php");

//SACAMOS LA CONFIGURACION PARA LA  VALIDACION DE STOCKS
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=4";
$respConf=mysql_query($sqlConf);
$banderaValidacionStock=mysql_result($respConf,0,0);

$usuarioVendedor=$_COOKIE['global_usuario'];
$globalSucursal=$_COOKIE['global_agencia'];

$tipoSalida=$_POST['tipoSalida'];
$tipoDoc=$_POST['tipoDoc'];
$almacenDestino=$_POST['almacen'];
$codCliente=$_POST['cliente'];

$tipoPrecio=$_POST['tipoPrecio'];
$tipoOrigen=$_POST['tipoOrigen'];
$razonSocial=$_POST['razonSocial'];
$razonSocial=strtoupper($razonSocial);

$nitCliente=$_POST['nitCliente'];
$codTipoPago=$_POST['tipo_pago'];
$observaciones=$_POST["observaciones"];
$almacenOrigen=$global_almacen;
$telefonoCliente=$_POST["telefono_cliente"];

$totalVenta=$_POST["totalVenta"];
$descuentoVenta=$_POST["descuentoVenta"];
$totalFinal=$_POST["totalFinal"];

$codConsumo=$_POST["codConsumo"];

$totalFinalRedondeado=round($totalFinal);

$fecha=$_POST["fecha"];
$cantidad_material=$_POST["cantidad_material"];

if($descuentoVenta=="" || $descuentoVenta==0){
	$descuentoVenta=0;
}

$fecha=formateaFechaVista($fecha);
//$fecha=date("Y-m-d");
$hora=date("H:i:s");

$sql="SELECT IFNULL(max(cod_salida_almacenes)+1,1) FROM salida_almacenes";
$resp=mysql_query($sql);
$codigo=mysql_result($resp,0,0);


$vectorNroCorrelativo=numeroCorrelativo($tipoDoc);
$nro_correlativo=$vectorNroCorrelativo[0];
$cod_dosificacion=$vectorNroCorrelativo[2];


if($tipoDoc==1){
	//SACAMOS DATOS DE LA DOSIFICACION PARA INSERTAR EN LAS FACTURAS EMITIDAS SOLO CUANDO EL TIPODOC ES 1
	$sqlDatosDosif="select d.nro_autorizacion, d.llave_dosificacion 
		from dosificaciones d where d.cod_dosificacion='$cod_dosificacion'";
	$respDatosDosif=mysql_query($sqlDatosDosif);
	$nroAutorizacion=mysql_result($respDatosDosif,0,0);
	$llaveDosificacion=mysql_result($respDatosDosif,0,1);
	include 'controlcode/sin/ControlCode.php';
	$controlCode = new ControlCode();
	$code = $controlCode->generate($nroAutorizacion,//Numero de autorizacion
								   $nro_correlativo,//Numero de factura
								   $nitCliente,//Número de Identificación Tributaria o Carnet de Identidad
								   str_replace('-','',$fecha),//fecha de transaccion de la forma AAAAMMDD
								   $totalFinalRedondeado,//Monto de la transacción
								   $llaveDosificacion//Llave de dosificación
								   );
	//FIN DATOS FACTURA	
}



$sql_inserta="INSERT INTO `salida_almacenes`(`cod_salida_almacenes`, `cod_almacen`,`cod_tiposalida`, 
		`cod_tipo_doc`, `fecha`, `hora_salida`, `territorio_destino`, 
		`almacen_destino`, `observaciones`, `estado_salida`, `nro_correlativo`, `salida_anulada`, 
		`cod_cliente`, `monto_total`, `descuento`, `monto_final`, razon_social, nit, cod_chofer, cod_vehiculo, monto_cancelado, cod_dosificacion, telefono_cliente, cod_tipopago, cod_tipoorigenpedido)
		values ('$codigo', '$almacenOrigen', '$tipoSalida', '$tipoDoc', '$fecha', '$hora', '0', '$almacenDestino', 
		'$observaciones', '1', '$nro_correlativo', 0, '$codCliente', '$totalVenta', '$descuentoVenta', '$totalFinal', '$razonSocial', 
		'$nitCliente', '$usuarioVendedor', '$vehiculo',0,'$cod_dosificacion','$telefonoCliente','$codTipoPago','$tipoOrigen')";
$sql_inserta=mysql_query($sql_inserta);

if($sql_inserta==1){
	//insertamos la factura solo cuando el tipodoc es 1
	if($tipoDoc==1){
		$sqlInsertFactura="insert into facturas_venta (cod_dosificacion, cod_sucursal, nro_factura, cod_estado, razon_social, nit, fecha, importe, 
		codigo_control, cod_venta) values ('$cod_dosificacion','$globalSucursal','$nro_correlativo','1','$razonSocial','$nitCliente','$fecha','$totalFinal',
		'$code','$codigo')";
		$respInsertFactura=mysql_query($sqlInsertFactura);		
	}

	//echo "inserto factura";
	
	//realizamos una verificacion de los items con su stock tanto si son de venta como si 
	//son insumos de preparado y recetas
	$banderaInventarios=1;
	for($i=1;$i<=$cantidad_material;$i++){   
		$codMaterial=$_POST["materiales$i"];
		
		if($codMaterial!=0){
			$cantidadUnitaria=$_POST["cantidad_unitaria$i"];
			//echo "entrando revisar inventario";
			$respuestaInventarios=revisarInventario($almacenOrigen, $codMaterial, $cantidadUnitaria);
			if($respuestaInventarios!=1){
				$banderaInventarios=2;
			}
		}
		//echo "saliendo revisar inventario";			
	}
	//echo "reviso inventarios";
	if(($banderaValidacionStock==1 & $banderaInventarios==1) || $banderaValidacionStock!=1){
		//DESDE ACA REALIZAMOS LOS DESCUENTOS DE LOS INVENTARIOS
		for($i=1;$i<=$cantidad_material;$i++)
		{   	
			$codMaterial=$_POST["materiales$i"];
			if($codMaterial!=0){
				$cantidadUnitaria=$_POST["cantidad_unitaria$i"];
				$precioUnitario=$_POST["precio_unitario$i"];
				$descuentoProducto=$_POST["descuentoProducto$i"];
				$montoMaterial=$_POST["montoMaterial$i"];
				
				//verificamos si es una preparacion o combo
				$sqlVeri="select cod_tipomaterial from material_apoyo where codigo_material='$codMaterial'";
				$respVeri=mysql_query($sqlVeri);
				$tipoMaterial=mysql_result($respVeri,0,0);
				
				//SI ES PREPARACION O COMBO INSERTAMOS EL DETALLE
				if($tipoMaterial==3 || $tipoMaterial==4){
					$respuesta=insertar_detalleSalidaVenta($codigo, $almacenOrigen,$codMaterial,$cantidadUnitaria,$precioUnitario,$descuentoProducto,$montoMaterial,1, $banderaValidacionStock);
				}else{
					if($tipoMaterial==2 & $banderaValidacionStock!=1){
						$respuesta=insertar_detalleSalidaVenta($codigo, $almacenOrigen,$codMaterial,$cantidadUnitaria,$precioUnitario,$descuentoProducto,$montoMaterial,1, $banderaValidacionStock);
					}else{
						//ESTE ES EL CASO DE LOS INSUMOS DONDE NO HAY STOCKS
						if($banderaValidacionStock==1){
							//echo "descontando aca";
							$respuesta=descontar_inventarios($codigo, $almacenOrigen,$codMaterial,$cantidadUnitaria,$precioUnitario,$descuentoProducto,$montoMaterial,1,0);
						}else{
							$respuesta=insertar_detalleSalidaVenta($codigo, $almacenOrigen,$codMaterial,$cantidadUnitaria,$precioUnitario,$descuentoProducto,$montoMaterial,1, $banderaValidacionStock);
						}				
					}
				}
				if($respuesta!=1){
					echo "<script>
						alert('Existio un error en el detalle. Contacte con el administrador del sistema.');
					</script>";
				}
			}	
		}
		
		//ACA BORRAMOS EL CONSUMO
		if($codConsumo>0){			
			$sqlConsumo="update consumo_sala set cod_estado=0 where cod_mesa='$codConsumo'";
			$respConsumo=mysql_query($sqlConsumo);
			echo "<script>	
				window.opener.document.location='navegador_consumos.php'; 
			</script>";
		}
		
		if($tipoDoc==1){
			echo "<script type='text/javascript' language='javascript'>	
			location.href='formatoFactura.php?codVenta=$codigo';
			</script>";	
			//window.open('formatoFactura.php?codVenta=$codigo','','scrollbars=yes,width=1000,height=800');
		}
		if($tipoDoc==2){
			echo "<script type='text/javascript' language='javascript'>
			location.href='formatoNotaRemision.php?codVenta=$codigo';
			</script>";	
		}
		
	}else{
		$sqlDel="delete from salida_almacenes where cod_salida_almacenes='$codigo'";
		$respDel=mysql_query($sqlDel);
		
		$sqlDel="delete from facturas_venta where cod_venta='$codigo'";
		$respDel=mysql_query($sqlDel);
		
		echo "<script type='text/javascript' language='javascript'>
			alert('Tenemos un problema con los stocks de los productos. Contacte con el administrador del sistema.');
			location.href='navegadorVentas.php';
			</script>";
	}
	
}else{
		echo "<script type='text/javascript' language='javascript'>
		alert('Ocurrio un error en la transaccion. Contacte con el administrador del sistema.');
		location.href='navegador_salidamateriales.php';
		</script>";
}

?>



