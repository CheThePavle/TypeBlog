<?php
    require_once "../../src/db.php";
    require_once "../../src/fieldValidation.php"; //validate()
    require_once "../ajax.php"; //IS_AJAX

        $heading = validate($_POST['heading']);
        $description = validate($_POST['description']);

        // добавить доп проверку длины $heading
        // добавить доп проверку длины $description

        $body = $_POST['body']; //$body = validate($_POST['body']);
        $img_main = $_FILES['image'];
        $tags = $pieces = explode(",", $_POST['tags']); // нужно ли формировать массив из строки?

        // записать теги в бд!!! и присвоить их этому посту
//        $db->response([
//            "status"=>409,
//            "error"=>$tags
//        ]);

        if ($category) {
            $resCategory = $db->query("SELECT * FROM `category` WHERE id_category = ?", [$category]);
            if (!$resCategory) {
                http_response_code(409);
                $db->response([
                    "status"=>409,
                    "error"=>"Такой категории не существует!"
                ]);
            }
        } else {
            http_response_code(409);
            $db->response([
                "status"=>409,
                "error"=>"Выберите категорию поста!"
            ]);
        }

        // добавить нормальные проверки, если пост не создан то вывести, если файлы не записаны вывести
        if ($heading && $description && $body && $img_main) {
            $nameFile = uniqid() . time();
            // добавить проверка на размер и тд
            if (!$img_main['type']) {
                http_response_code(409);
                $db->response([
                    "status"=>409,
                    "error"=>"Загрузите главную картинку для поста!"
                ]);
            }
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
                    http_response_code(409);
                    $db->response([
                        "status"=>409,
                        "error"=>"Расширение изображения должно быть .png!"
                    ]);
            }

            if (!move_uploaded_file($img_main['tmp_name'], '../../jsonData/posts/'.$nameFile.'.'.$type)) {
                http_response_code(409);
                $db->response([
                    "status"=>409,
                    "error"=>"Файл не загружен на сервер!"
                ]);
            }

            $id_creator = $_SESSION['id'];
            $created_at = date("Y-m-d H:i:s");
            $updated_at = date("Y-m-d H:i:s");
            $res = $db->query("INSERT INTO `post` (`id_category`, `id_creator`, `heading`, `description`, `body`, `id_status`, `created_at`, `updated_at`) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $category,
                $id_creator,
                $heading,
                $description,
                $nameFile,
                1, // status поста сменить на другой
                $created_at,
                $updated_at

            ]);
            // добавить условие, если запрос не выполнился
            $body = str_replace('&quot;', '"', $body);
            // временное сохранение
            file_put_contents('../../jsonData/posts/'.$nameFile.'.json', $body);
        } else {
            http_response_code(409);
            $db->response([
            "status"=>409,
            "error"=>"Данные получены не все!"
            ]);
        }