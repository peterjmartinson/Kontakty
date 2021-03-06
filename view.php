<?php

  /* view.php */
	// This file displays all the contact information.
	// So far, it produces a table which displays all
	// fields for all records in the database which
	// corresponds to the logged-in user.  The user can
	// narrow this listing down by entering information
	// into the 'filter' form.
	// The nuts and bolts works as follows:
	// Variables corresponding to the searchable columns
	// in the database are initialized to '%', which is
	// MySQL's wildcard character.  These variables are
	// then assigned values entered into the form.  A prepared
	// statement is then executed, which creates a database
	// query.  The results of the query are then printed
	// to the table using function 'printQuery()'.  If the
	// user hasn't entered anything in the field, the database
	// is queried with all the wildcards, and all entries
	// are returned.  If the form was filled in, then the
	// hemmed down results are displayed.  Hitting the
	// 'filter' button with nothing filled in the form
	// resets the table.
	// Note that this function is defined in this file,
	// not 'konfunctions.php,' because I'm not sure how to
	// generalize the field names yet.  Thus, it's specific
	// to this query.
  require_once 'header.php';
	
	// initialize the variables to wildcard '%'
  $first    = $middle   = $last = $phone_1 = $phone_2 =
	$street_1 = $street_2 = $city = $state   = $zip     =
	$notes    = $created  = '%';

  // query generated by the form entry
	$queryString = 'SELECT 
                    id,
                    user,
                    first,
                    middle,
                    last,
                    phone_1,
                    phone_2,
                    street_1,
                    street_2,
                    city,
                    state,
                    zip,
                    notes
                  FROM
                    contacts
                  WHERE
                    user     LIKE :user     AND
                    first    LIKE :first    AND
                    middle   LIKE :middle   AND
                    last     LIKE :last     AND
                    phone_1  LIKE :phone_1  AND
                    phone_2  LIKE :phone_2  AND
                    street_1 LIKE :street_1 AND
                    street_2 LIKE :street_2 AND
                    city     LIKE :city     AND
                    state    LIKE :state    AND
                    zip      LIKE :zip      AND
                    notes    LIKE :notes';

  // assigning form entries to the variables
	$user = $_SESSION['user'];
	$first = '%'.$_POST['first'].'%';
	$middle = '%'.$_POST['middle'].'%'; 
	$last = '%'.$_POST['last'].'%';
	$phone_1 = '%'.$_POST['phone_1'].'%';
	$phone_2 = '%'.$_POST['phone_2'].'%';
	$street_1 = '%'.$_POST['street_1'].'%';
	$street_2 = '%'.$_POST['street_2'].'%';
	$city = '%'.$_POST['city'].'%';
	$state = '%'.$_POST['state'].'%';
	$zip = '%'.$_POST['zip'].'%';
	$notes = '%'.$_POST['notes'].'%';
  
	// execute the database query
	// put the result into $query
	try {
	  $query = $konnection->prepare($queryString);

		$query->bindParam('user', $user, PDO::PARAM_INT);
		$query->bindParam('first', $first, PDO::PARAM_INT);
		$query->bindParam('middle', $middle, PDO::PARAM_INT);
		$query->bindParam('last', $last, PDO::PARAM_INT);
		$query->bindParam('phone_1', $phone_1, PDO::PARAM_INT);
		$query->bindParam('phone_2', $phone_2, PDO::PARAM_INT);
		$query->bindParam('street_1', $street_1, PDO::PARAM_INT);
		$query->bindParam('street_2', $street_2, PDO::PARAM_INT);
		$query->bindParam('city', $city, PDO::PARAM_INT);
		$query->bindParam('state', $state, PDO::PARAM_INT);
		$query->bindParam('zip', $zip, PDO::PARAM_INT);
		$query->bindParam('notes', $notes, PDO::PARAM_INT);

		$query->execute();
	}
	catch( PDOException $e ) {
	  echo $e->getMessage();
	}
  
	// capture the $query into an associative array
	try {
	  $record = 0;
		$queryArray = array();

		while ( $row = $query->fetch(PDO::FETCH_NUM) ) {
			$queryArray[$record]['id'] = $row[0];
			$queryArray[$record]['first'] = $row[2];
			$queryArray[$record]['middle'] = $row[3];
			$queryArray[$record]['last'] = $row[4];
			$queryArray[$record]['phone_1'] = $row[5];
			$queryArray[$record]['phone_2'] = $row[6];
			$queryArray[$record]['street_1'] = $row[7];
			$queryArray[$record]['street_2'] = $row[8];
			$queryArray[$record]['city'] = $row[9];
			$queryArray[$record]['state'] = $row[10];
			$queryArray[$record]['zip'] = $row[11];
			$queryArray[$record]['notes'] = $row[12];
      $record++;
		}
	}
	catch( PDOException $e ) {
	  echo $e->getMessage();
	}
  
  // Here is the form
  echo <<<_END
	  <form method='post' action='view.php'>
			<table class='contact'>
				<tr>
					<td>Last Name</td>
					<td class='field'>
					<input class='filter' type='text' maxlength='16' name='last'>
					</td>
				</tr>
				<tr>
					<td>Phone Number</td>
					<td class='field'>
					<input class='filter' type='text' maxlength='16' name='phone_1'>
					</td>
				</tr>
				<tr>
					<td>Alternate Phone</td>
					<td class='field'>
					<input class='filter' type='text' maxlength='16' name='phone_2'>
					</td>
				</tr>
				<tr>
					<td>Address</td>
					<td class='field'>
					<input class='filter' type='text' maxlength='16' name='street_1'>
					</td>
				</tr>
				<tr>
					<td>City</td>
					<td class='field'>
					<input class='filter' type='text' maxlength='16' name='city'>
					</td>
				</tr>
				<tr>
					<td>State</td>
					<td class='field'>
					<input class='filter' type='text' maxlength='16' name='state'>
					</td>
				</tr>
				<tr>
					<td>Zipcode</td>
					<td class='field'>
					<input class='filter' type='text' maxlength='16' name='zip'>
					</td>
				</tr>
				<tr>
					<td>Notes</td>
					<td class='field'>
					<input type='text' maxlength='16' name='notes'>
					</td>
				</tr>
			</table>
			<input type='submit' value='Filter'><br>
		</form>
_END;

	// Print the resulting table of records
  printQuery($queryArray);
  
	function printQuery( $queryResult ) {
	  $row = "";
		echo "<br> <table border='1' class='view'>";

		foreach ( $queryResult as $row ) {
		  echo "<tr><td><a href='updateKontakt.php?id=$field'>Edit</a></td><td>";
			echo $row['first'].' ';
			if ( $row['middle'] != '' ) {
			  echo $row['middle'].' ';
			}
			echo $row['last']."<br>";
			echo $row['street_1']."<br>";
			if ( $row['street_2'] != '' ) {
			  echo $row['street_2']."<br>";
			}
			echo $row['city'].", ".$row['state'].
			  " ".$row['zip']."<br>";
		  echo $row['phone_1']."<br>";
			if ( $row['phone_2'] != '' ) {
			  echo $row['phone_2']."<br>";
			}
			echo "<br>".$row['notes'];
			echo "</td></tr>";
		}
		echo "</table>";
	}
?>
