<?php
require_once 'header.php';

echo "<br>diagnostics:<br>";
print_r($_SESSION);
echo $_SESSION['user'];
destroySession();
echo "<br>after destroySession()<br>";
print_r($_SESSION);
echo $_SESSION['user'];
echo "<br>end diagnostics.<br>";
?>

</body>
</html>
