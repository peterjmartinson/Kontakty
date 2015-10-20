<?php

/* checkuser.php */
// This file is called from the JavaScript AJAX function
// checkUser( user ) located in 'signup.php'. It determines
// what text to show the user while picking a username,
// and bases its decision on whether or not the desired
// username already exists in the database. Hence, it
// queries the database, and requires 'konfunctions.php'.

require_once 'konfunctions.php';

// Only execute something if $_POST['user'] is not empty.
if ( isset($_POST['user']) ) {
  
	// Remove any security hazards from $_POST['user']
  $user = sanitizeString( $_POST['user'] );

	/* BEGIN Query 'users' database for a   */
	/* record that contains  $_POST['user]' */
	
	// Prepare the query statement
	$queryUsers = "SELECT * FROM users WHERE user=':user'";
  
	// try to query the database
	try {
	  $query = $konnection->prepare($queryUsers);
		$query = bindParam(':user', $user, PDO::PARAM_INT);
		$query->execute();
		$result = $query->fetch(PDO::FETCH_NUM);
	}
	catch( PDOException $e ) {
	  echo $sql."<br>".$e-getMessage();
	}
	/* END Query 'users' database */

	// Check if the query returned any results
	// and return the appropriate response message
	if ( $result[0]['user'] != "" ) {
	  echo "<font color=red>&nbsp;&larr;
		  Sorry, already taken</font>";
	}
	else {
	  echo "<font color=green>&nbsp;&larr;
		  Username available</font>";
	}
}

?>
