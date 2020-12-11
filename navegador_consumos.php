<html lang="en">
  <head>
    <meta charset="utf-8">
	<title>Consumo en Sala</title>
	<script src="lib/js/jquery.min.js"></script>	
	<script src="lib/js/popper.min.js"></script>
	<script src="lib/js/bootstrap-material-design.min.js"></script>
	<script src="lib/js/bootstrap-selectpicker.js"></script>  	
	
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="menuLibs/css/material-dashboard.css" />

  </head>

<script language="JavaScript">

$(function(){
	$("#formDetalleConsumo").on("submit", function(e){
		e.preventDefault();
		var f = $(this);
		console.log("entro a enviar");
		//var divContenedor=document.getElementById('divContenedor').value;
		//console.log("DIV A AFECTAR: "+divContenedor);
		var formData = new FormData(document.getElementById("formDetalleConsumo"))
		$.ajax({
			url: "ajaxGuardaDetalleConsumo.php",
			type: "post",
			dataType: "html",
			data: formData,
			cache: false,
			contentType: false,
			processData: false
		})
			.done(function(res){
				console.log("CODIGO UNICO DETALLE: "+res);
				var codigos = res.split('|');
				var codigoDetalle=codigos[0];
				var numeroPedido=codigos[1];
				console.log("codigo: "+codigoDetalle+" numeroPedido: "+numeroPedido);
				//$("#mensaje").html("Respuesta: " + res);
				//document.getElementById(divContenedor).innerHTML="<i class='material-icons' style='color:green'>attachment</i>";
				var montoNuevo=parseFloat(document.getElementById("footer"+cardGlobal).innerHTML);
				var cantidad=document.getElementById("cantidad_consumo").value;
				var precio=document.getElementById("precio_consumo").value;
				var montoUnitario=(parseFloat(cantidad)*parseFloat(precio));
				var montoCard=montoNuevo+montoUnitario;
				document.getElementById("footer"+cardGlobal).innerHTML=montoCard;
				setItem(codigoDetalle, numeroPedido);
				
				$('#exampleModal').modal('hide');
				//location.reload();
			});
	});
});

var cardGlobal=0;
var filaGlobal=0;

function nuevoAjax()
{	var xmlhttp=false;
	try {
			xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
	} catch (e) {
	try {
		xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	} catch (E) {
		xmlhttp = false;
	}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
 	xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}

function menos(card, fila) {	
	console.log("card: "+card+" fila: "+fila);
	var numeroFilas=parseFloat(document.getElementById('numitems'+card).value);
	console.log("filas:"+numeroFilas);
	if(fila==numeroFilas){
		numeroFilas=parseInt(numeroFilas)-1;
 	}
	var montoNuevo=parseFloat(document.getElementById("footer"+card).innerHTML);
	console.log("montoAnterior: "+montoNuevo);
	var montoUnitario=parseFloat(document.getElementById("div"+card+"monto"+fila).innerHTML);
	console.log("montoDetalle: "+montoUnitario);
	var montoCard=montoNuevo-montoUnitario;
	console.log("montoNuevo: "+montoCard);
	document.getElementById("footer"+card).innerHTML=montoCard;
	if(montoCard==0){
		document.getElementById("card_title"+card).innerHTML="-";		
	}

	var codigoUnico=document.getElementById(card+"codigo_unico"+fila).value;
	
	fi = document.getElementById('fiel'+card);
	fi.removeChild(document.getElementById(card+'div'+fila));
	ajax=nuevoAjax();
	ajax.open("GET","ajaxMinusDetalleConsumo.php?mesa="+card+"&codigo="+codigoUnico,true);
	ajax.onreadystatechange=function(){
		if (ajax.readyState==4) {
			console.log(ajax.responseText);
		}
	}		
	ajax.send(null);
}

function mas(form,indice) {		
	fi = document.getElementById('fiel'+indice);
	var numeroFilas=parseFloat(document.getElementById('numitems'+indice).value);

	console.log("filas: "+numeroFilas);
	
	document.getElementById('numero_mesa').value=indice;
		
	numeroFilas=numeroFilas+1;
	document.getElementById('numitems'+indice).value=numeroFilas;
		
	cardGlobal=indice;
	filaGlobal=numeroFilas;
	
	contenedor = document.createElement('div');
	contenedor.id = indice+'div'+numeroFilas;  
	fi.type="style";
	fi.appendChild(contenedor);
	var div_material;
	div_material=document.getElementById(indice+"div"+numeroFilas);			
	ajax=nuevoAjax();
	ajax.open("GET","ajaxDetalleConsumo.php?card="+indice+"&codigo="+numeroFilas,true);
	ajax.onreadystatechange=function(){
		if (ajax.readyState==4) {
			div_material.innerHTML=ajax.responseText;
		}
	}		
	ajax.send(null);
}

function ajaxPrecioItem(indice){
	var contenedor;
	contenedor=document.getElementById("divprecio");
	var codmat=document.getElementById("item_consumo").value;
	document.getElementById("cantidad_consumo").value="1";
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxPrecioItem2.php?codmat="+codmat,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
			calculaMontoConsumo();
			//calculaMontoMaterial(indice);
		}
	}
	ajax.send(null);
}

function calculaMontoConsumo(){
	var cantidadUnitaria=document.getElementById("cantidad_consumo").value;
	var precioUnitario=document.getElementById("precio_consumo").value;
	var montoUnitario=(parseFloat(cantidadUnitaria)*parseFloat(precioUnitario));
	montoUnitario=Math.round(montoUnitario*100)/100;
	document.getElementById("monto_consumo").value=montoUnitario;
}

function setItem(codigoUnico, nroPedido){
	var codItem=document.getElementById("item_consumo").value;
	var combo = document.getElementById("item_consumo");
	var nombreItem = combo.options[combo.selectedIndex].text;
	var cantidad=document.getElementById("cantidad_consumo").value;
	var precio=document.getElementById("precio_consumo").value;
	var montoUnitario=(parseFloat(cantidad)*parseFloat(precio));
	
	if(codItem=="" || codItem==0){
		alert("Debe seleccionar un Item.");
	}
	if(cantidad=="" || cantidad==0){
		alert("La cantidad no es valida.");
	}
	if(precio=="" || precio==0){
		alert("El precio no es valido.");
	}
	document.getElementById("div"+cardGlobal+"item"+filaGlobal).innerHTML=nombreItem;
	document.getElementById("div"+cardGlobal+"cantidad"+filaGlobal).innerHTML=cantidad;
	document.getElementById("div"+cardGlobal+"monto"+filaGlobal).innerHTML=montoUnitario;

	document.getElementById(cardGlobal+"codigo_unico"+filaGlobal).value=codigoUnico;	
	
	if(nroPedido>0){
		document.getElementById("card_title"+cardGlobal).innerHTML="PEDIDO # "+nroPedido;	
	}
}

</script>


  <body>
<?php
	require("conexion.inc");
	require("estilos_almacenes.inc");
?>

<h1 class="text-center">Registro de Consumos</h1>

<div class="content">
	<div class="container-fluid">
		<div class="row">

			<?php
			$colorCard="card-header-primary";
			for($i=1;$i<=9;$i++){
				if($i==1){	$colorCard="primary";	}
				if($i==2){	$colorCard="warning";	}
				if($i==3){	$colorCard="rose";	}
				if($i==4){	$colorCard="info";	}
				if($i==5){	$colorCard="success";	}
				if($i==6){	$colorCard="danger";	}
				if($i==7){	$colorCard="primary";	}
				if($i==8){	$colorCard="warning";	}
				if($i==9){	$colorCard="rose";	}
				
				//sacamos el numero de PEDIDO
				$sqlNroPedido="select nro_pedido from consumo_sala c where c.cod_estado=1 and c.cod_mesa='$i'";
				$respNroPedido=mysql_query($sqlNroPedido);
				$nroFilasPedido=mysql_num_rows($respNroPedido);
				$numeroPedido=0;				
				$txtNroPedido="-";
				if($nroFilasPedido>0){
					$numeroPedido=mysql_result($respNroPedido,0,0);
					$txtNroPedido="Pedido # $numeroPedido";
				}

			?>
			<div class="col-md-4">
			  <div class="card">
				<div class="card-header card-header-text card-header-<?=$colorCard;?>">
				  <div class="card-text">
					<h6 class="card-title">Mesa <?=$i;?></h6>
					<h4 class="card-title"><div id="card_title<?=$i;?>"><?=$txtNroPedido;?></div></h4>
					</div>
				</div>
				<div class="card-body table-responsive">
				
					<form name="form<?=$i;?>" method="post" action="guardaConsumos.php">
					<fieldset id="fiel<?=$i;?>" style="width:100%;border:0;">

					<table class="table table-condensed bordered" width="100%">
						<thead class="text-warning">
							<th width="50%">Item</th>
							<th width="15%">Cant.</th>
							<th width="15%">Monto</th>
							<th width="5%">-</th>
						</thead>
					</table>
						<?php
						$sqlDetalle="select cd.cod_material, (select m.descripcion_material from material_apoyo m where m.codigo_material=cd.cod_material)material, 
						cd.cantidad, cd.precio, cd.monto, cd.codigo from consumo_sala c, consumo_saladetalle cd where c.cod_estado=1 and c.codigo=cd.cod_consumo and c.cod_mesa='$i'";
						$respDetalle=mysql_query($sqlDetalle);
						$num=1;
						$totalCard=0;
						while($datDetalle=mysql_fetch_array($respDetalle)){
							$codItem=$datDetalle[0];
							$nombreItem=$datDetalle[1];
							$cantidadItem=$datDetalle[2];
							$precioItem=$datDetalle[3];
							$montoItem=$datDetalle[4];
							$totalCard+=$montoItem;
							$codigoUnico=$datDetalle[5];
						?>
							<div id="<?=$i;?>div<?=$num;?>">
							<table class="table table-condensed bordered" width="100%">
							<tbody>
								<input type="hidden" name="<?=$i;?>item<?=$num;?>" id="<?=$i;?>item<?=$num;?>" value="<?=$codItem;?>">
								<input type="hidden" name="<?=$i;?>cantidad<?=$num;?>" id="<?=$i;?>cantidad<?=$num;?>" value="<?=$cantidadItem;?>">
								<input type="hidden" name="<?=$i;?>precio<?=$num;?>" id="<?=$i;?>precio<?=$num;?>" value="<?=$precioItem;?>">
								<input type="hidden" name="<?=$i;?>monto<?=$num;?>" id="<?=$i;?>monto<?=$num;?>" value="<?=$montoItem;?>">
								<input type="hidden" name="<?=$i;?>codigo_unico<?=$num;?>" id="<?=$i;?>codigo_unico<?=$num;?>" value="<?=$codigoUnico;?>">

								<tr>
									<td width="50%" class="text-left"><?=$nombreItem;?></td>
									<td width="15%"><?=$cantidadItem;?></td>
									<td width="15%"><div id="div<?=$i;?>monto<?=$num;?>" class="text-center"><?=$montoItem;?></div></td>
									<td width="5%" align="center">
										<a href="#" class="boton2peque" onclick="menos(<?=$i;?>,<?=$num;?>)" size="5">-</a>
									</td>
								</tr>
							</tbody>
							</table>
							</div>
						<?php
							$num++;
						}
						?>
						<input type="hidden" name="numitems<?=$i;?>" id="numitems<?=$i;?>" value="<?=$num;?>">
				</fieldset>
					<table class="table table-condensed bordered" width="100%">
						<tfoot class="text-danger">
							<th width="50%">-</th>
							<th width="15%">-</th>
							<th width="15%"><div id="footer<?=$i;?>"><?=$totalCard;?></div></th>
							<th width="5%">-</th>
						</tfoot>
					</table>
				  
					<table class="table table-condensed bordered" width="100%">
						<td class="text-left">
							<a href="#" class="btn btn-<?=$colorCard;?> btn-round btn-fab" data-toggle="modal" data-target="#exampleModal" onClick="mas(this.form,<?=$i;?>)">
								<i class="material-icons">add</i>
							</a>
						</td>
						<td class="text-right">
							<a href="notaConsumo.php?codConsumo=<?=$i;?>" class="btn btn-primary btn-round btn-fab" target="_BLANK">
								<i class="material-icons" title="Imprimir">local_printshop</i>
							</a>
							<a href="#" onclick="window.open('registrarVentaConsumo.php?codConsumo=<?=$i;?>')"  class="btn btn-primary btn-round btn-fab">
								<i class="material-icons" title="Registrar Venta">launch</i>
							</a>
						</td>
					</table>
					
					</form>
				</div>
				
			  </div>
			</div>
			
			<?php
			}
			?>
		</div>
	</div>
</div

  </body>
</html>



<!-- Modal -->
<form id="formDetalleConsumo" name="formDetalleConsumo" class="form-horizontal" method="post">

<input type="hidden" name="numero_mesa" id="numero_mesa" value="0">
<input type="hidden" name="cliente_mesa" id="cliente_mesa" value="0">

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Adicionar Consumo en Mesa</h5>
        <!--button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button-->
      </div>
      <div class="modal-body">
		<div class="col-md-12">
			<div class="row">
			
				<div class="col-md-6">
					<div class="form-group">
						<label for="item_consumo" class="bmd-label-floating">Item</label>	
						<select class="selectpicker form-control" data-style="btn btn-primary" name="item_consumo" id="item_consumo" data-live-search="true" required="true" onChange="ajaxPrecioItem();">
							<option disabled selected value="">Item</option>
								<?php
								$sql="select m.codigo_material, m.descripcion_material, imagen from material_apoyo m where cod_tipomaterial in (2,3,4) and estado=1 order by 2";
								$resp=mysql_query($sql);
								while ($dat = mysql_fetch_array($resp)) {
									$codigoX=$dat[0];
									$nombreX=$dat[1];
								?>
								<option value="<?=$codigoX;?>"><?=$nombreX;?></option>	
								<?php
								}
								?>
						</select>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label for="cantidad_consumo" class="bmd-label-floating">Cantidad</label>	
						<input class="form-control" type="number" min="1" step="1" name="cantidad_consumo" id="cantidad_consumo" onChange="calculaMontoConsumo();" onKeyUp="calculaMontoConsumo();" placeholder="Cantidad" value="" required />
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label for="precio_consumo" class="bmd-label-floating">Precio</label>	
						<div id="divprecio"><input class="form-control" type="number" min="0.1" step="0.1" name="precio_consumo" id="precio_consumo" onChange="calculaMontoConsumo();" onKeyUp="calculaMontoConsumo();" placeholder="Precio" required /></div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label for="monto_consumo" class="bmd-label-floating">Monto</label>	
						<input class="form-control" type="number" name="monto_consumo" id="monto_consumo" placeholder="Monto" disabled="true"/>
					</div>
				</div>
			</div>
		</div>
      </div>
      <div class="modal-footer">
        <!--button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button-->
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>
</form>
