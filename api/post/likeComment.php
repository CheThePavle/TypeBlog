<?php
    require_once "../../src/db.php"; // class $db
    require_once "../../src/fieldValidation.php"; //validate()
    require_once "../ajax.php";

    $getIdComment = validate($_POST['getIdComment']);
    $id_author = $_SESSION['id'];

    // доп. проверка полей
    if (!$getIdComment) {
        http_response_code(409);
        $db->response([
            "status"=>409,
            "error"=>"Этот комментарий не может быть оценен!"
        ]);
    }
    if (!$id_author) {
        http_response_code(409);
        $db->response([
            "status"=>409,
            "error"=>"Только зарегистрированные пользователи могут оценивать комментарии!"
        ]);
    }

    if ($id_author) {
        $res = $db->query("SELECT * FROM comment_like as cl WHERE cl.id_comment = ? AND cl.id_author = ? ", [$getIdComment, $id_author]);

        if ($res) {
            if ($res[0]['mark'] == '1') {
                $check = $db->make("UPDATE `comment_like` SET `id_comment` = ?, `id_author` = ?, `mark` = ? WHERE `comment_like`.`id_like` = ?", [
                    $getIdComment,
                    $id_author,
                    NULL,
                    $res[0]['id_like']
                ]);
                if (!$check) {
                    http_response_code(409);
                    $db->response([
                        "status"=>409,
                        "error"=>"Что-то пошло не так, невозможно отменить лайк!"
                    ]);
                } else {
                    http_response_code(200);
                    $db->response([
                        "status"=>200,
                        "result"=>[
                            "msg"=>"Лайк отменен",
                            "toggle"=>0
                        ]
                    ]);
                }
            } elseif ($res[0]['mark'] == '0') {
                $check = $db->make("UPDATE `comment_like` SET `id_comment` = ?, `id_author` = ?, `mark` = b? WHERE `comment_like`.`id_like` = ?", [
                    $getIdComment,
                    $id_author,
                    1,
                    $res[0]['id_like']
                ]);
                if (!$check) {
                    http_response_code(409);
                    $db->response([
                        "status"=>409,
                        "error"=>"Что-то пошло не так, невозможно поставить вместо дизлайка лайк!"
                    ]);
                } else {
                    http_response_code(200);
                    $db->response([
                        "status"=>200,
                        "result"=>[
                            "msg"=>"Дизлайк заменился лайком",
                            "toggle"=>1
                        ]
                    ]);
                }
            } else {
                $check = $db->make("UPDATE `comment_like` SET `id_comment` = ?, `id_author` = ?, `mark` = b? WHERE `comment_like`.`id_like` = ?", [
                    $getIdComment,
                    $id_author,
                    1,
                    $res[0]['id_like']
                ]);
                if (!$check) {
                    http_response_code(409);
                    $db->response([
                        "status"=>409,
                        "error"=>"Что-то пошло не так, невозможно поставить лайк!"
                    ]);
                } else {
                    http_response_code(200);
                    $db->response([
                        "status"=>200,
                        "result"=>[
                            "msg"=>"Лайк поставлен",
                            "toggle"=>1
                        ]
                    ]);
                }
            }
        } else {
            $check = $db->make("INSERT INTO `comment_like` (`id_comment`, `id_author`, `mark`) VALUES (?, ?, b?)", [
                $getIdComment,
                $id_author,
                1
            ]);
            if (!$check) {
                http_response_code(409);
                $db->response([
                    "status"=>409,
                    "error"=>"Что-то пошло не так, невозможно поставить лайк!"
                ]);
            } else {
                http_response_code(200);
                $db->response([
                    "status"=>200,
                    "result"=>[
                        "msg"=>"Лайк поставлен",
                        "toggle"=>1
                    ]
                ]);
            }
        }
    } else {
        http_response_code(409);
        $db->response([
            "status"=>409,
            "error"=>"Для того, чтобы поставить лайк комментарию нужно авторизоваться!"
        ]);
    }