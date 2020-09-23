<?php
    //$_SESSION['user']='host67'; $_SESSION['password']='h50RV267';   // HOSTING
    $_SESSION['user']='root'; $_SESSION['password']='';             // LOCALHOST
    $pdo = new PDO('mysql:host=localhost;dbname=host67_hostal', $_SESSION['user'], $_SESSION['password']);
    $GLOBALS['pdo']->query("SET NAMES 'UTF8' ");
?>