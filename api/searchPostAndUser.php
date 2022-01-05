<?php
    require_once "ajax.php";
    require_once "../src/db.php";
    require_once "../src/fieldValidation.php"; // validate()

    $get = validate($_POST["referal"]);

    $res = $db->query("SELECT `heading` FROM `post` WHERE heading LIKE '%?%'", [$get]);

    foreach ($res as $el) { // &
        echo "\n<li>".$el["heading"]."</li>";
    }
//    while ($row = $db_referal -> fetch_array()) {
//        echo "\n<li>".$row["name"]."</li>";
//    //$row["name"] - имя поля таблицы
//
//    }
