<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";
    require_once "../../src/RDir.php";
    require_once "../../src/isBase64.php";

    $id_creator = $_SESSION['id'];
    $heading = validate($_POST['heading']);
    $description = validate($_POST['description']);
    $category = validate($_POST['category']);
    $body = json_decode($_POST['body'], true);
    $img_main = $_FILES['image'];
    $tags = explode(",", $_POST['tags']);

    if (!$id_creator) {
        $db->response(409, "Вы должны быть авторизованы, чтобы создать пост!");
    }
    if (!$heading) {
        $db->response(409, "Название обязательное поле!");
    }
    if (iconv_strlen($heading) > 200) {
        $db->response(409, "Название не может быть больше 200 символов!");
    }
    if (iconv_strlen($heading) < 6) {
        $db->response(409, "Название не может быть меньше 6 символов!");
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
    if (!$img_main) {
        $db->response(409, "Главное изображение должно быть загружено!");
    }

    if ($category) {
        $resCategory = $db->query("SELECT * FROM `category` WHERE id_category = ?;", [$category]);
        if (!$resCategory) {
            $db->response(409, "Такой категории не существует!");
        }
    } else {
        $db->response(409, "Выберите категорию поста!");
    }

    if (!$img_main['type']) {
        $db->response(409, "Загрузите главную картинку для поста!");
    }
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

    $uniqUrl = md5(uniqid(rand(), true));
    $newDir = '../../data/posts/'.$uniqUrl;
    if(!is_dir($newDir)){
        mkdir($newDir);
    } else {
        $db->response(409, "Что-то пошло не так невозможно создать папку для изображений! Попробуйте перезапустить страницу");
    }

    if (!move_uploaded_file($img_main['tmp_name'], $newDir.'/main_img.'.$typeMainImg)) {
        RDir($newDir);
        $db->response(409, "Что-то пошло не так главное изображение не было загружено на сервер!");
    }

    //$body['uniqUrl'] = $uniqUrl;
    //$body['typeMainImg'] = $typeMainImg;
    $body['storage'] = 0; // место хранения данных поста

//            добавить проверку на размер изображений в simple-image ??? а если изображение будет превышать столько то ... то урезаем качество до нужного размера
    foreach ($body["blocks"] as &$el) {
        if ($el["type"] == "image") {
            // возможно можно упростить этот момент
            $getUrlBase64 = explode(",", $el["data"]["url"]);
            $getTypeImgBase64 = explode("/", $getUrlBase64[0]);
            $getTypeEndImgBase64 = explode(";", $getTypeImgBase64[1]);

//            Придумать, как вы сжимать картинку base64 до определенного размера
//            $getSizeImg = mb_strlen($el["data"]["url"], '8bit');
//            while ($getSizeImg > 5000000) {
//
//            }

            if ($getUrlBase64[0] === 'data:image/'.$getTypeEndImgBase64[0].';base64') {
                if (isBase64($getUrlBase64[1])) {
                    file_put_contents('../../data/posts/'.$uniqUrl.'/'.$el["id"].'.'.$getTypeEndImgBase64[0], file_get_contents($el["data"]["url"]));
                    $el['data']['url'] = $uniqUrl.'/'.$el["id"].'.'.$getTypeEndImgBase64[0];
                } else {
                    $db->response(409, "Изображение не является base64!");
                }
            }
        }
    }

//  можно ли обойти ограничение на размер body?
    $getSizeBody = mb_strlen(json_encode($body), '8bit');
    if ($getSizeBody > 27000) {
        RDir($newDir);
        $db->response(409, "Превышен лимит содержания поста! (текущий ".$getSizeBody.", лимит 27000)");
    }

    $res = $db->make("INSERT INTO `post` (`id_category`, `id_creator`, `heading`, `description`, `main_folder`, `main_img_type`, `body`, `id_status`, `updated_at`) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);",
        [
            $category,
            $id_creator,
            $heading,
            $description,
            $uniqUrl,
            $typeMainImg,
            json_encode($body),
            $_SESSION['code_role'] === 'admin' || $_SESSION['code_role'] === 'moderator' ? 2 : 1,
            NULL
        ]);
    if (!$res) {
        RDir($newDir);
        $db->response(409, "Что-то пошло не так, невозможно создать пост!");
    }

//  возможно есть лучше способ записи в бд
    $getIdPost = $db->query("SELECT `id_post` FROM `post` WHERE id_post=(SELECT max(`id_post`) FROM `post` WHERE post.id_creator = ?);", [$id_creator]);

    if ($tags) {
        if (count($tags) > 10) {
            $n = array_keys($tags);
            $count = array_search(9, $n);
            $tags = array_slice($tags, 0, $count + 1, true);
        }
        foreach ($tags as $el) {
            $resTag = $db->query("SELECT `id_tag`, `name_tag` FROM `tag` WHERE `name_tag` = ?;", [$el]);
            if (!$resTag) {
                $resTag = $db->make("INSERT INTO `tag` (`name_tag`) VALUES (?);", [$el]);
                $resTag = $db->query("SELECT `id_tag` FROM `tag` WHERE id_tag=(SELECT max(`id_tag`) FROM `tag`);");
            }
            $db->make("INSERT INTO `post_tag` (`id_post`, `id_tag`) VALUES (?, ?);", [$getIdPost[0]['id_post'], $resTag[0]['id_tag']]);
        }
    }

    $db->response(200, "Пост был успешно создан");
