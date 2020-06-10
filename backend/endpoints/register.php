<?php

$phpInput = json_decode(file_get_contents('php://input'), true);
require_once "../models/User.php";

$user = new User(null, $phpInput['username'], $phpInput['password'], $phpInput['email'], $phpInput['firstname'],
				$phpInput['lastname'], $phpInput['role']);


try {
    $user->validate();
    $user->storeInDb();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
    ]);
}
