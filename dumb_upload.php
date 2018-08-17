<?php

require_once "session.php";
require_once "config.php";

if (isset($_FILES["dumb_file"]))
{
	$name = $_FILES["dumb_file"]["name"];

	if (file_exists("dumb_uploads.index/$name"))
	{
		echo "Your file already exists.";
		exit();
	}

	if ($_FILES["dumb_file"]["size"] > $MAX_FILE_SIZE)
	{
		echo "Your file was too big; it may be no larger than $MAX_FILE_SIZE octets.";
		exit();
	}

	if (move_uploaded_file($_FILES["dumb_file"]["tmp_name"], "dumb_uploads.index/$name"))
	{
		header("Location: dumb_uploads.index/");
	}
	else
	{
		echo "An error occured uploading your file.";
		exit();
	}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Dumb File Upload</title>
</head>

<body>
	<!--
	     This funny directory name tells my web server to autogenerate a
	     file index for the directory. I don't happen to know whether lesser
	     web servers than OpenBSD httpd(8) (http://man.openbsd.org/httpd.8)
	     can do this easily, but most web servers will be configured to
	     generate their own indices no matter what anyway.
	-->
	<a href='dumb_uploads.index/' class='btn btn-success'>Uploaded files (warning: unauthenticated read access!)</a>

	<form class='form-horizontal' action='dumb_upload.php' method='post' enctype='multipart/form-data'>
		<input type='file' name="dumb_file"/>
		<input type='submit' />
	</form>
</body>

</html>
