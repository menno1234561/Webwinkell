DROP DATABASE IF EXISTS candylife;
CREATE DATABASE IF NOT EXISTS candylife;
USE candylife;

CREATE TABLE Klant (
	Voornaam		VARCHAR(16),
	Achternaam		VARCHAR(32),
	EmailAdres		VARCHAR(32),	
	Adres			VARCHAR(32),
	Postcode		VARCHAR(6),
	Woonplaats		VARCHAR(32),
	TelNr			INT(10),
	PRIMARY KEY(Voornaam, Achternaam, EmailAdres)
);

CREATE TABLE Bestelling (
	OrderNummer		INT(16),
	Datum			DATE,
	Tijd			TIME,	
	VoornaamKL		VARCHAR(16),
	AchternaamKL	VARCHAR(32),
	EmailAdresKL	VARCHAR(32),
	PRIMARY KEY(OrderNummer),
	CONSTRAINT KlantFK
		FOREIGN KEY (VoorNaamKL, AchterNaamKL, EmailAdresKL)
		REFERENCES Klant(Voornaam, AchterNaam, EmailAdres)
);

CREATE TABLE Herkomst (
	Land			VARCHAR(32),
	Vlag			VARCHAR(50),
	PRIMARY KEY(Land)
);

CREATE TABLE Categorie (
	Categorie		VARCHAR(32),
	Image			LONGBLOB,
	PRIMARY KEY(Categorie)
);

CREATE TABLE Product (
	ProductNaam		VARCHAR(32),
	ProductPrijs	FLOAT(4),
	Image			LONGBLOB,	
	Beschrijving	VARCHAR(64),
	Voorraad		INT(4),
	LandHK			VARCHAR(32),
	CategorieCT		VARCHAR(32),
	PRIMARY KEY(ProductNaam),
CONSTRAINT LandFK
		FOREIGN KEY (LandHK)
		REFERENCES Herkomst(Land),
CONSTRAINT CategorieFK
		FOREIGN KEY (CategorieCT)
		REFERENCES Categorie(Categorie)
);

CREATE TABLE OrderProduct (
	Aantal			INT(4),
	OrderNummerOR	INT(16),
	ProductNaamPR	VARCHAR(32),	
CONSTRAINT OrderFK
		FOREIGN KEY (OrderNummerOR)
		REFERENCES Bestelling(OrderNummer),
CONSTRAINT ProductFK
		FOREIGN KEY (ProductNaamPR)
		REFERENCES Product(ProductNaam)
);
