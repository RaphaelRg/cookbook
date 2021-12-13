<html>

<head>
    <title>Zufallsrezept</title>
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

    <h1 style="margin-top:1em">Zufalls Rezept</h1>
	<form id="rezeptsuche" method="post">
		<div>
			<input type="submit" name="Zufall"
				class="formfield" value="Auf gut Glück!"/>
		</div>
	</form>

	<div id="retrievedrezepte">
		<?php
			if (isset($_POST['Zufall'])){
				require_once("connectdb.php");
				$results = zufall_rezept();
				foreach ($results as $result) {
					echo $result;
				}
			}
		?>
	</div>

</body>

</html>