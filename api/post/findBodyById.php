<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    $idPost = validate($_POST['id']);

    if ($_SESSION['code_role'] === 'admin' || $_SESSION['code_role'] === 'moderator') {
        if (!$idPost) {
            $db->response(409, "Пост не передан!");
        }

        $res = $db->query("SELECT body FROM post WHERE id_post = ?;", [$idPost]);

        $db->response(200, "", $res[0]['body']);
    } else {
        $db->response(409, "Нет доступа!");
    }
