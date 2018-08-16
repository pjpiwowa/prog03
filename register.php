<?php

// This is the only page that does not involve sessions.

require_once "config.php";
require_once "database.php";

if ($_GET)
{
	if ($_GET['dup'])
	{
		$err = "username is taken";
	}
	else if ($_GET['pwmismatch'])
	{
		$err = "password and confirmation do not match";
	}
}

if ($_POST)
{
	if ($_POST['password'] != $_POST['confirmword'])
	{
		header("Location: register.php?pwmismatch=true");
		exit();
	}
	$username = $_POST['username'];
	$password = password_hash($_POST['password'], PASSWORD_BCRYPT, ["cost" => $BCRYPT_COST]);

	// Make sure the username is not taken.
	$sql = "SELECT * FROM $USERS_TABLE WHERE username = ? LIMIT 1";
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$query = $pdo->prepare($sql);
	$query->execute(array($username));
	$line = $query->fetch(PDO::FETCH_ASSOC);

	echo "eggs";
	if ($line)
	{
		header("Location: register.php?dup=true");
		exit();
	}
	else
	{
		$sql = "INSERT INTO $USERS_TABLE (username, password) VALUES (?, ?)";
		$query = $pdo->prepare($sql);
		$query->execute(array($username, $password));
		echo "baz";
		header("Location: login.php");
		exit();
	}
} // else { empty registration form }

?>

<!DOCTYPE html>

<html lang="en">

<head>
	<meta charset="utf-8">
	<link   href="css/bootstrap.min.css" rel="stylesheet">
	<script src="js/bootstrap.min.js"></script>
</head>

<body>

<h1>Register</h1>

<form class="form-horizontal" action="register.php" method="post">
	<?php if (isset($err)) { echo "<p>$err</p>"; } ?>
	<input name="username" type="text" placeholder="you@example.com" require_onced />
	<input name="password" type="password" require_onced placeholder="password" />
	<input name="confirmword" type="password" require_onced placeholder="confirm password" />
	<input type="submit" />
</form>

</body>

<html>
