<?php
    $_SESSION['user']='host67';
    $_SESSION['password']='h50RV267';
    $pdo = new PDO('mysql:host=localhost;dbname=elecad', $_SESSION['user'], $_SESSION['password']);
    $GLOBALS['pdo']->query("SET NAMES 'UTF8' ");
?>