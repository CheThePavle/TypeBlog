<?php
    require_once "../../src/db.php";
    require_once "../../src/fieldValidation.php"; //validate()

    $email = validate($_POST['email']);
    if ($email) {
        if (strlen($email) > 100) {
            $db->response([
                "error"=>"Email не может быть больше 100 символов"
            ]);
        }
        $res = $db->query("SELECT * FROM `user` WHERE `email` = ?", [$email]);
        if ($res) {
            $db->response([
                "error"=>"Такой email уже занят)"
            ]);
        }
    } else {$db->response(["error"=>"Заполните email!"]);}