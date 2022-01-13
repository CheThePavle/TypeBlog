<?php
    require_once "src/db.php";
    require_once "src/validate.php";

    $getUser = $db->query("SELECT id_user, login FROM `user` WHERE id_user = ?", [validate($urls[1])]);
