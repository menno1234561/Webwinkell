--
-- Dit script maakt een tabel 'afbeelding'. In de webwinkel is deze tabel
-- al aanwezig. Dit script is alleen nodig wanneer je een andere database 
-- zou gebruiken.
--

DROP TABLE IF EXISTS `afbeelding`;
CREATE TABLE afbeelding (
  image_id tinyint(3) NOT NULL AUTO_INCREMENT,
  image_type varchar(25) NOT NULL,
  image longblob NOT NULL,
  image_size varchar(25) NOT NULL,
  image_ctgy varchar(25) NOT NULL,
  image_name varchar(50) NOT NULL,
  KEY image_id (image_id)
);

-- Auto-increment telt automatisch door, ook wanneer je later records verwijdert.
-- Met de volgende query kun je de automatische telling herstellen.
-- Tellen gaat door na de hoogste waarde in de tabel.
ALTER TABLE `afbeelding` auto_increment=1;