<html>

<head>
    <title>Eintragen</title>
    <link rel="stylesheet" href="styles.css">
</head>

<!-- Header Menu of the Page -->
<header>
		
	<!-- Top header menu containing
		logo and Navigation bar -->
	<div id="headfloater">
			
		<!-- Logo -->
		<div id="logo">
			<a href="home.php">
				<img src="images/logo.png"/>
			</a>
		</div>
				
		<!-- Navigation Menu -->
        <div id="dropdown">
            <img src="images/menu.png"/>
            <div id="dropdown-content">
				<a href="home.php">Rezept</a>
                <a href="eintragen.php">Eintragen</a>
                <a href="zufall.php">Zufall</a>
            </div>
        </div>
	</div>

</header>

<body>
    <h1 style="margin-top:1em">Neues Rezept eintragen</h1>

	<div id="eintragenphp">
		<?php
			$output ="<div class='noentry'>No output Created!</div>";
			if (isset($_POST['Eintragen'])){
				require_once("connectdb.php"); 
				$output = insert_rezepte($_POST["titel"], $_POST["jahr"], $_POST["heft"], $_POST["gang"], $_POST["hauptzutat"], $_POST["schlagwort"]);
				echo $output;
			}
		?>
	</div>
	
    <form id="rezeptsuche" method="post">
		<div class = "formline">
			<label for="titel">
				<div class = "formfield"> Titel:	<input type="text" name="titel" class = "formfield"  size="25"> </div>
			</label>
		</div>
		<div class = "formline">
			<label for="heft">
				<div class = "formfield"> Jahr:	<input type="text" class = "formfield" name="jahr" size="5"> </div>
			</label>
			<label for="heft">
				<div class = "formfield"> Heft Nr.:	<input type="text" class = "formfield" name="heft" size="5"> </div>
			</label>
		</div>
		<div class = "formline">
			<div class = "formfield">
			<label for="gang">Gang:					
				<select name="gang" class = "formfield" id="gang">
					<option value="Vorspeise">Vorspeise</option>
					<option value="Hauptgang">Hauptgang</option>
					<option value="Nachspeise">Nachspeise</option>
					<option value="Gebäck">Gebäck</option>
				</select>
			</label>
			</div>
			<label for="hauptzutat">
				<div class = "formfield"> Hauptzutat:	<input type="text" class = "formfield" name="hauptzutat" size="20"> </div>
			</label>
			<label for="schlagwort">
				<div class = "formfield"> Schlagwort:	<input type="text" class = "formfield" name="schlagwort" size="20"> </div>
			</label>
		</div>
		<div>
			<input type="submit" name="Eintragen"
				class = "formfield" value="Eintragen"/>
		</div>
	</form>
	
	<img id="eintragenimg" src="images/nachtisch.jpg">

</body>
</html>