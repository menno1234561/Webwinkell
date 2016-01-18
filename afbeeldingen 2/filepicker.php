<html>
<head>
<title>Image upload naar database voorbeeld</title>	
<link rel="stylesheet" href="includes/style.css" type="text/css" media="screen" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
<body>
<h1>Image upload naar database</h1>
<p>Selecteer een image om het aan de database toe te voegen.</p>
<p>
<form enctype="multipart/form-data" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="99999999" />
<div id="invoer">
<input name="userfile" type="file" />
<input class="button" type="submit" value="Submit" />
</div>
</form>
</p>
</body>
</html>

<?php
//
// Dit bestand maakt het mogelijk om een bestand te selecteren en toe te voegen 
// aan de database. Wanneer het bestand succesvol is toegevoegd wordt het meteen
// op het scherm getoond via de aanroep index.php?image_id=id.  

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

/*** check if a file was submitted ***/
if(isset($_FILES['userfile'])) {

    try {
        $id = upload();
        // echo '<p>Afbeelding nr. '.$id.' toegevoegd aan de database.</p>';
		// Meteen terug naar overzicht van images.
		header("Location: index.php?image_id=$id");
    }
    catch(Exception $e) {
        echo '<h4>'.$e->getMessage().'</h4>';
	}
}
	
/**
 * the upload function
 */
function upload(){
/*** check if a file was uploaded ***/
if(is_uploaded_file($_FILES['userfile']['tmp_name']) && getimagesize($_FILES['userfile']['tmp_name']) != false)
{
	$image_id = 0;
	
    /***  get the image info. ***/
    $size = getimagesize($_FILES['userfile']['tmp_name']);
    /*** assign our variables ***/
    $type = $size['mime'];
    $imgfp = fopen($_FILES['userfile']['tmp_name'], 'rb');
    $size = $size[3];
    $name = $_FILES['userfile']['name'];
    $maxsize = 99999999;


    /***  check the file is less than the maximum file size ***/
    if($_FILES['userfile']['size'] < $maxsize )
        {
        /*** connect to db ***/
        $dbh = new PDO("mysql:host=localhost;dbname=webwinkel", 'root', '');

                /*** set the error mode ***/
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            /*** our sql query ***/
        $stmt = $dbh->prepare("INSERT INTO afbeelding (image_type ,image, image_size, image_name) VALUES (? ,?, ?, ?)");

        /*** bind the params ***/
        $stmt->bindParam(1, $type);
        $stmt->bindParam(2, $imgfp, PDO::PARAM_LOB);
        $stmt->bindParam(3, $size);
        $stmt->bindParam(4, $name);

        /*** execute the query ***/
        $stmt->execute();
		$image_id = $dbh->lastInsertId(); 
		echo "id = ".$image_id;
        }
    else
        {
        /*** throw an exception is image is not of type ***/
        throw new Exception("File Size Error");
        }
    }
else
    {
    // if the file is not less than the maximum allowed, print an error
    throw new Exception("Unsupported Image Format!");
    }
return $image_id;
}
?> 