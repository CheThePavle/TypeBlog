<?php
    require_once "../../src/db.php";
    require_once "../ajax.php"; //IS_AJAX

        $id = $_GET['id'];

        $res = $db->query("SELECT * FROM `post` WHERE id_post = ?", [$id]);

        $getJson = json_decode(file_get_contents('../../jsonData/posts/'.$res[0]["body"].'.json'.''));

        if ($id) {
            echo json_encode([
                "dbData"=>$res,
                "jsonData"=>[$getJson]
            ]);
        } else {
            http_response_code(409);
            echo $db->response([
                "status"=>409,
                "error"=>"пост с таким id не найден!"
            ]);
        }


        // $test = file_get_contents('../../jsonData/posts/610eaf42e0e901628352322.json');
        // echo $number;