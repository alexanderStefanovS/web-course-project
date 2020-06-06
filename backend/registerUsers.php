<?php

require_once "./User.php";

// $user1 = new User('ivan44', '1234Ab', 'ivan44@abv.bg', 'Иван', 'Димитров', '1');

// $user1->storeInDb();

// $user2 = new User('desislava.p', '1234Ab', 'desislavaPP@abv.bg', 'Десислава', 'Петкова', '2');

// $user2->storeInDb();

$user3 = new User('georgi.g', '1234Ab', 'georgi.georgiev@abv.bg', 'Георги', 'Георгиев', '2');

$user3->storeInDb();

?>