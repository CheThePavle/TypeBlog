<?php
    require_once "../../src/db.php";

    $email = $_POST['email'];
    if ($email) {
        $res = $db->query("SELECT * FROM `user` WHERE `email` = ?", [$email]);
        if ($res) {
            $db->response([
                "error"=>"Такой email уже занят)"
            ]);
        }
    } else {$db->response(["error"=>"Заполните email!"]);}