<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    $idPost = validate($_POST['idPost']);
    $comment = validate($_POST['comment']);
    $id_author = $_SESSION['id'];

    if (!$id_author) {
        $db->response(409, "Только зарегистрированные пользователи могут добавлять комментарий!");
    }
    if (!$idPost) {
        $db->response(409, "Комментарий не может быть добавлен на этот пост!");
    }
    if (!$comment || iconv_strlen($comment) < 1) {
        $db->response(409, "Комментарий не должен быть пустым!");
    }
    if (iconv_strlen($comment) > 1000) {
        $db->response(409, "Комментарий не может быть больше 1000 символов!");
    }

    $res = $db->make("INSERT INTO `comments` (`id_post`, `id_author`, `body_comment`, `updated_at`) VALUES (?, ?, ?, ?)", [
        $idPost,
        $id_author,
        $comment,
        NULL,
    ]);

    $db->response(200, "Комментарий успешно добавлен!");
