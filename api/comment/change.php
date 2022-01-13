<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    $idComment = validate($_POST['idComment']);
    $change_comment = validate($_POST['change_comment']);
    $id_author = $_SESSION['id'];

    if (!$id_author) {
        $db->response(409, "Только зарегистрированные пользователи могут изменять свой комментарий!");
    }
    if (!$idComment) {
        $db->response(409, "Не удалось изменить не существующий комментарий!");
    }
    if (!$change_comment || strlen($change_comment) < 1) {
        $db->response(409, "Измененный комментарий не должен быть пустым!");
    }
    if (strlen($change_comment) > 2000) { // fixme
        $db->response(409, "Измененный комментарий не может быть больше 1000 символов!");
    }

    $res = $db->make("UPDATE `comments` SET `id_author` = ?, `body_comment` = ? WHERE `comments`.`id_comment` = ?", [
        $id_author,
        $change_comment,
        $idComment
    ]);

    if ($res) {
        $db->response(200, "Ответный комментарий добавлен!");
    } else {
        $db->response(409, "Что-то пошло не так, невозможно изменить комментарий!");
    }

