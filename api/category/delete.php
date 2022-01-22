<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    if ($_SESSION['code_role'] === 'admin') {
        $idCategory = validate($_POST['id']);

        $checkEmpty = $db->query("SELECT id_post FROM post WHERE id_category = ?;", [$idCategory]);
        if ($checkEmpty) {
            $idPost = $checkEmpty[0]['id_post'];
            $db->response(409, "Эта категория уже используется в посте с id - $idPost (уберите её сначала у поста)");
        }

        $res = $db->make("DELETE FROM `tag` WHERE `tag`.`id_tag` = ?;", [$idCategory]);
        if (!$res) {
            $db->response(409, "Не удалось удалить тег");
        }

        $db->response(200, "Тег успешно удален");
    } else {
        $db->response(409, "Извините нет доступа.");
    }

