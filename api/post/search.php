<?php
    require_once "../../src/db.php";
    require_once "../ajax.php"; //IS_AJAX

    $start = $_GET['start'];
    $order = $_GET['order'];
    $category = $_GET['category'];
    $tag = $_GET['tag'];
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

    $count = apache_getenv('numberLoadPosts');

    $res = $db->query("SELECT $selectParams FROM `post` as p $where $whereOrTags $rightScob $whereOrder LIMIT $start, $count");

    // $jsonData = [];

    $getTags = [];
    $getNameUser = [];
    foreach ($res as $el) {
        // array_push($jsonData, $el['body']);
        $get = $db->query("SELECT t.id_tag, t.name_tag FROM tag as t JOIN post_tag as pt ON t.id_tag = pt.id_tag WHERE pt.id_post = ?", [$el['id_post']]);
        array_push($getTags, $get);

        $get = $db->query("SELECT u.login FROM user as u JOIN post as p ON p.id_creator = u.id_user WHERE p.id_creator = ?", [$el['id_creator']]);
        array_push($getNameUser, $get);
    }

    for ($i = 0; $i <  count($res); $i++) {
        $res[$i]['tags'] = $getTags[$i];
        $res[$i]['user_login'] = $getNameUser[$i];
//      можно оптимизировать body? через запрос SQL, чтобы не приходило тело поста
//        $res[$i]['body']['blocks'] = [];
    }

//        $db->response([
//            "sql"=>"SELECT $selectParams FROM `post` as p $where $whereOrTags $rightScob $whereOrder LIMIT $start, $count",
//            "res"=>$lk
//        ]);

    echo json_encode($res);

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
