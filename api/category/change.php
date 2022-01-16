<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    $idCategory = validate($_POST['id']);
    $changeCategory = validate($_POST['change_category']);

    if ($_SESSION['code_role'] === 'admin') {
        if (!$idCategory) {
            $db->response(409, "Категория не была передана!");
        }
        if (!$changeCategory) {
            $db->response(409, "Категория не может быть пустой!");
        }
        if (iconv_strlen($changeCategory) > 30) {
            $db->response(409, "Категория не может быть больше 30 символов!");
        }

        $res = $db->make("UPDATE `category` SET `name_category` = ? WHERE `category`.`id_category` = ?;", [
            $changeCategory,
            $idCategory
        ]);
        if (!$res) {
            $db->response(409, "Категория не изменена!");
        }
        $db->response(200, "Категория изменена!");
    } else {
        $db->response(409, "Извините нет доступа.");
    }
