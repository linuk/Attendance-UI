<?php
	include 'configs.php';

	$conn = new mysqli($server, $user, $pw, $db);

	if($conn->connect_errno){
		printf("Connect failed:  %s\n", $conn->connect_error);
		exit();
	}
	
	// Read names from the user_table
	$query = 'SELECT name FROM user_table ORDER BY name';		
	if($result = $conn->query($query)){
		$name_list = array();
		while($row = $result->fetch_assoc()){
			$name_list[] = $row['name'];
		}
		echo json_encode($name_list);
		$result->free();
	}

	mysqli_close($conn);
?>