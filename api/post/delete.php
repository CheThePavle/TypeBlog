<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";
    require_once "../../src/RDir.php";

    if ($_SESSION['code_role'] === 'admin') {
        $idPost = validate($_POST['id']);

        $mainFolder = $db->query("SELECT main_folder FROM post WHERE id_post = ?;", [$idPost]);
        if (!$mainFolder) {
            $db->response(409, "Не удалось найти пост");
        }
        $mainFolder = $mainFolder[0]['main_folder'];

        $res = $db->make("DELETE FROM `post` WHERE `post`.`id_post` = ?;", [$idPost]);
        if (!$res) {
            $db->response(409, "Не удалось удалить пост");
        }

        $newDir = '../../data/posts/'.$mainFolder;
        RDir($newDir);
        $db->response(200, "Пост успешно удален");
    } else {
        $db->response(409, "Извините нет доступа.");
    }

