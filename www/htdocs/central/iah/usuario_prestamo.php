<?php
include ("../common/get_post.php");
//foreach ($arrHttp as $var=>$value)  echo "$var=$value<br>";
include ("../config.php");
echo "<script>
Opcion='".$arrHttp["desde"]."'
</script>\n";
$db_path=$arrHttp["DB_PATH"];
switch ($arrHttp["desde"]){
	case "IAH_RESERVA":
		$action="../reserve/buscar.php";
		if (isset($def["LOGO"]))echo "<img src=".$def["LOGO"]."><BR>";
		break;
	case "IAH_ECTA":
		$action="../circulation/opac_statement_ex.php";
	    break;
}
?>

<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
  <title>User ID</title>
</head>

<?php //include ("header.php")?>
<form name=solicitud action=<?php echo $action?>  method=post>
<?php
include ("../common/get_post.php");
include ("../config.php");
echo "<script>
Opcion='".$arrHttp["desde"]."'
</script>\n";
$db_path=$arrHttp["DB_PATH"];

?>
<font face="arial" size="2">
<font color="maroon">

<p>
<font face="arial" size="1">
<b>For
<?php
switch ($arrHttp["desde"]){
	case "IAH_RESERVA":
		echo " reservation";
		break;
	case "IAH_ECTA":
		echo " consult user account ";
	    break;

}
?>
	enter your user ID <BR>

</b>
	<br><br>


<?php
echo "<br><br>";
echo "User ID : ";
echo "<input type=text name=usuario size=10>\n";
echo "<input type=submit value=OK >\n";
echo "&nbsp; &nbsp;<input type=button name=cerrar value=Close  onclick=\"self.close()\">";
foreach ($arrHttp as $var=>$value){
	echo "<input type=hidden name=$var value=\"$value\">\n";
}




?>
</form>
<br><br>


</body>

</html>