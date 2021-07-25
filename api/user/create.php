<?php
//echo "<script>window.location.href='https://example.com/final.php';</script>";
    require_once "../../src/db.php";
    $login = $_POST['login'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $about = $_POST['about'];
    echo 1;

    $test = new db();
    $test->make('INSERT INTO `user` (`id`, `email`, `login`, `password`, `about`) VALUES (NULL, ?, ?, ?, ?);', [
        $login,
        $email,
        $password,
        $about
    ]);
