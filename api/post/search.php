<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    $start = validate($_GET['start']);
    $order = validate($_GET['order']);
    $category = validate($_GET['category']);
    $tag = $_GET['tag'];
    $leftScob = $tag ? '(' : '';
    $rightScob = $tag && $category ? ')' : '';

    $params = [];
    $selectParams = 'p.id_post, p.id_category, p.id_creator, p.id_status, p.heading, p.description, p.main_folder, p.main_img_type, p.created_at, p.updated_at, c.name_category';
    $where = 'JOIN category as c ON p.id_category = c.id_category ';

    if ($tag) {
        $selectParams = 'DISTINCT ' . $selectParams;
        $where = $where . 'JOIN post_tag as pt ON pt.id_post = p.id_post ';
        $where = $where . "WHERE p.id_status = ? ";
        array_push($params, 2);
        if ($category) {
            if ($category != '1') {
                $where = $where . "AND p.id_category = ? ";
                array_push($params, $category);
            }
        }
        if ($category) {
            $whereOrTags = 'AND '.$leftScob;
        } else {
            $whereOrTags = 'WHERE ';
        }
        foreach ($tag as $el) {
            $whereOrTags = $whereOrTags . "pt.id_tag = ? OR ";
            array_push($params, $el);
        }
        $whereOrTags = substr($whereOrTags,0,-3);
    } else {
        $where = $where . "AND p.id_status = ?";
        array_push($params, 2);
        if ($category) {
            if ($category != '1') {
                $where = $where . "AND p.id_category = ? ";
                array_push($params, $category);
            }
        }
    }

    $whereOrder = '';
    switch ($order) {
        case 1:
            $whereOrder = $whereOrder . 'ORDER BY p.id_post ASC';
            break;
        default:
            $whereOrder = $whereOrder . 'ORDER BY p.id_post DESC';
            break;
    }

    $count = 3; //fixme

    $res = $db->query("SELECT $selectParams FROM `post` as p $where $whereOrTags $rightScob $whereOrder LIMIT $start, $count;", $params);

    $getTags = [];
    $getNameUser = [];
    $getLikesPost = [];
    $getViews = [];
    $getViewsDay = [];
    $getReadPost = [];
    $getCommentsCount = [];

    // оптимизировать ?
    foreach ($res as $el) {
        $get = $db->query("SELECT t.id_tag, t.name_tag FROM tag as t JOIN post_tag as pt ON t.id_tag = pt.id_tag WHERE pt.id_post = ?;", [$el['id_post']]);
        array_push($getTags, $get);

        $get = $db->query("SELECT DISTINCT u.login FROM user as u JOIN post as p ON p.id_creator = u.id_user WHERE p.id_creator = ?;", [$el['id_creator']]);
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

