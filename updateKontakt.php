<?php

  /* updateKontakt.php */
  // This gets called from 'view.php' with the GET
	// superglobal $_GET['id'] superglobal appended to
	// the URL.  This file runs two ways, discriminated
	// by whether the form was submitted.  If nothing
	// has been submitted, such as just after we get here
	// from the 'view.php' edit link, a form is presented
	// to the user.  The code queries the database for the
	// record identified by the 'id', and the entries from
	// that record are used to populate the form.  The user
	// modifies the values in the form, and submits.  Then,
	// the values from the form are used to update the
	// database record, which is selected by the
	// $_GET['id'] variable.

	require_once 'header.php';

  // get $id from the URI, passed over from 'view.php'
  $id = $_GET['id'];
	
	// if (TRUE), then run the update query
	// else if (FALSE), then display the form
  if ( isset($_POST['last']) ) {
	  // update the contact record
    $queryUpdateString = "UPDATE contacts "
			. "SET "
			.   "first    = :first, "
			.		"middle   = :middle, "
			.		"last     = :last, "
			.		"phone_1  = :phone_1, "
			.		"phone_2  = :phone_2, "
			.		"street_1 = :street_1, "
			.		"street_2 = :street_2, "
			.		"city     = :city, "
			.		"state    = :state, "
			.		"zip      = :zip, "
			.		"notes    = :notes "
			. "WHERE "
			.  "id       = $id";

		try {
			$query = $konnection->prepare($queryUpdateString);
			$query->bindParam(':first', $_POST['first'],
			  PDO::PARAM_INT);
			$query->bindParam(':middle', $_POST['middle'],
			  PDO::PARAM_INT);
			$query->bindParam(':last', $_POST['last'],
			  PDO::PARAM_INT);
			$query->bindParam(':phone_1', $_POST['phone_1'],
			  PDO::PARAM_INT);
			$query->bindParam(':phone_2', $_POST['phone_2'],
			  PDO::PARAM_INT);
			$query->bindParam(':street_1', $_POST['street_1'],
			  PDO::PARAM_INT);
			$query->bindParam(':street_2', $_POST['street_2'],
			  PDO::PARAM_INT);
			$query->bindParam(':city', $_POST['city'],
			  PDO::PARAM_INT);
			$query->bindParam(':state', $_POST['state'],
			  PDO::PARAM_INT);
			$query->bindParam(':zip', $_POST['zip'],
			  PDO::PARAM_INT);
			$query->bindParam(':notes', $_POST['notes'],
			  PDO::PARAM_INT);
			$query->execute();
		}
		catch( PDOException $e ) {
			echo "<br>".$e->getMessage();
		}
    echo "<br>Contact updated successfully.";
		echo "<br>Click <a href='view.php'>here</a> "
		  .  "to return to your contacts list.";
	}
	else {
		// initialize the varibles to be empty
		// They'll be filled by the initial database query
		$first    = $middle   = $last = $phone_1 = $phone_2 =
		$street_1 = $street_2 = $city = $state   = $zip     =
		$notes    = $created  = '';
		
		/* -BEGIN- Initial database query */
		// Getting the contact's information 
		$queryString = "SELECT * FROM contacts WHERE id=:id";
		
		try {
			$query = $konnection->prepare($queryString);
			$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->execute();
			$result = $query->fetch(PDO::FETCH_ASSOC);
		}
		catch( PDOException $e ) {
			echo $sql."<br>".$e->getMessage();
		}

		$first = $result['first'];
		$middle = $result['middle'];
		$last = $result['last'];
		$phone_1 = $result['phone_1'];
		$phone_2 = $result['phone_2'];
		$street_1 = $result['street_1'];
		$street_2 = $result['street_2'];
		$city = $result['city'];
		$state = $result['state'];
		$zip = $result['zip'];
		$notes = $result['notes'];
		/* -END- Initial database query */
	  
		echo "<br>Please enter your changes<br>";
		echo <<<_END
			<form method='post' action='updateKontakt.php?id=$id'>
				First Name
					<input type='text' maxlength='16'
					  name='first' value='$first'><br>
				Middle Name
					<input type='text' maxlength='16'
					  name='middle' value='$middle'><br>
				Last Name
					<input type='text' maxlength='16'
					  name='last' value='$last'><br>
				Phone Number
					<input type='text' maxlength='16'
					  name='phone_1' value='$phone_1'><br>
				Alternate Phone
					<input type='text' maxlength='16'
					  name='phone_2' value='$phone_2'><br>
				Address
					<input type='text' maxlength='16'
					  name='street_1' value='$street_1'><br>
				Apartment, etc.
					<input type='text' maxlength='16'
					  name='street_2' value='$street_2'><br>
				City
					<input type='text' maxlength='16'
					  name='city' value='$city'><br>
				State
					<input type='text' maxlength='2'
					  name='state' value='$state'><br>
				Zipcode
					<input type='text' maxlength='16'
					  name='zip' value='$zip'><br>
				Notes
					<textarea maxlength='4096'
					  name='notes' value='$notes'></textarea><br>
				<input type='submit' value='Commit Change'><br>
			</form>
_END;
	}
?>
