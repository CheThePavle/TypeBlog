<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    if ($_SESSION['code_role'] === 'admin') {
        $idTag = validate($_POST['id']);

        $res = $db->make("DELETE FROM `tag` WHERE `tag`.`id_tag` = ?;", [$idTag]);
        if (!$res) {
            $db->response(409, "Не удалось удалить тег");
        }

        $db->response(200, "Тег успешно удален");
    } else {
        $db->response(409, "Извините нет доступа.");
    }

