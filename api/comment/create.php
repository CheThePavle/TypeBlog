<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    $idPost = validate($_POST['idPost']);
    $comment = validate($_POST['comment']);
    $id_author = $_SESSION['id'];

    if (!$idPost) {
        $db->response(409, "Комментарий не может быть добавлен на этот пост!");
    }
    if (!$comment || strlen($comment) < 1) {
        $db->response(409, "Комментарий не должен быть пустым!");
    }
    if (strlen($comment) > 2000) { // fixme
        $db->response(409, "Комментарий не может быть больше 1000 символов!");
    }
    if (!$id_author) {
        $db->response(409, "Только зарегистрированные пользователи могут добавлять комментарий!");
    }

    $created_at = date("Y-m-d H:i:s");

    $res = $db->make("INSERT INTO `comments` (`id_post`, `id_author`, `body_comment`, `created_at`) VALUES (?, ?, ?, ?)", [
        $idPost,
        $id_author,
        $comment,
        $created_at
    ]);

    $db->response(200);
