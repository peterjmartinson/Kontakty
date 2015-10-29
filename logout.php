<?php // logout.php
  //require_once 'header.php';

  require_once 'konfunctions.php';
	echo "<!DOCTYPE html>\n<html>";
	echo "<head><title>Logout</title>"
     . "<link rel='stylesheet' href='kontakty.css' type='text/css'>"
     . "</head>";
	echo "<body>";
	
	echo "<br>This is the logout page<br>";
  
	session_start();
  if (isset($_SESSION['user'])) {
	  destroySession();
		echo "<div class='main'>You have been logged out. "
		   . "Please <a href='index.php'>click here</a> to "
			 . "refresh the screen.";
  }
	else {
	  echo "<div class='main><br>"
		   . "You cannot logout because you are not logged in!";
  }
?>

    <br><br></div>
  </body>
</html>
