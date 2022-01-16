<?php
    require_once "src/db.php";

    $getList = $db->query("SELECT * FROM tag ORDER BY id_tag DESC;");
