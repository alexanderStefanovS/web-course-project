<?php

require_once "../db/DB.php";

class Reservation {

    public $id;
    public $date;
    public $hour;
    public $usersSubjectsId;
    public $hallsId;

    function __construct($id, $date, $hour, $usersSubjectsId, $hallsId) {
        $this->id = $id;
        $this->date = $date;
        $this->hour = $hour;
        $this->usersSubjectsId = $usersSubjectsId;
        $this->hallsId = $hallsId;
    }

    public function storeInDb(): void {
        try {
            $database = new DB();
            $connection = $database->getConnection();
        }
        catch (PDOException $e) {
			echo json_encode([
				'success' => false,
				'message' => "Неуспешно свързване с базата данни",
            ]);
            exit();
		}

        $insertStatement = $connection->prepare(
            "INSERT INTO `halls_schedule` (date, hour, users_subjects_id, halls_id)
             VALUES (:date, :hour, :users_subjects_id, :halls_id)");

        $insertResult = $insertStatement->execute([
                'date' => $this->date,
				'hour' => $this->hour,
				'users_subjects_id' => $this->usersSubjectsId,
				'halls_id' => $this->hallsId,
            ]);
		
        if (!$insertResult) {
            $errorMessage = "Грешка при запис на информацията.";
            throw new Exception($errorMessage);
        }
    }

    private function checkHall($connection): void {
        $selectStatement = $connection->prepare("SELECT * FROM `halls` WHERE id = :id");
        
        $selectStatement->execute([
            'id' => $this->hallsId,
            ]);

        $errorMessage = "Зала от този тип не може да бъде запазвана.";
        $hall = $selectStatement->fetch();
        if ($hall['type'] == "WC" || $hall['type'] == "книжарница" || $hall['type'] == "библиотека" || $hall['type'] == "канцелария") {
            throw new Exception($errorMessage);
        }
    }

    public function checkReservation(): void {

        try{
            $database = new DB();
            $connection = $database->getConnection();
        }
        catch (PDOException $e) {
			echo json_encode([
				'success' => false,
				'message' => "Неуспешно свързване с базата данни",
            ]);
            exit();
		}

        $this->checkHall($connection);

        $selectStatement = $connection->prepare("SELECT * FROM `halls_schedule` WHERE date = :date 
            AND hour = :hour AND halls_id = :halls_id");
        
        $result = $selectStatement->execute([
            'date' => $this->date,
            'hour' => $this->hour,
            'halls_id' => $this->hallsId,
            ]);
        
        $reservation = $selectStatement->fetch();
        
        $errorMessage = "Залата вече е запазена за този часови диапазон. Моля, изберете друга зала.";
        if($reservation) {
            throw new Exception($errorMessage);
        }
    }

}

?>