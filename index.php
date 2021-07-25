<?php
//    // Временные роуты для тестирования проекта Postman
//    require_once "src/db.php";
//    $db = new db();
//    //для api ответов
//    if ($urls[0] === 'api' && $urls[1] === 'user' && $urls[2] === 'create'){include_once 'api/user/create.php';exit();}
    session_start();

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
        if ($_SESSION) {header("Location: /");};
        include_once 'pages/register.php'; // Регистрация страница
        exit();
    }

    if ($urls[0] === 'authorization' && sizeof($urls) == 1){
        if ($_SESSION) {header("Location: /");};
        include_once 'pages/authorization.php'; // Вход страница
        exit();
    }

    http_response_code(404);
	echo "404 page"; //добавить код 404