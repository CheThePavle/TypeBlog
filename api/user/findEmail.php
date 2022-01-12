<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    $email = validate($_POST['email']);

    $res = $db->query("SELECT `email` FROM `user` WHERE `email` = ?", [$email]);
    if ($res) {
        $db->response(409, "Такой email уже занят)");
    }

    $db->response(200);
