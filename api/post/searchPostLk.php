<?php
    require_once "../../src/db.php";
    require_once "../ajax.php"; //IS_AJAX

    $start = $_GET['start'];
    $lk = $_GET['lk'];

    $params = [];

    $where = 'JOIN category as c ON p.id_category = c.id_category ';

    if ($lk) {
        $getIdUser = $_SESSION['id'];
        array_push($params, $getIdUser);
        $where = $where . " WHERE p.id_creator = ?";
    }

    $count = apache_getenv('numberLoadPosts');

    $res = $db->query("SELECT * FROM `post` as p $where LIMIT $start, $count", $params);

    //  $jsonData = [];
    $getTags = [];
    $getNameUser = [];
    $getViews = [];
    $getViewsDay = [];

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
    }

    for ($i = 0; $i <  count($res); $i++) {
        $res[$i]['tags'] = $getTags[$i];
        $res[$i]['user_login'] = $getNameUser[$i];
        $res[$i]['views'] = $getViews[$i];
        $res[$i]['views_day'] = $getViewsDay[$i];
    }

//        $db->response([
//            "sql"=>"SELECT * FROM `post` as p $where LIMIT $start, $count",
//            "res"=>$res
//        ]);

    echo json_encode($res);

