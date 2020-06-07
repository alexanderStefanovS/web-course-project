<?php

require_once "../db/DB.php";
require_once "../models/UserSubject.php";

function getSubjects($connection) {

    $userId = $_REQUEST["userId"];

    $sql = "SELECT users_subjects.id, users_subjects.course, users_subjects.specialty, subjects.name
    FROM `subjects` JOIN `users_subjects` ON users_subjects.subjects_id = subjects.id 
    WHERE users_id = :users_id;";

    $query = $connection->prepare($sql);
	$query->execute([ 'users_id' => $userId ]);

    $subjects = array();

	while ($row = $query->fetch()) {
        $subject = new UserSubject($row['id'], $row['course'], $row['specialty'], $row['name']);
        array_push($subjects, $subject);
    }
	return $subjects;
}

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

$subjects = getSubjects($connection);

echo json_encode([
    'success' => true,
    'message' => "Списък от всички предмети на преподавателя.",
    'value' => $subjects,
]);

?>