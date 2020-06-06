<?php

class User {

	private $username;
	private $password;
	private $email;
	private $firstname;
	private $lastname;
	private $role;
	
	public function __construct($username, $password, $email, $firstname, $lastname, $role) {
		
		$this->username = $username;
		$this->password = $password;
		$this->email = $email;
		$this->firstname = $firstname;
		$this->lastname = $lastname;
		$this->role = $role;

	}

	public function checkLogin(): void {
        
        require_once "../db/DB.php";

        $db = new DB();
        
		$conn = $db->getConnection();
		
        $selectStatement = $conn->prepare("SELECT * FROM `users` WHERE username = :username");
        $result = $selectStatement->execute(['username' => $this->username]);
        
		$dbUser = $selectStatement->fetch();
		if ($dbUser == false) {
            throw new Exception("Грешно потребителско име.");
		}
		
        if (!password_verify($this->password, $dbUser['password'])) {
            throw new Exception("Грешна парола.");
        }

	}
	
	public function storeInDb(): void {
		require_once "./DB.php";

		$db = new DB();
	
        $conn = $db->getConnection();

        $insertStatement = $conn->prepare(
            "INSERT INTO `users` (username, password, email, firstname, lastname, roles_id)
             VALUES (:username, :password, :email, :firstname, :lastname, :roles_id)");

		$hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

        $insertResult = $insertStatement->execute([
                'username' => $this->username,
				'password' => $hashedPassword,
				'email' => $this->email,
				'firstname' => $this->firstname,
				'lastname' => $this->lastname,
				'roles_id' => $this->role,
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