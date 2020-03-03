<?php

include 'db.class.php';

// echo 'User details: <br>';

// echo $_GET['apMac'] . '<br>';
// echo $_GET['userMac'] . '<br>';
// echo $_GET['clientIp'] . '<br>';
// echo $_GET['userUrl'] . '<br>';
// echo $_GET['loginUrl'] . '<br>';

// var_dump($_GET);

/*


<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<body>

<h2>Setting the Viewport</h2>
<p>This example does not really do anything, other than showing you how to add the viewport meta element.</p>

</body>
</html>
// */


// Redirect URL:
// $REDIRECT_URL = 'http://google.com';
// header('Location: ' . $_GET['loginUrl'] . '?username=user1&password=JD208R9Y1IEHF&popup=true&dst=' . urlencode($REDIRECT_URL));

?>

<form action="<?=$_GET['loginUrl']?>" method="get">
	<input type="hidden" name="username" value="user2" />
	<input type="hidden" name="password" value="pass2" />
	<input type="hidden" name="popup" value="true" />
	<input type="hidden" name="dst" value="http://google.com" />
	
	<input type="submit" name="submit" value="VAZHDO" />
</form>
