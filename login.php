<?php 

	/* login.php */
	// This file processes a form for username and password.
	// The user enters their username and password and hits
	// the 'submit' button. The input text is first cleansed
	// of security risks.  Then, if text was actually
	// entered, a query is sent to the 'users' database to
	// see if the two entries match a record in the database.
	// If yes, then the two entries are passed to the session
	// variables $_SESSION['user'] and $_SESSION['pass'].
	// At that point, a link is presented to the user to
	// return to the index.php page as a logged in user.
	// If the query returns no records, an error is displayed
	// on the page. The user can keep entering usernames and
	// passwords on this page after that.

	require_once 'header.php';

	echo "<div class='main'><h2>Please enter your username and password to log in</h2>";

	//initialize the variables
	$error = $user = $pass = "";

	// Check to see if $_POST['user'] and $_POST['pass']
	// are already set, for example by the signup page.
	// If they're set, then give those values to $user & $pass
	if ( isset($_POST['user']) ) {
		// Remove any security hazards from the user input
		$user = sanitizeString( $_POST['user'] );
		$pass = sanitizeString( $_POST['pass'] );
		
		// Begin the conditional investigation of the user input
		// First, make sure the user actually submitted something:
		if ( $user == "" || $pass == "" ) {
			$error = "Not all fields were entered<br>";
		}
		// Next, query the database, searching for a record
		// with matching username and password.
		else {
			$queryUser = "SELECT user,pass FROM users "
								 . "WHERE user=:user AND pass=:pass";
			// Attempt the query with a prepared statement
			try {
					$query = $konnection->prepare($queryUser);
					$query->bindParam(':user', $user, PDO::PARAM_INT);
					$query->bindParam(':pass', $pass, PDO::PARAM_INT);
					$query->execute();
					$result = $query->fetch(PDO::FETCH_NUM);
			}
			catch( PDOException $e ) {
				echo $sql."<br />".$e->getMessage();
			}
			
			// see if the query returned any results.
			// If not, print an error message.
			if ( $result[0]['user'] == "" ) {
				$error = "<span class='error'>"
							 . "Username/Password invalid</span>"
							 . "<br /><br />";
			}
			// If the query returned a result, pass the input
			// to the $_SESSION variables, print a confirmation,
			// and provide a link back to the index.php page.
			else {
				$_SESSION['user'] = $user;
				$_SESSION['pass'] = $pass;
				die("You are now logged in. Please "
					. "<a href='index.php?view=$user'>"
					. "click here</a> to continue.<br /><br />"
				);
			}
		}
	}

	// The following displays the form HTML
	echo <<<_END
		<form method='post' action='login.php'>$error
		<table>
		  <tr>
			  <td>Username</td>
				<td><input type='text' maxlength='16'
			       name='user' value='$user'>
				</td>
			</tr>
			<tr>
			  <td>Password</td>
				<td><input type='password' maxlength='16'
			       name='pass' value='$pass'>
			</tr>
_END;

?>

<!-- End the html file with a 'submit' button -->

    <br>
    <span class='fieldname'>&nbsp;</span>
    <input type='submit' value='Login'>
    </table></form><br></div>
  </body>
</html>
