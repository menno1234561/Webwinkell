<html>
<head>
<title>Images uit database voorbeeld</title>	
<link rel="stylesheet" href="includes/style.css" type="text/css" media="screen" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
<body>
<h1>Images uit database</h1>
<p>
Klik op de naam van een image om het uit de database te halen. Klik <a href="filepicker.php">hier om een image toe te voegen</a>.
</p>
<?php
// Dit bestand toont de images uit de database in een HTML pagina.
// In index.php wordt de informatie over de images opgahaald uit de database. Het image
// zelf wordt hier niet opgehaald, dat gebeurt in showfile.php, die aangeroepen wordt
// als source van de <img> tag.
//

error_reporting(E_ERROR | E_PARSE);

// mysqli_connect.php bevat de inloggegevens voor de database.
// Per server is er een apart inlogbestand - localhost vs. remote server
include ('includes/mysqli_connect_'.$_SERVER['SERVER_NAME'].'.php');

// Stap 1: maak verbinding met MySQL.
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// check connection
if (mysqli_connect_errno()) {
	printf("<p><b>Fout: verbinding met de database mislukt.</b><br/>\n%s</p>\n", mysqli_connect_error());
	exit();
}

// Stap 2: Stel de query op, en voer deze uit.
// We halen het image zelf hier nog niet op; alleen de beschrijvende informatie.
$sql = "SELECT `image_id`, `image_type`, `image_size`, `image_name` FROM `afbeelding` ORDER BY `image_id` ASC;" ;

// Query uitvoeren
$result = mysqli_query($conn, $sql);
if($result == false || mysqli_num_rows($result)==0) {
	echo "<br/><p>Er zijn geen images gevonden.</p>\n";
} else {
	// Stap 3: Verwerk het resultaat tot HTML.

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
	print "<td><a href=\"index.php?image_id=$i\">".$row["image_name"]."</a></td>" ;
	print "<td><a href=\"deletefile.php?image_id=$i\"><img src=\"delete.png\"/></a></td>" ;
	print "</tr>\n" ;
}
?>
</tbody>
</table>
</form>
</p>

	<?php
	$image_id = 0;
	// De ID van het image dat we willen afbeelden wordt in de URL meegegeven. 
	// Hier testen we of deze aanwezig is.
	if (isset($_GET["image_id"]))
	{
		$image_id = $_GET["image_id"];

		$sql = "SELECT `image_type`, `image_size`, `image_name` FROM `afbeelding` WHERE `image_id`=".$image_id;

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
	}
	/* maak de resultset leeg */
	mysqli_free_result($result);	

	/* sluit de connection */
	mysqli_close($conn);
}
?>
</body>
</html>

