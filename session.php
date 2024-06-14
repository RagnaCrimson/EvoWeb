<?php
if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function check_login() {
    if (!isset($_SESSION['username'])) {
        header("Location: login.html"); 
        exit();
    }
}
?>
