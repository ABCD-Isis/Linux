
<html>

<head>
  <title></title>
</head>

<body>

<?php
session_start();
$base=$_SESSION["base_barcode"];
$arrHttp["base"]=$base;
include("../config.php");
$parms=array();
if (file_exists($db_path.$base."/def/".$_SESSION["lang"]."/barcode_label.tab")){
	$fp=file($db_path.$base."/def/".$_SESSION["lang"]."/barcode_label.tab");
	foreach ($fp as $linea){
		$linea=trim($linea);
		$l=explode('=',$linea);
		$parms[$l[0]]=$l[1];
	}

}else{	echo "<h1>Falta la tabla $base/def/".$_SESSION["lang"]."/barcode_label.tab</h1>";
	die;}
$desde=$_REQUEST["desde"];
$hasta=$_REQUEST["hasta"];
echo "<style>
td{
	width:".$parms["width"]."cm;height:".$parms["height"]."cm;
}
</style>\n";
echo "<table  border=0 cellpadding=0 cellspacing=0>";
$cols=$parms["cols"];
$ixcol=$cols+2;
$ixrow=0;
$width="5cm";
$height="5cm";
$code_size=trim(strlen($desde));
for ($ix=$desde;$ix<=$hasta;$ix++){	if ($ixcol>=$cols){		$ixcol=0;
		$ixrow=$ixrow+1;
		echo "<tr>\n";
	}
	$ix_formatted=str_pad ( $ix , $max_cn_length , "0" , STR_PAD_LEFT );
	//echo "<td class=td align=center><img src=barcode_draw.php?text=$ix_formatted></td>";
	echo "<td class=td align=center><font face='".trim($parms["font_name"])."' size=".trim($parms["font_size"]).">$ix_formatted</td>";
	$ixcol=$ixcol+1;
	if ($ixcol>=$cols){		echo "</tr>\n";
	}}
?>
</table>
</body>

</html>