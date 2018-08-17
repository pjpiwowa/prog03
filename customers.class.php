<?php

require_once "config.php";

class Customers {
	
	public $id;
	
	public $name;
	public $email;
	public $mobile;
	public $pic1;
	public $pic2;
	
	private $nameError = null;
	private $emailError = null;
	private $mobileError = null;
	private $pic1Error = null;
	private $pic2Error = null;
	
	private $title = "Customer";
	
	function create_record() { // display create form
		echo "
		<html>
			<head>
				<title>Create a $this->title</title>
					";
		echo "
				<meta charset='UTF-8'>
				<link href='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css' rel='stylesheet'>
				<script src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js'></script>
					"; 
		echo "
			</head>

			<body>
				<div class='container'>

					<div class='span10 offset1'>
						<p class='row'>
							<h3>Create a $this->title</h3>
						</p>
						<form class='form-horizontal' action='customer.php?fun=11' method='post' enctype='multipart/form-data'>
					";
		$this->control_group("name", $this->nameError, $this->name);
		$this->control_group("email", $this->emailError, $this->email);
		$this->control_group("mobile", $this->mobileError, $this->mobile);
		$this->file_group("pic1", $this->pic1Error);
		$this->file_group("pic2", $this->pic2Error);
		echo " 
							<div class='form-actions'>
								<button type='submit' class='btn btn-success'>Create</button>
								<a class='btn' href='customer.php'>Back</a>
							</div>
						</form>
					</div>

				</div> <!-- /container -->
			</body>
		</html>
					";
	}
	
	function list_records() {
		echo "
		<html>
			<head>
				<title>$this->title" . "s" . "</title>
					";
		echo "
				<meta charset='UTF-8'>
				<link href='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css' rel='stylesheet'>
				<script src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js'></script>
					";  
		echo "
			</head>
			<body>
				<div class='container'>
					<p class='row'>
						<h3>$this->title" . "s" . "</h3>
					</p>
					<p>
						<a href='customer.php?fun=1' class='btn btn-success'>Create</a>
						<a href='dumb_upload.php' class='btn btn-danger'>Dumb File Upload</a>
						<a href='https://github.com/pjpiwowa/prog03' class='btn btn-success'>Source code</a>
						<a href='logout.php' class='btn btn-danger'>Logout</a>
					</p>
					<div class='row'>
						<table class='table table-striped table-bordered'>
							<thead>
								<tr>
									<th>Name</th>
									<th>Email</th>
									<th>Mobile</th>
									<th>Picture 1</th>
									<th>Picture 2</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
					";
		$pdo = Database::connect();
		$sql = "SELECT * FROM prog03_customers ORDER BY id DESC";
		foreach ($pdo->query($sql) as $row) {
			echo "<tr>";
			echo "<td>". $row["name"] . "</td>";
			echo "<td>". $row["email"] . "</td>";
			echo "<td>". $row["mobile"] . "</td>";
			echo "<td><img src=\"pic1s/". $row["pic1"] . "\" /></td>";
			echo "<td><img src=\"data:image/png;base64," . base64_encode($row["pic2"]) . "\" /></td>";
			echo "<td width=250>";
			echo "<a class='btn' href='read.php?id=".$row["id"]."'>Read</a>";
			echo "&nbsp;";
			echo "<a class='btn btn-success' href='update.php?id=".$row["id"]."'>Update</a>";
			echo "&nbsp;";
			echo "<a class='btn btn-danger' href='delete.php?id=".$row["id"]."'>Delete</a>";
			echo "</td>";
			echo "</tr>";
		}
		Database::disconnect();        
		echo "
							</tbody>
						</table>
					</div>
				</div>

			</body>

		</html>
					";  
	} // end list_records()
	

	function control_group ($label, $labelError, $val) {
		echo "<div class='control-group";
		echo !empty($labelError) ? 'error' : '';
		echo "'>";
		echo "<label class='control-label'>$label</label>";
		echo "<div class='controls'>";
		echo "<input name='$label' type='text' placeholder='$label' value='";
		echo !empty($val) ? $val : '';
		echo "'>";
		if (!empty($labelError)) {
			echo "<span class='help-inline'>";
			echo $labelError;
			echo "</span>";
		}
		echo "</div>";
		echo "</div>";
	}

	function file_group ($label, $labelError) {?>
		<div class='control_group <?php echo !empty($labelError) ? 'error' : ''; ?>'>
			<label class='control-label'><?php echo $label ?></label>
			<div class='controls'>
				<input name='<?php echo $label ?>' type='file' accept="image/png" />
				<?php if (!empty($labelError)) { ?>
					<span class='help-inline'><?php echo $labelError ?></span>
				<?php } ?>
			</div>
		</div>
	<?php }
	
	function insert_record () {
		global $MAX_FILE_SIZE;

		// validate input
		$valid = true;
		if (empty($this->name)) {
			$this->nameError = 'Please enter Name';
			$valid = false;
		}

		if (empty($this->email)) {
			$this->emailError = 'Please enter Email Address';
			$valid = false;
		} 
		/*
		else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
		
			$this->emailError = 'Please enter a valid Email Address';
			$valid = false;
		}
		*/

		if (empty($this->mobile)) {
			$this->mobileError = 'Please enter Mobile Number';
			$valid = false;
		}

		if (empty($this->pic1)) {
			$this->pic1Error = 'Please provide Picture 1';
			$valid = false;
		} else {
			// For simplicity, permit only image/png
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			if (finfo_file($finfo, $this->pic1["tmp_name"]) != "image/png") {
				$this->pic1Error = 'Only PNG images are permitted';
				$valid = false;
			}

			if ($this->pic1["size"] > $MAX_FILE_SIZE) {
				$this->pic1Error = "Images may not exceed $MAX_FILE_SIZE octets.";
				$valid = false;
			}

			
		}

		if (empty($this->pic2)) {
			$this->pic2Error = 'Please provide Picture 2';
			$valid = false;
		} else {
			// For simplicity, permit only image/png
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			if (finfo_file($finfo, $this->pic2["tmp_name"]) != "image/png")
			{
				$this->pic2Error = 'Only PNG images are permitted';
			}
			
			if ($this->pic2["size"] > $MAX_FILE_SIZE) {
				$this->pic2Error = "Images may not exceed $MAX_FILE_SIZE octets.";
				$valid = false;
			}
		}

		var_dump($_FILES);
		var_dump($this->pic1);
		var_dump($this->pic2);
		/*
		 * pic1 will be stored in the 'pic1s/' directory, while pic2 will be
		 * embedded directly in the database.
		 */

		// Generate a unique filename for each image
		$pic1_name = uniqid() . ".png";
		if (!move_uploaded_file($this->pic1["tmp_name"], "pic1s/$pic1_name"))
		{
			$this->pic1Error = "Unable to upload Picture 1";
			$valid = false;
		}

		$pic2_dump = file_get_contents($this->pic2["tmp_name"]);
		if (!$pic2_dump)
		{
			$this->pic2Error = "Unable to upload Picture 2";
			$valid = false;
		}

		// insert data
		if ($valid) {
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "INSERT INTO prog03_customers (name,email,mobile, pic1, pic2) values(?, ?, ?, ?, ?)";
			$q = $pdo->prepare($sql);
			$q->execute(array($this->name,$this->email,$this->mobile, $pic1_name, $pic2_dump));
			Database::disconnect();
			header("Location: customer.php");
		}
		else {
			$this->create_record();
		}
	}
	
} // end class Customers
