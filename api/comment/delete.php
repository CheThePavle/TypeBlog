<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    $getIdComment = validate($_POST['idComment']);

    if ($_SESSION['id']) {
        if ($_SESSION['code_role'] === 'admin' || $_SESSION['code_role'] === 'moderator') {
            $res = $db->make("DELETE FROM `comments` WHERE `comments`.`id_comment` = ?;", [$getIdComment]);
            if (!$res) {
                $db->response(409, "Комментарий не удален");
            } else {
                $db->response(200, "Комментарий удален");
            }
        } else {
            // для пользователя, который хочет удалить свой комментарий
            $check = $db->query("SELECT id_comment FROM comments WHERE id_author = ? AND id_comment = ?;", [$_SESSION['id'], $getIdComment]);
            if (!$check) {
                $db->response(409, "Комментарий не найден или у вас нет доступа к удалению этого комментария");
            }

            $res = $db->make("DELETE FROM `comments` WHERE `comments`.`id_comment` = ?;", [$getIdComment]);
            if (!$res) {
                $db->response(409, "Комментарий не удалось удалить");
            }
            $db->response(200, "Комментарий удален");
        }
    } else {
        $db->response(409, "Только зарегистрированные пользователи могут удалять комментарии!");
    }
