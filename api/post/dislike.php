<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    $getIdPost = validate($_POST['id']);
    $id_author = $_SESSION['id'];

    if (!$id_author) {
        $db->response(409, "Только зарегистрированные пользователи могут ставить дизлайк на пост!");
    }
    if (!$getIdPost) {
        $db->response(409, "Этот пост не может быть оценен!");
    }

    if ($id_author) {
        $res = $db->query("SELECT * FROM post_like WHERE id_post = ? AND id_author = ?;", [$getIdPost, $id_author]);

        if ($res) {
            if ($res[0]['mark'] == '0') {
                $check = $db->make("UPDATE `post_like` SET `id_post` = ?, `id_author` = ?, `mark` = ? WHERE `post_like`.`id_like` = ?;", [
                    $getIdPost,
                    $id_author,
                    NULL,
                    $res[0]['id_like']
                ]);
                if (!$check) {
                    $db->response(409, "Что-то пошло не так, невозможно отменить дизлайк!");
                } else {
                    $db->response(200, "Дизлайк отменен");
                }
            } elseif ($res[0]['mark'] == '1') {
                $check = $db->make("UPDATE `post_like` SET `id_post` = ?, `id_author` = ?, `mark` = b? WHERE `post_like`.`id_like` = ?;", [
                    $getIdPost,
                    $id_author,
                    0,
                    $res[0]['id_like']
                ]);
                if (!$check) {
                    $db->response(409, "Что-то пошло не так, невозможно поставить вместо лайка дизлайк!");
                } else {
                    $db->response(200, "Лайк заменился дизлайком");
                }
            } else {
                $check = $db->make("UPDATE `post_like` SET `id_post` = ?, `id_author` = ?, `mark` = b? WHERE `post_like`.`id_like` = ?;", [
                    $getIdPost,
                    $id_author,
                    0,
                    $res[0]['id_like']
                ]);
                if (!$check) {
                    $db->response(409, "Что-то пошло не так, невозможно поставить дизлайк!");
                } else {
                    $db->response(200, "Дизлайк поставлен");
                }
            }
        } else {
            $check = $db->make("INSERT INTO `post_like` (`id_post`, `id_author`, `mark`) VALUES (?, ?, b?);", [
                $getIdPost,
                $id_author,
                0
            ]);
            if (!$check) {
                $db->response(409, "Что-то пошло не так, невозможно поставить дизлайк!");
            } else {
                $db->response(200, "Дизлайк поставлен");
            }
        }
    } else {
        $db->response(409, "Для того, чтобы поставить дизлайк посту нужно авторизоваться!");
    }