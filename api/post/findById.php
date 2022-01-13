<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    $id = validate($_GET['id']);

    if ($id) {
        $res = $db->query("SELECT * FROM `post` WHERE id_post = ?", [$id]);
        if (!$res) {
            $db->response(404, "Пост не найден");
        }
        $db->response(200, "", $res);
    }
