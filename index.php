<?php
//    // Временные роуты для тестирования проекта Postman
//    require_once "src/db.php";
//    $db = new db();
//    //для api ответов
//    if ($urls[0] === 'api' && $urls[1] === 'user' && $urls[2] === 'create'){include_once 'api/user/create.php';exit();}

//    define('MyConst', TRUE);
//    if(!defined('MyConst')) {
//        die('Direct access not permitted');
//    }

    if ($_SERVER['REQUEST_URI'] === '/index.php') {
        header("Location: /");
    }

    // Разбираем url

    $url = (isset($_GET['q'])) ? $_GET['q'] : '';
    $url = rtrim($url, '/'); // Удаляет "/" из конца строки
    $urls = explode('/', $url); // Разбивает строку с помощью разделителя "/"

    //Переменная $urls - доступна во всех файлах

    // Определяем роутер
    if ($urls[0] === '' && sizeof($urls) == 1){
        include_once 'pages/index.php'; // Главная страница
        exit();
    }

    if ($urls[0] === 'register' && sizeof($urls) == 1){
        if ($_SESSION['id']) {header("Location: /");};
        include_once 'pages/register.php'; // Регистрация страница
        exit();
    }

    if ($urls[0] === 'authorization' && sizeof($urls) == 1){
        if ($_SESSION['id']) {header("Location: /");};
        include_once 'pages/authorization.php'; // Вход страница
        exit();
    }

    if ($urls[0] === 'lk' && sizeof($urls) == 1){
        if (!$_SESSION['id']) {header("Location: /");};
        include_once 'pages/lk.php'; // ЛК страница
        exit();
    }

//    if ($urls[0] === 'post' && $urls[1] === 'create' && sizeof($urls) == 2){
//        if (!$_SESSION['id']) {header("Location: /");};
//        include_once 'pages/post/create.php'; // Создание поста страница
//        exit();
//    }

    if ($urls[0] === 'post' && $urls[1] === 'change' && sizeof($urls) == 2){
        if (!$_SESSION['id']) {header("Location: /");};
        include_once 'pages/post/change.php'; // Изменение поста страница
        exit();
    }

    if ($urls[0] === 'post' && sizeof($urls) == 2){
        include_once 'pages/post.php'; // Пост страница
        exit();
    }

    if ($urls[0] === 'posts' && sizeof($urls) == 1){
        include_once 'pages/posts.php'; // Посты страница
        exit();
    }

    if ($urls[0] === 'profile' && sizeof($urls) == 2){
        include_once 'pages/profile.php'; // Профиль пользователей страница
        exit();
    }

    http_response_code(404);
    include_once 'pages/404.php'; // 404 страница
