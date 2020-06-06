<?php

  require_once "../db/DB.php";
  require_once "../models/User.php";

  session_start();

  function getUser($connection, $username): User {
    $sql = "SELECT * FROM `users` WHERE username = :username";
    $query = $connection->prepare($sql);
    $query->execute(['username' => $username]);
  
    while ($row = $query->fetch()) {
      $user = new User($row["id"], $row["username"], null, null, null, null, $row["roles_id"]);
    }

    return $user;
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

    $user = getUser($connection, $username);
    echo json_encode([
      'success' => true,
      'message' => "Данни за потребител",
      'value' => $user
    ]);

  } else {
    echo json_encode([
      'success' => true,
      'message' => "Данни за потребител",
      'value' => false
    ]);
  } 

?>