<?php
    require_once "../../src/db.php";
    require_once "../../src/fieldValidation.php"; //validate()

    session_start();

    $heading = validate($_POST['heading']);
    $description = validate($_POST['description']);

// добавить доп проверку длины $heading
// добавить доп проверку длины $description

    $body = $_POST['body']; //$body = validate($_POST['body']);
    $img_main = $_FILES['image'];


    if ($heading && $description && $body && $img_main) {
        $id_creator = $_SESSION['id'];
        $created_at = date("Y-m-d H:i:s");
        $updated_at = date("Y-m-d H:i:s");
        $nameFile = uniqid() . time();
        $res = $db->query("INSERT INTO `post` (`id_creator`, `heading`, `description`, `body`, `id_status`, `created_at`, `updated_at`) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)",
        [
            $id_creator,
            $heading,
            $description,
            $nameFile,
            1,
            $created_at,
            $updated_at

        ]);
        // добавить условие, если запрос не выполнился
        $body = str_replace('&quot;', '"', $body);
        file_put_contents('../../jsonData/posts/'.$nameFile.'.json', $body);

        // добавить проверка на размер и тд
        switch ($img_main['type']) {
//            case 'image/jpeg':
//            case 'image/pjpeg':
//                $type = 'jpeg';
//                break;

            case 'image/png':
            case 'image/x-png':
                $type = 'png';
                break;

            default:
                $db->response([
                    "status"=>http_response_code(409),
                    "error"=>"Тип изображения должен быть .png или .jpeg!"
                ]);
            }

            if (!move_uploaded_file($img_main['tmp_name'], '../../jsonData/posts/'.$nameFile.'.'.$type)) {
                $db->response([
                    "status"=>http_response_code(409),
                    "error"=>"Файл не загружен на сервер!"
                ]);
            }
    } else {$db->response([
        "status"=>http_response_code(409),
        "error"=>"Данные получены не все!"
    ]);}
