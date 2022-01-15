<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";

    $res = $db->query("SELECT id_post FROM post WHERE id_status = ? ORDER BY RAND() LIMIT 1;", [2]);

    $db->response(200, "", $res[0]['id_post']);
