<?php
    require_once "../../src/db.php";

    $number = $_GET['number'];

    echo json_encode($db->query("SELECT * FROM `post` WHERE id >= ? AND id <= ?", [$number, $number]));



    // $test = file_get_contents('../../jsonData/posts/610eaf42e0e901628352322.json');
    // echo $number;