<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    $idTag = validate($_POST['id']);
    $changeTag = validate($_POST['change_tag']);

    if ($_SESSION['code_role'] === 'admin') {
        if (!$idTag) {
            $db->response(409, "Тег не был передан!");
        }
        if (!$changeTag) {
            $db->response(409, "Тег не может быть пустым!");
        }
        if (iconv_strlen($changeTag) > 25) {
            $db->response(409, "Тег не может быть больше 25 символов!");
        }

        $res = $db->make("UPDATE `tag` SET `name_tag` = ? WHERE `tag`.`id_tag` = ?;", [
            $changeTag,
            $idTag
        ]);
        if (!$res) {
            $db->response(409, "Тег не изменен!");
        }
        $db->response(200, "Тег изменен!");
    } else {
        $db->response(409, "Извините нет доступа.");
    }
