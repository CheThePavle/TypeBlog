<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    if ($_SESSION['code_role'] === 'admin' || $_SESSION['code_role'] === 'moderator') {
        $idPost = validate($_POST['id']);

        // удалить все просмотры у поста
        $db->make("DELETE FROM `view` WHERE `view`.`id_post` = ?;", [$idPost]);

        // удалить все комментарии у поста
        $db->make("DELETE FROM `comments` WHERE `comments`.`id_comment` = ?;", [$idPost]);

        // удалить, как-то лайки на комментарии к этому удаляемому посту

        // удалить лайки на пост
        $db->make("DELETE FROM `post_like` WHERE `post_like`.`id_like` = ?;", [$idPost]);

        // удалить id связанных тегов к посту
        $db->make("DELETE FROM `post_tag` WHERE `post_tag`.`id_post` = ?;", [$idPost]);

        // удалить сам пост
        $db->make("DELETE FROM `post` WHERE `post`.`id_post` = ?;", [$idPost]);

        $db->response(200, "Пост типо удален ) - доделать");
    } else {
        $db->response(409, "Извините нет доступа.");
    }

