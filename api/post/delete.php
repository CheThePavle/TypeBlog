<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    if ($_SESSION['code_role'] === 'admin') {
        $idPost = validate($_POST['id']);

        $res = $db->make("DELETE FROM `post` WHERE `post`.`id_post` = ?;", [$idPost]);
        if (!$res) {
            $db->response(409, "Не удалось удалить пост");
        }

        $db->response(200, "Пост успешно удален");
    } else {
        $db->response(409, "Извините нет доступа.");
    }

