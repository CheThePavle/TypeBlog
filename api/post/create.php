<?php
    require_once "../../src/db.php";
    require_once "../../src/fieldValidation.php"; //validate()
    require_once "../../src/RDir.php";
    require_once "../../src/isBase64.php";
    require_once "../ajax.php"; //IS_AJAX

        $heading = validate($_POST['heading']);
        $description = validate($_POST['description']);
        $category = validate($_POST['category']);
        $body = json_decode($_POST['body'], true);
        $img_main = $_FILES['image'];
        $tags = explode(",", $_POST['tags']);

        // доп. проверка полей
        if (!$heading) {
            http_response_code(409);
            $db->response([
                "status"=>409,
                "error"=>"Название обязательное поле!"
            ]);
        }
        if (strlen($heading) > 200) {
            http_response_code(409);
            $db->response([
                "status"=>409,
                "error"=>"Название не может быть больше 200 символов!"
            ]);
        }
        if (strlen($heading) < 6) {
            http_response_code(409);
            $db->response([
                "status"=>409,
                "error"=>"Название не может быть меньше 6 символов!"
            ]);
        }
        if (!$description) {
            http_response_code(409);
            $db->response([
                "status"=>409,
                "error"=>"Описание обязательное поле!"
            ]);
        }
        if (strlen($description) > 1000) {
            http_response_code(409);
            $db->response([
                "status"=>409,
                "error"=>"Описание не может быть больше 1000 символов!"
            ]);
        }
        if (strlen($description) < 20) {
            http_response_code(409);
            $db->response([
                "status"=>409,
                "error"=>"Описание не может быть меньше 20 символов!"
            ]);
        }
        if (!$body["blocks"]) {
            http_response_code(409);
            $db->response([
                "status"=>409,
                "error"=>"Содержание должно быть заполнено!"
            ]);
        }
        if (!$img_main) {
            http_response_code(409);
            $db->response([
                "status"=>409,
                "error"=>"Главное изображение должно быть загружено!"
            ]);
        }

// записать теги в бд!!! и присвоить их этому посту
//      добавить статусы у тегов, публичные не публичные теги, т.к. пост будет проходить модерацию или временно записывать в body
//        if ($tags) {
//            if (count($tags) > 10) {
//                http_response_code(409);
//                $db->response([
//                    "status"=>409,
//                    "error"=>"Можно задать не больше 10 тегов!"
//                ]);
//            }
//            foreach ($tags as $el) {
//                $resTag = $db->query("SELECT `name_tag` FROM `tag` WHERE `name_tag` = ?", [$el]);
//                if (!$resTag) {
//                    $resTag = $db->make("INSERT INTO `tag` (`name_tag`) VALUES (?)", [$el]);
//                }
//            }
//        }

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
            // добавить проверку на размер и тд

            if (!$img_main['type']) {
                http_response_code(409);
                $db->response([
                    "status"=>409,
                    "error"=>"Загрузите главную картинку для поста!"
                ]);
            }
            if ($img_main['size'] > 5000000) {
                http_response_code(409);
                $db->response([
                    "status"=>409,
                    "error"=>"Главное изображение не должно превышать 5 Мегабайт!"
                ]);
            }
            switch ($img_main['type']) {
                case 'image/jpeg':
                case 'image/pjpeg':
                    $typeMainImg = 'jpeg';
                    break;
                case 'image/png':
                case 'image/x-png':
                    $typeMainImg = 'png';
                    break;
                case 'image/gif':
                    $typeMainImg = 'gif';
                    break;
                default:
                    http_response_code(409);
                    $db->response([
                        "status"=>409,
                        "error"=>"Расширение изображения должно быть png, jpg или gif!"
                    ]);
            }

            $uniqUrl = md5(uniqid(rand(), true));
            $newDir = '../../jsonData/posts/'.$uniqUrl;
            if(!is_dir($newDir)){
                mkdir($newDir);
            } else {
                http_response_code(409);
                $db->response([
                    "status"=>409,
                    "error"=>"Что-то пошло не так невозможно создать папку для изображений! Попробуйте перезапустить страницу"
                ]);
            }

            if (!move_uploaded_file($img_main['tmp_name'], $newDir.'/main_img.'.$typeMainImg)) {
                RDir($newDir);
                http_response_code(409);
                $db->response([
                    "status"=>409,
                    "error"=>"Что-то пошло не так главное изображение не было загружено на сервер!"
                ]);
            }

            $body['uniqUrl'] = $uniqUrl;
            $body['typeMainImg'] = $typeMainImg;

//            добавить проверку на размер изображений в simple-image ??? а если изображение будет превышать столько то ... то урезаем качество до нужного размера
            foreach ($body["blocks"] as &$el) {
                if ($el["type"] == "image") {
                    // возможно можно упростить этот момент
                    $getUrlBase64 = explode(",", $el["data"]["url"]);
                    $getTypeImgBase64 = explode("/", $getUrlBase64[0]);
                    $getTypeEndImgBase64 = explode(";", $getTypeImgBase64[1]);
                    if ($getUrlBase64[0] === 'data:image/'.$getTypeEndImgBase64[0].';base64') {
                        if (is_base64($getUrlBase64[1])) {
                            file_put_contents('../../jsonData/posts/'.$uniqUrl.'/'.$el["id"].'.'.$getTypeEndImgBase64[0], file_get_contents($el["data"]["url"]));
                            $el['data']['url'] = $uniqUrl.'/'.$el["id"].'.'.$getTypeEndImgBase64[0];
                        } else {
                            http_response_code(409);
                            $db->response([
                                "status"=>409,
                                "error"=>'Изображение не является base64!'
                            ]);
                        }
                    }
                }
            }

            $id_creator = $_SESSION['id'];
            $created_at = date("Y-m-d H:i:s");
            $updated_at = date("Y-m-d H:i:s");
            $res = $db->make("INSERT INTO `post` (`id_category`, `id_creator`, `heading`, `description`, `body`, `id_status`, `created_at`, `updated_at`) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $category,
                $id_creator,
                $heading,
                $description,
                json_encode($body),
                1, // status поста сменить на другой
                $created_at,
                $updated_at

            ]);
            if (!$res) {
                RDir($newDir);
                http_response_code(409);
                $db->response([
                    "status"=>409,
                    "error"=>"Что-то пошло не так, невозможно записать данные в бд!"
                ]);
            }

            http_response_code(200);
            $db->response([
                "status"=>200
            ]);

//            $body = str_replace('&quot;', '"', $body);
// mb_strlen(json_encode($body), '8bit'); // размер тела поста