<?php
    require_once "../../src/db.php";
    require_once "../../src/fieldValidation.php"; //validate()

//    if ($_POST['checkbox']) {
//        // вечная сессия реализовать
//    }
    session_start();

    $login = validate($_POST['login']);
    $password = validate($_POST['password']);

    if ($login && $password) {
        $res = $db->query("SELECT * FROM `user` WHERE `login` = ?", [$login]);
        if (password_verify($password, $res[0]['password'])) {
//            ini_set('session.gc_maxlifetime', 2);
//            ini_set('session.cookie_lifetime', 2);

            $_SESSION['id'] = $res[0]['id'];
            $_SESSION['login'] = $res[0]['login'];

            $db->response([
                "status"=>http_response_code(200),
                "msg"=>'Успешная авторизация!'
            ]);
        } else {
            $db->response([
                "status"=>http_response_code(409),
                "error"=>"Неверный логин или пароль!"
            ]);
        }
    } else {$db->response([
        "status"=>http_response_code(409),
        "error"=>"Введите логин и пароль!"
    ]);}
