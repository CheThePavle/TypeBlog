<?php
    require_once "src/db.php";
    require_once "src/validate.php";

    $idPost = validate($urls[1]);

    $getInfoPost = [];
    $getTagsStr = '';
    if ($_SESSION['code_role'] === 'admin' || $_SESSION['code_role'] === 'moderator') {
        $getInfoPost = $db->query("SELECT heading, description, main_folder, main_img_type FROM post WHERE id_post = ?;", [$idPost]);
        if ($getInfoPost) {
            $getTags = $db->query("SELECT t.name_tag FROM post_tag as pt JOIN tag as t ON t.id_tag = pt.id_tag WHERE pt.id_post = ?;", [$idPost]);
            $getInfoPost = $getInfoPost[0];
            $getTagsArry = [];
            foreach ($getTags as $el) {
                array_push($getTagsArry, $el['name_tag']);
            }
            $getTagsStr = implode(', ', $getTagsArry);
        }
    } else {
        $getInfoPost = $db->query("SELECT heading, description, main_folder, main_img_type FROM post WHERE id_post = ? AND id_status = ?;", [$idPost, 2]);
        if ($getInfoPost) {
            $getTags = $db->query("SELECT t.name_tag FROM post_tag as pt JOIN tag as t ON t.id_tag = pt.id_tag WHERE pt.id_post = ?;", [$idPost]);
            $getInfoPost = $getInfoPost[0];
            $getTagsArry = [];
            foreach ($getTags as $el) {
                array_push($getTagsArry, $el['name_tag']);
            }
            $getTagsStr = implode(', ', $getTagsArry);
        }
    }
