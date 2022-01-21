<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";
    require_once "../../src/RDir.php";
    require_once "../../src/isBase64.php";

    $idPost = validate($_POST['idPost']);
    $heading = validate($_POST['heading']);
    $description = validate($_POST['description']);
    $category = validate($_POST['category']);
    $body = json_decode($_POST['body'], true);
    $img_main = $_FILES['image'];
    $tags = explode(",", $_POST['tags']);

    if ($_SESSION['code_role'] === 'admin' || $_SESSION['code_role'] === 'moderator') {
        if (!$idPost) {
            $db->response(409, "Пост не передан!");
        }
        if (!$heading) {
            $db->response(409, "Заголовок обязательное поле!");
        }
        if (iconv_strlen($heading) > 200) {
            $db->response(409, "Заголовок не может быть больше 200 символов!");
        }
        if (iconv_strlen($heading) < 6) {
            $db->response(409, "Заголовок не может быть меньше 6 символов!");
        }
        if (!$description) {
            $db->response(409, "Описание обязательное поле!");
        }
        if (iconv_strlen($description) > 1000) {
            $db->response(409, "Описание не может быть больше 1000 символов!");
        }
        if (iconv_strlen($description) < 20) {
            $db->response(409, "Описание не может быть меньше 20 символов!");
        }
        if (!$body["blocks"]) {
            $db->response(409, "Содержание должно быть заполнено!");
        }

        $res = $db->query("SELECT id_status, main_folder, main_img_type FROM post WHERE id_post = ?;", [$idPost]);
        if (!$res) {
            $db->response(409, "Папка с постом не найдена на сервере!");
        }
        if ($res[0]['id_status'] != '1' && $_SESSION['code_role'] === 'moderator') {
            $db->response(409, "Извините, модератору доступно изменять только посты со статусом - Отправлен!");
        }

        if ($category) {
            $resCategory = $db->query("SELECT * FROM `category` WHERE id_category = ?;", [$category]);
            if (!$resCategory) {
                $db->response(409, "Такой категории не существует!");
            }
        } else {
            $db->response(409, "Выберите категорию поста!");
        }

        $uniqUrl = md5(uniqid(rand(), true));
        $newDir = '../../data/posts/'.$uniqUrl;
        if(!is_dir($newDir)){
            mkdir($newDir);
        } else {
            $db->response(409, "Что-то пошло не так невозможно создать папку для изображений! Попробуйте перезапустить страницу");
        }

        $typeMainImg = false;
        if (!$img_main['type']) {
            copy('../../data/posts/' . $res[0]['main_folder'] . '/main_img.' . $res[0]['main_img_type'], $newDir.'/main_img.'.$res[0]['main_img_type']);
        } else {
            if ($img_main['size'] > 5000000) {
                $db->response(409, "Главное изображение не должно превышать 5 Мегабайт!");
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
                    $db->response(409, "Расширение изображения должно быть png, jpg или gif!");
            }

            if (!move_uploaded_file($img_main['tmp_name'], $newDir.'/main_img.'.$typeMainImg)) {
                RDir($newDir);
                $db->response(409, "Что-то пошло не так главное изображение не было загружено на сервер!");
            }
        }

        foreach ($body["blocks"] as &$el) {
            if ($el["type"] == "image") {
                // возможно можно упростить этот момент
                $getUrlBase64 = explode(",", $el["data"]["url"]);
                $getTypeImgBase64 = explode("/", $getUrlBase64[0]);
                $getTypeEndImgBase64 = explode(";", $getTypeImgBase64[1]);

                if ($getUrlBase64[0] === 'data:image/'.$getTypeEndImgBase64[0].';base64') {
                    if (isBase64($getUrlBase64[1])) {
                        file_put_contents('../../data/posts/'.$uniqUrl.'/'.$el["id"].'.'.$getTypeEndImgBase64[0], file_get_contents($el["data"]["url"]));
                        // расположение временное, изменить потом при выводе поста
                        $el['data']['url'] = "/data/posts/" . $uniqUrl.'/'.$el["id"].'.'.$getTypeEndImgBase64[0];
                    } else {
                        $db->response(409, "Изображение не является base64!");
                    }
                }
            }
        }

//        $getSizeBody = mb_strlen(json_encode($body), '8bit');
//        if ($getSizeBody > 27000) {
//            RDir($newDir);
//            $db->response(409, "Превышен лимит содержания поста! (текущий ".$getSizeBody.", лимит 27000)");
//        }

        $change = $db->make("UPDATE `post` SET `id_category` = ?, `heading` = ?, `description` = ?, `main_folder` = ?, `main_img_type` = ?, `body` = ? 
                                    WHERE `post`.`id_post` = ?;",
        [
            $category,
            $heading,
            $description,
            $uniqUrl,
            $typeMainImg ? $typeMainImg : $res[0]['main_img_type'],
            json_encode($body),
            $idPost
        ]);
        if (!$change) {
            RDir($newDir);
            $db->response(409, "Что-то пошло не так, невозможно изменить пост");
        }

        $db->make("DELETE FROM `post_tag` WHERE `post_tag`.`id_post` = ?", [$idPost]);
        if ($tags) {
            if (count($tags) > 10) {
                $n = array_keys($tags);
                $count = array_search(9, $n);
                $tags = array_slice($tags, 0, $count + 1, true);
            }
            foreach ($tags as $el) {
                $resTag = $db->query("SELECT `id_tag`, `name_tag` FROM `tag` WHERE `name_tag` = ?;", [$el]);
//                $checkTag = $db->query("SELECT * FROM post_tag WHERE id_post = ? AND id_tag = ?;", [$resTag[0]['id_tag'], $idPost]);
                if (!$resTag ) {
                    $resTag = $db->make("INSERT INTO `tag` (`name_tag`) VALUES (?);", [$el]);
                    $resTag = $db->query("SELECT `id_tag` FROM `tag` WHERE id_tag=(SELECT max(`id_tag`) FROM `tag`);");
                }
                $db->make("INSERT INTO `post_tag` (`id_post`, `id_tag`) VALUES (?, ?);", [$idPost, $resTag[0]['id_tag']]);
            }
        }

        RDir('../../data/posts/' . $res[0]['main_folder']);
        $db->response(200, "Пост был успешно изменен");
    } else {
        $db->response(409, "Нет доступа!");
    }



