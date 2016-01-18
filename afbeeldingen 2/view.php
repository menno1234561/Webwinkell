<html>
<head>
<title>Images uit database voorbeeld</title>	
<link rel="stylesheet" href="style.css" type="text/css" media="screen" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
<body>
<h1>Images uit database</h1>
<p>
Klik op de naam van een image om het uit de database te halen. Klik <a href="filepicker.php">hier om een image toe te voegen</a>.
</p>
<?php
// Dit bestand toont de images uit de database in een HTML pagina.
// In view.php wordt de informatie over de images opgahaald uit de database. Het image
// zelf wordt hier niet opgehaald, dat gebeurt in showfile.php, die aangeroepen wordt
// als source van de <img> tag.
//

$DBServer = 'localhost'; 	// e.g 'localhost' or '192.168.1.100'
$DBUser   = 'root';			// database username
$DBPass   = '';				// password voor database user
$DBName   = 'test';			// database die je wilt gebruiken

error_reporting(E_ERROR | E_PARSE);

// Stap 1: maak verbinding met MySQL.
$conn = mysqli_connect($DBServer, $DBUser, $DBPass, $DBName);
// check connection
if (mysqli_connect_errno()) {
	trigger_error('Database connection failed: '  . mysqli_connect_error(), E_USER_ERROR);
}

// Stap 2: Stel de query op, en voer deze uit.
// We halen het image zelf hier nog niet op; alleen de beschrijvende informatie.
$sql = "SELECT image_id, image_type, image_size, image_name FROM testblob ORDER BY image_id ASC;" ;

// Query uitvoeren
$result = mysqli_query($conn, $sql);
if($result === false) {
	echo "<p>Er zijn geen images gevonden.</p>\n";
} else {
	$num = mysqli_num_rows($result);
}

// Stap 3: Verwerk het resultaat tot HTML.
$result->data_seek(0);	// Terug naar het begin van de resultaten
?>
<p>
<table>
<thead>
<tr>
	<th>image_id</th>
	<th>image_type</th>
	<th>image_size</th>
	<th>image_naam</th>
	<th>&nbsp;</th>
</tr>
</thead>
<tbody>
<?php
while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
	$i = $row["image_id"];
	print "<tr>";
	print "<td>".$row["image_id"]."</td>" ;
	print "<td>".$row["image_type"]."</td>" ;
	print "<td>".$row["image_size"]."</td>" ;
	print "<td><a href=\"view.php?image_id=$i\">".$row["image_name"]."</a></td>" ;
	print "<td><a href=\"deletefile.php?image_id=$i\"><img src=\"delete.png\"/></a></td>" ;
	print "</tr>\n" ;
}
?>
</tbody>
</table>
</form>
<p>

<?php
$image_id = 0;
// De ID van het image dat we willen afbeelden wordt in de URL meegegeven. 
// Hier testen we of deze aanwezig is.
if (isset($_GET["image_id"]))
{
	$image_id = $_GET["image_id"];

	$sql = "SELECT image_type, image_size, image_name FROM testblob WHERE image_id=".$image_id;

	/* maak de resultset leeg */
	mysqli_free_result($result);

	/*** exceute the query ***/
	$result = mysqli_query($conn, $sql);
	if($result === false) {
		echo "<p>Er zijn geen resultaten gevonden.</p>\n";
	}

	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
	/*** the size of the array should be 3 (1 for each field) ***/
	if(sizeof($row) === 3) {
		echo '<p>Dit is de afbeelding \''.$row['image_name'].'\' uit de database</p>';
		echo '<p><img id=\'plaatje\' '.$row['image_size'].' src="showfile.php?image_id='.$image_id.'"></p>';
	}

	/* maak de resultset leeg */
	mysqli_free_result($result);

	/* sluit de connection */
	mysqli_close($conn);
}
?>

