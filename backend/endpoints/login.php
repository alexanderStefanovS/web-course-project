<?php

session_start();

$phpInput = json_decode(file_get_contents('php://input'), true);

if (isset($_SESSION['username'])) {
    echo json_encode([
        'success' => true,
        'username' => $_SESSION['username'],
    ]);
} else {

    if (empty($phpInput['username']) || empty($phpInput['password'])) {
        echo json_encode([
            'success' => false,
            'message' => "Моля, попълнете всички задължителни полета.",
        ]);
    }
    else {

        $username = $phpInput['username'];
        $password = $phpInput['password'];

        require_once "../models/User.php";

        $user = new User($phpInput['username'], $phpInput['password'], null, null, null, null);

        try {

            $user->checkLogin();

            $_SESSION['username'] = $phpInput['username'];

            echo json_encode([
                'success' => true,
                'username' => $_SESSION['username'],
            ]);
            
        } catch (Exception $e) {
            
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }  
}