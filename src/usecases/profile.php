<?php
    require_once "src/db.php";
    require_once "src/validate.php";

    $getUser = $db->query("SELECT u.login, u.about, ur.name, u.folder, u.img_type  FROM user as u JOIN user_role as ur ON ur.id_role = u.id_role WHERE u.id_user = ?;", [validate($urls[1])]);
