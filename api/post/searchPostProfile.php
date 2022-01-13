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
        $where = $where . " WHERE p.id_creator = ?";
    }

    $count = apache_getenv('numberLoadPosts');

    $res = $db->query("SELECT * FROM `post` as p $where LIMIT $start, $count", $params);

    $getTags = [];
    $getNameUser = [];
    $getViews = [];
    $getViewsDay = [];
    $getReadPost = [];

    // оптимизировать ?
    foreach ($res as $el) {
        // array_push($jsonData, $el['body']);
        $get = $db->query("SELECT t.id_tag, t.name_tag FROM tag as t JOIN post_tag as pt ON t.id_tag = pt.id_tag WHERE pt.id_post = ?", [$el['id_post']]);
        array_push($getTags, $get);

        $get = $db->query("SELECT u.login FROM user as u JOIN post as p ON p.id_creator = u.id_user WHERE p.id_creator = ?", [$el['id_creator']]);
        array_push($getNameUser, $get);

        $get = $db->query("SELECT COUNT(v.id_view) as `number` FROM view as v JOIN post as p ON p.id_post = v.id_post WHERE v.id_post = ?", [$el['id_post']]);
        array_push($getViews, $get);

        $get = $db->query("SELECT COUNT(v.id_view) as `number` FROM view as v JOIN post as p ON p.id_post = v.id_post WHERE v.id_post = ? AND v.created_at >= NOW() - INTERVAL 1 DAY", [$el['id_post']]);
        array_push($getViewsDay, $get);

        if ($_SESSION['id']) {
            $get = $db->query("SELECT `id_post`, `id_looked` FROM `view` WHERE id_looked = ? AND id_post = ?", [$_SESSION['id'], $el['id_post']]);

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
        $res[$i]['views'] = $getViews[$i];
        $res[$i]['views_day'] = $getViewsDay[$i];
        $res[$i]['read_post'] = $getReadPost[$i];
    }

    $db->response(200, "", $res);
