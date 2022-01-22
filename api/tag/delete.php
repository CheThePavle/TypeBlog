<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    if ($_SESSION['code_role'] === 'admin') {
        $idTag = validate($_POST['id']);

        $checkEmpty = $db->query("SELECT * FROM post_tag WHERE id_tag = ?;", [$idTag]);
        if ($checkEmpty) {
            $idPost = $checkEmpty[0]['id_post'];
            $db->response(409, "Этот тег уже используется в посте с id - $idPost (удалите его сначала у поста)");
        }

        $res = $db->make("DELETE FROM `tag` WHERE `tag`.`id_tag` = ?;", [$idTag]);
        if (!$res) {
            $db->response(409, "Не удалось удалить тег");
        }

        $db->response(200, "Тег успешно удален");
    } else {
        $db->response(409, "Извините нет доступа.");
    }

