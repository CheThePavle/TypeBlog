<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    $getIdComment = validate($_POST['idComment']);
    $id_author = $_SESSION['id'];

    if (!$id_author) {
        $db->response(409, "Только зарегистрированные пользователи могут ставить лайки на комментарии!");
    }
    if (!$getIdComment) {
        $db->response(409, "Этот комментарий не может быть оценен!");
    }

    if ($id_author) {
        $res = $db->query("SELECT * FROM comment_like WHERE id_comment = ? AND id_author = ?", [$getIdComment, $id_author]);

        if ($res) {
            if ($res[0]['mark'] == '1') {
                $check = $db->make("UPDATE `comment_like` SET `id_comment` = ?, `id_author` = ?, `mark` = ? WHERE `comment_like`.`id_like` = ?", [
                    $getIdComment,
                    $id_author,
                    NULL,
                    $res[0]['id_like']
                ]);
                if (!$check) {
                    $db->response(409, "Что-то пошло не так, невозможно отменить лайк!");
                } else {
                    $db->response(200, "Лайк отменен");
                }
            } elseif ($res[0]['mark'] == '0') {
                $check = $db->make("UPDATE `comment_like` SET `id_comment` = ?, `id_author` = ?, `mark` = b? WHERE `comment_like`.`id_like` = ?", [
                    $getIdComment,
                    $id_author,
                    1,
                    $res[0]['id_like']
                ]);
                if (!$check) {
                    $db->response(409, "Что-то пошло не так, невозможно поставить вместо дизлайка лайк!");
                } else {
                    $db->response(200, "Дизлайк заменился лайком");
                }
            } else {
                $check = $db->make("UPDATE `comment_like` SET `id_comment` = ?, `id_author` = ?, `mark` = b? WHERE `comment_like`.`id_like` = ?", [
                    $getIdComment,
                    $id_author,
                    1,
                    $res[0]['id_like']
                ]);
                if (!$check) {
                    $db->response(409, "Что-то пошло не так, невозможно поставить лайк!");
                } else {
                    $db->response(200, "Лайк поставлен");
                }
            }
        } else {
            $check = $db->make("INSERT INTO `comment_like` (`id_comment`, `id_author`, `mark`) VALUES (?, ?, b?)", [
                $getIdComment,
                $id_author,
                1
            ]);
            if (!$check) {
                $db->response(409, "Что-то пошло не так, невозможно поставить лайк!");
            } else {
                $db->response(200, "Лайк поставлен");
            }
        }
    } else {
        $db->response(409, "Для того, чтобы поставить лайк комментарию нужно авторизоваться!");
    }
