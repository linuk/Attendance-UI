<?php
	include 'configs.php';

	$conn = new mysqli($server, $user, $pw, $db);

	if($conn->connect_errno){
		printf("Connect failed:  %s\n", $conn->connect_error);
		exit();
	}
	
	// fetch id from name 
	if($_POST['name']){
		$name = $_POST['name'];
		$query = "SELECT AVG(TIME_TO_SEC(time_diff.WorkingHours))/3600 AS 'Avg_Hours', time_diff.month AS 'Month', name FROM( SELECT distinct(resultForDay.day), TIMEDIFF(MAX(time), MIN(time)) AS 'WorkingHours', MONTH(resultForDay.time) AS 'month', name FROM ( SELECT DISTINCT(DATE(time)) AS 'day', time, name FROM psh_raw INNER JOIN user_table ON psh_raw.fk_user = user_table.id WHERE (name = '$name') AND (YEAR(time)=2017) ) resultForDay GROUP BY resultForDay.day ) time_diff GROUP BY time_diff.month;";
	}else{
		$query = 'SELECT SEC_TO_TIME(AVG(TIME_TO_SEC(time_diff.WorkingHours))) AS "AVG HOUR", time_diff.month AS "Month", name FROM(SELECT distinct(resultForDay.day), TIMEDIFF(MAX(time), MIN(time)) AS "WorkingHours", MONTH(resultForDay.time) AS "month", name FROM ( SELECT DISTINCT(DATE(time)) AS "day", time, name FROM psh_raw INNER JOIN user_table ON psh_raw.fk_user = user_table.id WHERE YEAR(time)=2017) resultForDay GROUP BY resultForDay.day ) time_diff GROUP BY time_diff.month; ORDER BY name';
	}

	if($result = $conn->query($query)){
		
		// return an object 
		$hour_list = array();
		while($row = $result->fetch_assoc()){
			$hour_list[0] = $row['name'];
			$hour_list[] = round($row['Avg_Hours'],2);
			// echo $row['name'];
			// echo $row['Month'];
			// echo round($row['Avg_Hours'],2);
		}
		
		echo json_encode($hour_list);

		$result->free();
	}

	mysqli_close($conn);
?>