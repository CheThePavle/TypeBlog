<?php
    require_once "../../src/db.php";
    require_once "../../src/fieldValidation.php"; //validate()
    require_once "../ajax.php"; //IS_AJAX


        $login = validate($_POST['login']);

        if ($login) {
            if ((strlen($login) > 20) || (strlen($login) < 5)) {
                http_response_code(409);
                $db->response([
                    "status"=>409,
                    "error"=>"Логин имеет длину от 5 до 20 символов"
                ]);
            }
            // брать только нужное!
            $res = $db->query("SELECT * FROM `user` WHERE `login` = ?", [$login]);
            if ($res) {
                http_response_code(409);
                $db->response([
                    "status"=>409,
                    "error"=>"Такой логин уже занят)"
                ]);
            }
        } else {
            http_response_code(409);
            $db->response([
            "status"=>409,
            "error"=>"Заполните логин!"
            ]);
        }
