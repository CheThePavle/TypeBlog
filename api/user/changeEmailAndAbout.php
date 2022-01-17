<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";
    require_once "../../src/RDir.php";
    require_once "../../src/isBase64.php";

    $idUser = $_SESSION['id'];
    $changeEmail = validate($_POST['changeEmail']);
    $changeAbout = validate($_POST['changeAbout']);

    if (!$idUser) {
        $db->response(409, "Пользователь должен быть авторизован!");
    }
    if (!$changeEmail) {
        $db->response(409, "Email должен быть указан!");
    }
    if (iconv_strlen($changeEmail) > 100) {
        $db->response(409, "Email не может быть больше 100 символов!");
    }
    if (iconv_strlen($changeAbout) > 1000) {
        $db->response(409, "О себе не может быть больше 1000 символов!");
    }

    $check = $db->query("SELECT id_user, email FROM user WHERE id_user = ?;", [$idUser]);
    if (!$check) {
        $db->response(409, "Пользователь не найден!");
    }

    if ($check[0]['email'] == $changeEmail) {
        $res = $db->make("UPDATE `user` SET `about` = ? WHERE `user`.`id_user` = ?;", [
            $changeAbout,
            $idUser
        ]);
        if (!$res) {
            $db->response(409, "Не удалось изменить данные пользователя!");
        }
    } else {
        $checkEmail = $db->query("SELECT email FROM user WHERE email = ? AND id_user != ?;", [
            $changeEmail,
            $idUser
        ]);

        if (!$checkEmail) {
            $res = $db->make("UPDATE `user` SET `email` = ?, `about` = ? WHERE `user`.`id_user` = ?;", [
                $changeEmail,
                $changeAbout,
                $idUser
            ]);
            if (!$res) {
                $db->response(409, "Не удалось изменить данные пользователя!");
            }
        } else {
            $db->response(409, "Такой Email уже занят!");
        }
    }

    $db->response(200, "Данные успешно изменены!", [
        "email"=>$changeEmail,
        "about"=>$changeAbout
    ]);