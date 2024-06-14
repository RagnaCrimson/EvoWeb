<?php
session_start(); 

if(isset($_SESSION['username'])) {
    unset($_SESSION['username']); 
    session_destroy(); 
    header("Location: login.html");
    exit;
} else {
    header("Location: login.html");
    exit;
}
?>