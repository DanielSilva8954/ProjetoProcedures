<?php
    require_once("models/User.php");
    require_once("dao/UserDAO.php");
    require_once("config/conn.php");
    require_once("config/globals.php");
    $user = new User();
    $userDao = new UserDAO($conn, $BASE_URL);
    $userDao->destroyToken();
?>