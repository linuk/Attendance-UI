<?php
	include 'configs.php';

	$conn = new mysqli($server, $user, $pw, $db);

	if($conn->connect_errno){
		printf("Connect failed:  %s\n", $conn->connect_error);
		exit();
	}

	// insert data with ajax into psh_raw
	$fk_user = $_POST['rid'];
	$time = date("Y-m-d H:i:s");
	$direct = $_POST['direct'];
	$type = $_POST['type'];
	$query = "INSERT INTO psh_raw VALUES('','$fk_user', '$time', '$direct', '$type');";

	mysqli_query($conn, $query);

	mysqli_close($conn);
?>