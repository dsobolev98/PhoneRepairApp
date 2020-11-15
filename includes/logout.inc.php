<?php
    if(!isset($_COOKIE['username'])){
        header('Location: login.php');
        exit();
    }else{
        setcookie('username', '', time()-1, '/');
        header('Location: ../login.php');
        exit();
    }
?>