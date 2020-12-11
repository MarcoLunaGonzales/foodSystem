<?php
require("conexion.inc");
require("estilos.inc");

//recogemos variables
$codProducto=$_POST['codProducto'];

$fechahora=date("dmy.Hi");
$archivoName=$fechahora.$_FILES['archivo']['name'];
if ($_FILES['archivo']["error"] > 0){
	echo "Error: " . $_FILES['archivo']['error'] . "<br>";
}
move_uploaded_file($_FILES['archivo']['tmp_name'], "imagenesprod/".$archivoName);	

$sqlUpd="update material_apoyo set imagen='$archivoName' where codigo_material='$codProducto'";
$respUpd=mysql_query($sqlUpd);

if($respUpd){
		echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='navegador_material.php';
			</script>";
}else{
	echo "<script language='Javascript'>
			alert('ERROR EN LA TRANSACCION. COMUNIQUESE CON EL ADMIN.');
			history.back();
			</script>";
}
	

?>