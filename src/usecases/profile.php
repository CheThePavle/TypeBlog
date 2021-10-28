<?php
    require_once "src/db.php";

    $getUser = $db->query("SELECT u.id_user, u.login FROM `user` as u WHERE u.id_user = ?", [$urls[1]]);
