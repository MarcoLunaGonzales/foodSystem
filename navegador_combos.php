<?php

echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='registrar_combo.php';
		}
		function eliminar_nav(f)
		{
			var i;
			var j=0;
			datos=new Array();
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	datos[j]=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j==0)
			{	alert('Debe seleccionar al menos un material de apoyo para proceder a su eliminación.');
			}
			else
			{
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminar_combos.php?datos='+datos+'';
				}
				else
				{
					return(false);
				}
			}
		}
		function editar_nav(f)
		{
			var i;
			var j=0;
			var j_ciclo;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_ciclo=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un material de apoyo para editar sus datos.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un material de apoyo para editar sus datos.');
				}
				else
				{
					location.href='editar_combo.php?cod_material='+j_ciclo+'';
				}
			}
		}
		function duplicar(f)
		{
			var i;
			var j=0;
			var j_ciclo;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_ciclo=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un registro para duplicarlo.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un registro para duplicarlo.');
				}
				else
				{
					location.href='duplicarProducto.php?cod_material='+j_ciclo+'&tipo=3';
				}
			}
		}
		function cambiar_vista(sel_vista, f)
		{
			var modo_vista;
			modo_vista=sel_vista.value;
			location.href='navegador_combos.php?vista='+modo_vista+'';
		}
		</script>";
		
	require("conexion.inc");
	require('estilos.inc');
	require('funciones.php');
	
	echo "<h1>Registro de Combos</h1>";

	echo "<form method='post' action=''>";
	$sql="select m.codigo_material, m.descripcion_material, m.estado, 
		(select e.nombre_grupo from grupos e where e.cod_grupo=m.cod_grupo), 
		(select t.nombre_tipomaterial from tipos_material t where t.cod_tipomaterial=m.cod_tipomaterial), 
		(select pl.nombre_linea_proveedor from proveedores p, proveedores_lineas pl where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=m.cod_linea_proveedor),
		m.observaciones, imagen
		from material_apoyo m
		where m.estado='1' and m.cod_tipomaterial in (4) order by m.descripcion_material";
	if($vista==1)
	{	$sql="select m.codigo_material, m.descripcion_material, m.estado, 
		(select e.nombre_grupo from grupos e where e.cod_grupo=m.cod_grupo), 
		(select t.nombre_tipomaterial from tipos_material t where t.cod_tipomaterial=m.cod_tipomaterial), 
		(select pl.nombre_linea_proveedor from proveedores p, proveedores_lineas pl where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=m.cod_linea_proveedor),
		m.observaciones, imagen
		from material_apoyo m
		where m.estado='0' and m.cod_tipomaterial in (4) order by m.descripcion_material";
	}
	
	//echo $sql;
	$resp=mysql_query($sql);
	
	echo "<table align='center' class='texto'><tr><th>Ver Combos:</th>
	<th><select name='vista' class='texto' onChange='cambiar_vista(this, this.form)'>";
	if($vista==0)	echo "<option value='0' selected>Activos</option><option value='1'>Retirados</option><option value='2'>Todo</option>";
	if($vista==1)	echo "<option value='0'>Activos</option><option value='1' selected>Retirados</option><option value='2'>Todo</option>";
	if($vista==2)	echo "<option value='0'>Activos</option><option value='1'>Retirados</option><option value='2' selected>Todo</option>";
	echo "</select>";
	echo "</th></tr></table><br>";
	
	echo "<center><table border='0' class='textomini'><tr><th>Leyenda:</th><th>Combos Retirados</th><td bgcolor='#ff6666' width='30%'></td></tr></table></center><br>";
	
	
	echo "<div class='divBotones'>
		<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'>
		<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'>
		<input type='button' value='Eliminar' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
		<input type='button' value='Duplicar' name='Duplicar' class='boton' onclick='duplicar(this.form)'>

		</div>";
	
	echo "<center><table class='texto'>";
	echo "<tr><th>Indice</th><th>&nbsp;</th><th>Nombre Combo</th><th>Descripcion</th>
		<th>Grupo</th><th>Tipo</th><th>Precio Venta</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th></tr>";
	
	$indice_tabla=1;
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$nombreProd=$dat[1];
		$estado=$dat[2];
		$grupo=$dat[3];
		$tipoMaterial=$dat[4];
		$nombreLinea=$dat[5];
		$observaciones=$dat[6];
		$imagen=$dat[7];
		$precioVenta=precioVenta($codigo);
		
		$sqlInsumos="select m.cod_insumo, (select ma.descripcion_material from material_apoyo ma where 
			ma.codigo_material=m.cod_insumo), m.cantidad_insumo
			 from material_insumos m where m.cod_material=$codigo";
		$respInsumos=mysql_query($sqlInsumos);
		$txtDetalle="<table class='texto'><tr><th>Producto</th><th>Cant</th></tr>";
		while($datInsumos=mysql_fetch_array($respInsumos)){
			$codInsumo=$datInsumos[0];
			$nombreInsumo=$datInsumos[1];
			$cantidadInsumo=$datInsumos[2];
			$txtDetalle.="<tr><td>$nombreInsumo</td><td>$cantidadInsumo</td></tr>";
		}
		$txtDetalle.="</table>";
		
		$sqlComodines="select (select g.nombre_grupo from grupos g where 
			g.cod_grupo=m.cod_grupo), (select t.nombre from tamanios t where 
			t.codigo=m.cod_tamanio) from material_comodines m where m.cod_material=$codigo";
		$respComodines=mysql_query($sqlComodines);
		$txtDetalle2="<table class='texto'><tr><th>Grupo</th><th>Tamaño</th></tr>";
		while($datComodines=mysql_fetch_array($respComodines)){
			$nombreGrupoX=$datComodines[0];
			$nombreTamanioX=$datComodines[1];
			$txtDetalle2.="<tr><td>$nombreGrupoX</td><td>$nombreTamanioX</td></tr>";
		}
		$txtDetalle2.="</table>";
		
		echo "<tr><td align='center'>$indice_tabla</td><td align='center'>
		<input type='checkbox' name='codigo' value='$codigo'></td>
		<td>$nombreProd</td><td>$observaciones</td>
		<td>$grupo</td>
		<td>$tipoMaterial</td>
		<td align='center'>$precioVenta [Bs]</td>
		<td>$txtDetalle</td>
		<td>$txtDetalle2</td>
		<td><img src='imagenesprod/$imagen' width='200'></td>
		<td><a href='reemplazarImagen.php?codigo=$codigo&nombre=$nombreProd'><img src='imagenes/change.png' width='40' title='Reemplazar Imagen'></a></td>
		</tr>";
		$indice_tabla++;
	}
	echo "</table></center><br>";
	
		echo "<div class='divBotones'>
		<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'>
		<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'>
		<input type='button' value='Eliminar' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
		<input type='button' value='Duplicar' name='Duplicar' class='boton' onclick='duplicar(this.form)'>
	</div>";
		
	echo "</form>";
?>
