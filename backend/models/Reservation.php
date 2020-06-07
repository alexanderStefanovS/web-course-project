<?php

class Reservation {

    public $id;
    public $date;
    public $hour;
    public $users_subjects_id;
    public $halls_id;

    function __construct($id, $date, $hour, $users_subjects_id, $halls_id) {
        $this->id = $id;
        $this->date = $date;
        $this->hour = $hour;
        $this->users_subjects_id = $users_subjects_id;
        $this->halls_id = $halls_id;
    }

    public function storeInDb(): void {

        require_once "../db/DB.php";
        
        $database = new DB();
        $connection = $database->getConnection();

        $insertStatement = $conn->prepare(
            "INSERT INTO `halls_schedule` (date, hour, users_subjects_id, halls_id)
             VALUES (:date, :hour, :users_subjects_id, :halls_id)");

        $insertResult = $insertStatement->execute([
                'date' => $this->username,
				'hour' => $hashedPassword,
				'users_subjects_id' => $this->email,
				'halls_id' => $this->firstname,
            ]);
		
        if (!$insertResult) {
            $errorInfo = $insertStatement->errorInfo();
            $errorMessage = "";
            
            if ($errorInfo[1] == 1062) {
                $errorMessage = "Потребителското име вече съществува.";
            } else {
                $errorMessage = "Грешка при запис на информацията.";
            }
            throw new Exception($errorMessage);
        }
    }

}

?>