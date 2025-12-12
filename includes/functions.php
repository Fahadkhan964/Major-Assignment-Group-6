<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function checkRole($required_role) {
    if (!isLoggedIn()) {
        redirect('../login.php');
    }
    if ($_SESSION['role'] !== $required_role) {
        // Redirect to their appropriate dashboard if they try to access wrong area
        if ($_SESSION['role'] === 'teacher') {
            redirect('../admin/index.php');
        } else {
            redirect('../student/index.php');
        }
    }
}

function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}
?>
