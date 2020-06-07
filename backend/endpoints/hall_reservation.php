<?php

require_once "../models/Reservation.php";

session_start();

$phpInput = json_decode(file_get_contents('php://input'), true);

function findUsersSubjectsId($users_id, $subjects_id) {
    require_once "../db/DB.php";
    
    $database = new DB();
    $connection = $database->getConnection();

    $selectStatement = $connection->prepare(
        "SELECT * FROM `users_subjects` WHERE users_id = :users_id AND subjects_id = :subjects_id");

    $result = $selectStatement->execute([
            'users_id' => $users_id,
            'subjects_id' => $subjects_id,
        ]);
    
    $users_subjects = $selectStatement->fetch();
    return $users_subjects;
}


if (!isset($_SESSION['username'])) {
    echo json_encode([
        'success' => false,
        'message' => "Потребителят не е влязъл в системата.",
    ]);
} else {
    $username = $_SESSION['username'];

    if (empty($phpInput['hall_id']) || empty($phpInput['subject_id']) || empty($phpInput['date']) 
    || empty($phpInput['hour_from']) || empty($phpInput['hour_to'])) {
        echo json_encode([
            'success' => false,
            'message' => "Моля, попълнете всички полета.",
        ]);
    } else {

        $user_id = $phpInput['user_id'];
        $halls_id = $phpInput['hall_id'];
        $subjects_id = $phpInput['subject_id'];
        $date = $phpInput['date'];
        $hour_from = $phpInput['hour_from'];
        $hour_to = $phpInput['hour_to'];

        $users_subjects = findUsersSubjectsId($user_id, $subjects_id);
        $users_subjects_id = $users_subjects['id'];

        // check if reservation does not already exist
        $doExist = false;
        for($x = $hour_from; $x < $hour_to; $x++) {

            $reservation = new Reservation(null, $date, $x, $users_subjects_id, $halls_id);
            try {
                $reservation->checkReservation();

            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage(),
                ]);
                $doExist = true;
                return;
            }
        }
        
        if($doExist == false) {
            // for every hour reserve hall
            for($x = $hour_from; $x < $hour_to; $x++) {

                $reservation = new Reservation(null, $date, $x, $users_subjects_id, $halls_id);
                try {
                    $reservation->storeInDb();

                } catch (Exception $e) {
                    echo json_encode([
                        'success' => false,
                        'message' => $e->getMessage(),
                    ]);
                }
            }

            echo json_encode([
                'success' => true,
                'message' => "Залата е запазена успешно.",
            ]);
        }

    }

}
?>