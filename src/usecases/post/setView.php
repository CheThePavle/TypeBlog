<?php
    $ip = $_SERVER['REMOTE_ADDR'];

//  возможно можно оптимизировать + добавить доп. проверки + попробовать без id_view сравнивать ip - адреса
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        require_once "src/db.php";
        require_once "src/validate.php";

        $getIdPost = validate($urls[1]);
        $idLooked = $_SESSION['id'];
        $created_at = date("Y-m-d H:i:s");
        $checkView = true;

        // учет просмотров только для публичных постов
        $checkPublic = $db->query("SELECT id_post FROM post WHERE id_post = ? AND id_status = ?;", [$getIdPost, 2]);
        if ($checkPublic) {
            if ($idLooked) {
                // запрос нужен для учета просмотра - когда кто-то авторизован и постоянно меняет ip-просмотры или меняет аккаунта с одного ip не будут учитываться
                $checkView = $db->query("SELECT `id_view`, `id_looked`, `id_post` FROM `view` WHERE id_view = 
                                            (SELECT max(id_view) FROM `view` WHERE (id_looked = ? AND id_post = ?) OR (ip_address = INET_ATON(?) AND id_post = ?));",[
                    $idLooked,
                    $getIdPost,
                    $ip,
                    $getIdPost
                ]);
                if (!$checkView[0]['id_looked']) {
                    $db->make("UPDATE `view` SET `id_looked` = ? WHERE `view`.`id_view` = ?;",[$idLooked, $checkView[0]['id_view']]);
                }
            } else {
                $checkView = $db->query("SELECT `id_post`, `ip_address` FROM `view` WHERE ip_address = INET_ATON(?) AND id_post = ?;",[$ip, $getIdPost]);
            }

            if (!$checkView) {
                $res = $db->make("INSERT INTO `view` (`id_post`, `ip_address`, `id_looked`, `created_at`) VALUES (?, INET_ATON(?), ?, ?);", [
                    $getIdPost,
                    $ip,
                    $idLooked,
                    $created_at
                ]);
            }
        }
    }

    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
        require_once "src/db.php";
        require_once "src/validate.php";

        $getIdPost = validate($urls[1]);
        $idLooked = $_SESSION['id'];
        $created_at = date("Y-m-d H:i:s");
        $checkView = true;

        // учет просмотров только для публичных постов
        $checkPublic = $db->query("SELECT id_post FROM post WHERE id_post = ? AND id_status = ?;", [$getIdPost, 2]);
        if ($checkPublic) {
            if ($idLooked) {
                $checkView = $db->query("SELECT `id_view`, `id_looked`, `id_post` FROM `view` WHERE id_view = 
                                            (SELECT max(id_view) FROM `view` WHERE (id_looked = ? AND id_post = ?) OR (ip_address = INET6_ATON(?) AND id_post = ?));",[
                    $idLooked,
                    $getIdPost,
                    $ip,
                    $getIdPost
                ]);
                if (!$checkView[0]['id_looked']) {
                    $db->make("UPDATE `view` SET `id_looked` = ? WHERE `view`.`id_view` = ?;",[$idLooked, $checkView[0]['id_view']]);
                }
            } else {
                $checkView = $db->query("SELECT `id_post`, `ip_address` FROM `view` WHERE ip_address = INET6_ATON(?) AND id_post = ?;",[$ip, $getIdPost]);
            }

            if (!$checkView) {
                $res = $db->make("INSERT INTO `view` (`id_post`, `ip_address`, `id_looked`, `created_at`) VALUES (?, INET6_ATON(?), ?, ?);", [
                    $getIdPost,
                    $ip,
                    $idLooked,
                    $created_at
                ]);
            }
        }
    }
