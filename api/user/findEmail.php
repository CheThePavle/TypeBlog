<?php
    require_once "../../src/db.php";
    require_once "../../src/fieldValidation.php"; //validate()
    require_once "../ajax.php"; //IS_AJAX


        $email = validate($_POST['email']);

        if ($email) {
            if (strlen($email) > 100) {
                http_response_code(409);
                $db->response([
                    "status"=>409,
                    "error"=>"Email не может быть больше 100 символов"
                ]);
            }
            // брать только нужное!
            $res = $db->query("SELECT * FROM `user` WHERE `email` = ?", [$email]);
            if ($res) {
                http_response_code(409);
                $db->response([
                    "status"=>409,
                    "error"=>"Такой email уже занят)"
                ]);
            }
        } else {
            http_response_code(409);
            $db->response([
            "status"=>409,
            "error"=>"Заполните email!"
            ]);
        }

