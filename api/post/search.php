<?php
    require_once "../../src/db.php";

    $numberStart = $_GET['numberStart'];
    $numberEnd = $_GET['numberEnd'];

    $res = $db->query("SELECT * FROM `post` WHERE id >= ? AND id <= ?", [$numberStart, $numberEnd]);

    $jsonData = [];
    foreach ($res as $el) {
        array_push($jsonData, $el['body']);
    }

//    $getJson = [];
//    foreach ($jsonData as $el) {
//        $get = file_get_contents('../../jsonData/posts/'.$el.'.json'.'');
//        array_push($getJson, json_decode($get));
//    }

    echo json_encode($res);

//    echo json_encode([
//        "dbData"=>$res,
//        "jsonData"=>$getJson
//    ]);

    // $test = file_get_contents('../../jsonData/posts/610eaf42e0e901628352322.json');
    // echo $number;