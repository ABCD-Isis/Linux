<?php

        require_once (dirname(__FILE__)."/../config.php");
        $def = parse_ini_file($db_path."abcd.def");
        //print_r($def);
        ?>

		<div class="footer">
			<div class="systemInfo">
				<strong>ABCD <?php if (isset($def["VERSION"])) echo $def["VERSION"];?></strong>
				<span><?php if (isset($def["LEGEND1"])) echo $def["LEGEND1"]; ?></span>
				<?php if(isset($def["URL1"])){
					echo "<a href=".$def["URL1"]." target=_blank>". $def["URL1"]."</a>";
				}
				?>
			</div>
			<?php if (isset($def["URL2"])){
				echo "<div class=\"distributorLogo\">";
				echo "<a href=".$def["URL2"]." target=_blank><span>". $def["LEGEND2"]."</span></a>
			</div> ";
			}
			?>
			<div class="spacer">&#160;</div>
		</div>
