<?php

//header('Content-Type: text/html; charset=UTF-8'); 
$global_usuario=$_COOKIE["global_usuario"];

if(!function_exists('register_globals')){
	include('register_globals.php');
	register_globals();
}else{
}

//if($global_usuario>0){
	$conexion=mysql_connect("localhost","root","4868422Marco");
	$bd=mysql_select_db("candyrice");
//}else{
//	header("index.html");
//}

?>
