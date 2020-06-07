<?php

session_start();

$phpInput = json_decode(file_get_contents('php://input'), true);

if (isset($_SESSION['username'])) {
    echo json_encode([
        'success' => true,
        'username' => $_SESSION['username'],
    ]);
} else {
    $username = $_SESSION['username'];

    if (empty($phpInput['hall_id']) || empty($phpInput['subject_id']) || empty($phpInput['date']) || empty($phpInput['time'])) {
        echo json_encode([
            'success' => false,
            'message' => "Моля, попълнете всички полета.",
        ]);
    } else {

        $user_id = $phpInput['user_id'];
        $halls_id = $phpInput['hall_id'];
        $subjects_id = $phpInput['subject_id'];
        $date = $phpInput['date'];
        $hour = $phpInput['hour'];

        require_once "../models/Reservation.php";

        $reservation = new Reservation(null, $date, $hour, $users_subjects_id, $hall_id);

        try {

            $reservation->storeInDb();
            
        } catch (Exception $e) {
            
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }


    }

}
?>