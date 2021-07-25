<?php 
//header("Access-Control-Allow-Origin: http://diplom/");
//header("Access-Control-Allow-Methods: POST");

    // Разбираем url
    $url = (isset($_GET['q'])) ? $_GET['q'] : '';
    $url = rtrim($url, '/'); // Удаляет "/" из конца строки
    $urls = explode('/', $url); // Разбивает строку с помощью разделителя "/"

    // Определяем роутер
    if ($urls[0] === '' && sizeof($urls) == 1){
	include_once 'pages/index.php'; // Главная страница
	exit();
    }

    if ($urls[0] === 'register' && sizeof($urls) == 1){
        include_once 'pages/register.php'; // Регистрация страница
        exit();
    }

    if ($urls[0] === 'authorization' && sizeof($urls) == 1){
        include_once 'pages/authorization.php'; // Вход страница
        exit();
    }

    http_response_code(404);
	echo "404 page"; //добавить код 404
