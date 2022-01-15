<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    $start = validate($_GET['start']);
    $order = validate($_GET['order']);
    $category = validate($_GET['category']);
    $tag = validate($_GET['tag']);
    $leftScob = $tag ? '(' : '';
    $rightScob = $tag && $category ? ')' : '';

    // $params = []; // добавить для всех sql в будущем
    $selectParams = '*';
    $where = 'JOIN category as c ON p.id_category = c.id_category ';

    if ($tag) {
        $selectParams = 'DISTINCT p.id_post, p.id_category, p.id_creator, p.id_status, p.heading, p.description, p.body, p.created_at, p.updated_at, c.name_category';
        $where = $where . 'JOIN post_tag as pt ';
        if ($category) {
            $whereOrTags = 'AND '.$leftScob;
        } else {
            $whereOrTags = 'WHERE ';
        }
        foreach ($tag as $el) {
            // array_push($params, $el);
            $whereOrTags = $whereOrTags . "pt.id_tag = ".$el." AND pt.id_post = p.id_post OR ";
        }
        $whereOrTags = substr($whereOrTags,0,-3);
    }

    if ($category) {
        // array_push($params, $category);
        if ($category != 'all') {
            $where = $where . "WHERE p.id_category = ".$category." ";
        }
    }

    $whereOrder = '';
    switch ($order) {
        case 1:
            $whereOrder = $whereOrder . 'ORDER BY p.id_post ASC';
            break;
        case 2:
            echo "по популярности";
            break;
        case 3:
            echo "по рейтингу";
            break;
        default:
            $whereOrder = $whereOrder . 'ORDER BY p.id_post DESC';
            break;
    }

    $count = 5; //fixme

    $where = $where . "AND p.id_status = 2"; //fixme

    $res = $db->query("SELECT $selectParams FROM `post` as p $where $whereOrTags $rightScob $whereOrder LIMIT $start, $count;");

    // $jsonData = [];

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

        //fixme не получать из запроса
        $res[$i]['body'] = NULL;

//      можно оптимизировать body? через запрос SQL, чтобы не приходило тело поста
//        $res[$i]['body']['blocks'] = [];
    }

    $db->response(200, "", $res);




//        $db->response([
//            "sql"=>"SELECT $selectParams FROM `post` as p $where $whereOrTags $rightScob $whereOrder LIMIT $start, $count",
//            "res"=>$lk
//        ]);

//        SELECT * FROM `post` as p
//JOIN post_tag as pt
//ON p.id = pt.id_post
//WHERE p.id_category = 1 AND pt.id_tag = 1 ORDER BY p.id ASC LIMIT 0, 2
//    $getJson = [];
//    foreach ($jsonData as $el) {
//        $get = file_get_contents('../../jsonData/posts/'.$el.'.json'.'');
//        array_push($getJson, json_decode($get));
//    }

    //    echo json_encode([
    //        "dbData"=>$res,
    //        "jsonData"=>$getJson
    //    ]);

        // $test = file_get_contents('../../jsonData/posts/610eaf42e0e901628352322.json');
        // echo $number;
