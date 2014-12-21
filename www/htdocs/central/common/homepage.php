<?php
$Permiso=$_SESSION["permiso"];
$modulo_anterior="";
if (isset($_SESSION["MODULO"]))
	$modulo_anterior=$_SESSION["MODULO"];

if (isset($arrHttp["modulo"])) {	$_SESSION["MODULO"]=$arrHttp["modulo"];
}
$lista_bases=array();
if (file_exists($db_path."bases.dat")){
	$fp = file($db_path."bases.dat");
	foreach ($fp as $linea){
		$linea=trim($linea);
		if ($linea!="") {
			$ix=strpos($linea,"|");
			$llave=trim(substr($linea,0,$ix));
			$lista_bases[$llave]=trim(substr($linea,$ix+1));
		}
	}
}
$central="";
$circulation="";
$acquisitions="";
$ixcentral=0;
foreach ($_SESSION["permiso"] as $key=>$value){	$p=explode("_",$key);
	if (isset($p[1]) and $p[1]=="CENTRAL"){		$central="Y";
		$ixcentral=$ixcentral+1;	}
	if (substr($key,0,8)=="CENTRAL_")  	{		$central="Y";
		$ixcentral=$ixcentral+1;	}
	if (substr($key,0,4)=="ADM_"){		$central="Y";
		$ixcentral=$ixcentral+1;	}
	if (substr($key,0,5)=="CIRC_")  	$circulation="Y";
	if (substr($key,0,4)=="ACQ_")  		$acquisitions="Y";

}
// Se determina el nombre de la p�gina de ayuda a mostrar
if (!isset($_SESSION["MODULO"])) {	if ($central=="Y" and $ixcentral>0) {		$arrHttp["modulo"]="catalog";	}else{		if ($circulation=="Y"){			$arrHttp["modulo"]="loan";		}else{			$arrHttp["modulo"]="acquisitions";		}	}}else{	$arrHttp["modulo"]=$_SESSION["MODULO"];}
switch ($arrHttp["modulo"]){	case "catalog":
		$ayuda="homepage.html";
		$module_name=$msgstr["catalogacion"];
		$_SESSION["MODULO"]="catalog";
		break;
	case "acquisitions":
		$ayuda="acquisitions/homepage.html";
		$module_name=$msgstr["acquisitions"];
		$_SESSION["MODULO"]="acquisitions";
		break;
	case "loan":
		$ayuda="circulation/homepage.html";
		$module_name=$msgstr["loantit"];
		$_SESSION["MODULO"]="loan";}
if (file_exists($db_path."logtrans/data/logtrans.mst")){
	if ($_SESSION["MODULO"]!="loan" and $modulo_anterior=="loan"){
		include("../circulation/grabar_log.php");
		$datos_trans["operador"]=$_SESSION["login"];
		GrabarLog("Q",$datos_trans,$Wxis,$xWxis,$wxisUrl,$db_path);
	}else{		if ($_SESSION["MODULO"]=="loan" and $modulo_anterior!="loan"){
			include("../circulation/grabar_log.php");
			$datos_trans["operador"]=$_SESSION["login"];
			GrabarLog("P",$datos_trans,$Wxis,$xWxis,$wxisUrl,$db_path);
		}	}
}
include("header.php");
?>
<script>
function Modulo(){
	Opcion=document.cambiolang.modulo.options[document.cambiolang.modulo.selectedIndex].value
	switch (Opcion){
		case "loan":
			top.location.href="../common/change_module.php?modulo=loan"
			break
		case "acquisitions":
			top.location.href="../common/change_module.php?modulo=acquisitions"
			break

		case "catalog":
			top.location.href="../common/change_module.php?modulo=catalog"
			break


	}
}

	function CambiarLenguaje(){
		if (document.cambiolang.lenguaje.selectedIndex>0){
               lang=document.cambiolang.lenguaje.options[document.cambiolang.lenguaje.selectedIndex].value
               self.location.href="inicio.php?reinicio=s&lang="+lang
		}
	}

	function CambiarBaseAdministrador(Modulo){
		db=""
		if (Modulo!="traducir"){
			ix=document.admin.base.selectedIndex
		    if (ix<1){
		    	alert("<?php echo $msgstr["seldb"]?>")
		    	return
		    }
		    db=document.admin.base.options[ix].value
		    ix=db.indexOf("^",2)
		    db=db.substr(2,ix-2)
		}
	    switch(Modulo){			case 'table':
				document.admin.action="../dataentry/browse.php"
				break
	    	case "resetautoinc":
	    		if (db+"_CENTRAL_RESETLCN" in perms || "CENTRAL_RESETLCN" in perms || "CENTRAL_ALL" in perms || db+"_CENTRAL_ALL" in perms){
	    	   		document.admin.action="../dbadmin/resetautoinc.php";
	    		}else{	    			alert("<?php echo $msgstr["invalidright"];?>")
	    			return;	    		}
	    		break;
	    	case "toolbar":
	    		document.admin.action="../dataentry/inicio_main.php";
	    		break;
			case "utilitarios":
				if (db+"_CENTRAL_DBUTILS" in perms || "CENTRAL_DBUTILS" in perms || "CENTRAL_ALL" in perms || db+"_CENTRAL_ALL" in perms ){
					document.admin.action="../dbadmin/menu_mantenimiento.php";
				}else{
	    			alert("<?php echo $msgstr["invalidright"];?>")
	    			return;
	    		}
                break;
   			case "estructuras":
   				if (db+"_CENTRAL_MODIFYDEF" in perms || "CENTRAL_MODIFYDEF" in perms || "CENTRAL_ALL" in perms || db+"_CENTRAL_ALL" in perms){					document.admin.action="../dbadmin/menu_modificardb.php";
				}else{
	    			alert("<?php echo $msgstr["invalidright"];?>")
	    			return;
	    		}
                break;
    		case "reportes":
    			if (db+"_CENTRAL_PREC" in perms || "CENTRAL_PREC" in perms || "CENTRAL_ALL" in perms || db+"_CENTRAL_ALL" in perms){
					document.admin.action="../dbadmin/pft.php";
				}else{
	    			alert("<?php echo $msgstr["invalidright"];?>")
	    			return;
	    		}
                break;
    		case "traducir":
    			if (db+"_CENTRAL_TRANSLATE" in perms || "CENTRAL_TRANSLATE" in perms || "CENTRAL_ALL" in perms || db+"_CENTRAL_ALL" in perms){
					document.admin.action="../dbadmin/menu_traducir.php";
				}else{
	    			alert("<?php echo $msgstr["invalidright"];?>")
	    			return;
	    		}
                break;
    		case "stats":
    			if (db+"_CENTRAL_STATGEN" in perms || "CENTRAL_STATGEN" in perms || "CENTRAL_STATGEN" in perms || "CENTRAL_ALL" in perms || db+"_CENTRAL_ALL" in perms){
					document.admin.action="../statistics/tables_generate.php";
				}else{
	    			alert("<?php echo $msgstr["invalidright"];?>")
	    			return;
	    		}
    			break;
    		case "z3950":
    			if (db+"_CENTRAL_Z3950CONF" in perms || "CENTRAL_Z3950CONF" in perms || "CENTRAL_ALL" in perms || db+"CENTRAL_ALL" in perms){
					document.admin.action="../dbadmin/z3950_conf.php";
				}else{
	    			alert("<?php echo $msgstr["invalidright"];?>")
	    			return;
	    		}
    			break;
	    }
		document.admin.submit();
	}

	</script>

<body>
<div class=heading>
	<div class="institutionalInfo">
		<h1><img src=<?php if (isset($logo))
								echo $logo;
							else
								echo "../images/logoabcd.jpg";
					  ?>><?php echo $institution_name?> </h1>
    </div>
	<div class="userInfo">
		<span><?php echo $_SESSION["nombre"]?></span>,
		<?php echo $_SESSION["profile"]?> |
		<?php  $dd=explode("/",$db_path);
               if (isset($dd[count($dd)-2])){
			   		$da=$dd[count($dd)-2];
			   		echo " (".$da.") ";
				}
		?> |
		<a href="../dataentry/logout.php" xclass="button_logout"><span>[logout]</span></a><br>
<?php
	if (isset($msg_path))
		$path_this=$msg_path;
	else
		$path_this=$db_path;
	$a=$path_this."lang/".$_SESSION["lang"]."/lang.tab";
 	if (!file_exists($a)) {
 		$a=$path_this."lang/en/lang.tab";
 	}
 	if (!file_exists($a)){
		echo $msgstr["flang"]." ".$a;
		die;
	}
 	$a=$path_this."lang/".$_SESSION["lang"]."/lang.tab";
 	if (!file_exists($a)) {
 		$a=$path_this."lang/en/lang.tab";
 	}
 	if (!file_exists($a)){
		echo $msgstr["flang"]." ".$path_this."lang/".$_SESSION["lang"]."/lang.tab";
		die;
	}
?>
<div class="language"><form name=cambiolang> <table border=0><td><?php echo $msgstr["lang"]?>:</td>
	<td><select name=lenguaje style="width:90px;font-size:10pt;font-family:arial narrow" onchange=CambiarLenguaje()>
		<option value=""></option>
		 <?php

 	if (file_exists($a)){
		$fp=file($a);
		$selected="";
		foreach ($fp as $value){
			$value=trim($value);
			if ($value!=""){
				$l=explode('=',$value);
				if ($l[0]==$_SESSION["lang"]) $selected=" selected";
				echo "<option value=$l[0] $selected>".$l[1]."</option>";
				$selected="";
			}
		}
		echo "</select>";
	}else{
		echo $msgstr["flang"].$db_path."lang/".$_SESSION["lang"]."/lang.tab";
		die;
	}
	echo "</td><tr>";
	$central="";
$circulation="";
$acquisitions="";
foreach ($_SESSION["permiso"] as $key=>$value){
	$p=explode("_",$key);
	if (isset($p[1]) and $p[1]=="CENTRAL") $central="Y";
	if (substr($key,0,8)=="CENTRAL_")  $central="Y";
	if (substr($key,0,5)=="CIRC_")  $circulation="Y";
	if (substr($key,0,4)=="ACQ_")  $acquisitions="Y";

}
if ($circulation=="Y" or $acquisitions=="Y" or $central=="Y"){
	echo "<td>".$msgstr["modulo"].":</td><td>";
  	echo '<select name=modulo style="width:90px;font-size:8pt;font-family:arial narrow"   onchange=Modulo()>';
  	echo '<option value=""></option>';
  	if ($central=="Y") {
  		echo "<option value=catalog";
  		if ($_SESSION["MODULO"]=="catalog") echo " selected";
  		echo ">".$msgstr["catalogacion"];
  	}
  	if ($circulation=="Y") {
  		echo "<option value=loan";
  		if ($_SESSION["MODULO"]=="loan") echo " selected";
  		echo ">".$msgstr["prestamo"];
  	}
  	if ($acquisitions=="Y") {
  		echo "<option value=acquisitions";
  		if ($_SESSION["MODULO"]=="acquisitions") echo " selected";
  		echo ">".$msgstr["acquisitions"];
  	}
  	echo "</select></td></table>";
}
?>
    </form>
    </div>
	</div>
	<div class="spacer">&#160;</div>
</div>



<div class="sectionInfo">
	<div class="breadcrumb">
		<strong><?php echo $msgstr["inicio"]." - $module_name"?></strong>
	</div>
	<div class="actions">
      &nbsp;
	</div>
	<div class="spacer">&#160;</div>
</div>

<div class="helper">
	<a href=../documentacion/ayuda.php?help=<?php echo $_SESSION["lang"]."/$ayuda"?> target=_blank><?php echo $msgstr["help"]?></a>&nbsp &nbsp;
 <?php
if (isset($_SESSION["permiso"]["CENTRAL_EDHLPSYS"])){
 	echo "<a href=../documentacion/edit.php?archivo=".$_SESSION["lang"]."/$ayuda target=_blank>".$msgstr["edhlp"];
	echo "</a>
		<font color=white>&nbsp; &nbsp; Script: homepage.php </font>";
}
?>
</div>
<div class="middle homepage">
<?php
$Permiso=$_SESSION["permiso"];
switch ($_SESSION["MODULO"]){	case "catalog":
		AdministratorMenu();
		break;
	case "loan":
		MenuLoanAdministrator();
		break;
	case "acquisitions":
		MenuAcquisitionsAdministrator();
		break;}
echo "		</div>
	</div>";
include("footer.php");
echo "	</body>
</html>";

///---------------------------------------------------------------

function AdministratorMenu(){
global $msgstr,$db_path,$arrHttp,$lista_bases,$Permiso,$dirtree;
	$_SESSION["MODULO"]="catalog";
?>

	<div class="mainBox" onmouseover="this.className = 'mainBox mainBoxHighlighted';" onmouseout="this.className = 'mainBox';">
		<div class="boxTop">
			<div class="btLeft">&#160;</div>
			<div class="btRight">&#160;</div>
		</div>
		<div class="boxContent toolSection ">
			<div class="sectionIcon">
				&#160;
			</div>
			<div class="sectionTitle">
				<h4><strong><?php echo $msgstr["database"]?></strong></h4>
			</div>
			<div class="sectionButtons">
            	<div class="searchTitles">
					<form name="admin" action="dataentry/inicio_main.php" method="post">
					<input type=hidden name=encabezado value=s>
					<input type=hidden name=retorno value="../common/inicio.php">
					<input type=hidden name=modulo value=catalog>
					<?php if (isset($arrHttp["newindow"]))
					echo "<input type=hidden name=newindow value=Y>\n";?>
					<div class="stInput">
						<label for="searchExpr"><?php echo $msgstr["seleccionar"]?>:</label>
						<select name=base  class="textEntry singleTextEntry" >
							<option value=""></option>
<?php
$i=-1;
foreach ($lista_bases as $key => $value) {
	$xselected="";
	$value=trim($value);
	$t=explode('|',$value);
	if (isset($Permiso["db_".$key]) or isset($_SESSION["permiso"]["db_ALL"]) or isset($_SESSION["permiso"]["CENTRAL_ALL"])){
		if (isset($arrHttp["base"]) and $arrHttp["base"]==$key or count($lista_bases)==1) $xselected=" selected";
		echo "<option value=\"^a$key^badm|$value\" $xselected>".$t[0]."\n";
	}
}
?>
						</select>
					</div>
					<a href="javascript:CambiarBaseAdministrador('toolbar')" class="menuButton nextButton">
						<img src="../images/mainBox_iconBorder.gif" alt="" title="" />
						<span><strong><?php echo $msgstr["dataentry"]?></strong></span>
					</a>
					</form>
				</div>
					&nbsp;
<?php
//if (isset($Permiso["CENTRAL_STATGEN"]) or isset($Permiso["CENTRAL_ALL"])){?>
				<a href="javascript:CambiarBaseAdministrador('stats')" class="menuButton statButton">
					<img src="../images/mainBox_iconBorder.gif" alt="" title="" />
					<span><strong><?php echo $msgstr["statistics"]?></strong></span>
				</a>
<?php
//}
//if (isset($Permiso["CENTRAL_PREC"]) or isset($Permiso["CENTRAL_ALL"])){
?>
				<a href="javascript:CambiarBaseAdministrador('reportes')" class="menuButton reportButton">
					<img src="../images/mainBox_iconBorder.gif" alt="" title="" />
					<span><strong><?php echo $msgstr["reports"]?></strong></span>
				</a>
<?php
//}
//if (isset($Permiso["CENTRAL_MODIFYDEF"]) or isset($Permiso["CENTRAL_ALL"])){
?>
				<a href="javascript:CambiarBaseAdministrador('estructuras')" class="menuButton update_databaseButton">
					<img src="../images/mainBox_iconBorder.gif" alt="" title="" />
					<span><strong><?php echo $msgstr["updbdef"]?></strong></span>
				</a>
<?php
//}
//if (isset($Permiso["CENTRAL_DBUTILS"]) or isset($Permiso["CENTRAL_ALL"])){
?>

				<a href="javascript:CambiarBaseAdministrador('utilitarios')" class="menuButton utilsButton">
					<img src="../images/mainBox_iconBorder.gif" alt="" title="" />
					<span><strong><?php echo $msgstr["maintenance"]?></strong></span>
				</a>
<?php
//}
//if (isset($Permiso["CENTRAL_Z3950CONF"])  or isset($Permiso["CENTRAL_ALL"])){
?>
				<a href="javascript:CambiarBaseAdministrador('z3950')"  class="menuButton z3950Button">
					<img src="../images/mainBox_iconBorder.gif" alt="" title="" />
					<span><strong><?php echo $msgstr["z3950"]?></strong></span>
				</a>
<?php
//}
?>
			</div>
			<div class="spacer">&#160;</div>
			</div>
			<div class="boxBottom">
			<div class="bbLeft">&#160;</div>
			<div class="bbRight">&#160;</div>
		</div>
	</div>
<?php

if (isset($Permiso["CENTRAL_ALL"])  or isset($Permiso["CENTRAL_CRDB"])  or isset($Permiso["CENTRAL_URDADM"])
  or isset($Permiso["CENTRAL_RESETLIN"])  or isset($Permiso["CENTRAL_TRANSLATE"])  or isset($Permiso["CENTRAL_EXDBDIR"]))
{
?>
			<div class="mainBox" onmouseover="this.className = 'mainBox mainBoxHighlighted';" onmouseout="this.className = 'mainBox';">
				<div class="boxTop">
					<div class="btLeft">&#160;</div>
					<div class="btRight">&#160;</div>
				</div>
				<div class="boxContent toolSection ">
					<div class="sectionIcon">
						&#160;
					</div>
					<div class="sectionTitle">
						<h4><strong><?php echo $msgstr["admtit"]?></strong></h4>
					</div>
					<div class="sectionButtons">
<?Php
if (isset($Permiso["CENTRAL_ALL"])  or isset($Permiso["CENTRAL_CRDB"]) or isset($Permiso["ADM_CRDB"])){?>
                    <a href="../dbadmin/menu_creardb.php?encabezado=S" class="menuButton databaseButton">
					<img src="../images/mainBox_iconBorder.gif" alt="" title="" />
					<span><strong><?php echo $msgstr["createdb"]?></strong></span></a>
<?Php
}
if (isset($Permiso["CENTRAL_ALL"])  or isset($Permiso["CENTRAL_URADM"]) or isset($Permiso["ADM_CRDB"])){
?>
				<a href="../dbadmin/users_adm.php?encabezado=s&base=acces&cipar=acces.par" class="menuButton userButton">
					<img src="../images/mainBox_iconBorder.gif" alt="" title="" />
					<span><strong><?php echo $msgstr["usuarios"]?></strong></span>
				</a>
<?Php
}
if (isset($Permiso["CENTRAL_ALL"])  or isset($Permiso["CENTRAL_RESETLIN"])){
?>
				<a href="../dbadmin/reset_inventory_number.php?encabezado=s" class="menuButton resetButton">
					<img src="../images/mainBox_iconBorder.gif" alt="" title="" />
					<span><strong><?php echo $msgstr["resetinv"]?></strong></span>
				</a>
<?Php
}
if (isset($Permiso["CENTRAL_ALL"])  or isset($Permiso["CENTRAL_TRANSLATE"])){
?>
				<a href="javascript:CambiarBaseAdministrador('traducir')" class="menuButton exportButton">
					<img src="../images/mainBox_iconBorder.gif" alt="" title="" />
					<span><strong><?php echo $msgstr["translate"]?></strong></span>
				</a>
<?Php
}
if ($dirtree==1){	if (isset($Permiso["CENTRAL_ALL"])  or isset($Permiso["CENTRAL_EXDBDIR"])){
?>
				<a href="../dbadmin/dirtree.php?encabezado=s" class="menuButton exploreButton">
					<img src="../images/mainBox_iconBorder.gif" alt="" title="" />
					<span><strong><?php echo $msgstr["expbases"]?></strong></span>
				</a>
<?Php }
}?>
					</div>
					<div class="spacer">&#160;</div>
				</div>
				<div class="boxBottom">
					<div class="bbLeft">&#160;</div>
					<div class="bbRight">&#160;</div>
				</div>
			</div>


<?php
	}
}
// end function Administrador



function MenuAcquisitionsAdministrator(){
	include("menuacquisitions.php");
}

function MenuLoanAdministrator(){
   include("menucirculation.php");
}
echo "\n<script>\n";
echo "var perms= new Array()\n";
foreach ($_SESSION["permiso"] as $key=>$value){
	echo "perms['$key']='$value';\n";
}
echo "</script>\n";
?>