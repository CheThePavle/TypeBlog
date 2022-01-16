<?php
    require_once "src/db.php";

    $getList = $db->query("SELECT * FROM category ORDER BY id_category DESC;");
