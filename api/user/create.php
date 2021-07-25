<?php
    require_once "../../src/db.php";

    require_once "findLogin.php";

    require_once "findEmail.php";

    $password = $_POST['password'];
    $password2 = $_POST['password2'];
    if ($password === $password2) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    } else {
        $db->response([
            "error"=>"Пароли не совпадают!"
        ]);
    }

    $about = $_POST['about'];
    $db->make('INSERT INTO `user` (`id`, `email`, `login`, `password`, `about`) VALUES (NULL, ?, ?, ?, ?);', [
        $email,
        $login,
        $password,
        $about
    ]);

    $db->response([
        "msg"=>"Успешная регистрация!"
    ]);
