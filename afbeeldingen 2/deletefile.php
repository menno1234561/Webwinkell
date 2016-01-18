<?php
//
// Image verwijderen uit de database, daarna meteen terug naar overzichtspagina.
// Image_id wordt meegegeven via GET in de URL. De URL heeft dus de vorm:
// deletefile.php?image_id=31. Via GET hebben we toegang tot deze variabele.
//

// mysqli_connect.php bevat de inloggegevens voor de database.
// Per server is er een apart inlogbestand - localhost vs. remote server
include ('includes/mysqli_connect_'.$_SERVER['SERVER_NAME'].'.php');

error_reporting(E_ERROR | E_PARSE);

// De ID van het image dat we willen verwijderen wordt in de URL meegegeven. 
$image_id = 0;

// Als het ID beschikbaar is kunnen we verder.
if (isset($_GET["image_id"]))
{
	$image_id = $_GET["image_id"];

	// Stap 1: maak verbinding met MySQL.
	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	// check connection
	if (mysqli_connect_errno()) {
		trigger_error('Database connection failed: '. mysqli_connect_error(), E_USER_ERROR);
	}

	// Stap 2: Stel de query op, en voer deze uit.
	$sql = "DELETE FROM `afbeelding` where `image_id` = '$image_id';";

	// Query uitvoeren
	$result = mysqli_query($conn, $sql);
	if($result === false) {
		echo "<p>Er zijn geen images gevonden.</p>\n";
	}

	/* maak de resultset leeg */
	mysqli_free_result($result);

	/* sluit de connection */
	mysqli_close($conn);
}

header("Location: index.php");
?>

