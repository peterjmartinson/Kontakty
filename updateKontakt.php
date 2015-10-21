<?php

  /* updateKontakt.php */
	
	require_once 'header.php';
// Nest everything in 'if ( !isset($_GET['id']) )'
	// initialize the variables
  $id = $_GET['id']; // gets the id from the URI
	$first    = $middle   = $last = $phone_1 = $phone_2 =
	$street_1 = $street_2 = $city = $state   = $zip     =
	$notes    = $created  = '';
  
  // Getting the contact's information 
	$queryString = "SELECT * FROM contacts WHERE id=:id"
	
	try {
	  $query = $konnection->prepare($queryString);
		$query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
		$result = $query->fetch(PDO::FETCH_ASSOC);
	}
	catch( PDOException $e ) {
	  echo $sql."<br>".$e->getMessage();
	}

	// assign the fields to the variables
  foreach ( $result as $row ) {
	  foreach ( $row as $column => $field ) {
		  $$column = $field;
	  }
	}

  if ( isset($_POST['last']) ) {
	  // update the contact record
    $queryUpdateString = "UPDATE contacts
			SET
				user     = :user,
				first    = :first,
				middle   = :middle,
				last     = :last,
				phone_1  = :phone_1,
				phone_2  = :phone_2,
				street_1 = :street_1,
				street_2 = :street_2,
				city     = :city,
				state    = :state,
				zip      = :zip,
				notes    = :notes
			WHERE
				id       = $id";

		try {
			$query = $konnection->prepare($queryUpdateString);
			$query->bindParam(':first', $first, PDO::PARAM_INT);
			$query->bindParam(':middle', $middle, PDO::PARAM_INT);
			$query->bindParam(':last', $last, PDO::PARAM_INT);
			$query->bindParam(':phone_1', $phone_1, PDO::PARAM_INT);
			$query->bindParam(':phone_2', $phone_2, PDO::PARAM_INT);
			$query->bindParam(':street_1', $street_1, PDO::PARAM_INT);
			$query->bindParam(':street_2', $street_2, PDO::PARAM_INT);
			$query->bindParam(':city', $city, PDO::PARAM_INT);
			$query->bindParam(':state', $state, PDO::PARAM_INT);
			$query->bindParam(':zip', $zip, PDO::PARAM_INT);
			$query->bindParam(':notes', $notes, PDO::PARAM_INT);
			$query->execute();
		}
		catch( PDOException $e ) {
			echo $sql."<br>".$e->getMessage();
		}
    echo "<br>Contact updated successfully.";
		echo "<br>Click<a href='view.php'>here</a> "
		  .  "to return to your contacts list.";
	}
	else {
	  // gimme the form to fill out
	  echo "Please enter your changes<br>";
		echo <<<_END
			<form method='post' action='newKontakt.php'>
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
				<input type='submit' value='Create Contact'><br>
			</form>
_END;
	}
?>
