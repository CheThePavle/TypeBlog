<?php
    require_once "../../src/db.php"; // class $db
    require_once "../../src/fieldValidation.php"; //validate()
    require_once "../ajax.php";

    $idPost = validate($_POST['idPost']);
    $comment = validate($_POST['comment']);
    $id_author = $_SESSION['id'];

    // доп. проверка полей
    if (!$idPost) {
        http_response_code(409);
        $db->response([
            "status"=>409,
            "error"=>"Комментарий не может быть добавлен на этот пост!"
        ]);
    }
    if (!$comment || strlen($comment) < 1) {
        http_response_code(409);
        $db->response([
            "status"=>409,
            "error"=>"Комментарий не должен быть пустым!"
        ]);
    }
    if (strlen($comment) > 2000) { // fixme
        http_response_code(409);
        $db->response([
            "status"=>409,
            "error"=>"Комментарий не может быть больше 1000 символов!"
        ]);
    }
    if (!$id_author) {
        http_response_code(409);
        $db->response([
            "status"=>409,
            "error"=>"Только зарегистрированные пользователи могут добавлять комментарий!"
        ]);
    }

    $created_at = date("Y-m-d H:i:s");

    $res = $db->make("INSERT INTO `comments` (`id_post`, `id_author`, `body_comment`, `created_at`) VALUES (?, ?, ?, ?)", [
        $idPost,
        $id_author,
        $comment,
        $created_at
    ]);

    http_response_code(200);
    $db->response([
        "status"=>200
    ]);
