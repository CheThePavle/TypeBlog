<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";
    require_once "../../src/RDir.php";
    require_once "../../src/isBase64.php";

    $idUser = validate($_POST['idUser']);
    $idRole = validate($_POST['idRole']);

    if ($_SESSION['code_role'] === 'admin') {
        if (!$idUser) {
            $db->response(409, "Пользователь не передан!");
        }
        if (!$idRole) {
            $db->response(409, "Роль не передана!");
        }

        $check = $db->query("SELECT id_user FROM user WHERE id_user = ?;", [$idUser]);
        if (!$check) {
            $db->response(409, "Пользователь не найден!");
        }

        $change = $db->make("UPDATE `user` SET `id_role` = ? WHERE `user`.`id_user` = ?", [
            $idRole,
            $idUser
        ]);
        if (!$change) {
            $db->response(409, "Что-то полшло не так, поменять роль не удалось");
        }

        $db->response(200, "Роль изменена");
    } else {
        $db->response(409, "Нет доступа!");
    }