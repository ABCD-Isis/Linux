<?php
session_start();
if (!isset($_SESSION["login"])){
	die;
include("../config.php");
include("../common/get_post.php");
$us=explode("\n",$arrHttp["ValorCapturado"]);
$fp=fopen($db_path."/reserva/tablas/usuarios.tab","w");
foreach ($us as $linea){
	if ($linea!="")
		fwrite($fp,$linea."\n");
fclose($fp);
header ("Location: inicio.php?Opcion=continuar");


?>