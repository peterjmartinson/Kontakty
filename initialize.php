<!DOCTYPE html>
<html>
<head>
  <title>Initializing Database</title>
</head>
<body>
<h2>Working...</h2>

<?php // initialize.php
echo '<h2>Initializing Database</h2><br />';

/* BEGIN Establish connection to the database */
// database variables:
$dbHost  = 'localhost';
$dbName  = 'kontakty';
$dbUser  = 'root';
$dbPass  = 'nose';
$appName = "Kontakty";

// Log in to the MySQL database:
try {
  $konnection = new PDO( "mysql:host=$dbHost;dbname=$dbName",
    $dbUser, $dbPass);

  $konnection->setAttribute(
	  PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch( PDOException $e) {
  echo $sql."<br />".$e->getMessage();
}
/* END Establish connection to the database */

try {
  $makeUserTable = "CREATE TABLE IF NOT EXISTS users (
    user VARCHAR(16),
	  pass VARCHAR(16),
	  INDEX(user(6)),
		PRIMARY KEY(user)
  )";

	$makeContactsTable = "CREATE TABLE IF NOT EXISTS contacts (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  	user VARCHAR(16),
	  first VARCHAR(25),
	  middle VARCHAR(25),
	  last VARCHAR(50),
	  phone_1 CHAR(10),
	  phone_2 CHAR(10),
	  street_1 VARCHAR (50),
	  street_2 VARCHAR(50),
	  city VARCHAR(50), 
	  state CHAR(2),
	  zip INT UNSIGNED,
	  notes VARCHAR(4096),
	  created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	  INDEX(user(6)),
	  INDEX(first(6)),
	  INDEX(last(6)),
		PRIMARY KEY(id)
  )";

	$konnection->exec($makeUserTable);
  echo "Table 'users' created or already exists.<br />";
	$konnection->exec($makeContactsTable);
  echo "Table 'contacts' created or already exists.<br />";

} catch(PDOException $e) {
  echo $sql."<br />".$e->getMessage();
}

$konnection = null;

?>
<h2>...done</h2>
</body>
</html>
