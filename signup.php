<?php
  
	/* signup.php */
	// This page allows a user to create a new username
	// and password, but does not automatically log the
	// new user in.  It includes the header.php by
	// default, which is probably OK.  The form presents
	// the user with fields for username and password.
	// The file first determines whether someone is already
	// logged in, in which case the $_SESSION['user']
	// variable will be set.  If someone's logged in,
	// we log out.  The file determines whether the form
	// has been filled out.  If both entries are filled,
	// then we check the database to see if the username is
	// already taken.  Note, another version of this file
	// used a JS AJAX call to also alert the user if the
	// username is already taken, but there was some error
	// I couldn't understand.  If the username is not taken,
	// the 'users' database gets a new record containing
	// the new username and password, and a link to the
	// login page is presented to the new user.
	
	require_once 'header.php';
  
	echo "<div class='main'><h3>Please enter your details to sign up</h3>";
	// do the signup checking and the form here
  
  $error = $user = $pass = "";

  // See if someone is logged in
	// if yes, log them out.
  if ( isset($_SESSION['user']) ) {
	  destroySession();
	}

  // Check whether user submitted the form
	if ( isset($_POST['user']) ) {
    $user = sanitizeString($_POST['user']);
		$pass = sanitizeString($_POST['pass']);
    
		// Check whether user filled out the form completely
		if ( $user == "" || $pass == "" ) {
		  $error = "<span class='error'>" .
			         "Not all fields were entered" .
							 "</span><br>";
		}
		else {
		  
			/*-- BEGIN Database query block --*/
			// See if the desired username already exists
			// If it does, throw an error
			$queryUser = "SELECT * FROM users WHERE user=:user";

			try {
				$query = $konnection->prepare($queryUser);
				$query->bindParam(':user', $user, PDO::PARAM_INT);
				$query->execute();
				$result = $query->fetch(PDO::FETCH_NUM);
				print_r($result);
			}
			catch( PDOExeption $e ) {
				echo $e->getMessage();
			}
			/*-- END Database query block --*/ 
			// Check if the query returned any results
			// If it did, then display an error response
			if ( $result[0]['user'] != "" ) {
			  $error = "<span class='error'>" .
				         "That username already exists" .
								 "</span><br>";
			}

			// If the query returned nothing, insert a new record
			else {

				/*-- BEGIN Database query block --*/
				$queryNewUser = "INSERT INTO "
				              .   "users "
											. "SET "
											.   "user = :user, "
											.   "pass = :pass";
        try {
					$query = $konnection->prepare($queryNewUser);
					print_r($query);
					$query->bindParam(':user', $user);
					$query->bindParam(':pass', $pass);
					$query->execute();
		    }
				catch( PDOException $e ) {
					echo $sql."<br>".$e->getMessage();
				}
        /*-- END Database query block --*/

				die("<span class='confirmation'>Account Created</span><br><span class='main'>Please log in.</span>");
			}
		}
  }			
  
	/*-- BEGIN The Form --*/

	// The form comprises two fields, Username and Password.
	// User entries are passed into the $user & $pass varables.
  echo <<<_END
	  <form method='post' action='signup.php'>$error
		<table>
		  <tr>
			  <td>Username</td>
		    <td class='field'>
				  <input class='login' type='text' maxlength='16' name='user'>
				</td>
			</tr>
			<tr>
			  <td>Password</td>
				<td class='field'>
					<input class='login' type='password' maxlength='16' name='pass'>
				</td>
			</tr>
_END;
  /*-- END The Form --*/


?>

    <span class='fieldname'>&nbsp;</span>
		<tr><td><input type='submit' value='Sign up'></td></tr>
		</table></form></div><br>
	</body>
</html>
