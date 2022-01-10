<?php
    require_once "../../src/db.php";
    require_once "../../src/fieldValidation.php"; //validate()
    require_once "../ajax.php";

    $idComment = validate($_POST['idComment']);
    $change_comment = validate($_POST['change_comment']);
    $id_author = $_SESSION['id'];

    // доп. проверка полей
    if (!$idComment) {
        http_response_code(409);
        $db->response([
            "status"=>409,
            "error"=>"Не удалось изменить не существующий комментарий!"
        ]);
    }
    if (!$change_comment || strlen($change_comment) < 1) {
        http_response_code(409);
        $db->response([
            "status"=>409,
            "error"=>"Измененный комментарий не должен быть пустым!"
        ]);
    }
    if (strlen($change_comment) > 2000) { // fixme
        http_response_code(409);
        $db->response([
            "status"=>409,
            "error"=>"Измененный комментарий не может быть больше 1000 символов!"
        ]);
    }
    if (!$id_author) {
        http_response_code(409);
        $db->response([
            "status"=>409,
            "error"=>"Только зарегистрированные пользователи могут изменять свои комментарии!"
        ]);
    }

    $updated_at = date("Y-m-d H:i:s");

    $res = $db->make("UPDATE `comments` SET `id_author` = ?, `body_comment` = ?, `updated_at` = ? WHERE `comments`.`id_comment` = ?", [
        $id_author,
        $change_comment,
        $updated_at,
        $idComment
    ]);

    http_response_code(200);
    $db->response([
        "status"=>200
    ]);
