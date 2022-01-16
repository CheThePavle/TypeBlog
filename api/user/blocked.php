<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    $idUser = validate($_POST['id']);

    if ($_SESSION['code_role'] === 'admin') {
        if (!$idUser) {
            $db->response(409, "Что-то пошло не так, пользователь не указан!");
        }

        $check = $db->query("SELECT id_user FROM user WHERE id_user = ?;", [$idUser]);
        if (!$check) {
            $db->response(409, "Что-то пошло не так, пользователь не найден!");
        }

        $res = $db->make("UPDATE `user` SET `blocked` = b? WHERE `user`.`id_user` = ?;", [1, $idUser]);
        if ($res) {
            $db->response(200, "Пользователь заблокирован!");
        }
        $db->response(409, "Что-то пошло не так, не удалось заблокировать пользователя!");
    } else {
        $db->response(409, "Нет доступа!");
    }
