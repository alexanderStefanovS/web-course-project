<?php

require_once "../db/DB.php";

function getFloor($hall, $connection) {
  $sql = "SELECT `floor` FROM `halls` where `number` = :hall";
	$query = $connection->prepare($sql);
	$query->execute(['hall' => $hall]);

  $floor = null;
	while ($row = $query->fetch()) {
    $floor = $row['floor'];
	}

  return $floor;
}

function calculateDistance($from, $to, $fromFloor, $toFloor) {
  $seconds = 0;

  if ($from == 325 || $from == 326 || $from == 327) {
    $from -= 23;
  }
  if ($to == 325 || $to == 326 || $to == 327) {
    $to -= 23;
  }

  if ($fromFloor != 4 && $toFloor != 4) {
    $seconds = abs($fromFloor - $toFloor) * 120 + abs($from % 100 - $to % 100) * 20;
  } else if ($fromFloor == 4 && $toFloor == 4) {
    $seconds = abs($from - $to) * 20;
  } else {
    $add = ($fromFloor == 4) ? $toFloor : $fromFloor;
    $seconds = 2700 + $add * 120;
  }

  return $seconds / 60 + 1;
}

function getDistance($fromHall, $toHall, $connection): int {
  $from = (int)$fromHall;
  $to = (int)$toHall;

  $fromFloor = (int)getFloor($fromHall, $connection);
  $toFloor = (int)getFloor($toHall, $connection);

  if ($fromFloor == null || $toFloor == null) {
    return -1;
  }

  return calculateDistance($from, $to, $fromFloor, $toFloor);
}

if (!isset($_POST["halls"])) {

  echo json_encode([
    'success' => false,
    'message' => "Некоректни данни",
    'value' => null
  ]); 

} else {

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

  $json = $_POST["halls"];
  $halls = json_decode($json, false);
  $fromHall = $halls->fromHall;
  $toHall = $halls->toHall;

  $distance = getDistance($fromHall, $toHall, $connection);

  $response = null;
  if ($distance == -1) {
    $response = json_encode([
      'success' => false,
      'message' => "Некоректни данни",
      'value' => null
    ]); 
  } else {
    $response = json_encode([
      'success' => true,
      'message' => "Разтоянието между двете зали",
      'value' => $distance
    ]); 
  }

  echo $response; 
}


?>