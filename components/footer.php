<?php 
	// Разбираем url
	$url = (isset($_GET['q'])) ? $_GET['q'] : '';
	$url = rtrim($url, '/'); // Удаляет "/" из конца строки
	$urls = explode('/', $url); // Разбивает строку с помощью разделителя "/"

	// Определяем роутер
	if ($urls[0] === ''){
	    include_once 'pages/main.php';
	    exit();
	}

	echo "404 page"; //добавить код 404
