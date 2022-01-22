<?php
    require_once "src/db.php";

    $getCategory = $db->query("SELECT * FROM `category`;");
    $getTag = $db->query("SELECT * FROM `tag`WHERE public_tag = ?;", [1]);

