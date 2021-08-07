<?php
    require_once "../../src/db.php";
    require_once "../../src/fieldValidation.php"; //validate()

    session_start();

    $heading = validate($_POST['heading']);
    $body = validate($_POST['body']);

    if ((strlen($heading) > 30) || (strlen($heading) < 6)) {
        $db->response([
            "status"=>http_response_code(409),
            "error"=>"Заголовок не может быть < 6 и > 30 символов"
        ]);
    }

    if ($heading && $body) {
        $id_creator = $_SESSION['id'];
        $created_at = date("Y-m-d H:i:s");
        $updated_at = date("Y-m-d H:i:s");
        $nameFile = uniqid() . time();
        $res = $db->query("INSERT INTO `post` (`id_creator`, `heading`, `body`, `id_status`, `created_at`, `updated_at`) 
                                VALUES (?, ?, ?, ?, ?, ?)",
        [
            $id_creator,
            $heading,
            "jsonData/posts/".$nameFile.".json",
            1,
            $created_at,
            $updated_at

        ]);
        // добавить условие, если запрос не выполнился
         $body = str_replace('&quot;', '"', $body);
         file_put_contents('../../jsonData/posts/'.$nameFile.'.json', $body);
    } else {$db->response([
        "status"=>http_response_code(409),
        "error"=>"Данные получены не все!"
    ]);}
