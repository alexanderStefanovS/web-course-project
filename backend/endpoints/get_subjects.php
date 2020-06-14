<?php

require_once "../db/DB.php";
require_once "../models/Subject.php";

function getSubjects($connection) {

    $query = $connection->prepare("SELECT * FROM `subjects`");
	$query->execute([]);

    $subjects = array();

	while ($row = $query->fetch()) {
        $subject = new Subject($row['id'], $row['name'], $row['type']);
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
    exit();
}

$subjects = getSubjects($connection);

echo json_encode([
    'success' => true,
    'message' => "Списък от всички предмети.",
    'value' => $subjects,
]);

?>