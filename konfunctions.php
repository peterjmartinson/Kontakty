<?php

	/* konfunctions.php */
	// This file contains all the functions used by the
	// webpage, and also opens a connection to the database
	// server.  Since this file is never opened by the user
	// as a webpage, it does not require header.php. Each
	// file that requires konfunctions.php gains access to
	// both the database as well as the functions herein.

	/* BEGIN: Establish connection to the database */
	// database variables:
	$dbHost  = 'localhost';
	$dbName  = 'kontakty';
	$dbUser  = 'gordon';
	$dbPass  = 'gecko';
	$appname = 'Kontakty';

	// Log in to the MySQL database:
	try {
		$konnection = new PDO(
			"mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass
		);
		$konnection->setAttribute(
			PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION
		);
	} 
	catch( PDOException $e ) {
		echo $sql."<br>".$e->getMessage();
	}
	/* END: Establish connection to the database */
	
	
	/* BEGIN Function assignments */

	// destroySession() ends the session,
	// which happens when the user logs out.
	// NOTE: session_start() needs to be invoked first,
	// either by including header.php, or just calling
	// session_start()
	function destroySession() {
		$_SESSION = array();
		if ( session_id() != "" || isset( $_COOKIE[session_name()] ) ) {
			setcookie( session_name(),'', time()-2592000,'/' );
		}
		session_destroy();
	}

	// sanitizeString() defends against SQL injection
	// and cross-server scripting.
	// HOWEVER, you need to figure out just what PDO's
	// prepared statements defend against.
	// You may not even need this function...
	function sanitizeString($var) {
		$var = strip_tags($var);
		$var = htmlentities($var);
		$var = stripslashes($var);
	  return $var;
	}

	/* END Function assignments */
?>
