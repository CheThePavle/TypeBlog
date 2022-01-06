<?php
    require_once "src/db.php";

    $getCategory = $db->query("SELECT * FROM `category`");
    $getTag = $db->query("SELECT * FROM `tag`WHERE public_tag = 1");


    //    echo "<script>window.location.href='/posts?number=5'</script>";
    //function setGetParam($get, $par) {
    //    return http_build_query(array_merge([$get => $par], $_GET));
    //}
    // echo http_build_query($_GET);