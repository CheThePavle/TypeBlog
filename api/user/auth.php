<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    $login = validate($_POST['login']);
    $password = validate($_POST['password']);

    if ($login && $password) {
        $res = $db->query("SELECT `id_user`, `login`, `password` FROM `user` WHERE `login` = ?", [$login]);
        if (password_verify($password, $res[0]['password'])) {
            $_SESSION['id'] = $res[0]['id_user'];
            $_SESSION['login'] = $res[0]['login'];
            unset($_SESSION['verifyEmail']);

            $db->response(200, "Успешная авторизация!");
        } else {
            $db->response(409, "Неверный логин или пароль!");
        }
    } else {
        $db->response(409, "Введите логин и пароль!");
    }


//    if ($_POST['checkbox']) {
//        // вечная сессия реализовать
//    }
//  ini_set('session.gc_maxlifetime', 2);
//  ini_set('session.cookie_lifetime', 2);