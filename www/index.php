<?php

$REDIRECT_URL = 'http://google.com';
$USER = 'user1';
$PASS = 'JD208R9Y1IEHF';


include 'db.class.php';

// $_GET: apMac, userMac, clientIp, userUrl, loginUrl
// var_dump($_GET);

// var_dump($_POST);

if(isset($_POST['firstname'])){
	
	$db->sql('CREATE TABLE IF NOT EXISTS `data` (
	  `id` int(11) NOT NULL auto_increment,   
	  `firstname` varchar(100)  NOT NULL,
	  `lastname` varchar(100)  NOT NULL,
	  `email` varchar(100)  NOT NULL,
	  `reason` varchar(20)  NOT NULL,
	   PRIMARY KEY  (`id`)
	);', false, false);

	$db->send([
		'firstname' => $_POST['firstname'],
		'lastname' => $_POST['lastname'],
		'email' => $_POST['email'],
		'reason' => $_POST['reason'],
	], 'data');

	// Redirect to Router for login
	header('Location: ' . $_GET['loginUrl']
		. "?username=${USER}&password=${PASS}&popup=true&dst=" . urlencode($REDIRECT_URL) );
	exit();
}

if(isset($_GET['export4128093'])){
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=data.csv');
	
	$output = fopen('php://output', 'w');
	
	fputcsv($output, array('id', 'First Name', 'Last Name', 'Email', 'Reason'));

	$list = $db->sql('SELECT id, firstname, lastname, email, reason FROM data');
	// var_dump($list);
	foreach ($list as $row) {
		fputcsv($output, $row);
	}

	die();
}

?><html>
<head>
	<meta charset="UTF-8">
	<title>Hotspot Login</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="node_modules/jquery/dist/jquery.min.js"></script>
	<link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
	<script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
</head>
<body>
	<style>
		label{
			color:#000;
		}
	</style>
	<br>
	<div class="container">

		<form method="post">

			<h2 class="text-center" style="color:#3498db;margin:40px auto;font-weight:300;">Hotspot Login</h2>
			
			<div class="form-group row">
				<div class="col">
					<label for="firstname">First Name</label>
					<input name="firstname" id="firstname" class="form-control" type="text" required>
				</div>
				<div class="col">
					<label for="lastname">Last Name</label>
					<input name="lastname" id="lastname" class="form-control" type="text" required>
				</div>
			</div>

			<div>
				<label for="email">Email Address</label>
				<input name="email" id="email" class="form-control" type="text" required>
			</div>

			<br>

			<label>Reason of stay</label>
			<div class="form-check">
			  <input class="form-check-input" type="radio" name="reason" id="reason1" value="Business" required>
			  <label class="form-check-label" for="reason1">
			    Business
			  </label>
			</div>
			<div class="form-check">
			  <input class="form-check-input" type="radio" name="reason" id="reason2" value="Tourism" required>
			  <label class="form-check-label" for="reason2">
			    Tourism
			  </label>
			</div>
			
			<br>
			<div class="alert alert-light text-center" role="alert">
			  By clicking Register you agree to our <a href="tos.html">terms and conditions</a>.
			</div>
			<div class="text-center">
				<button class="btn btn-primary">Register</button>
			</div>

		</form>

	</div>

</body>
</html>