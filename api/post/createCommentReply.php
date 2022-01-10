<?php
    require_once "../../src/db.php";
    require_once "../../src/fieldValidation.php"; //validate()
    require_once "../ajax.php";

    $idPost = validate($_POST['idPost']);
    $parent_comment = validate($_POST['parent_comment']);
    $id_parent_comment = validate($_POST['idParentComment']);
    $id_author = $_SESSION['id'];

    // доп. проверка полей
    if (!$idPost) {
        http_response_code(409);
        $db->response([
            "status"=>409,
            "error"=>"Ответный комментарий не может быть добавлен к этому комментарию!"
        ]);
    }
    if (!$id_parent_comment) {
        http_response_code(409);
        $db->response([
            "status"=>409,
            "error"=>"Ответный комментарий не может быть добавлен к не существующему комментарию!"
        ]);
    }
    if (!$parent_comment || strlen($parent_comment) < 1) {
        http_response_code(409);
        $db->response([
            "status"=>409,
            "error"=>"Ответный комментарий не должен быть пустым!"
        ]);
    }
    if (strlen($parent_comment) > 2000) { // fixme
        http_response_code(409);
        $db->response([
            "status"=>409,
            "error"=>"Ответный комментарий не может быть больше 1000 символов!"
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

    $res = $db->make("INSERT INTO `comments` (`id_post`, `id_author`, `body_comment`, `id_parent_comment`, `created_at`) VALUES (?, ?, ?, ?, ?)", [
        $idPost,
        $id_author,
        $parent_comment,
        $id_parent_comment,
        $created_at
    ]);

    http_response_code(200);
    $db->response([
        "status"=>200
    ]);
