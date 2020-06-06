<?php

  require_once "../db/DB.php";

  session_start();

  function doesUserExist($connection, $username) {
    $sql = "SELECT * FROM `users` WHERE username = :username";
    $query = $connection->prepare($sql);
    $query->execute(['username' => $username]);
  
    $userId = null;
    while ($row = $query->fetch()) {
      $userId = $row["id"];
    }

    return $userId != null;
  }

  if (isset($_SESSION['username'])) {

    $username = $_SESSION['username'];

    try {
      $database = new DB();
      $connection = $database->getConnection();
    }
    catch (PDOException $e) {
      echo json_encode([
        'success' => false,
        'message' => "Неуспешно свързване с базата данни",
        'value' => null
      ]);
    }

    $exists = doesUserExist($connection, $username);
    echo json_encode([
      'success' => true,
      'message' => "Данни за потребител",
      'value' => $exists
    ]);

  } else {
    echo json_encode([
      'success' => true,
      'message' => "Данни за потребител",
      'value' => false
    ]);
  } 

?>