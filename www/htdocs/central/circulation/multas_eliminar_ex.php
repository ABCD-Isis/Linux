<?php
session_start();
// ELIMINAR MULTAS
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
if (!isset($_SESSION["permiso"]["CENTRAL_ALL"]) and !isset($_SESSION["permiso"]["CIRC_CIRCALL"])  and (!isset($_SESSION["permiso"]["CIRC_DELSUS"])or !isset($_SESSION["permiso"]["CIRC_DELFINE"]))){
}

if (!isset($_SESSION["lang"]))  $_SESSION["lang"]="en";
include("../common/get_post.php");
//foreach ($arrHttp as $var=>$value)  echo "$var=$value<br>";die;
include("../config.php");

$lang=$_SESSION["lang"];

include("../lang/prestamo.php");

include("grabar_log.php");

function ProcesarRegistro($Accion,$Mfn,$trans){
	switch ($Accion){
			$Accion="2";
			$trans="L";    //Para el log de transacciones
			break;
		case "C":  //CANCELAR
			$Accion="1";
			switch ($trans){
					$trans="K";   //Cancelaci�n de multa
					break;
				case "S";
					$trans="O";   //Cancelaci�n de suspensi�n
					break;
			break;
	$ValorCapturado="d10<010 0>".$Accion."</10>";
	$ValorCapturado=urlencode($ValorCapturado);
	$IsisScript=$xWxis."actualizar_registro.xis";
	$query = "&base=suspml&cipar=$db_path"."par/suspml.par&login=".$_SESSION["login"]."&Mfn=".$Mfn."&Opcion=actualizar&ValorCapturado=".$ValorCapturado;

	//SE GRABA EL LOG CON LA CANCELACI�N DE LA MULTA O LA SUSPENSI�N
	if (file_exists($db_path."logtrans/data/logtrans.mst")){
		$datos_trans["CODIGO_USUARIO"]=$arrHttp["usuario"];
		$ValorCapturado=GrabarLog($trans,$datos_trans,$Wxis,$xWxis,$wxisUrl,$db_path,"RETORNAR");
		$query.="&logtrans=".$ValorCapturado;
	}
    include("../common/wxis_llamar.php");


function EliminarRegistro($Mfn,$trans){
	$IsisScript=$xWxis."eliminarregistro.xis";
	//SE GRABA EL LOG CON LA CANCELACI�N DE LA MULTA O LA SUSPENSI�N
	if (file_exists($db_path."logtrans/data/logtrans.mst")){
		switch($trans){
				$trans="R";
				break;
			case "S":
				$trans="O";
				break;
		$datos_trans["CODIGO_USUARIO"]=$arrHttp["usuario"];
		$ValorCapturado=GrabarLog($trans,$datos_trans,$Wxis,$xWxis,$wxisUrl,$db_path,"RETORNAR");
		$query.="&logtrans=".$ValorCapturado;
	}
	include("../common/wxis_llamar.php");
}
//foreach ($arrHttp as $var=>$value)  echo "$var=$value<br>";die;
$Mfn=explode('|',$arrHttp["Mfn"]);
foreach ($Mfn as $value) {
		switch ($arrHttp["Accion"]){
				EliminarRegistro($value,$arrHttp["Tipo"]);
				break;
			case "C":  //CANCEL
				ProcesarRegistro("C",$value,$arrHttp["Tipo"]);
				break;
			case "P":   //PAY
				ProcesarRegistro("P",$value,$arrHttp["Tipo"]);
				break;


    }
}
if (isset($arrHttp["reserve"])){
header("Location: usuario_prestamos_presentar.php?base=users&usuario=".$arrHttp["usuario"].$reserve);
?>