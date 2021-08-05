<?php
    require_once "../../src/db.php";
    require_once "../../src/fieldValidation.php"; //validate()

    require_once "findLogin.php";

    require_once "findEmail.php";

    $password = validate($_POST['password']);
    $password2 = validate($_POST['password2']);
    if ($password === $password2) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    } else {
        $db->response([
            "error"=>"Пароли не совпадают!"
        ]);
    }

    $about = $_POST['about'];
    $created_at = date("Y-m-d H:i:s");
    $db->make('INSERT INTO `user` (`email`, `login`, `password`, `about`, `created_at`) 
                    VALUES (?, ?, ?, ?, ?)',
    [
        $email,
        $login,
        $password,
        $about,
        $created_at
    ]);

    $db->response([
        "msg"=>"Успешная регистрация!"
    ]);
