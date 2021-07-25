<?php
    require_once "../../src/db.php";
    session_start();
    $login = $_POST['login'];
    $password = $_POST['password'];

    if ($login && $password) {
        $res = $db->query("SELECT * FROM `user` WHERE `login` = ?", [$login]);
        if (password_verify($password, $res[0]['password'])) {
            $_SESSION['id'] = $res[0]['id'];
            $_SESSION['login'] = $res[0]['login'];
            $db->response([
                "msg"=>'Успешная авторизация!'
            ]);
        } else {
            $db->response([
                "error"=>"Неправельный логин или пароль!"
            ]);
        }
    } else {$db->response(["error"=>"Введите логин и пароль!"]);}
