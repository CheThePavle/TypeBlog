<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    $idPost = validate($_POST['id']);

    if ($_SESSION['code_role'] === 'admin' || $_SESSION['code_role'] === 'moderator') {
        if (!$idPost) {
            $db->response(409, "Пост не передан!");
        }

        $res = $db->query("SELECT body FROM post WHERE id_post = ?;", [$idPost]);

        $res[0]['body'] = json_decode($res[0]['body'], true);
        foreach ($res[0]['body']['blocks'] as &$el) {
            if ($el['type'] == 'image') {
                $url = $el['data']['url'];

                if (mb_substr($url, 0, 4) != "http") {
                    $image = '../..' . $url;
                    $imageSize = getimagesize($image);
                    $imageData = base64_encode(file_get_contents($image));
                    $el['data']['url'] = "data:{$imageSize['mime']};base64,{$imageData}";
                }
            }
        }

        $db->response(200, "", $res[0]['body']);
    } else {
        $db->response(409, "Нет доступа!");
    }
