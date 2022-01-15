<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    $idUserAccused = validate($_POST['idUserAccused']);
    $idUserWitness  = $_SESSION['id'];
    $body_complaint = validate($_POST['body_complaint']);

    if (!$idUserWitness) {
        $db->response(409, "Чтобы пожаловаться на пользователя нужно авторизоваться!");
    }
    if (!$body_complaint) {
        $db->response(409, "Жалоба не может быть пустой!");
    }
    if (iconv_strlen($body_complaint) > 200) {
        $db->response(409, "Жалоба не может быть больше 200 символов!");
    }
    if (!$idUserAccused) {
        $db->response(409, "Что-то пошло не так, обвиняемый пользователь не найден!");
    }
    if ($idUserAccused === $idUserWitness) {
        $db->response(409, "Жаловаться на себя же нельзя :)");
    }

    $check = $db->query("SELECT id_complaint FROM user_complaint WHERE id_accused = ? AND id_witness = ?;", [
        $idUserAccused,
        $idUserWitness
    ]);

    if ($check) {
        $db->response(409, "Жалобу можно отправлять один раз на одного пользователя");
    }

    $res = $db->make("INSERT INTO `user_complaint` (`id_accused`, `id_witness`, `body_complaint`) VALUES (?, ?, ?);", [
        $idUserAccused,
        $idUserWitness,
        $body_complaint
    ]);

    if ($res) {
        $db->response(200, "Жалоба успешно отправлена!");
    } else {
        $db->response(409, "Что-то пошло не так, жалоба не отправлена!");
    }
