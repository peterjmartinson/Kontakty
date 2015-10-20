<?php

	/* header.php */
	// This file is included in all the other files that will
	// be opened by the user.  Therefore, the introductory
	// HTML begins this file.  The closing HTML exists at the
	// end of all the other pages. Likewise, the session_start()
	// invocation is at the beginning of this file.
	// The main purpose of header.php is to display a menu of
	// pages to which the user can navigate.  Two menus are
	// displayed: one for when nobody is logged on, and one
	// for when a user is logged on.  The distinction is
	// determined by checking the contents of the session
	// variable $_SESSION['user'].
	// Also, note that konfunctions.php is inherited by all
	// files that require header.php.

	require_once 'konfunctions.php';
	session_start();

	// Begin the HTML
	echo "<!DOCTYPE html>\n<html><head>";

	// $userTitleStr is a label that shows who is logged in
	$userTitleStr = ' (Guest)';

	// Beginning of the $_SESSION['user'] investigation
	// The main purpose here is to set $loggedin, to discriminate
	// between the two headers, and to assign $_SESSION['user']
	// to $user, if the user is logged in.
	if (isset($_SESSION['user'])) {
		$user         = $_SESSION['user'];
		$loggedin     = TRUE;
		$userTitleStr = " ($user)";
	}
	else {
		$loggedin     = FALSE;
	}

	// The next block closes the <head> of the HTML,
	// and invokes the css style file.
	echo "<title>$appname$userTitleStr</title>"
		. "<link rel='stylesheet' href='kontakty.css' type='text/css'>"
		. "</head><body>";

	// Finally, choose the appropriate menu,
	// based on whether $loggedin is TRUE or FALSE.
	if ( $loggedin ) {
		echo "<a href='index.php'>Main</a> |
					<a href='view.php'>View Contacts</a> |
					<a href='newKontakt.php'>New Contact</a> |
					<a href='about.php'>About</a> |
					<a href='logout.php'>Logout</a> |
					<a href='diagnostic.php'>Diagnostics</a>";
	}
	else {
		echo "<a href='index.php'>Main</a> |
		      <a href='login.php'>Log In</a> |
					<a href='signup.php'>Sign Up</a> |
					<a href='about.php'>About</a>";
	}
?>
