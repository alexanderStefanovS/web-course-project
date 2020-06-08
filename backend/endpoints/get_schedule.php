<?php

require_once "../db/DB.php";
require_once "../models/Schedule.php";

function getSchedules($connection, $date, $hour) {

    $query = $connection->prepare("SELECT halls_schedule.id, halls.number, subjects.name, users.firstname, users.lastname,
    users_subjects.course, users_subjects.specialty
    FROM `halls_schedule` JOIN `halls` ON halls_schedule.halls_id = halls.id 
    JOIN `users_subjects` ON halls_schedule.users_subjects_id = users_subjects.id
    JOIN `users` ON users_subjects.users_id = users.id 
    JOIN `subjects` ON users_subjects.subjects_id = subjects.id
    WHERE date = :date AND hour = :hour;");

	$query->execute([
        'date' => $date,
        'hour' => $hour,
    ]);

    $schedules = array();

	while ($row = $query->fetch()) {
        $schedule = new Schedule($row['id'], $date, $hour, $row['number'], $row['name'], $row['firstname'],
            $row['lastname'], $row['course'], $row['specialty']);
        array_push($schedules, $schedule);
    }
	return $schedules;
}

$phpInput = json_decode(file_get_contents('php://input'), true);

$date = $phpInput['date'];
$hour = $phpInput['hour'];

try {
    $database = new DB();
    $connection = $database->getConnection();
}
catch (PDOException $e) {
	echo json_encode([
		'success' => false,
        'message' => "Неуспешно свързване с базата данни.",
        'value' => null,
	]);
}

$schedules = getSchedules($connection, $date, $hour);

echo json_encode([
    'success' => true,
    'message' => "Списък от всички предмети.",
    'value' => $schedules,
]);

?>