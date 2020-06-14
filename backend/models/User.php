<?php

class User {

	public $id;
	public $username;
	public $password;
	public $email;
	public $firstname;
	public $lastname;
	public $role;
	
	public function __construct($id, $username, $password, $email, $firstname, $lastname, $role) {
		
		$this->id = $id;
		$this->username = $username;
		$this->password = $password;
		$this->email = $email;
		$this->firstname = $firstname;
		$this->lastname = $lastname;
		$this->role = $role;

	}

	public function checkLogin(): void {
        
        require_once "../db/DB.php";

		try{
			$db = new DB();
			$conn = $db->getConnection();
		}
		catch (PDOException $e) {
			echo json_encode([
				'success' => false,
				'message' => "Неуспешно свързване с базата данни",
			]);
			exit();
		}
		
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
		require_once "../db/DB.php";

		try{
			$db = new DB();
			$conn = $db->getConnection();
		}
		catch (PDOException $e) {
			echo json_encode([
				'success' => false,
				'message' => "Неуспешно свързване с базата данни",
			]);
		}

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
	
	private function required($field1, $field2, $field3, $field4, $field5, $field6): bool {
		return !empty($field1) && !empty($field2) && !empty($field3) && !empty($field4) && !empty($field5) && !empty($field6);
	}
	
	private function validName($field): bool{
		return strlen($field) >= 2 && strlen($field) <= 45 && (preg_match('/^[\p{Cyrillic}]+[- \']?[\p{Cyrillic}]+$/u', $field) || preg_match('/^[a-zA-Z]+[- \']?[a-zA-Z]+$/', $field));	
	}
	
	private function validEmail($field): bool {
		return preg_match('/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $field);
	}

	private function validUsername($field): bool {
		return strlen($field) >= 3 && strlen($field) <= 45 && preg_match('/^[a-zA-Z0-9]+([-._]?[a-zA-Z0-9]+)*$/', $field);	
	}
	
	private function validPassword($field): bool {
		return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[a-zA-Z0-9]{6,30}$/', $field);
	}
	
	private function validRole($field): bool { 
		return $field === "1" || $field === "2";
	}
	
	 public function validate(): void {
		
		if (!$this->required($this->firstname, $this->lastname, $this->email, $this->username, $this->password, $this->role)) {
			throw new Exception("Моля, попълнете всички задължителни полета.");
		}
		
		if (!$this->validName($this->firstname)) {
			throw new Exception("Моля, попълнете валидно име.");
		}
		
		if (!$this->validName($this->lastname)) {
			throw new Exception("Моля, попълнете валиднa фамилия.");
		}
		
		if (!$this->validEmail($this->email)) {
			throw new Exception("Моля, попълнете валиден имейл.");
		}
		
		if (!$this->validUsername($this->username)) {
			throw new Exception("Моля, попълнете валидно потребителско име.");
		}
		
		if (!$this->validPassword($this->password)) {
			throw new Exception("Моля, попълнете валидна парола.");
		}
		
		if (!$this->validRole($this->role)) {
			throw new Exception("Моля, попълнете валидна роля.");
		}
	}

}