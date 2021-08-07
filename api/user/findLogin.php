<?php
    require_once "../../src/db.php";
    require_once "../../src/fieldValidation.php"; //validate()

    $login = validate($_POST['login']);

    if ($login) {
        if ((strlen($login) > 20) || (strlen($login) < 5)) {
            $db->response([
                "status"=>http_response_code(409),
                "error"=>"Логин имеет длину от 5 до 20 символов"
            ]);
        }
        $res = $db->query("SELECT * FROM `user` WHERE `login` = ?", [$login]);
        if ($res) {
            $db->response([
                "status"=>http_response_code(409),
                "error"=>"Такой логин уже занят)"
            ]);
        }
    } else {$db->response([
        "status"=>http_response_code(409),
        "error"=>"Заполните логин!"
    ]);}