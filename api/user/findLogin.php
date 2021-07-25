<?php
    require_once "../../src/db.php";

    $login = $_POST['login'];
    if ($login) {
        $res = $db->query("SELECT * FROM `user` WHERE `login` = ?", [$login]);
        if ($res) {
            $db->response([
                "error"=>"Такой логин уже занят)"
            ]);
        }
    } else {$db->response(["error"=>"Заполните логин!"]);}