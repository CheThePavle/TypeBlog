<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    $start = validate($_GET['start']);
    $id_user = validate($_GET['id_user']);

    $params = [];

    $where = 'JOIN category as c ON p.id_category = c.id_category ';

    if ($id_user) {
        array_push($params, $id_user);
        array_push($params, 2);
        $where = $where . " WHERE p.id_creator = ? AND p.id_status = ?";
    }

    $count = 3; //fixme

    $res = $db->query("SELECT * FROM `post` as p $where ORDER BY p.id_post DESC LIMIT $start, $count;", $params);

    $getTags = [];
    $getNameUser = [];
    $getLikesPost = [];
    $getViews = [];
    $getViewsDay = [];
    $getReadPost = [];
    $getCommentsCount = [];

    // оптимизировать ?
    foreach ($res as $el) {
        // array_push($jsonData, $el['body']);
        $get = $db->query("SELECT t.id_tag, t.name_tag FROM tag as t JOIN post_tag as pt ON t.id_tag = pt.id_tag WHERE pt.id_post = ?;", [$el['id_post']]);
        array_push($getTags, $get);

        $get = $db->query("SELECT u.login FROM user as u JOIN post as p ON p.id_creator = u.id_user WHERE p.id_creator = ?;", [$el['id_creator']]);
        array_push($getNameUser, $get[0]['login']);

        $get = $db->query("SELECT COUNT(cl.mark) as 'like' FROM post_like as cl WHERE cl.id_post = ? AND cl.mark = ?
                                    UNION SELECT COUNT(cl.mark) FROM post_like as cl WHERE cl.id_post = ? AND cl.mark = ?;", [$el['id_post'], 1, $el['id_post'], 0]);
        $get = $get[0]['like'] - $get[1]['like'];
        array_push($getLikesPost, $get);

        $get = $db->query("SELECT COUNT(v.id_view) as `number` FROM view as v JOIN post as p ON p.id_post = v.id_post WHERE v.id_post = ?;", [$el['id_post']]);
        array_push($getViews, $get[0]['number']);

        $get = $db->query("SELECT COUNT(v.id_view) as `number` FROM view as v JOIN post as p ON p.id_post = v.id_post WHERE v.id_post = ? AND v.created_at >= NOW() - INTERVAL 1 DAY;", [$el['id_post']]);
        array_push($getViewsDay, $get[0]['number']);

        $get = $db->query("SELECT COUNT(id_comment) as 'comments' FROM comments WHERE id_post = ?;", [$el['id_post']]);
        array_push($getCommentsCount, $get[0]['comments']);

        if ($_SESSION['id']) {
            $get = $db->query("SELECT `id_post`, `id_looked` FROM `view` WHERE id_looked = ? AND id_post = ?;", [$_SESSION['id'], $el['id_post']]);

            if ($get) {
                array_push($getReadPost, true);
            } else {
                array_push($getReadPost, false);
            }
        }
    }

    for ($i = 0; $i <  count($res); $i++) {
        $res[$i]['tags'] = $getTags[$i];
        $res[$i]['user_login'] = $getNameUser[$i];
        $res[$i]['likes'] = $getLikesPost[$i];
        $res[$i]['views'] = $getViews[$i];
        $res[$i]['views_day'] = $getViewsDay[$i];
        $res[$i]['read_post'] = $getReadPost[$i];
        $res[$i]['comments_count'] = $getCommentsCount[$i];
    }

    $db->response(200, "", $res);
