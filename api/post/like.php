<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    $getIdPost = validate($_POST['id']);
    $id_author = $_SESSION['id'];

    if (!$id_author) {
        $db->response(409, "Только зарегистрированные пользователи могут поставить лайк на пост!");
    }
    if (!$getIdPost) {
        $db->response(409, "Этот пост не может быть оценен!");
    }

    if ($id_author) {
        $res = $db->query("SELECT * FROM post_like WHERE id_post = ? AND id_author = ?", [$getIdPost, $id_author]);

        if ($res) {
            if ($res[0]['mark'] == '1') {
                $check = $db->make("UPDATE `post_like` SET `id_post` = ?, `id_author` = ?, `mark` = ? WHERE `post_like`.`id_like` = ?", [
                    $getIdPost,
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
                $check = $db->make("UPDATE `post_like` SET `id_post` = ?, `id_author` = ?, `mark` = b? WHERE `post_like`.`id_like` = ?", [
                    $getIdPost,
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
                $check = $db->make("UPDATE `post_like` SET `id_post` = ?, `id_author` = ?, `mark` = b? WHERE `post_like`.`id_like` = ?", [
                    $getIdPost,
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
            $check = $db->make("INSERT INTO `post_like` (`id_post`, `id_author`, `mark`) VALUES (?, ?, b?)", [
                $getIdPost,
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
        $db->response(409, "Для того, чтобы поставить лайк посту нужно авторизоваться!");
    }
