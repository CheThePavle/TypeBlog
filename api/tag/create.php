<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    if ($_SESSION['code_role'] === 'admin') {
        $nameCategory = validate($_POST['name_category']);

        $res = $db->make("INSERT INTO `category` (`name_category`) VALUES (?);", [$nameCategory]);
        if (!$res) {
            $db->response(409, "INSERT INTO `category` (`id_category`, `name_category`) VALUES ($nameCategory);");
        }

        $db->response(200, "Категория успешно добавлена");
    } else {
        $db->response(409, "Извините нет доступа.");
    }

