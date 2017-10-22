<?php
	include 'configs.php';

	$conn = new mysqli($server, $user, $pw, $db);

	if($conn->connect_errno){
		printf("Connect failed:  %s\n", $conn->connect_error);
		exit();
	}
	

	// search for specific year and user
	if($_POST['name'] && $_POST['year']){
		
		$year = $_POST['year'];
		$name = $_POST['name'];
		$query = "SELECT distinct fk_user, name, YEAR(resultForDay.time) AS 'year', MONTH(resultForDay.time) AS 'month', DATE_FORMAT(resultForDay.day,'%e') AS 'day', round(TIME_TO_SEC(TIMEDIFF(MAX(time), MIN(time)))/3600,2) AS 'WorkingHours' FROM ( SELECT distinct(name), DATE(time) AS 'day', time, fk_user FROM psh_raw INNER JOIN user_table ON psh_raw.fk_user = user_table.id WHERE YEAR(time)=$year AND name = '$name' ) resultForDay GROUP BY fk_user, resultForDay.day;";
	
	}

	// search for specific year	
	else if($_POST['year']){
	
		$year = $_POST['year'];
		$query = "SELECT distinct fk_user, name, YEAR(resultForDay.time) AS 'year', MONTH(resultForDay.time) AS 'month', DATE_FORMAT(resultForDay.day,'%e') AS 'day', round(TIME_TO_SEC(TIMEDIFF(MAX(time), MIN(time)))/3600,2) AS 'WorkingHours' FROM ( SELECT distinct(name), DATE(time) AS 'day', time, fk_user FROM psh_raw INNER JOIN user_table ON psh_raw.fk_user = user_table.id WHERE YEAR(time)=$year ) resultForDay GROUP BY fk_user, resultForDay.day; ";
	
	}
	
	
	// search for specific user	
	else if($_POST['name']){
	
		$name = $_POST['name'];
		$query = "SELECT distinct fk_user, name, YEAR(resultForDay.time) AS 'year', MONTH(resultForDay.time) AS 'month', DATE_FORMAT(resultForDay.day,'%e') AS 'day', round(TIME_TO_SEC(TIMEDIFF(MAX(time), MIN(time)))/3600,2) AS 'WorkingHours' FROM ( SELECT distinct(name), DATE(time) AS 'day', time, fk_user FROM psh_raw INNER JOIN user_table ON psh_raw.fk_user = user_table.id WHERE name = '$name' ) resultForDay GROUP BY fk_user, resultForDay.day;";
	}

	// no specific search user or year
	else {
		$query = "SELECT distinct fk_user, name, YEAR(resultForDay.time) AS 'year', MONTH(resultForDay.time) AS 'month', DATE_FORMAT(resultForDay.day,'%e') AS 'day', round(TIME_TO_SEC(TIMEDIFF(MAX(time), MIN(time)))/3600,2) AS 'WorkingHours' FROM ( SELECT distinct(name), DATE(time) AS 'day', time, fk_user FROM psh_raw INNER JOIN user_table ON psh_raw.fk_user = user_table.id) resultForDay GROUP BY fk_user, resultForDay.day;";
	}

	

	

	if($result = $conn->query($query)){
		
		// create a new object 
		$datas = array();
		while($row = $result->fetch_assoc()){
			
			// append new array if the user does not exist
			if(!$datas[$row['fk_user']]){
				$datas[$row['fk_user']] = array();
				$datas[$row['fk_user']]['fk_user'] = $row['fk_user'];
				$datas[$row['fk_user']]['name'] = $row['name'];
				$datas[$row['fk_user']]['statics'] = array();
			}

			// append year array if not exist
			if(!$datas[$row['fk_user']]['statics'][$row['year']]){
				$datas[$row['fk_user']]['statics'][$row['year']] = array();
			}

			// append a new array for each month if not exist
			if(!$datas[$row['fk_user']]['statics'][$row['year']][$row['month']]){
				// $datas[$row['fk_user']]['statics'][$row['year']][$row['month']] = array();
				$datas[$row['fk_user']]['statics'][$row['year']][$row['month']] = array();
			}

			// append data to each month array
			$datas[$row['fk_user']]['statics'][$row['year']][$row['month']][$row['day']] = array();
			array_push($datas[$row['fk_user']]['statics'][$row['year']][$row['month']][$row['day']], $row['WorkingHours']);


		}
		echo json_encode($datas);

		$result->free();
	}

	mysqli_close($conn);
?>