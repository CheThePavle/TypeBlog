<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    $id = validate($_GET['id']);

    if ($id) {
        $res = false;
        if ($_SESSION['code_role'] === 'admin' || $_SESSION['code_role'] === 'moderator') {
            $res = $db->query("SELECT p.id_post, p.heading, u.login, p.created_at, p.main_folder, p.main_img_type, p.description, p.id_creator, p.body, c.id_category, c.name_category
                                    FROM post as p JOIN category as c ON c.id_category = p.id_category JOIN user as u ON u.id_user = p.id_creator WHERE p.id_post = ?;", [$id]);
        } else {
            $check = $db->query("SELECT id_creator FROM post WHERE id_post = ?;", [$id]);
            if ($check[0]['id_creator'] == $_SESSION['id']) {
                $res = $db->query("SELECT p.id_post, p.heading, u.login, p.created_at, p.main_folder, p.main_img_type, p.description, p.id_creator, p.body, c.id_category, c.name_category
                                    FROM post as p JOIN category as c ON c.id_category = p.id_category JOIN user as u ON u.id_user = p.id_creator WHERE p.id_post = ?;", [$id]);
            } else {
                $res = $db->query("SELECT p.id_post, p.heading, u.login, p.created_at, p.main_folder, p.main_img_type, p.description, p.id_creator, p.body, c.id_category, c.name_category
                                    FROM post as p JOIN category as c ON c.id_category = p.id_category JOIN user as u ON u.id_user = p.id_creator WHERE p.id_post = ? AND p.id_status = ?;", [$id, 2]);
            }
        }
        if (!$res) {
            $db->response(404, "Пост не найден");
        }
        // оптимизировать
        $res[0]['tags'] = $db->query("SELECT t.id_tag, t.name_tag FROM tag as t JOIN post_tag as pt ON t.id_tag = pt.id_tag WHERE pt.id_post = ?;", [$id]);
        $get = $db->query("SELECT COUNT(cl.mark) as 'like' FROM post_like as cl WHERE cl.id_post = ? AND cl.mark = ?
                                UNION SELECT COUNT(cl.mark) FROM post_like as cl WHERE cl.id_post = ? AND cl.mark = ?;", [$id, 1, $id, 0]);
        $res[0]['likes'] = $get[0]['like'] - $get[1]['like'];
        $get = $db->query("SELECT COUNT(v.id_view) as `number` FROM view as v JOIN post as p ON p.id_post = v.id_post WHERE v.id_post = ?;", [$id]);
        $res[0]['views'] = $get[0]['number'];
        $get = $db->query("SELECT COUNT(v.id_view) as `number` FROM view as v JOIN post as p ON p.id_post = v.id_post WHERE v.id_post = ? AND v.created_at >= NOW() - INTERVAL 1 DAY;", [$id]);
        $res[0]['views_day'] = $get[0]['number'];
        $get = $db->query("SELECT COUNT(id_comment) as 'comments' FROM comments WHERE id_post = ?;", [$id]);
        $res[0]['comments_count'] = $get[0]['comments'];
        if ($_SESSION['id']) {
            $get = $db->query("SELECT mark FROM post_like WHERE id_author = ? AND id_post = ?;", [$_SESSION['id'] ,$id]);
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
