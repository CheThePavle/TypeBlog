<?php
    require_once "src/db.php";

    $getCategory = $db->query("SELECT * FROM `category`");