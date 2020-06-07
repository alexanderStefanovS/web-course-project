<?php

require_once "../models/Reservation.php";

session_start();

$phpInput = json_decode(file_get_contents('php://input'), true);

function validateHours($hourFrom, $hourTo) {
    if ($hourFrom == $hourTo || $hourTo < $hourFrom) {
        return false;
    }
    return true;
}

if (!isset($_SESSION['username'])) {
    echo json_encode([
        'success' => false,
        'message' => "Потребителят не е влязъл в системата.",
    ]);
} else {
    $username = $_SESSION['username'];

    if (empty($phpInput['hallsId']) || empty($phpInput['usersSubjectsId']) || empty($phpInput['date']) 
    || empty($phpInput['hourFrom']) || empty($phpInput['hourTo'])) {
        echo json_encode([
            'success' => false,
            'message' => "Моля, попълнете всички полета.",
        ]);
    } else {

        $hallsId = $phpInput['hallsId'];
        $usersSubjectsId = $phpInput['usersSubjectsId'];
        $date = $phpInput['date'];
        $hourFrom = $phpInput['hourFrom'];
        $hourTo = $phpInput['hourTo'];

        if(!validateHours($hourFrom, $hourTo)) {
            echo json_encode([
                'success' => false,
                'message' => "Въведените данни не са валидни.",
            ]);
            exit();
        }

        // check if reservation does not already exist
        $doExist = false;
        for($x = $hourFrom; $x < $hourTo; $x++) {

            $reservation = new Reservation(null, $date, $x, $usersSubjectsId, $hallsId);
            try {
                $reservation->checkReservation();

            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage(),
                ]);
                $doExist = true;
                break;
            }
        }
        
        if($doExist == false) {
            var_dump($doExist);
            // for every hour reserve hall
            for($x = $hourFrom; $x < $hourTo; $x++) {

                $reservation = new Reservation(null, $date, $x, $usersSubjectsId, $hallsId);
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