<?php  session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
include("../common/get_post.php");
//foreach ($arrHttp as $var=>$value) echo "<xmp>$var=$value</xmp>";
include("../config.php");

include ("../lang/admin.php");
include("../common/header.php");

//if (!isset($arrHttp["is_marc"]))   $arrHttp["is_marc"]="";

$fp=explode("\n",$arrHttp["SubC"]);
$subc="";
foreach ($fp as $linea){
	$linea=trim($linea);
	if (trim($linea)!=""){
		$l=explode('|',$linea);
		$subc.=$l[5];
	}
}
//echo $subc;
$ix=-1;
echo "<script>\n";
echo "is_marc='".$arrHttp["is_marc"]."'\n";
echo "PickList=new Array()\n";
echo "NamePickList=new Array()\n";
echo "SubCampos=new Array()\n";
foreach ($fp as $linea){
	//echo $linea."<br>";
		$l=explode('|',$linea);
		$ix=$ix+1;
		if ($l[0]=="S") {
	        $Ind="";
	        if ($ind_sc<2 and $arrHttp["is_marc"]=="S"){
	           	if (substr($subc,$ind_sc,1)==1 or substr($subc,$ind_sc,1)==2)
	           		$Ind="I";
	        }
	        $key=$Ind.substr($subc,$ind_sc,1);
			if (trim($l[11])!=""){
				echo "NamePickList['".$key."']='".$l[11]."'\n";
				PickList($key,$l[11]);
				$l=$ix-1;
				echo "PickList['".$key."']=''\n";
			echo "SubCampos['$key']='$key'\n";
echo "mod_picklist=\"".$msgstr["mod_picklist"]."\"\n";
echo "reload_picklist=\"".$msgstr["reload_picklist"]."\"\n";
?>
function RefrescarPicklist(tabla,Ctrl,valor){
	ValorOpcion=valor
	document.refrescarpicklist.picklist.value=tabla
	document.refrescarpicklist.Ctrl.value=Ctrl
	document.refrescarpicklist.valor.value=valor
	msgwin=window.open("","Picklist","width=20,height=10,scrollbars, resizable")
	document.refrescarpicklist.submit()
	msgwin.focus()
}

function AgregarPicklist(tabla,Ctrl,valor){
	ValorOpcion=valor
	document.agregarpicklist.picklist.value=tabla
	document.agregarpicklist.Ctrl.value=Ctrl
	document.agregarpicklist.valor.value=valor
	msgwin=window.open("","Picklist","width=600,height=500,scrollbars, resizable")
	document.agregarpicklist.submit()
	msgwin.focus()
}

//SE ACTUALIZA EL SELECT CON LA TABLA ACTUALIADA
ValorTabla=""
SelectName=""
ValorOpcion=""
function AsignarTabla(){
	opciones=ValorTabla.split('$$$$')
	var Sel = document.getElementById(SelectName);
	Sel.options.length = 0;
	var newOpt =Sel.appendChild(document.createElement('option'));
    newOpt.text = "";
    newOpt.value = " ";
	for (x in opciones){
		op=opciones[x].split('|')
		if (op[0]=="")
			op[0]=op[1]
		if (op[1]=="")
			op[1]=op[0]
		var newOpt =Sel.appendChild(document.createElement('option'));
    	newOpt.text = op[1];
    	newOpt.value = op[0];
    	if (op[0]==ValorOpcion)
    		newOpt.selected=true
	}
}

function EnviarArchivo(Tag,subc){

		msgwin=window.open("","Upload","status=yes,resizable=yes,toolbar=no,menu=no,scrollbars=yes,width=750,height=180,top=100,left=5");
		msgwin.document.close();
		msgwin.document.writeln("<html><title><?php echo $msgstr["uploadfile"]?></title><body link=black vlink=black bgcolor=white>\n");
		msgwin.document.writeln("<form name=upload action=upload_img.php method=POST enctype=multipart/form-data>\n");
		msgwin.document.writeln("<input type=hidden name=base value=<?php echo $arrHttp["base"]?>>\n");
		msgwin.document.writeln("<input type=hidden name=Tag value="+Tag+">")
		msgwin.document.writeln("<input type=hidden name=subc value=\""+subc+"\">")
		msgwin.document.writeln("  <?php echo $msgstr["storein"]?>: <input type=text name=storein size=40 value=\"\" onfocus=blur()>\n");
		msgwin.document.writeln(" <a href=dirs_explorer.php?Opcion=explorar&base=<?php echo $arrHttp["base"]?>&tag="+Tag+" target=_blank>explorar</a>")
		msgwin.document.writeln("<br>");
		msgwin.document.writeln("<table width=100%>");
		msgwin.document.writeln("<tr><td class=menusec1><?php echo $msgstr["archivo"]?></td><td class=menusec1></td>\n");
		msgwin.document.writeln("<tr><td><input name=userfile[] type=file size=50></td><td></td>\n");
		msgwin.document.writeln("</table>\n");
		msgwin.document.writeln("  <input type=submit value='<?php echo $msgstr["uploadfile"]?>'>\n");
		msgwin.document.writeln("</form>\n");
		msgwin.document.writeln("</body>\n");
		msgwin.document.writeln("</html>\n");
		msgwin.focus()  ;
	}

<?php
echo "</script>\n";

?>

input 		{BORDER-TOP-COLOR: #000000; BORDER-LEFT-COLOR: #000000; BORDER-RIGHT-COLOR: #000000; BORDER-BOTTOM-COLOR: #000000; BORDER-TOP-WIDTH: 1px; BORDER-LEFT-WIDTH: 1px; FONT-SIZE: 12px; BORDER-BOTTOM-WIDTH: 1px; FONT-FAMILY: Arial,Helvetica; BORDER-RIGHT-WIDTH: 1px}
	base=window.opener.top.base
	url_indice=""
	Ctrl_activo=""
	function getElement(psID) {
		return document.getElementById(psID);

	} else {
		return document.all[psID];
	}
}

	    document.forma1.Indice.value=xI
	    Separa="&delimitador="+Separa
  		msgwin.focus()

	}

	function AbrirIndice(ira){

	function Ayuda(tag){
		help=window.opener.url_H
		if (help!=""){
			tagx=String(tag)
			if (tagx.length<3) tagx="0"+tagx
			if (tagx.length<3) tagx="0"+tagx
			url="../documentacion/ayuda_db.php?help="+base+"/ayudas/<?php echo $_SESSION["lang"]."/"?>tag_"+tagx+".html"
		}
		msgwin=window.open(url,"Ayuda","status=yes,resizable=yes,toolbar=no,menu=yes,scrollbars=yes,width=600,height=400,top=100,left=100")
		msgwin.focus()

</script>
	<div class="helper">
	<a href=../documentacion/ayuda.php?help=<?php echo $_SESSION["lang"]?>/assist_sc.html target=_blank><?php echo $msgstr["help"]?></a>&nbsp &nbsp;
	<?php if (isset($_SESSION["permiso"]["CENTRAL_EDHLPSYS"])) echo "<a href=../documentacion/edit.php?archivo=".$_SESSION["lang"]."/assist_sc.html target=_blank>".$msgstr["edhlp"]."</a>";
	echo "<font color=white>&nbsp; &nbsp; Script: campos.php" ?>
</font>
	</div>
 <div class="middle form">
			<div class="formContent">
<body link=black vlink=black bgcolor=white>

<form name=agregarpicklist action=../dbadmin/picklist_edit.php method=post target=Picklist>
   <input type=hidden name=base value=<?php echo $arrHttp["base"]?>>
   <input type=hidden name=picklist>
   <input type=hidden name=Ctrl>
   <input type=hidden name=valor>
   <input type=hidden name=desde value=dataentry>
</form>

<form name=refrescarpicklist action=../dbadmin/picklist_refresh.php method=post target=Picklist>
   <input type=hidden name=base value=<?php echo $arrHttp["base"]?>>
   <input type=hidden name=picklist>
   <input type=hidden name=Ctrl>
   <input type=hidden name=valor>
   <input type=hidden name=desde value=dataentry>
</form>


<input type=hidden name=tagcampo>
<input type=hidden name=Formato>
<input type=hidden name=Repetible>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class=td2>
    	<td>
		</td>
	<tr>
		<td id="asubc">
		</td>
	<td align=center>
		<a href="javascript:self.close()"><img src=img/cancelar.gif  border=0><br><?php echo $msgstr["cancelar"]?></a>
	</td>
  	if (Occ>0) {
  	}else{
  		Redraw("")
</center>
</div>
</div>
<?php include("../common/footer.php")?>
<?php
// ===============================================
function PickList($ix,$file){
global $db_path,$lang_db,$arrHttp;
	$Options="";
	if (!file_exists($archivo)) $archivo=$db_path.$arrHttp["base"]."/def/".$lang_db."/".$file;
	if (file_exists($archivo)){
		$Options="";
		foreach ($fp as $value) {
			if ($value!=""){

?>
