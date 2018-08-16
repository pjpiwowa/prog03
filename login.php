<?php

// Don't include session.php, since we do not want its redirect
session_start();

require_once "config.php";
require_once "database.php";

if ($_GET && $_GET['failure'])
{
	$err = "login failure";
}

if ($_POST)
{
	$username = $_POST['username'];
	$password = $_POST['password'];

	// Fetch the hash
	$sql = "SELECT password FROM $USERS_TABLE WHERE username = ? LIMIT 1;";

	$pdo = Database::connect();
	$query = $pdo->prepare($sql);
	$query->execute(array($username));
	$line = $query->fetch(PDO::FETCH_ASSOC);

	echo "foo";

	if ($line && password_verify($password, $line['password']))
	{
		$_SESSION['auth'] = true;
		header("Location: index.php");
	}
	else
	{
		session_destroy();
		header("Location: login.php?failure=true");
	}
} // else { empty login form }

?>

<!DOCTYPE html>

<html lang="en">

<head>
	<meta charset="utf-8">
	<link   href="css/bootstrap.min.css" rel="stylesheet">
	<script src="js/bootstrap.min.js"></script>
</head>

<body>

<h1>Log In</h1>

<a href="https://github.com/pjpiwowa/prog2" class="btn">Source Code</a>

<form class="form-horizontal" action="login.php" method="post">
	<?php if (isset($err)) { echo "<p>$err</p>"; } ?>
	<input name="username" type="text" placeholder="your username" required />
	<input name="password" type="password" required />
	<input type="submit" />
	<a href="register.php" class="btn">Register</a>
</form>

</body>

<html>
