<!doctype html>
<?php
include("PHPconnectionDB.php");
?>
<html>
<head>
<meta charset="UTF-8">
<title>Login Information</title>
</head>

<body>
<h1>Login Information Change</h1>
<?php
	
	if (!isset($_COOKIE["user"])) {
		echo '<p>Access denied. You are not an Administrator.</p>';
		echo '</body>';
		echo '</html>';
		die();
	}
	else {
		$conn = connect();
		$sid = $_COOKIE["user"];
		$sql = "select class from users where user_name = '".$sid."'";
		$stmt = oci_parse($conn, $sql);
		oci_execute($stmt);
		$row = oci_fetch_row($stmt);
		$class = $row[0];
		if ($class != 'a') {
			echo '<p>Access denied. You are not an Administrator.</p>';
			echo '</body>';
			echo '</html>';
			die();
		}
	}
	
?>
<p>Please enter the email address associated with the person you wish to search.</p>
<form action="user.php" method="post" name="form1" id="form1">
  <p>
    <label for="email">Email:</label>
    <input type="email" name="email" id="email">
    <input type="submit" name="submit" id="submit" value="Submit">
  </p>
</form>
<?php
 
if (isset($_POST['submit'])) {
	$email = $_POST['email'];
	$sql = "select user_name from users where person_id = (select person_id from persons where email = '".$email."')";
	$stmt = oci_parse($conn, $sql);
	oci_execute($stmt);
	while (($row = oci_fetch_row($stmt)) != false) {
		echo $row[0]."\n";
	}
}

?>
<form id="form2" name="form2" method="post">

  <p>
    <label for="textfield">Username:</label>
    <input type="text" name="username" id="textfield">
  </p>
  <p>
    <label for="password">Password:</label>
    <input type="password" name="password" id="password">
  </p>
  <p>
    <label>
      <input type="radio" name="RadioGroup1" value="p" id="RadioGroup1_0">
      Patient</label>
    <br>
    <label>
      <input type="radio" name="RadioGroup1" value="d" id="RadioGroup1_1">
      Doctor</label>
    <br>
    <label>
      <input type="radio" name="RadioGroup1" value="r" id="RadioGroup1_2">
      Radiologist</label>
    <br>
    <label>
      <input type="radio" name="RadioGroup1" value="a" id="RadioGroup1_3">
      Admin</label>
  </p>
  <p>
    <input type="submit" name="submit2" id="submit2" value="Submit">
    <br>
  </p>
</form>
<?php
if (isset($_POST['submit2'])) {
	$username = $_POST['username'];
	$password = $_POST['password'];
	$class = $_POST['RadioGroup1'];

$sql = "update users set password = '".$password."', class = '".$class."' where user_name = '".$username."'";
$stmt = oci_parse($conn, $sql);
oci_execute($stmt);
echo "Update Successful";
}
oci_free_statement($stmt);
oci_close($conn);
?>
</body>
</html>

<?php
	echo '<br><h4><a href ="admin.html" >Back To Panel</a></h4>'
	
?>
