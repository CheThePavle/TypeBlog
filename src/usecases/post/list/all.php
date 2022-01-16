<?php
    require_once "src/db.php";

    $getList = $db->query("SELECT p.id_post, p.id_creator, p.heading, p.created_at, u.id_user, u.login, ps.name, ps.code 
                                FROM post as p JOIN post_status as ps ON ps.id_status = p.id_status 
                                JOIN user as u ON p.id_creator = u.id_user ORDER BY p.id_post DESC;");
