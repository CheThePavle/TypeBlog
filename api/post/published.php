<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    if ($_SESSION['code_role'] === 'admin' || $_SESSION['code_role'] === 'moderator') {
        $idPost = validate($_POST['id']);

        $findTags = $db->query("SELECT * FROM `post_tag` WHERE id_post = ?;", [$idPost]);
        if ($findTags) {
            foreach ($findTags as $el) {
                $db->make("UPDATE `tag` SET `public_tag` = b? WHERE `tag`.`id_tag` = ?;", [1 ,$el['id_tag']]);
            }
        }

        $res = $db->make("UPDATE `post` SET `id_status` = ? WHERE `post`.`id_post` = ?;", [2, $idPost]);
        if ($res) {
            $db->response(200, "Пост успешно опубликован!");
        } else {
            $db->response(409, "Что-то пошло не так, пост не опубликован");
        }
    } else {
        $db->response(409, "Извините нет доступа.");
    }

