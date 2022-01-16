<?php
    require_once "src/db.php";

    $getList = $db->query("SELECT u.id_user, u.email, u.login, u.blocked, u.created_at, ur.name 
                                FROM user as u JOIN user_role as ur ON ur.id_role = u.id_role ORDER BY u.id_user DESC;");
