<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    $getIdComment = validate($_POST['getIdComment']);
    $id_author = $_SESSION['id'];

    if (!$getIdComment) {
        $db->response(409, "Этот комментарий не может быть оценен!");
    }
    if (!$id_author) {
        $db->response(409, "Только зарегистрированные пользователи могут оценивать комментарии!");
    }

    if ($id_author) {
        $res = $db->query("SELECT * FROM comment_like as cl WHERE cl.id_comment = ? AND cl.id_author = ? ", [$getIdComment, $id_author]);
        if ($res) {
            if ($res[0]['mark'] == '0') {
                $check = $db->make("UPDATE `comment_like` SET `id_comment` = ?, `id_author` = ?, `mark` = ? WHERE `comment_like`.`id_like` = ?", [
                    $getIdComment,
                    $id_author,
                    NULL,
                    $res[0]['id_like']
                ]);
                if (!$check) {
                    $db->response(409, "Что-то пошло не так, невозможно отменить дизлайк!");
                } else {
                    $db->response(409, "Дизлайк отменен", ["toggle"=>0]);
                }
            } elseif ($res[0]['mark'] == '1') {
                $check = $db->make("UPDATE `comment_like` SET `id_comment` = ?, `id_author` = ?, `mark` = b? WHERE `comment_like`.`id_like` = ?", [
                    $getIdComment,
                    $id_author,
                    0,
                    $res[0]['id_like']
                ]);
                if (!$check) {
                    $db->response(409, "Что-то пошло не так, невозможно поставить вместо лайка дизлайк!");
                } else {
                    $db->response(409, "Лайк заменился дизлайком", ["toggle"=>1]);
                }
            } else {
                $check = $db->make("UPDATE `comment_like` SET `id_comment` = ?, `id_author` = ?, `mark` = b? WHERE `comment_like`.`id_like` = ?", [
                    $getIdComment,
                    $id_author,
                    0,
                    $res[0]['id_like']
                ]);
                if (!$check) {
                    $db->response(409, "Что-то пошло не так, невозможно поставить дизлайк!");
                } else {
                    $db->response(409, "Дизлайк поставлен", ["toggle"=>1]);
                }
            }
        } else {
            $check = $db->make("INSERT INTO `comment_like` (`id_comment`, `id_author`, `mark`) VALUES (?, ?, b?)", [
                $getIdComment,
                $id_author,
                0
            ]);
            if (!$check) {
                $db->response(409, "Что-то пошло не так, невозможно поставить дизлайк!");
            } else {
                $db->response(409, "Дизлайк поставлен", ["toggle"=>1]);
            }
        }
    } else {
        $db->response(409, "Для того, чтобы поставить дизлайк комментарию нужно авторизоваться!");
    }