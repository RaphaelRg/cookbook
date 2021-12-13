<?php
function conntectdb() {
  // Function to establish a db connection

  // Set connection values
  $servername = "localhost";
  $username = "root";
  $password = "password";
  $dbname = "dbname";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  //Return connection
  return $conn;
}

function create_rezept($result, $resultarray) {
  // Function to create Box for new recepy

  // Go through all retrieved db lines
  while($row = $result->fetch_assoc()) {
    // Set html string that defines recepy
    $string = "<div id='retrievedrezept'>" .
                "<div id='retrievedrezeptleft'>" .
                  "<div id='retrievedrezepttitel'>".$row["titel"]."</div>" .
                  "<div id='retrievedrezeptjahr'>" .
                    "Jahr: " . $row["jahr"] . 
                    "  Heft-Nr.: " . $row["heft"] .
                  "</div>" .
                "</div>" .
                "<div id='retrievedrezeptright'>" .
                  "<div id='retrievedrezeptgang'>".$row["gang"]."</div>" .
                  "<div id='retrievedrezeptzutat'>".
                    "Hauptzutat: " . $row["hauptzutat"] . "<br>" .
                    "Schlagwort: " . $row["schlagwort"] .
                  "</div>" .
                "</div>" .
              "</div>";

    // Add string to recepy array
    array_push($resultarray, $string);
  }

  // Return updated array
  return $resultarray;
}

function retrive_rezepte ($titel, $jahr, $heft, $gang, $hauptzutat, $schlagwort) {
  // Function to retrieve recepy based on search query
  
  // Establish connection
  $conn = conntectdb();

  // Create Result array
  $resultarray = array();

  // Title AND Hauptzutat AND Schlagwort
  if ($titel != "" && $hauptzutat != "" && $schlagwort != ""){
    // Create SQL query to extract based on title
    $sql = "SELECT * FROM rezepte WHERE titel = '$titel' AND hauptzutat = '$hauptzutat' AND schlagwort = '$schlagwort' AND gang ='$gang'";
    // Get Result from db
    $result = $conn->query($sql);
    
    // Add result to final array
    $resultarray = create_rezept($result, $resultarray);
  }

  // Title
  if (count($resultarray)<1){
    // Check db only for correct Title
    if ($titel != ""){
      // Create SQL query to extract based on title
      $sql = "SELECT * FROM rezepte WHERE titel = '$titel'";
      // Get Result from db
      $result = $conn->query($sql);
      
      // Add result to final array
      $resultarray = create_rezept($result, $resultarray);
    }
  }

  // Create Schlüsselwort array for more results
  if (count($resultarray)<1){
    if ($titel != "") {
      // Words to delete from Title
      $wordstodelete = array(" mit ", " und ", " der ", " die ", " das ", " an ", " bei ", " auf ", "-");
      // Create shortend title
      $shortendtitle = $titel;
      // Go through all word to delete from title
      foreach($wordstodelete as $word) {
        $shortendtitle = str_replace($word, " ", $shortendtitle);
      }
      // Split title string into single words -> save to array
      $schluesselwoerter = preg_split("/[\s,]+/", $shortendtitle);
    }
  }
  
  // Title variations, Hauptzutat, Schlagwort
  if (count($resultarray)<1){
    // Check db for different variations of title and Hauptzutat and Schlagwort
    if ($titel != "" && $hauptzutat != "" && $schlagwort != ""){
      // Create temp array to save results from title parts
      $schluesselwortresults = array();
  
      // Go through each word in schlüsselwörter
      foreach($schluesselwoerter as $schluesselwort) {
        // Create SQL query to extract based Schlüsselwort and Hauptzutat and Schlagwort
        $sql = "SELECT * FROM rezepte WHERE titel LIKE '%$schluesselwort%' AND hauptzutat = '$hauptzutat' AND schlagwort = '$schlagwort' AND gang ='$gang'";
        // Get Result from db
        $result = $conn->query($sql);
  
        // Write results to temp array
        $schluesselwortresults = create_rezept($result, $schluesselwortresults);
      }
      // Check if query resulted in results
      if (count($schluesselwortresults)>0) {
        // Write results to final array
        foreach ($schluesselwortresults as $schluesselwortresult) {
          array_push($resultarray, $schluesselwortresult);
        }
      } else{
        // No results based on Schlüsselwort AND Hauptzutat AND Schlagwort
  
        // Check db for different variations of title and Hauptzutat
        if ($titel != "" && $hauptzutat != ""){

          // Go through each word in schlüsselwörter
          foreach($schluesselwoerter as $schluesselwort) {
            // Create SQL query to extract based Schlüsselwort and Hauptzutat
            $sql = "SELECT * FROM rezepte WHERE titel LIKE '%$schluesselwort%' AND hauptzutat = '$hauptzutat' AND gang ='$gang'";
            // Get Result from db
            $result = $conn->query($sql);
          
            // Write results to temp array
            $schluesselwortresults = create_rezept($result, $schluesselwortresults);
          }
  
          // Check if query resulted in results
          if (count($schluesselwortresults)>0) {
            // Write results to final array
            foreach ($schluesselwortresults as $schluesselwortresult) {
              array_push($resultarray, $schluesselwortresult);
            }
          } else{
            // No results based on Schlüsselwort AND Hauptzutat
  
            // Check db for different variations of title and Schlagwort
            if ($titel != "" && $schlagwort != ""){
              
              // Go through each word in schlüsselwörter
              foreach($schluesselwoerter as $schluesselwort) {
                // Create SQL query to extract based Schlüsselwort and Schlagwort
                $sql = "SELECT * FROM rezepte WHERE titel LIKE '%$schluesselwort%' AND schlagwort = '$schlagwort' AND gang ='$gang'";
                // Get Result from db
                $result = $conn->query($sql);
              
                // Write results to temp array
                $schluesselwortresults = create_rezept($result, $schluesselwortresults);
              }
  
              // Check if query resulted in results
              if (count($schluesselwortresults)>0) {
                // Write results to final array
                foreach ($schluesselwortresults as $schluesselwortresult) {
                  array_push($resultarray, $schluesselwortresult);
                }
              } else{
                // No results based on Schlüsselwort AND Schlagwort
  
                // Check db for different variations of title
                if ($titel != ""){
                  
                  // Go through each word in schlüsselwörter
                  foreach($schluesselwoerter as $schluesselwort) {
                    // Create SQL query to extract based Schlüsselwort
                    $sql = "SELECT * FROM rezepte WHERE titel LIKE '%$schluesselwort%' AND gang ='$gang'";
                    // Get Result from db
                    $result = $conn->query($sql);
                  
                    // Write results to final array
                    $resultarray = create_rezept($result, $resultarray);
                  }
                }
              }
            }
          }
        }
      }
    }
  }

  // Title variations
  if (count($resultarray)<1){
    // Check db for different variations of title and Hauptzutat and Schlagwort
    if ($titel != ""){
      // Go through each word in schlüsselwörter
      foreach($schluesselwoerter as $schluesselwort) {
        // Create SQL query to extract based Schlüsselwort and Hauptzutat and Schlagwort
        $sql = "SELECT * FROM rezepte WHERE titel LIKE '%$schluesselwort%' AND gang ='$gang'";
        // Get Result from db
        $result = $conn->query($sql);
  
        // Write results to final array
        $resultarray = create_rezept($result, $resultarray);
      }
    }
  }

  // Hauptzutat, Schlagwort
  if (count($resultarray)<1){
    // Check db for correct Hauptzutat
    if ($hauptzutat != "") {
      // Create SQL query to extract based on Hauptzutat
      $sql = "SELECT * FROM rezepte WHERE hauptzutat = '$hauptzutat' AND gang ='$gang'";
      // Get Result from db
      $result = $conn->query($sql);
      
      // Write results to final array
      $resultarray = create_rezept($result, $resultarray);
    }
    // Check db for correct Schlagwort
    if ($schlagwort != "") {
      // Create SQL query to extract based on Schlagwort
      $sql = "SELECT * FROM rezepte WHERE schlagwort = '$schlagwort' AND gang ='$gang'";
      // Get Result from db
      $result = $conn->query($sql);
      
      // Write results to final array
      $resultarray = create_rezept($result, $resultarray);
      }
  }

  // Jahr AND Heft
  if (count($resultarray)<1){
    // Check db for jahr and heft
    if ($jahr!="" && $heft!=""){
      // Create SQL query to extract based on Jahr and Heft
      $sql = "SELECT * FROM rezepte WHERE  jahr = '$jahr' AND heft = '$heft' AND gang = '$gang'";
      // Get Result from db
      $result = $conn->query($sql);

      // Write results to final array
      $resultarray = create_rezept($result, $resultarray);
    }
  }

  // Jahr OR Heft
  if (count($resultarray)<1){
    // Check db for heft
    if ($heft!=""){
      // Create SQL query to extract based on Heft
      $sql = "SELECT * FROM rezepte WHERE heft = '$heft' AND gang = '$gang'";
      // Get Result from db
      $result = $conn->query($sql);

      // Write results to final array
      $resultarray = create_rezept($result, $resultarray);
    }
    // Check db for jahr
    if ($jahr!=""){
      // Create SQL query to extract based on Heft
      $sql = "SELECT * FROM rezepte WHERE jahr = '$jahr' AND gang = '$gang'";
      // Get Result from db
      $result = $conn->query($sql);
      
      // Write results to final array
      $resultarray = create_rezept($result, $resultarray);
    }
  }

  // Close the connection
  $conn->close();

  // Check if there were any results
  if(count($resultarray) > 0) {
    // Return Results
    return $resultarray;
  }else{
    // No Results were obtained
    // Return string
    $resultarray = array("Keine Übereinstimmungen!");
    return $resultarray;
  }
}

function insert_rezepte ($titel, $jahr, $heft, $gang, $hauptzutat, $schlagwort) {
  $conn = conntectdb();
  if($jahr!="" && $heft !=""){
    $sql = "INSERT INTO rezepte (titel, jahr, heft, gang, hauptzutat, schlagwort)
      VALUES ('$titel', '$jahr', '$heft', '$gang', '$hauptzutat', '$schlagwort')";
  }else{
    $sql = "INSERT INTO rezepte (titel, gang, hauptzutat, schlagwort)
      VALUES ('$titel', '$gang', '$hauptzutat', '$schlagwort')";
  }

  $sqlcheck = "SELECT * FROM rezepte WHERE titel = '$titel'";
  $resultcheck = $conn->query($sqlcheck);
  if ($resultcheck->num_rows > 0){
    return "<div class='noentry'>EINTRAG EXISTIERT SCHON!</div>";
  }
  else {
    if ($conn->query($sql) == TRUE) {
      $conn->close();
      return "<div class='entry'>Neuer Eintrag hinzugefügt</div>";
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
  }
}

function zufall_rezept () {
  $conn = conntectdb();

  for ($i=0; $i < 10; $i++){
    
    while (TRUE){
      $sql = "SELECT schlagwort FROM rezepte ORDER BY RAND() LIMIT 1";
      $schlagwort_sql = $conn->query($sql);
      $schlagwort = "";
      foreach ($schlagwort_sql as $array) {
        foreach ($array as $wort){
          if (strlen($wort)>0){
            $schlagwort = $wort;
          }
        }
      }
      if (strlen($schlagwort) > 0){
        break;
      }
    }

    $gaenge = array("Vorspeise", "Hauptgang", "Nachspeise");
    $zufallsmenu = array();
    foreach ($gaenge as $gang){
      $sql = "SELECT * FROM rezepte WHERE schlagwort = '$schlagwort' AND gang ='$gang'";
      $gericht = $conn->query($sql);

      if (mysqli_num_rows($gericht)>1) {
        $zufallsgericht = array();
        $zufallsgericht = create_rezept($gericht, $zufallsgericht);

        $random = rand(0, mysqli_num_rows($gericht)-1);

        array_push($zufallsmenu, $zufallsgericht[$random]);
      }else{
        $zufallsmenu = create_rezept($gericht, $zufallsmenu);
      }

    }

    if (count($zufallsmenu) == 3){
      break;
    }

  }
  
  $conn->close();
  if (count($zufallsmenu) == 3){
    return $zufallsmenu;
  }
  else{
    return array("Leider kein Menu gefunden. <br> Versuche es nochmal!");
  }
  
  
}
?>