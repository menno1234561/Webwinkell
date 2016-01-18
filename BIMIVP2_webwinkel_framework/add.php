<?php
// add.php
// stuurt gekozen artikel door naar de winkelwagen cart.php

session_start();

// Het product wat we toevoegen moeten we eerst controleren
if(is_numeric($_POST['productnummer'])) {
    $productnummer = $_POST['productnummer'];
}
else {
    echo("Productnummer is niet numeriek");
    echo "<p><a href=\"javascript:history.back()\">Pagina terug!!</a></p>\n";
    exit();
}

//Controleren of invoer numerieke waarde is
if(is_numeric($_POST['hoeveelheid'])) {
    $hoeveelheid = $_POST['hoeveelheid'];
}
else {
    echo("Hoeveelheid is niet numeriek!!");
    echo "<p><a href=\"javascript:history.back()\">Pagina terug!!</a></p>\n";
    exit();
}

// Kijken of er wel iets besteld is?
if ($hoeveelheid == 0) {
    echo "<p>Aantal 0 is niet te bestellen</p>\n";
    echo "<p><a href=\"javascript:history.back()\">Pagina terug!!</a></p>\n";
    exit();
}

// Controleren of er al inhoud is op de winkelwagen
if (empty($_SESSION['cart'])){
    // Nee dus, een nieuwe maken
	echo "Er is nog geen cart. <br/>";
    $_SESSION['cart'] = $productnummer.",".$hoeveelheid; // Het productnummer,hoeveelheid staat dus in een sessie
}
else {
	echo "cart is ".$_SESSION['cart']."<br/>";

    // Winkelwagen opsplitsen op de pipe
    $cart = explode("|",$_SESSION['cart']);

	echo "cart is ".$_SESSION['cart']."<br/>";

    // Winkelwagen inhoud tellen
    $count = count($cart);

    // En controleren of het product al in de winkelwagen zit
    $add = TRUE; // var om later te kijken of we moeten toevoegen
    $i = 0;
    foreach($cart as $value)
    {
        $product = explode(",",$value);
        if ($product[0] == $productnummer) {
            // Product al in de winkelwagen
            $product[1] = $product[1] + $hoeveelheid; // Nieuwe hoeveelheid is oude + nieuwe
            $add = FALSE; // Dus niet toevoegen
        }

        // En weer in de sessie zetten
        $i++;
        if ($i == 1) {
            $_SESSION['cart'] = $product[0].",".$product[1];
        }
        else {
            $_SESSION['cart'] = $_SESSION['cart']."|".$product[0].",".$product[1];
        }
    }

    if ($add) { // Als we dus wel moeten toevoegen
        $_SESSION['cart'] = $_SESSION['cart']."|".$productnummer.",".$hoeveelheid;
    }
}

echo "cart is ".$_SESSION['cart']."<br/>";

// forward to cart
header("Location: cart.php");
?> 
