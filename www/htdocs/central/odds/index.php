  <html>
      <head>
      	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">			
      	<title>Test ODDS</title>      
	  	<script type="text/javascript" src="js/odds.js"></script>	    
    </head>	
	<body> 
		<h1> Test - Acceso al formulario ODDS </h1>
		<a target="_blank" href='form_odds.php'>Formulario espontáneo (sin popup)</a>
		<hr/>
    <!--
    <a target="_blank" href='form_odds.php?lang=es&id=12987&name=Juan&category=est&email=juan@gmail.com&phone=12898-98 (29)&level=am&tag010=autor xxxx&tag012=titulo zzzz&tag016=Otro autor xxxx&tag018=Otro titulo zzzz&tag086=valor_tag86&referer=iah'>Form! sin popup con parámetros</a>
    <hr/>
		<a href="JavaScript:newPopup('form_odds.php?resize=yes&js=yes', 1273, 655);">Form! con popup</a><a>
		<hr/>
        <?php            
            $name ="Juan Pérez";            
            echo "<a href=\"JavaScript:newPopup('form_odds.php?".urlencode("lang=es&id=12987&name=$name&category=est&email=juan@gmail.com&phone=12898-98 (29)&level=am&tag010=autor xxxx&tag012=titulo zzzz&tag016=Otro autor xxxx&tag018=Otro titulo zzzz&tag086=valor_tag86&referer=iah&js=yes', 1300, 655)").";\">Form! con popup y datos por GET</a>";
        ?>
		  <hr/>
    -->
    <form id="verdocumentoSA" name="verdocumentoSA" action="../iah/ver_documento.php" method="post" target="AEUDOC">
          <a class="common_link" href="javascript:onClick=VerDocumentoSA('users', '')">
          Formulario con ventana para validación de usuario
          </a>          
          <input type="hidden" id="parametersODDS" name="parametersODDS" value="">
          <input type="hidden" id="sa" name="sa" value="sa">
          <input type="hidden" id="base" name="base" value="users">
    </form>

	</body>
</html>
