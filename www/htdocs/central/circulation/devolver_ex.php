<?php
/**
 * @program:   ABCD - ABCD-Central - http://reddes.bvsaude.org/projects/abcd
 * @copyright:  Copyright (C) 2009 BIREME/PAHO/WHO - VLIR/UOS
 * @file:      devolver_ex.php
 * @desc:      Returns a loan
 * @author:    Guilda Ascencio
 * @since:     20091203
 * @version:   1.0
 *
 * == BEGIN LICENSE ==
 *
 *    This program is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU Lesser General Public License as
 *    published by the Free Software Foundation, either version 3 of the
 *    License, or (at your option) any later version.
 *
 *    This program is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU Lesser General Public License for more details.
 *
 *    You should have received a copy of the GNU Lesser General Public License
 *    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * == END LICENSE ==
*/
// se determina si el pr�stamo est� vencido
function compareDate ($FechaP){
global $locales;
	$dia=substr($FechaP,6,2);
	$mes=substr($FechaP,4,2);
	$year=substr($FechaP,0,4);
	$exp_date=$year."-".$mes."-".$dia;
	$todays_date = date("Y-m-d");
	$today = strtotime($todays_date);
	$expiration_date = strtotime($exp_date);
	$diff=$expiration_date-$today;
	return $diff;

}//end Compare Date

session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
include("../common/get_post.php");
include("../config.php");
$lang=$_SESSION["lang"];
include("../lang/admin.php");
include("../lang/prestamo.php");
date_default_timezone_set('UTC');
//
//foreach ($arrHttp as $var=>$value) echo "$var=$value<br>";
//die;

//Calendario de d�as feriados
include("../circulation/calendario_read.php");
//Horario de la biblioteca, unidades de multa, moneda
include("../circulation/locales_read.php");
include ("../circulation/fecha_de_devolucion.php");   //Para calcular si la reserva est� vencida
require_once("../circulation/grabar_log.php");
//Se averiguan los recibos que hay que imprimir
$recibo_list=array();
$Formato="";
if (file_exists($db_path."trans/pfts/".$_SESSION["lang"]."/receipts.lst")){
		$Formato=$db_path."trans/pfts/".$_SESSION["lang"]."/receipts.lst";
	}else{
		if (file_exists($db_path."trans/pfts/".$lang_db."/receipts.lst")){
			$Formato=$db_path."trans/pfts/".$lang_db."/receipts.lst";
		}
	}
if ($Formato!=""){
	$fp=file($Formato);
	foreach ($fp as $value){
		if (trim($value)!=""){
			$value=trim($value);
			$recibo_list[$value]=$value;
		}
	}
}


function ReservesAssign($key,$espera){
global $xWxis,$Wxis,$wxisUrl,$db_path,$msgstr,$arrHttp,$reservas_u_cn;
	$Expresion=$key." and ST_0 ";
	$IsisScript=$xWxis."cipres_usuario.xis";
	$Pft="f(mfn,1,0),'|',v10,'|'v60,'|',v40,'|',v130,'|',v200,'|'v30,'|',v31,'|'v12,'|'v15,'|'v20/";
	                                                //v10:  C�digo del usuario
	                                               //v60:  Fecha en la cual se asign� el objeto al usuario de la reserva
	                                               //v40:  Fecha hasta la cual es v�lida la reserve
	                                               //v130: Fecha en que el operador cancel� una reserva
	                                               //v200: Fecha en la cual se dio el objeto en prestamo
	                                               //v30 : Fecha en que se hizo la reserva
	                                               //v31 : Hora en que se hizo la reserva
	                                               //v12 : Tipo de usuario
	                                               //v15 : Base de datos
	                                               //v20 : N�mero de control
	$query="&base=reserve&cipar=$db_path"."par/reserve.par&Expresion=$Expresion&Pft=$Pft";
	include("../common/wxis_llamar.php");
	$Usuario="";
	$string_act="";
	$Mfn="";
	$hoy=date("Ymd");
	$por_asignar=array();
	foreach ($contenido as $value) {
		$value=trim($value);
		if (trim($value)!=""){			$r=explode('|',$value);
			$fecha_asignacion=$r[2];     //fecha en la cual se asign� el objeto a un usuario de reserva
			$fecha_cancelacion=$r[4];    //fecha en la cual un operador anul� una reserva
			$fecha_prestamo=$r[5];
			$Mfn=$r[0];
			$Usuario=$r[1];
			if (($fecha_asignacion!="" and $fecha_asignacion>=$hoy) or $fecha_cancelacion!="" or $fecha_prestamo!=""){				continue;			}
			$por_asignar[$r[6]." ".$r[7]]=$value;
		}
	}
	$fecha_anulacion="";
	$fecha_asignacion="";
	if (count($por_asignar)>0){
		ksort($por_asignar);
		reset($por_asignar);
		$res=current($por_asignar);
		$r=explode('|',$res);
		$Mfn=$r[0];
		$Usuario=$r[1];
		$tipo_usuario=$r[8];
		$base_datos=$r[9];
		$ncontrol=$r[10];
  		$f_asignacion=$hoy;
        $lapso=$espera;
        $unidad="D";
        $fecha_anulacion=FechaDevolucion($lapso,$unidad,$f_asignacion,$tipo_usuario);
        $fecha_anulacion=substr($fecha_anulacion,0,8);
        $fecha_dev=date("Ymd");
		$hora_dev=date("H:i:s");
		$ValorCapturado="d1d40d60<1 0>3</1>";
		$ValorCapturado.="<60 0>$f_asignacion</60><40 0>$fecha_anulacion</40>";
		$ValorCapturado=urlencode($ValorCapturado);
  		$string_act=$ValorCapturado;
  		return array($Usuario,$string_act,$Mfn,$fecha_anulacion,$tipo_usuario,$base_datos,$ncontrol);
  	}

}

//Calculo de la sanci�n por atraso
include("sanctions_inc.php");

///////////
if (isset($arrHttp["vienede"])){   // viene del estado de cuenta	$items=explode('$$',trim(urldecode($arrHttp["searchExpr"])));}else{
	$items=explode("\n",trim(urldecode($arrHttp["searchExpr"])));
}
$resultado="";
$recibo="";
$Mfn_rec="";
$errores="";
$devueltos="";
$cn_l="";
$reservas_activadas="";
foreach ($items as $num_inv){
//Se ubica el ejemplar prestado en la base de datos de transacciones

	$num_inv=trim($num_inv);
	$inven=$num_inv;
	if ($num_inv!=""){
		$num_inv="TR_P_".$num_inv;
		if (!isset($arrHttp["base"])) $arrHttp["base"]="trans";
		$Formato="v10'|$'v20'|$'v30'|$'v35'|$'v40'|$'v45'|$'v70'|$'v80'|$'v100,'|$',v40,'|$'v400,'|$'v500,'|$',v95,'|$',v98/";
		$query = "&base=".$arrHttp["base"] ."&cipar=$db_path"."par/".$arrHttp["base"].".par&count=1&Expresion=".$num_inv."&Pft=$Formato";
		$contenido="";
		$IsisScript=$xWxis."buscar_ingreso.xis";
		include("../common/wxis_llamar.php");
		$Total=0;
		foreach ($contenido as $linea){			$linea=trim($linea);
			if ($linea!="") {
				$l=explode('|$',$linea);
				if (substr($linea,0,6)=="[MFN:]"){					$Mfn=trim(substr($linea,6));				}else{					if (substr($linea,0,8)=="[TOTAL:]"){						$Total=trim(substr($linea,8));					}else{						$prestamo=$linea;					}
				}
			}
		}
		$error="";
		if ($Total==0){
			$errores.=";".$inven;
		}
// se extrae la informaci�n del ejemplar a devolver
		if ($Total>0){
			$p=explode('|$',$prestamo);
			$cod_usuario=$p[1];
			$arrHttp["usuario"]=$cod_usuario;
			$inventario=$p[0];
			$fecha_p=$p[2];
			$hora_p=$p[3];
			$fecha_d=$p[9];   //fecha de devoluci�n en formato ISO
			$hora_d=$p[5];
			$tipo_usuario=$p[6];
			$tipo_objeto=$p[7];
			$referencia=$p[8];
			$ppres=$p[10];
			$ncontrol=$p[12];
			$bd=$p[13];
			// se lee la pol�tica de pr�stamos
			include_once("loanobjects_read.php");
			// se lee el calendario
			include_once("calendario_read.php");
			// se lee la configuraci�n local
			include_once("locales_read.php");

			//se determina la pol�tica a aplicar
			if ($ppres==""){				if (isset($politica[$tipo_objeto][$tipo_usuario])){
	    			$ppres=$politica[$tipo_objeto][$tipo_usuario];
				}
				if (trim($ppres)==""){
					if (isset($politica[0][$tipo_usuario])) {
						$ppres=$politica[0][$tipo_usuario];
					}
				}
				if (trim($ppres)==""){
					if (isset($politica[$tipo_usuario][0])){
	    				$ppres=$politica[$tipo_usuario][0];
	  				}
				}
				if (trim($ppres)==""){
					if (isset($politica["0"]["0"])){
						$ppres=$politica["0"]["0"];
					}
				}
			}
			//echo $ppres;
			$p=explode('|',$ppres);
			$espera_renovacion="";
			if (isset($p18))
				$espera_renovacion=$p[18];
			if (trim($espera_renovacion)=="") $espera_renovacion=2;
			$lapso=$p[3];
			$unidad=$p[5];
			$u_multa= $p[7];      //unidades de multa
			$u_multa_r= $p[8];    //unidades de multa si el libro est� reservado
			$u_suspension=$p[9];  //unidades de suspensi�n
			$u_suspension=$p[10];  //unidades de suspensi�n si el libro est� reservado
		    $devolucion=date("Ymd");
			$ValorCapturado="d1<1 0>X</1><500 0>$devolucion</500>";
			$ValorCapturado.="<130 0>^a".$_SESSION["login"]."^b".date("Ymd H:i:s")."</130>";
			$ValorCapturado=urlencode($ValorCapturado);
			$IsisScript=$xWxis."actualizar_registro.xis";
			$Formato="";

			if (isset($recibo_list["pr_return"])){
				if (file_exists($db_path."trans/pfts/".$_SESSION["lang"]."/r_return.pft")){
					$Formato=$db_path."trans/pfts/".$_SESSION["lang"]."/r_return";
				}else{
					if (file_exists($db_path."trans/pfts/".$lang_db."/r_return.pft")){
						$Formato=$db_path."trans/pfts/".$lang_db."/r_return";
					}
				}
				if ($Formato!="") {	                $Formato="&Formato=$Formato";
				}
			}
			$query = "&base=trans&cipar=$db_path"."par/trans.par&login=".$_SESSION["login"]."&Mfn=".$Mfn."&ValorCapturado=".$ValorCapturado."$Formato";

            if ($Formato!=""){
            	$Mfn_rec.=";".$Mfn;
            }
            $resultado.=";".$Mfn;

			// si est� atrasado se procesan las multas y suspensiones
			$atraso=compareDate ($fecha_d,$lapso);
			$dias_atraso=0;
			if ($atraso<0){				$atraso=floor(abs($atraso)/(60*60*24));
				$dias_atraso=abs($atraso);			}
			if ($politica==""){				$error="&error=".$msgstr["nopolicy"]." $tipo_usuario / $tipo_objeto";			}else{
				if ($u_multa>0 or $u_suspension>0){					if ($dias_atraso>0){
						Sanciones($fecha_d,$dias_atraso,$arrHttp["usuario"],$inventario,$ppres,$ncontrol,$bd,$tipo_usuario,$tipo_objeto);
						$resultado.=" ".$msgstr["overdue"];
					}
				}
			}

			//SE LEEN LAS RESERVAS Y AL PRIMER USUARIO DE LA COLA  QUE NO TENGA
			//FECHA DE ASIGNACI�N SE LE COLOCA LA FECHA DEL D�A
			$cn_l.=";CN_".$bd."_".$ncontrol;

			$user_reserved=ReservesAssign("CN_".$bd."_".$ncontrol,$espera_renovacion);
			//var_dump($user_reserved);   die;
			$reservas_activadas.=$user_reserved[0].";";
			if ($user_reserved[1]!="")  {				$query.="&reserva=".$user_reserved[1]."&Mfn_reserva=".$user_reserved[2];
			}
			//SE GRABA EL LOG DE LA DEVOLUCION
		if (file_exists($db_path."logtrans/data/logtrans.mst")){
			$datos_trans["BD"]=$bd;
				$datos_trans["NUM_CONTROL"]=$ncontrol;
				$datos_trans["NUM_INVENTARIO"]=$inventario;
				$datos_trans["TIPO_OBJETO"]=$tipo_objeto;
				$datos_trans["CODIGO_USUARIO"]=$cod_usuario;
				$datos_trans["TIPO_USUARIO"]=$tipo_usuario;
				$datos_trans["FECHA_PROGRAMADA"]=$fecha_d;
				$datos_trans["ATRASO"]=$dias_atraso;
				$ValorCapturado=GrabarLog("B",$datos_trans,$Wxis,$xWxis,$wxisUrl,$db_path,"RETORNAR");
				$query.="&logtrans=".$ValorCapturado;
			}

			//SE GRABA EL LOG DE LA RESERVA EN ESPERA, SI CORRESPONDE
            if ($user_reserved[1]!="")  {
				if (file_exists($db_path."logtrans/data/logtrans.mst")){
					$datos_trans["BD"]=$bd;
					$datos_trans["NUM_CONTROL"]=$ncontrol;
					$datos_trans["CODIGO_USUARIO"]=$user_reserved[0];
					$datos_trans["TIPO_USUARIO"]=$user_reserved[4];
					$datos_trans["FECHA_PROGRAMADA"]=$user_reserved[3];
					$ValorCapturado=GrabarLog("E",$datos_trans,$Wxis,$xWxis,$wxisUrl,$db_path,"RETORNAR");
					$query.="&logtrans_1=".$ValorCapturado;
				}

			}
			include("../common/wxis_llamar.php");
            //foreach ($contenido as $value) echo "$value<br>";die;
		}
	}
}
//die;
$cu="";
$recibo="";


if (isset($arrHttp["usuario"]))
	$cu="&usuario=".$arrHttp["usuario"];
else
	$cu="&usuario=$cod_usuario";
if (isset($arrHttp["reserve"])){
	$reserve="&reserve=\"S\"";
}else{
	$reserve="";
}
if (isset($arrHttp["vienede"]) or isset($arrHtp["reserve"])){
	header("Location: usuario_prestamos_presentar.php?devuelto=S&encabezado=s&resultado=".urlencode($resultado)."$cu&rec_dev=$Mfn_rec"."&inventario=".$arrHttp["searchExpr"]."&lista_control=".$cn_l.$reserve);}else{
	header("Location: devolver.php?devuelto=S&encabezado=s$error$cu&rec_dev=$Mfn_rec&resultado=$resultado&errores=$errores"."&lista_control=".$cn_l."&reservas=".$reservas_activadas);
}
die;

function ImprimirRecibo($recibo_arr){
	$salida="";
	foreach ($recibo_arr as $Recibo){
		$salida=$salida.$Recibo;
	}
?>
<script>
	msgwin=window.open("","recibo","width=400, height=300, scrollbars, resizable")
	msgwin.document.write("<?php echo $salida?>")
	msgwin.focus()
	msgwin.print()
	msgwin.close()
</script>
<?php
}

?>
?>