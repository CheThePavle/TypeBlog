<?php
    require_once "../../src/db.php";
    require_once "../ajax.php"; //IS_AJAX

    $start = $_GET['start'];
    $id_user = $_GET['id_user'];

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
    }

//        $db->response([
//            "sql"=>"SELECT $selectParams FROM `post` as p $where $whereOrTags $rightScob $whereOrder LIMIT $start, 2",
//            "res"=>$lk
//        ]);

    echo json_encode($res);