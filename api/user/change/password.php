<?php
    require_once "../../ajax.php";
    require_once "../../../src/db.php";
    require_once "../../../src/validate.php";

    $idUser = $_SESSION['id'];
    $oldPassword = validate($_POST['oldPassword']);
    $newPassword = validate($_POST['newPassword']);

    if (!$idUser) {
        $db->response(409, "Вы должны быть авторизованы, чтобы изменить пароль от аккаунта!");
    }
    if (!$oldPassword) {
        $db->response(409, "Укажите старый пароль!");
    }
    if (!$newPassword) {
        $db->response(409, "Укажите новый пароль!");
    }
    if (iconv_strlen($newPassword) < 6) {
        $db->response(409, "Новый пароль не может быть меньше 6 символов!");
    }
    if (iconv_strlen($newPassword) > 32) {
        $db->response(409, "Новый пароль не может быть больше 32 символов!");
    }

    $check = $db->query("SELECT password FROM user WHERE id_user = ?;", [$idUser]);
    if (!$check) {
        $db->response(409, "Пользователь не найден");
    }

    if (password_verify($oldPassword, $check[0]['password'])) {
        $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $change = $db->make("UPDATE `user` SET `password` = ? WHERE `user`.`id_user` = ?;", [$newPassword ,$idUser]);
        if (!$change) {
            $db->response(409, "Что-то пошло не так, пароль не изменен");
        }

        $db->response(200, "Пароль успешно изменен!");
    } else {
        $db->response(409, "Старый пароль неверный!");
    }
