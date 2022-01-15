<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    $idPost = validate($_POST['idPost']);
    $parent_comment = validate($_POST['parent_comment']);
    $id_parent_comment = validate($_POST['idParentComment']);
    $id_author = $_SESSION['id'];

    if (!$id_author) {
        $db->response(409, "Только зарегистрированные пользователи могут отвечать на комментарии!");
    }
    if (!$idPost) {
        $db->response(409, "Ответный комментарий не может быть добавлен к этому комментарию у этого поста!");
    }
    if (!$id_parent_comment) {
        $db->response(409, "Ответный комментарий не может быть добавлен к не существующему комментарию!");
    }
    if (!$parent_comment || iconv_strlen($parent_comment) < 1) {
        $db->response(409, "Ответный комментарий не должен быть пустым!");
    }
    if (iconv_strlen($parent_comment) > 1000) {
        $db->response(409, "Ответный комментарий не может быть больше 1000 символов!");
    }

    $res = $db->make("INSERT INTO `comments` (`id_post`, `id_author`, `body_comment`, `id_parent_comment`, `updated_at`) VALUES (?, ?, ?, ?, ?);", [
        $idPost,
        $id_author,
        $parent_comment,
        $id_parent_comment,
        NULL
    ]);

    $db->response(200, "Ответный комментарий добавлен!");
