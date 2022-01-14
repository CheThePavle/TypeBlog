<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    $idPost = validate($_POST['id']);

    if (!$idPost) {
        $db->response(409, "Комментарии не могут загрузиться для этого поста!");
    }

    $res = $db->query("SELECT c.id_comment, c.id_author, c.body_comment, c.id_parent_comment, c.created_at, c.updated_at, u.avatar_url, u.login 
                            FROM comments as c JOIN post as p ON c.id_post = p.id_post JOIN user as u ON c.id_author = u.id_user 
                            WHERE c.id_post = ? ORDER BY c.created_at DESC",[
        $idPost
    ]);

    $getLikes = [];
    $getYourLike = [];
    $getYourComment = [];

    // оптимизировать ?
    foreach ($res as $el) {
        $get = $db->query("SELECT COUNT(cl.mark) as 'like' FROM comment_like as cl WHERE cl.id_comment = ? AND cl.mark = ?
                                UNION SELECT COUNT(cl.mark) FROM comment_like as cl WHERE cl.id_comment = ? AND cl.mark = ?", [$el['id_comment'], 1, $el['id_comment'], 0]);
        $get = $get[0]['like'] - $get[1]['like'];
        array_push($getLikes, $get);

        if ($_SESSION['id']) {
            $get = $db->query("SELECT mark FROM comment_like WHERE id_author = ? AND id_comment = ?", [$_SESSION['id'], $el['id_comment']]);
            if ($get[0]['mark'] == '0') {
                array_push($getYourLike, false);
            } elseif ($get[0]['mark'] == '1') {
                array_push($getYourLike, true);
            } else {
                array_push($getYourLike, NULL);
            }

            $get = $db->query("SELECT id_comment FROM comments WHERE id_comment = ? AND id_author = ?", [$el['id_comment'], $_SESSION['id']]);
            if ($get) {
                array_push($getYourComment, true);
            } else {
                array_push($getYourComment, false);
            }
        }

    }

    for ($i = 0; $i <  count($res); $i++) {
        $res[$i]['like'] = $getLikes[$i];
        $res[$i]['myLike'] = $getYourLike[$i];
        $res[$i]['myComment'] = $getYourComment[$i];
    }

    $db->response(200, "", $res);
