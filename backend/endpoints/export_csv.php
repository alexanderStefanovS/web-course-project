<?php
require_once "../db/DB.php";
require_once "../models/Schedule.php";

function getSchedules($connection, $date, $hour, $output) {

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

    while ($row = $query->fetch()) {
        fputcsv($output, $row);
    }
}

$phpInput = json_decode(file_get_contents('php://input'), true);

if(!isset($phpInput['date']) || !isset($phpInput['hour'])) {
    echo json_encode([
		'success' => false,
        'message' => "Въведени са невалидни данни.",
	]);
}
else {
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
        ]);
    }

    header("Content-Type: text/csv; charset=utf-8");
    header('Content-Disposition: attachment; filename="schedule.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, array('ID', 'Date', 'Hour', 'Number', 'Lastname',
        'Course', 'Specialty'));

    getSchedules($connection, $date, $hour, $output);
    
    fclose($output);
    
}
?>