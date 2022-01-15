<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";
    require_once "../../src/RDir.php";
    require_once "../../src/isBase64.php";

    if ($_SESSION['id']) {
        $img = $_FILES['image'];

        if (!$img) {
            $db->response(409, "Изображение должно быть выбрано!");
        }
        if (!$img['type']) {
            $db->response(409, "Выберите картинку для аватарки!");
        }
        if ($img['size'] > 1000000) {
            $db->response(409, "Изображение не должно превышать 1 Мегабайта!");
        }

        switch ($img['type']) {
            case 'image/jpeg':
            case 'image/pjpeg':
                $typeImg = 'jpeg';
                break;
            case 'image/png':
            case 'image/x-png':
                $typeImg = 'png';
                break;
            case 'image/gif':
                $typeImg = 'gif';
                break;
            default:
                $db->response(409, "Расширение изображения должно быть png, jpg или gif!");
        }

        $getFolder = $db->query("SELECT folder FROM user WHERE id_user = ?", [$_SESSION['id']]);
        $getFolder = $getFolder[0]['folder'];
        if ($getFolder) {
            $uniqUrl = $getFolder;
        } else {
            $uniqUrl = md5(uniqid(rand(), true));
        }
        
        $newDir = '../../data/users/'.$uniqUrl;
        if(!is_dir($newDir)){
            mkdir($newDir);
        }

        // изменять разрешение картинки на 200x200
        if (!move_uploaded_file($img['tmp_name'], $newDir.'/avatar.'.$typeImg)) {
            RDir($newDir);
            $db->response(409, "Что-то пошло не так, изображение не было загружено на сервер!");
        }

        $id = $_SESSION['id'];

        $res = $db->make("UPDATE `user` SET `folder` = ?, `img_type` = ? WHERE `user`.`id_user` = ?", [$uniqUrl, $typeImg, $id]);
        if (!$res) {
            RDir($newDir);
            $db->response(409, "Что-то пошло не так, невозможно поменять аватарку!");
        }

        $db->response(200, "Аватар успешно изменен");
    } else {
        $db->response(409, "Вы должно быть авторизованы, чтобы поменять аватарку!");
    }




//function SetImgSize($img, $width, $height, $AspectRatio)
//{
////Создаем изображение в зависимости от типа исходного файла
//// для упрощения, считаю, что расширение соответствует типу файла
//    switch ( strtolower(strrchr($img, '.')) ){
//        case "jpg":
//            $srcImage = @ImageCreateFromJPEG($img);
//            break;
//        case "gif":
//            $srcImage = @ImageCreateFromGIF($img);
//            break;
//        case "png":
//            $srcImage = @ImageCreateFromPNG($img);
//            break;
//        default:
//            return -1;
//    }
//    $srcWidth = ImageSX($srcImage);
//    $srcHeight = ImageSY($srcImage);
//    echo 'Исходная картинка('.$srcWidth.'x'.$srcHeight.'): <b>'.$pURL_name.'</b><br><img src="'.$img.'" />';
//    if(($width < $srcWidth) || ($height > $srcHeight))
//    {   if($AspectRatio){
//        $ratioWidth = $srcWidth/$width;
//        $ratioHeight = $srcHeight/$height;
//        if($ratioWidth < $ratioHeight)
//        {
//            $destWidth = intval($srcWidth/$ratioHeight);
//            $destHeight = $height;
//        }
//        else
//        {
//            $destWidth = $width;
//            $destHeight = intval($srcHeight/$ratioWidth);
//        }
//    }else {
//        $destHeight = $height;
//        $destWidth = $width;}
//        $resImage = ImageCreateTrueColor($destWidth, $destHeight);
//        ImageCopyResampled($resImage, $srcImage, 0, 0, 0, 0, $destWidth, $destHeight, $srcWidth, $srcHeight);
//        unlink($img);// удаляю исходный файл
//        switch ( strtolower(strrchr($img, '.')) ){
//            case "jpg":
//                ImageJPEG($resImage, $img, 100); // 100 - максимальное качество
//                break;
//            case "gif":
//                ImageGIF($resImage, $img);
//                break;
//            case "png":
//                ImagePNG($resImage, $img);
//                break;
//        }
//        ImageDestroy($srcImage);
//        ImageDestroy($resImage);
//        echo "<br>\nИзмененная картинка(".$destWidth.'x'.$destHeight.'):<br><img src="'.$img.'" />';
//    }
//}