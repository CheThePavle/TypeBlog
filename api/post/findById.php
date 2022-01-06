<?php
    require_once "../../src/db.php";
    require_once "../ajax.php"; //IS_AJAX

        $id = $_GET['id'];

        if ($id) {
            $res = $db->query("SELECT * FROM `post` WHERE id_post = ?", [$id]);
            echo json_encode($res);
        } else {
            http_response_code(409);
            echo $db->response([
                "status"=>409,
                "error"=>"пост с таким id не найден!"
            ]);
        }


        // $test = file_get_contents('../../jsonData/posts/610eaf42e0e901628352322.json');
        // echo $number;