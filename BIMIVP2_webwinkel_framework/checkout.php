<?php
// checkout.php
//
// Dit bestand zorgt ervoor dat de winkelwagen van de klant in een bestelling en één of meer
// bestelregels wordt opgenomen. Als dit gelukt is is de bestelling geregistreerd
// en de winkelwagen geleegd.
//
// Opdracht: op dit moment wordt de actuele prijs van een product én de totaalprijs 
// van de bestelling nog niet bij de bestelling in de database geregistreerd. 
// Zorg ervoor dat deze prijzen worden geregistreerd bij een bestelling.

$page_title = 'de WebWinkel';
include ('includes/header.html');

// mysqli_connect.php bevat de inloggegevens voor de database.
// Per server is er een apart inlogbestand - localhost vs. remote server
include ('includes/mysqli_connect_'.$_SERVER['SERVER_NAME'].'.php');

// Page header:
echo '<h1>Bestelling afronden</h1>';

if (empty($_SESSION['klantnr'])) {
    echo "<p>U ben nog niet ingelogd. <a href=\"login.php\">Log eerst in</a> om uw bestelling af te ronden.</p>\n";
} else {

	// Afsluiten van bestelling en bestelregel opslaan in database

	//connectie maken met database webwinkel
	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	 
	// check connection
	if (mysqli_connect_errno()) {
		printf("<p><b>Fout: verbinding met de database mislukt.</b><br/>\n%s</p>\n", mysqli_connect_error());
		include ('includes/footer.html');
		exit();
	}

	// Stap 1, zet de order in de bestelling tabel
	// Een bestelling heeft ook een bestelnummer (autoincrement), besteldatum (huidige datum/tijd), 
	// en status (default: open).
	$sql = "INSERT INTO bestelling (`klantnummer`) VALUES ('".$_SESSION['klantnr']."');"; 
	$result = mysqli_query($conn, $sql) or die (mysqli_error($conn)."<br>in file ".__FILE__." on line ".__LINE__);

	$bestelnr = mysqli_insert_id($conn); // insert_id geeft de id terug van het autoincrement veld - het bestelnr dus.

	// Stap 2, winkelwagen splitsen en de producten in bestelregels in de database zetten
	$cart = explode("|",$_SESSION['cart']);

	foreach($cart as $products) {
		// Splits het product in stukjes: $product[x] --> x == 0 -> product id, x == 1 -> hoeveelheid
		$product = explode(",",$products);

		// Hier willen we productprijs toevoegen aan de productregel. De productprijs is de prijs van het 
		// product. Deze zit nog niet in de sessie, en moet daar dus bij het bestellen (bijvoorbeeld 
		// in index.php) in worden gezet.
		// We tellen hier ook het bedrag per product op (prijs x aantal) en tellen dit op bij de totaalprijs.
		// Je kunt in cart.php kijken hoe je dat kunt doen.
		$sql = "INSERT INTO bestelregel (bestelnummer, productnummer, productprijs, aantal_besteld) VALUES
		(".$bestelnr.", ".$product[0].", ".$product[1].", 0.0)";
		$result = mysqli_query($conn, $sql) or die (mysqli_error($conn)."<br>in file ".__FILE__." on line ".__LINE__);
	}

	// 
	// Op dit moment hebben we de totaalprijs berekend. Deze moeten we nu nog in een aparte
	// query in de bestelling zetten. Je hebt $bestelnr, dus voeg daar de totaalprijs aan toe.
	// 
	
	// Bericht naar de gebruiker.
	echo "<p>Uw bestelling is afgerond.</p>";

	// Leeg de winkelwagen door deze uit de sessie te verwijderen.
	// De overige gegevens in de sessie blijven behouden.
	if(isset($_SESSION['cart']))
		unset($_SESSION['cart']);

	// Sluit de connection
	mysqli_close($conn);
}	
include ('includes/footer.html');
?> 
