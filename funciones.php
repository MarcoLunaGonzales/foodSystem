<?php

function redondear2($valor) { 
   $float_redondeado=round($valor * 100) / 100; 
   return $float_redondeado; 
}

function formateaFechaVista($cadena_fecha)
{	$cadena_formatonuevo=$cadena_fecha[6].$cadena_fecha[7].$cadena_fecha[8].$cadena_fecha[9]."-".$cadena_fecha[3].$cadena_fecha[4]."-".$cadena_fecha[0].$cadena_fecha[1];
	return($cadena_formatonuevo);
}

function formatearFecha2($cadena_fecha)
{	$cadena_formatonuevo=$cadena_fecha[8].$cadena_fecha[9]."/".$cadena_fecha[5].$cadena_fecha[6]."/".$cadena_fecha[0].$cadena_fecha[1].$cadena_fecha[2].$cadena_fecha[3];
	return($cadena_formatonuevo);
}

function obtenerCodigo($sql)
{	require("conexion.inc");
	$resp=mysql_query($sql);
	$nro_filas_sql = mysql_num_rows($resp);
	if($nro_filas_sql==0){
		$codigo=1;
	}else{
		while($dat=mysql_fetch_array($resp))
		{	$codigo =$dat[0];
		}
			$codigo = $codigo+1;
	}
	return($codigo);
}
function stockMaterialesEdit($almacen, $item, $cantidad){
	//
	require("conexion.inc");
	$cadRespuesta="";
	$consulta="
	    SELECT SUM(id.cantidad_restante) as total
	    FROM ingreso_detalle_almacenes id, ingreso_almacenes i
	    WHERE id.cod_material='$item' AND i.cod_ingreso_almacen=id.cod_ingreso_almacen AND i.ingreso_anulado=0 AND i.cod_almacen='$almacen'";
	$rs=mysql_query($consulta);
	$registro=mysql_fetch_array($rs);
	$cadRespuesta=$registro[0];
	if($cadRespuesta=="")
	{   $cadRespuesta=0;
	}
	$cadRespuesta=$cadRespuesta+$cantidad;
	$cadRespuesta=redondear2($cadRespuesta);
	return($cadRespuesta);
}
function restauraCantidades($codigo_registro){
	$sql_detalle="select cod_ingreso_almacen, material, cantidad_unitaria
				from salida_detalle_ingreso
				where cod_salida_almacen='$codigo_registro'";
	$resp_detalle=mysql_query($sql_detalle);
	while($dat_detalle=mysql_fetch_array($resp_detalle))
	{	$codigo_ingreso=$dat_detalle[0];
		$material=$dat_detalle[1];
		$cantidad=$dat_detalle[2];
		$nro_lote=$dat_detalle[3];
		$sql_ingreso_cantidad="select cantidad_restante from ingreso_detalle_almacenes
								where cod_ingreso_almacen='$codigo_ingreso' and cod_material='$material'";
		$resp_ingreso_cantidad=mysql_query($sql_ingreso_cantidad);
		$dat_ingreso_cantidad=mysql_fetch_array($resp_ingreso_cantidad);
		$cantidad_restante=$dat_ingreso_cantidad[0];
		$cantidad_restante_actualizada=$cantidad_restante+$cantidad;
		$sql_actualiza="update ingreso_detalle_almacenes set cantidad_restante=$cantidad_restante_actualizada
						where cod_ingreso_almacen='$codigo_ingreso' and cod_material='$material'";
		
		$resp_actualiza=mysql_query($sql_actualiza);			
	}
	return(1);
}
function numeroCorrelativo($tipoDoc){
	require("conexion.inc");
	$banderaErrorFacturacion=0;
	//SACAMOS LA CONFIGURACION PARA CONOCER SI LA FACTURACION ESTA ACTIVADA
	$sqlConf="select valor_configuracion from configuraciones where id_configuracion=3";
	$respConf=mysql_query($sqlConf);
	$facturacionActivada=mysql_result($respConf,0,0);

	$fechaActual=date("Y-m-d");
	$globalAgencia=$_COOKIE['global_agencia'];
	
	if($facturacionActivada==1 && $tipoDoc==1){
		//VALIDAMOS QUE LA DOSIFICACION ESTE ACTIVA
		$sqlValidar="select count(*) from dosificaciones d 
		where d.cod_sucursal='$globalAgencia' and d.cod_estado=1 and d.fecha_limite_emision>='$fechaActual'";
		$respValidar=mysql_query($sqlValidar);
		$numFilasValidar=mysql_result($respValidar,0,0);
		
		if($numFilasValidar==1){
			$sqlCodDosi="select cod_dosificacion from dosificaciones d 
			where d.cod_sucursal='$globalAgencia' and d.cod_estado=1";
			$respCodDosi=mysql_query($sqlCodDosi);
			$codigoDosificacion=mysql_result($respCodDosi,0,0);
		
			if($tipoDoc==1){//validamos la factura para que trabaje con la dosificacion
				$sql="select IFNULL(max(nro_correlativo)+1,1) from salida_almacenes where cod_tipo_doc='$tipoDoc' 
				and cod_dosificacion='$codigoDosificacion'";	
			}else{
				$sql="select IFNULL(max(nro_correlativo)+1,1) from salida_almacenes where cod_tipo_doc='$tipoDoc'";
			}
			$resp=mysql_query($sql);
			$codigo=mysql_result($resp,0,0);
			
			$vectorCodigo = array($codigo,$banderaErrorFacturacion,$codigoDosificacion);
			return $vectorCodigo;
		}else{
			$banderaErrorFacturacion=1;
			$vectorCodigo = array("DOSIFICACION INCORRECTA O VENCIDA",$banderaErrorFacturacion,0);
			return $vectorCodigo;
		}
	}
	if(($facturacionActivada==1 && $tipoDoc==2) || $facturacionActivada!=1){
		$sql="select IFNULL(max(nro_correlativo)+1,1) from salida_almacenes where cod_tipo_doc='$tipoDoc'";
		$resp=mysql_query($sql);
		while($dat=mysql_fetch_array($resp)){
			$codigo=$dat[0];
			$vectorCodigo = array($codigo,$banderaErrorFacturacion,0);
			return $vectorCodigo;
		}
	}
}
function precioVenta($codigo){
	
	$sqlTipo="select cod_tipomaterial from material_apoyo m where m.codigo_material='$codigo'";
	$respTipo=mysql_query($sqlTipo);
	$codTipoMaterial=mysql_result($respTipo,0,0);
	
	$consulta="select p.`precio` from precios p where p.`codigo_material`='$codigo' and p.`cod_precio`='1'";
	$rs=mysql_query($consulta);
	$registro=mysql_fetch_array($rs);
	$precioVenta=$registro[0];
	if($precioVenta=="")
	{   $precioVenta=0;
	}

	$precioVenta=redondear2($precioVenta);
	if($codTipoMaterial==1){
		$precioVenta="-";
	}
	return $precioVenta;
}

function buscarTelefono($nit){
	$sql="select s.telefono_cliente from salida_almacenes s where s.nit='$nit' and s.telefono_cliente<>'' order by s.cod_salida_almacenes desc;";
	$resp=mysql_query($sql);
	$numFilas=mysql_num_rows($resp);
	$telefono="";
	if($numFilas>0){
			$telefono=mysql_result($resp,0,0);
	}
	return($telefono);
}


?>