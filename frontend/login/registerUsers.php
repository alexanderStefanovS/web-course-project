<?php

require_once "../User.php";

$user1 = new User('ivan44', '1234Ab', 'ivan44@abv.bg', 'Иван', 'Димитров', '1');

$user1->storeInDb();

$user1 = new User('desislava.p@gmail.com', '1234Ab', 'desislavaPP@abv.bg', 'Десислава', 'Петкова', '2');

$user1->storeInDb();

?>