<?php
  
	/* signup.php */
	// This page allows a user to create a new username
	// and password.  Don't know how it works, yet!
	
	require_once 'header.php';
/*
	echo <<<_END
	<script>
	function checkUser( user ) {
		
		if ( user.value == '' ) {
			document.getElementById('info').innerHTML = ''
			return
		}

		params = "user=" + user.value
		request = new ajaxRequest()
		request.open("POST", "checkuser.php", true)
		request.setRequestHeader("Content-type",
			"application/x-www-form-urlencoded")
		request.setRequestHeader("Content-length", params.length)
		request.setRequestHeader("Connection", "close")

		request.onreadystatechange = function() {
			if (this.readyState == 4) {
				if (this.status == 200) {
					if (this.responseText != null) {
						document.getElementById('info').innerHTML =
							this.responseText
					}
					else {
					  alert("Ajax error: No data received")
				  }
				}
				else {
				  alert("Ajax error: " + this.statusText)
				}
			}
		}
		request.send(params)
	};

	function ajaxRequest() {
		try {
			var request = new XMLHttpRequest()
		}
		catch(e1) {
			try {
				request = new ActiveXObject("Msxml2.XMLHTTP")
			}
			catch(e2) {
				try {
					request = new ActiveXObject("Microsoft.XMLHTTP")
				}
				catch(e3) {
					request = false
				}
			}
		}
		return request
	};
	</script>

	<h3>Sign up Form</h3>
_END;
*/
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
		  $error = "Not all fields were entered<br><br>";
		}
		else {
		  
			/*-- BEGIN Database query block --*/
			// See if the desired username already exists
			// If it does, throw an error
			$queryUser = "SELECT * FROM users WHERE user=:user";

			try {
			  echo "<br>running the query<br>";
				$query = $konnection->prepare($queryUser);
				$query->bindParam(':user', $user, PDO::PARAM_INT);
				$query->execute();
				$result = $query->fetch(PDO::FETCH_NUM);
				print_r($result);
			}
			catch( PDOExeption $e ) {
				echo $sql."<br>".$e->getMessage();
			}
			/*-- END Database query block --*/ 
			// Check if the query returned any results
			// If it did, then display an error response
			if ( $result[0]['user'] != "" ) {
			  $error = "That username already exists<br><br>";
			}

			// If the query returned nothing, insert a new record
			else {
			  echo "<br>Begin INSERT<br>";
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

				die("<h4>Account Created</h4>Please log in.");
			}
		}
  }			
  
	/*-- BEGIN The Form --*/

	// The form comprises two fields, Username and Password.
	// User entries are passed into the $user & $pass varables.
  echo <<<_END
	  <form method='post' action='signup.php'>$error
		Username
		  <input type='text' maxlength='16' name='user'
			 value='$user' onBlur='checkUser(this)'>
			<span id='info'></span><br>
		Password 
		  <input type='text' maxlength='16' name='pass'
			 value='$pass'><br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
_END;
  /*-- END The Form --*/


?>

    <span class='fieldname'>&nbsp;</span>
		<input type='submit' value='Sign up'>
		</form></div><br>
	</body>
</html>
