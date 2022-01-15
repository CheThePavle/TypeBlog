<?php
    require_once "src/db.php";

    $getList = $db->query("SELECT p.id_post, p.id_creator, p.heading, p.created_at, u.id_user, u.login FROM post as p 
                                JOIN user as u ON p.id_creator = u.id_user WHERE p.id_status = ? ORDER BY p.id_post DESC;", [1]);
