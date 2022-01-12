<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    $id = validate($_GET['id']);

    if ($id) {
        $res = $db->query("SELECT * FROM `post` WHERE id_post = ?", [$id]);
        echo json_encode($res); //fixme
    } else {
        $db->response(409, "Пост не найден!");
    }
