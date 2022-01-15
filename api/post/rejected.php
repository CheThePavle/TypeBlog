<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    if ($_SESSION['code_role'] === 'admin' || $_SESSION['code_role'] === 'moderator') {
        $idPost = validate($_POST['id']);

        $res = $db->make("UPDATE `post` SET `id_status` = ? WHERE `post`.`id_post` = ?;", [3, $idPost]);
        if ($res) {
            $db->response(200, "Пост успешно отклонен!");
        } else {
            $db->response(409, "Что-то пошло не так, пост не отклонен");
        }
    } else {
        $db->response(409, "Извините нет доступа.");
    }

