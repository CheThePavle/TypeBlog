<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    $idComplaint = validate($_POST['id']);

    if (!$idComplaint) {
        $db->response(409, "Что-то пошло не так, жалоба не передана!");
    }

    $check = $db->query("SELECT id_accused FROM user_complaint WHERE id_complaint = ?", [$idComplaint]);
    if (!$check) {
        $db->response(409, "Что-то пошло не так, жалоба не найдена!");
    }

    $res = $db->query("SELECT id_complaint FROM user_complaint WHERE id_accused = ?", [$check[0]['id_accused']]);
    if (!$res) {
        $db->response(409, "Что-то пошло не так, жалоба не найдена для этого пользователя!");
    }

    // сделать через IN(1,2)
    foreach ($res as $el) {
        $db->make("DELETE FROM `user_complaint` WHERE `user_complaint`.`id_complaint` = ?", [$el['id_complaint']]);
    }

    $block = $db->make("UPDATE `user` SET `blocked` = b? WHERE `user`.`id_user` = ?", [1 ,$check[0]['id_accused']]);
    if ($block) {
        $db->response(200, "Пользователь заблокирован!");
    }

    $db->response(409, "Что-то пошло не так, не удалось заблокировать пользователя!");
