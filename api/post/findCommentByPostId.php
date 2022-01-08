<?php
    require_once "../../src/db.php"; // class $db
    require_once "../../src/fieldValidation.php"; //validate()
    require_once "../ajax.php";
    require_once "../../src/getTree.php"; //getTree()

    $idPost = validate($_POST['idPost']);

    // доп. проверка полей
    if (!$idPost) {
        http_response_code(409);
        $db->response([
            "status"=>409,
            "error"=>"Комментарий не может быть добавлен на этот пост!"
        ]);
    }

    $res = $db->query("SELECT c.id_comment, c.id_author, c.body_comment, c.id_parent_comment, c.created_at, c.updated_at, u.avatar_url, u.login 
                            FROM comments as c JOIN post as p ON c.id_post = p.id_post JOIN user as u ON c.id_author = u.id_user 
                            WHERE c.id_post = ? ORDER BY c.created_at DESC",[
        $idPost
    ]);

    $getLikes = [];
    $getParents = [];

    // оптимизировать ?
    foreach ($res as $el) {
        $get = $db->query("SELECT COUNT(cl.mark) as 'like' FROM comment_like as cl WHERE cl.id_comment = ? AND cl.mark = ?
                                UNION SELECT COUNT(cl.mark) as 'dislike' FROM comment_like as cl WHERE cl.id_comment = ? AND cl.mark = ?", [$el['id_comment'], 1, $el['id_comment'], 0]);
        $get = $get[0]['like'] - $get[1]['like'];
        array_push($getLikes, $get);
    }

    for ($i = 0; $i <  count($res); $i++) {
        $res[$i]['like'] = $getLikes[$i];
    }

    http_response_code(200);
    $db->response([
        "status"=>200,
        "result"=>getTree($res, 'id_comment', 'id_parent_comment')
    ]);