<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    $login = validate($_POST['login']);

    $res = $db->query("SELECT `login` FROM `user` WHERE `login` = ?", [$login]);
    if ($res) {
        $db->response(409, "Такой логин уже занят");
    }

    $db->response(200);
