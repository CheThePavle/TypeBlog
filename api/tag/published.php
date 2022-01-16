<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    if ($_SESSION['code_role'] === 'admin') {
        $idPost = validate($_POST['id']);

        $res = $db->make("UPDATE `tag` SET `public_tag` = b? WHERE `tag`.`id_tag` = ?;", [1, $idPost]);
        if ($res) {
            $db->response(200, "Тег успешно опубликован!");
        } else {
            $db->response(409, "Что-то пошло не так, тег не опубликован");
        }
    } else {
        $db->response(409, "Извините нет доступа.");
    }

