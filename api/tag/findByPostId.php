<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    $idPost = validate($_POST['id']);

    if ($_SESSION['code_role'] === 'admin' || $_SESSION['code_role'] === 'moderator') {
        if (!$idPost) {
            $db->response(409, "Пост не передан!");
        }

        $res = $db->query("SELECT t.name_tag FROM post_tag as pt JOIN tag as t ON t.id_tag = pt.id_tag WHERE pt.id_post = ?;", [$idPost]);

        $db->response(200, "", $res);
    } else {
        $db->response(409, "Нет доступа!");
    }
