<html>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="lib/externos/jquery/jquery-ui/completo/jquery-ui-1.8.9.custom.css" rel="stylesheet" type="text/css"/>
        <link href="lib/css/paneles.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.core.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.widget.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.button.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.mouse.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.draggable.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.position.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.resizable.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.dialog.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.datepicker.min.js"></script>
        <script type="text/javascript" src="lib/js/xlibPrototipo-v0.1.js"></script>

<script>
function funOk(codReg,funOkConfirm){   
	$.get("programas/ingresos/frmConfirmarCodigoIngreso.php","codigo="+codReg, function(inf1) {
        dlgAC("#pnldlgAC","Codigo de confirmacion",inf1,function(){
            var cad1=$("input#idtxtcodigo").val();
            var cad2=$("input#idtxtclave").val();
            if(cad1!="" && cad2!="") {
                dlgEsp.setVisible(true);
                $.get("programas/ingresos/validacionCodigoConfirmar.php","codigo="+cad1+"&clave="+cad2, function(inf2) {
                    inf2=xtrim(inf2);
                    dlgEsp.setVisible(false);
                    if(inf2=="" || inf2=="OK") {
                        /**/funOkConfirm();/**/
                    } else {
                        dlgA("#pnldlgA2","Informe","<div class='pnlalertar'>El codigo ingresado es incorrecto.</div>",function(){},function(){});
                    }
                });
            } else {
                dlgA("#pnldlgA3","Informe","<div class='pnlalertar'>Introducir el codigo de confirmacion.</div>",function(){},function(){});
            }
        },function(){});
    });
}
function enviar_nav()
{   location.href='registrar_cajachica.php';
}
function editar_gasto(f)
{   var i;
    var j=0;
    var j_cod_registro;
    var fecha_registro;
    for(i=0;i<=f.length-1;i++)
    {   if(f.elements[i].type=='checkbox')
        {   if(f.elements[i].checked==true)
            {   j_cod_registro=f.elements[i].value;
                fecha_registro=f.elements[i-1].value;
                j=j+1;
            }
        }
    }
	if(j>1)
    {   alert('Debe seleccionar solamente un registro.');
    }
    else
    {   if(j==0)
        {   alert('Debe seleccionar un registro.');
        }
        else
        {       funOk(j_cod_registro,function(){
                    location.href='editar_cajachica.php?cod_cajachica='+j_cod_registro+'';
                });
        }
    }
}
function anular_gasto(f)
{   
	var i;
    var j=0;
    var j_cod_registro;
    var fecha_registro;
    for(i=0;i<=f.length-1;i++)
    {   if(f.elements[i].type=='checkbox')
        {   if(f.elements[i].checked==true)
            {   j_cod_registro=f.elements[i].value;
                fecha_registro=f.elements[i-1].value;
                j=j+1;
            }
        }
    }
    if(j>1)
    {   alert('Debe seleccionar solamente un registro para anularlo.');
    }
    else
    {   if(j==0)
        {   alert('Debe seleccionar un registro para anularlo.');
        }
        else
        {	funOk(j_cod_registro,function(){
                    location.href='anular_cajachica.php?codigo_registro='+j_cod_registro;
             });
        }
    }
}
        </script>
    </head>
    <body>
<form method='post' name='form1' action=''>
<?php

require("conexion.inc");
require('function_formatofecha.php');
require("estilos_almacenes.inc");
require('home_almacen.php');
require('funciones.php');

$fechaHoy=date("d/m/Y");
$fechaHoyFormato=date("Y-m-d");

$sqlVeri="select count(*) from cajachica_inicio where fecha_cajachica='$fechaHoyFormato'";
//echo $sqlVeri;
$respVeri=mysql_query($sqlVeri);
$registrosHoy=mysql_result($respVeri,0,0);


echo "<h1>Registro Apertura de Caja Chica</h1>";

	if($registrosHoy>0){
		echo "<div class='divBotones'>
			<input type='button' value='Editar' name='editar' class='boton' onclick='editar_gasto(this.form)'>
			<input type='button' value='Eliminar' name='adicionar' class='boton2' onclick='anular_gasto(this.form)'>
			</div>";
	}else{
		echo "<div class='divBotones'>
		<input type='button' value='Registrar' name='adicionar' class='boton' onclick='enviar_nav()'>
				<input type='button' value='Editar' name='editar' class='boton' onclick='editar_gasto(this.form)'>
<input type='button' value='Eliminar' name='adicionar' class='boton2' onclick='anular_gasto(this.form)'></div>";
	}


	echo "<br><center><table class='texto'>";

	echo "<tr><th>&nbsp;</th><th>Fecha</th><th>Monto Caja Chica</th></tr>";
	
	$consulta = "select DATE_FORMAT(c.fecha_cajachica, '%d/%m/%Y'), c.monto, c.fecha_cajachica from cajachica_inicio c";
	$resp = mysql_query($consulta);

	while ($dat = mysql_fetch_array($resp)) {
		$fechaCajaChica = $dat[0];
		$montoCajaChica = $dat[1];
		$fechaCodigo = $dat[2];
		
		$montoCajaChica=redondear2($montoCajaChica);
		
		if($fechaCajaChica==$fechaHoy){
			$chkbox = "<input type='checkbox' name='codigo' value='$fechaCodigo'>";
		}else{
			$chkbox="<input type='checkbox' name='codigo' value='$fechaCodigo'>";
		}
		
		echo "<tr>
		<td align='center'>$chkbox</td>
		<td align='center'>$fechaCajaChica</td>
		<td align='center'>$montoCajaChica</td>
		</tr>";
	}
	echo "</table></center><br>";
	
	if($registrosHoy>0){
		echo "<div class='divBotones'>
			<input type='button' value='Editar' name='editar' class='boton' onclick='editar_gasto(this.form)'>
			<input type='button' value='Eliminar' name='adicionar' class='boton2' onclick='anular_gasto(this.form)'>
			</div>";
	}else{
		echo "<div class='divBotones'>
		<input type='button' value='Registrar' name='adicionar' class='boton' onclick='enviar_nav()'>
		<input type='button' value='Editar' name='editar' class='boton' onclick='editar_gasto(this.form)'>
		<input type='button' value='Eliminar' name='adicionar' class='boton2' onclick='anular_gasto(this.form)'></div>";
	}
	
	echo "</form>";
?>
    
	<div id="pnldlgfrm"></div>
        <div id="pnldlgSN"></div>
        <div id="pnldlgAC"></div>
        <div id="pnldlgA1"></div>
        <div id="pnldlgA2"></div>
        <div id="pnldlgA3"></div>
        <div id="pnldlgArespSvr"></div>
        <div id="pnldlggeneral"></div>
        <div id="pnldlgenespera"></div>
	</body>
	
	
</html>
