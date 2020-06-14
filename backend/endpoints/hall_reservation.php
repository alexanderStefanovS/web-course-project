<?php

require_once "../models/Reservation.php";

session_start();

$phpInput = json_decode(file_get_contents('php://input'), true);

function validateHours($hourFrom, $hourTo) {
    return $hourFrom != $hourTo && $hourTo > $hourFrom 
        && $hourFrom >= 7 && $hourFrom <= 20 && $hourTo>= 7 && $hourTo <= 20;
}

function validateDate($date){
    if(!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $date)) {
        return false;
    }
    $dateSplit = explode('-', $date);
    $myDate = strtotime($date);
    $minDate = date('Y-m-d');
    return checkdate($dateSplit[1], $dateSplit[2], $dateSplit[0]) && $myDate > $minDate;
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
        $hourFrom = (int)$phpInput['hourFrom'];
        $hourTo = (int)$phpInput['hourTo'];

        if(!validateHours($hourFrom, $hourTo) || !validateDate($date)) {
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
                    exit();
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