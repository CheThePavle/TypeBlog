<?php
    require_once "../../src/db.php";
    require_once "../ajax.php"; //IS_AJAX

    if(IS_AJAX) {
        $start = $_GET['start'];
        $order = $_GET['order'];
        $category = $_GET['category'];
        $tag = $_GET['tag'];

        $params = [];
        $where = '';

        if ($tag) {
            $where = $where . 'JOIN post_tag as pt ON p.id = pt.id_post ';
            if ($category) {
                $whereOrTags = 'AND ';
            } else {
                $whereOrTags = 'WHERE ';
            }
            foreach ($tag as $el) {
                array_push($params, $el);
                $whereOrTags = $whereOrTags . "pt.id_tag = ? OR ";
            }
            $whereOrTags = substr($whereOrTags,0,-3);
        }

        if ($category) {
            array_push($params, $category);
            $where = $where . 'WHERE p.id_category = ? ';
        }

        $whereOrder = '';
        switch ($order) {
            case 1:
                $whereOrder = $whereOrder . 'ORDER BY p.id ASC';
                break;
            case 2:
                echo "по популярности";
                break;
            case 3:
                echo "по рейтингу";
                break;
            default:
                $whereOrder = $whereOrder . 'ORDER BY p.id DESC';
                break;
        }

        $res = $db->query("SELECT * FROM `post` as p $where $whereOrTags $whereOrder LIMIT $start, 2", $params);

//        $db->response([
//            "response"=>"SELECT * FROM `post` as p $where $whereOrTags $whereOrder LIMIT $start, 2"
//        ]);

        $jsonData = [];
        foreach ($res as $el) {
            array_push($jsonData, $el['body']);
        }

//        SELECT * FROM `post` as p
//JOIN post_tag as pt
//ON p.id = pt.id_post
//WHERE p.id_category = 1 AND pt.id_tag = 1 ORDER BY p.id ASC LIMIT 0, 2
    //    $getJson = [];
    //    foreach ($jsonData as $el) {
    //        $get = file_get_contents('../../jsonData/posts/'.$el.'.json'.'');
    //        array_push($getJson, json_decode($get));
    //    }

        echo json_encode($res);

    //    echo json_encode([
    //        "dbData"=>$res,
    //        "jsonData"=>$getJson
    //    ]);

        // $test = file_get_contents('../../jsonData/posts/610eaf42e0e901628352322.json');
        // echo $number;
    } else {
        http_response_code(403);
        $db->response([
            "status"=>403,
            "error"=>"Нет доступа!"
        ]);
    }