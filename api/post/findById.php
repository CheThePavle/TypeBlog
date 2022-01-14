<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    $id = validate($_GET['id']);

    if ($id) {
        // просмотр не опубликованных постов доступен только админу и модератору
        if ($_SESSION['code_role'] === 'admin' || $_SESSION['code_role'] === 'moderator') {
            $res = $db->query("SELECT * FROM `post` WHERE id_post = ?", [$id]);
        } else {
            $res = $db->query("SELECT * FROM `post` WHERE id_post = ? AND id_status = ?", [$id, 2]);
        }
        if (!$res) {
            $db->response(404, "Пост не найден");
        }
        if ($_SESSION['id']) {
            $get = $db->query("SELECT mark FROM post_like WHERE id_author = ? AND id_post = ?", [$_SESSION['id'] ,$id]);
            if ($get[0]['mark'] == '0') {
                $getYourLike = false;
            } elseif ($get[0]['mark'] == '1') {
                $getYourLike = true;
            } else {
                $getYourLike = NULL;
            }
            $res[0]['myLike'] = $getYourLike;
        }
        $db->response(200, "", $res);
    }
