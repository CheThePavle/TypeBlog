<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    $idPost = validate($_POST['idPost']);
    $parent_comment = validate($_POST['parent_comment']);
    $id_parent_comment = validate($_POST['idParentComment']);
    $id_author = $_SESSION['id'];

    if (!$idPost) {
        $db->response(409, "Ответный комментарий не может быть добавлен к этому комментарию у этого поста!");
    }
    if (!$id_parent_comment) {
        $db->response(409, "Ответный комментарий не может быть добавлен к не существующему комментарию!");
    }
    if (!$parent_comment || strlen($parent_comment) < 1) {
        $db->response(409, "Ответный комментарий не должен быть пустым!");
    }
    if (strlen($parent_comment) > 2000) { // fixme
        $db->response(409, "Ответный комментарий не может быть больше 1000 символов!");
    }
    if (!$id_author) {
        $db->response(409, "Только зарегистрированные пользователи могут добавлять комментарий!");
    }

    $created_at = date("Y-m-d H:i:s");

    $res = $db->make("INSERT INTO `comments` (`id_post`, `id_author`, `body_comment`, `id_parent_comment`, `created_at`) VALUES (?, ?, ?, ?, ?)", [
        $idPost,
        $id_author,
        $parent_comment,
        $id_parent_comment,
        $created_at
    ]);

    $db->response(200);
