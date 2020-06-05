<?php

class User
{
	private $username;
	private $password;
	private $email;
	private $firstname;
	private $lastname;
	private $role;
	
	public function __construct($username, $password, $email, $firstname, $lastname, $role)
	{
		$this->username = $username;
		$this->password = $password;
		$this->email = $email;
		$this->firstname = $firstname;
		$this->lastname = $lastname;
		$this->role = $role;
	}

    public function storeInDb(): void
    {
        require_once "../DB.php";

        $db = new DB();

        $conn = $db->getConnection();

        $insertStatement = $conn->prepare(
            "INSERT INTO `users` (firstname, lastname, year, specialty, faculty_number, group_number, birthday, zodiac_sign, link, photo, motivation)
             VALUES (:firstname, :lastname, :year, :specialty, :faculty_number, :group_number, :birthday, :zodiac_sign, :link, :photo, :motivation)");

        $insertResult = $insertStatement->execute([
                'firstname' => $this->firstname,
				'lastname' => $this->lastname,
				'year' => $this->year,
				'specialty' => $this->specialty,
				'faculty_number' => $this->facultyNumber,
				'group_number' => $this->groupNumber,
				'birthday' => $this->birthday,
				'zodiac_sign' => $this->zodiacSign,
				'link' => $this->link,
				'photo' => $this->photo,
				'motivation' => $this->motivation
            ]);
		
        if (!$insertResult)
        {
            $errorInfo = $insertStatement->errorInfo();
            $errorMessage = "";
            
            if ($errorInfo[1] == 1062) {
                $errorMessage = "Факултетният номер вече съществува.";
            } else {
                $errorMessage = "Грешка при запис на информацията.";
            }
            
            throw new Exception($errorMessage);
        }
    }
	
}