<?php
require("conexion.inc");

function verificarStock($cod_almacen, $cod_material, $cantidad){
	$banderaError=1;

	$sqlExistencias="select IFNULL(sum(id.cantidad_restante),0)
		from ingreso_almacenes i, ingreso_detalle_almacenes id 
		where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$cod_almacen' and i.ingreso_anulado=0 
		and id.cod_material='$cod_material' and id.cantidad_restante>0";
	
	//echo $sqlExistencias;
	
	$respExistencias=mysql_query($sqlExistencias);
	$stockItem=mysql_result($respExistencias,0,0);
	if($stockItem<$cantidad){
		$banderaError=2;
	}
	return($banderaError);
}

function revisarInventario($cod_almacen, $cod_material, $cantidad){
	$banderaError=1;
	//sacamos el tipo de material si es 3 descontamos los insumos y si es 4 descontamos los insumos de los productos del combo
	//echo "entrando funcion revisar. ";
	$sqlTipo="select cod_tipomaterial from material_apoyo where codigo_material='$cod_material'";
	$respTipo=mysql_query($sqlTipo);
	$codTipo=mysql_result($respTipo,0,0);
	if($codTipo==3){
		$sqlInsumos="select m.cod_insumo, m.cantidad_insumo from material_insumos m where m.cod_material='$cod_material'";
		
		//echo $sqlInsumos;
		
		$respInsumos=mysql_query($sqlInsumos);
		while($datInsumos=mysql_fetch_array($respInsumos)){
			$codInsumo=$datInsumos[0];
			$cantidadInsumo=$datInsumos[1];
			$cantidadInsumo=$cantidadInsumo*$cantidad;
			$revisarInventario=verificarStock($cod_almacen,$codInsumo, $cantidadInsumo);
			if($revisarInventario!=1){
				$banderaError=2;
			}
		}
	}
	
	if($codTipo==4){
		$sqlRecetas="select m.cod_insumo, m.cantidad_insumo from material_insumos m where m.cod_material='$cod_material'";
		$respRecetas=mysql_query($sqlRecetas);
		while($datRecetas=mysql_fetch_array($respRecetas)){
			$codReceta=$datRecetas[0];
			$cantidadReceta=$datRecetas[1];
			$cantidadReceta=$cantidadReceta*$cantidad;
			
			$sqlTipoX="select cod_tipomaterial from material_apoyo where codigo_material='$codReceta'";
			$respTipoX=mysql_query($sqlTipoX);
			$codTipoX=mysql_result($respTipoX,0,0);
			if($codTipoX==3){
				$sqlInsumos="select m.cod_insumo, m.cantidad_insumo from material_insumos m where m.cod_material='$codReceta'";
				$respInsumos=mysql_query($sqlInsumos);
				while($datInsumos=mysql_fetch_array($respInsumos)){
					$codInsumo=$datInsumos[0];
					$cantidadInsumo=$datInsumos[1];
					$cantidadInsumo=$cantidadInsumo*$cantidad;
					
					$revisarInventario=verificarStock($cod_almacen,$codInsumo, $cantidadInsumo);
					if($revisarInventario!=1){
						$banderaError=2;
					}
				}	
			}else{
				$revisarInventario=verificarStock($cod_almacen, $codReceta, $cantidadReceta);
				if($revisarInventario!=1){
					$banderaError=2;
				}
			}
		}	
	}
	return($banderaError);
}

function descontar_inventarios($cod_salida, $cod_almacen, $cod_material, $cantidad, $precio, $descuento, $montoparcial, $estado, $cod_materialrelacionado){
	
	//echo $cod_salida." ".$cod_almacen." ".$cod_material." ".$cantidad;
	$cantidadPivote=$cantidad;
	
	$banderaError=1;
	
	$sqlExistencias="select id.cod_material, id.cantidad_restante, id.lote, id.fecha_vencimiento, id.cod_ingreso_almacen 
		from ingreso_almacenes i, ingreso_detalle_almacenes id 
		where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$cod_almacen' and i.ingreso_anulado=0 
		and id.cod_material='$cod_material' and id.cantidad_restante>0 order by id.lote, id.fecha_vencimiento asc";
	//echo $sqlExistencias."<br>";
	$respExistencias=mysql_query($sqlExistencias);
	while($datExistencias=mysql_fetch_array($respExistencias)){
		if($cantidadPivote>0){
			$codMaterial=$datExistencias[0];
			$cantidadRestante=$datExistencias[1];
			$loteProducto=$datExistencias[2];
			$fechaVencProducto=$datExistencias[3];
			$codIngreso=$datExistencias[4];
			
			//echo $codMaterial." ".$cantidadRestante." ".$loteProducto." ".$fechaVencProducto."<br>";
			
			if($cantidadPivote<=$cantidadRestante){
				$cantidadInsert=$cantidadPivote;
				$cantidadPivote=0;
			}else{
				$cantidadPivote=$cantidadPivote-$cantidadRestante;
				$cantidadInsert=$cantidadRestante;
			}
			$sqlInsert="insert into salida_detalle_almacenes (cod_salida_almacen, cod_material, cantidad_unitaria, lote, fecha_vencimiento, precio_unitario,
			descuento_unitario, monto_unitario, estado, cod_materialrelacionado) values ('$cod_salida', '$codMaterial', '$cantidadInsert', '$loteProducto', '$fechaVencProducto',
			'$precio','$descuento','$montoparcial','$estado','$cod_materialrelacionado')";
			//echo $sqlInsert."<br>";
			$respInsert=mysql_query($sqlInsert);
			
			if($respInsert!=1){
				$banderaError=2;
			}
			
			$sqlUpd="update ingreso_detalle_almacenes set cantidad_restante=cantidad_restante-$cantidadInsert where 
			cod_ingreso_almacen='$codIngreso' and lote='$loteProducto' and cod_material='$codMaterial'";
			$respUpd=mysql_query($sqlUpd);
			
			if($respUpd!=1){
				$banderaError=3;
			}
		}
		//ESTO SE APLICA CUANDO NO HAY EXISTENCIAS DE UN PRODUCTO PERO IGUAL INSERTAMOS POR REALIZAR LA SALIDA DE ALMACENES
		if($cantidadPivote>0){
			$sqlInsert="insert into salida_detalle_almacenes (cod_salida_almacen, cod_material, cantidad_unitaria, lote, fecha_vencimiento, precio_unitario,
			descuento_unitario, monto_unitario, estado) values ('$cod_salida', '$codMaterial', '$cantidadPivote', '0', '0000-00-00',
			'$precio','$descuento','$montoparcial','$estado')";
			$respInsert=mysql_query($sqlInsert);
		}
	}
	
	return($banderaError);
}

function insertar_detalleSalidaVenta($cod_salida, $cod_almacen, $cod_material, $cantidad, $precio, $descuento, $montoparcial, $estado, $banderaStock){
	
	//la $banderaStock es 1 cuando se validan stocks y 0 cuando no se validan los stocks
	//echo $cod_salida." ".$cod_almacen." ".$cod_material." ".$cantidad;
	$cantidadPivote=$cantidad;
	
	$banderaError=1;
	
	$sqlInsert="insert into salida_detalle_almacenes (cod_salida_almacen, cod_material, cantidad_unitaria, lote, fecha_vencimiento, precio_unitario,
	descuento_unitario, monto_unitario, estado) values ('$cod_salida', '$cod_material', '$cantidad', '0', '0000-00-00',
	'$precio','$descuento','$montoparcial','$estado')";
	//echo $sqlInsert;
	$respInsert=mysql_query($sqlInsert);
	if($respInsert!=1){
		$banderaError=2;
	}
	
	//sacamos el tipo de material si es 3 descontamos los insumos y si es 4 descontamos los insumos de los productos del combo
	$sqlTipo="select cod_tipomaterial from material_apoyo where codigo_material='$cod_material'";
	//echo $sqlTipo;
	$respTipo=mysql_query($sqlTipo);
	$codTipo=mysql_result($respTipo,0,0);
	if($codTipo==3){
		$sqlInsumos="select m.cod_insumo, m.cantidad_insumo from material_insumos m where m.cod_material='$cod_material'";
		$respInsumos=mysql_query($sqlInsumos);
		while($datInsumos=mysql_fetch_array($respInsumos)){
			$codInsumo=$datInsumos[0];
			$cantidadInsumo=$datInsumos[1];
			$cantidadInsumo=$cantidadInsumo*$cantidad;
			if($banderaStock==1){
				$revisarInventario=descontar_inventarios($cod_salida, $cod_almacen, $codInsumo, $cantidadInsumo, 0, 0, 0, 0, $cod_material);
			}else{
				$revisarInventario=insertar_detalleSalidaVenta2($cod_salida, $cod_almacen, $codInsumo, $cantidadInsumo, 0, 0, 0, 0, $banderaStock, $cod_material);
			}
			if($revisarInventario!=1){
				$banderaError=2;
			}
		}
	}
	if($codTipo==4){
		$sqlRecetas="select m.cod_insumo, m.cantidad_insumo from material_insumos m where m.cod_material='$cod_material'";
		$respRecetas=mysql_query($sqlRecetas);
		while($datRecetas=mysql_fetch_array($respRecetas)){
			$codReceta=$datRecetas[0];
			$cantidadReceta=$datRecetas[1];
			$cantidadReceta=$cantidadReceta*$cantidad;
			$sqlTipoX="select cod_tipomaterial from material_apoyo where codigo_material='$codReceta'";
			$respTipoX=mysql_query($sqlTipoX);
			$codTipoX=mysql_result($respTipoX,0,0);
			if($codTipoX==3){
				$sqlInsumos="select m.cod_insumo, m.cantidad_insumo from material_insumos m where m.cod_material='$codReceta'";
				$respInsumos=mysql_query($sqlInsumos);
				while($datInsumos=mysql_fetch_array($respInsumos)){
					$codInsumo=$datInsumos[0];
					$cantidadInsumo=$datInsumos[1];
					$cantidadInsumo=$cantidadInsumo*$cantidadReceta;
					if($banderaStock==1){
						$revisarInventario=descontar_inventarios($cod_salida, $cod_almacen, $codInsumo, $cantidadInsumo, 0, 0, 0, 0, $codReceta);
					}else{
						$revisarInventario=insertar_detalleSalidaVenta2($cod_salida, $cod_almacen, $codInsumo, $cantidadInsumo, 0, 0, 0, 0, $banderaStock, $codReceta);
					}
					if($revisarInventario!=1){
						$banderaError=2;
					}
				}	
			}else{
				if($banderaStock==1){
					$revisarInventario=descontar_inventarios($cod_salida, $cod_almacen, $codReceta, $cantidadReceta, 0, 0, 0, 0, 0);
				}else{
					$revisarInventario=insertar_detalleSalidaVenta2($cod_salida, $cod_almacen, $codReceta, $cantidadReceta, 0, 0, 0, 0, $banderaStock, $codReceta);
				}
				if($revisarInventario!=1){
					$banderaError=2;
				}
			}
		}	
	}
	return($banderaError);
}


function insertar_detalleSalidaVenta2($cod_salida, $cod_almacen, $cod_material, $cantidad, $precio, $descuento, $montoparcial, $estado, $banderaStock, $cod_materialrelacionado){
	
	//la $banderaStock es 1 cuando se validan stocks y 0 cuando no se validan los stocks
	//echo $cod_salida." ".$cod_almacen." ".$cod_material." ".$cantidad;
	$cantidadPivote=$cantidad;
	
	$banderaError=1;
	
	$sqlInsert="insert into salida_detalle_almacenes (cod_salida_almacen, cod_material, cantidad_unitaria, lote, fecha_vencimiento, precio_unitario,
	descuento_unitario, monto_unitario, estado, cod_materialrelacionado) values ('$cod_salida', '$cod_material', '$cantidad', '0', '0000-00-00',
	'$precio','$descuento','$montoparcial','$estado','$cod_materialrelacionado')";
	//echo $sqlInsert;
	$respInsert=mysql_query($sqlInsert);
	if($respInsert!=1){
		$banderaError=2;
	}
	
	return($banderaError);
}

?>