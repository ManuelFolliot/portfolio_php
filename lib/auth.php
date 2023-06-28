<?php 
session_start();
if(!isset($auth)) {
    if(!isset($_SESSION['auth']['id'])){
        header('Location:' . WEBROOT . 'login.php');
        die();
    }
}
?>