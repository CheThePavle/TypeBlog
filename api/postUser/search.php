<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    $get = validate($_POST["searchPostAndUser"]);
    $get = '%' . $get . '%';

    $res = $db->query("SELECT `id_post`, `heading` FROM `post` WHERE heading LIKE ? ORDER BY heading;", [$get]);

    if (!$res) {
        $res = $db->query("SELECT `id_user`, `login` FROM `user` WHERE login LIKE ? ORDER BY login;", [$get]);
    }

    $db->response(200, "", $res);
