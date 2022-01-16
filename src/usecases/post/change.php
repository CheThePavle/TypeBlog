<?php
    require_once "src/db.php";
    require_once "src/validate.php";

    $getCategory = $db->query("SELECT * FROM `category`;");

    $getId = validate($urls[2]);
    $getPostInfo = $db->query("SELECT p.heading, p.main_folder, p.main_img_type, p.description, c.id_category, c.name_category 
                                    FROM post as p JOIN category as c ON c.id_category = p.id_category WHERE id_post = ?;", [$getId]);

    $getPostInfo = $getPostInfo[0];
