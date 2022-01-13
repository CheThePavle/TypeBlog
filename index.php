<?php
    if ($_SERVER['REQUEST_URI'] === '/index.php') {header("Location: /");}

    $url = (isset($_GET['q'])) ? $_GET['q'] : '';
    $url = rtrim($url, '/');
    $urls = explode('/', $url);

    if ($urls[0] === '' && sizeof($urls) == 1){
        $setStyleAndScript = '
            <title>TypeBlog - Главная</title>
            <link rel="stylesheet" href="../public/styles/pages/index.css">
            <script src="../public/js/pages/index.js"></script>
            <link rel="stylesheet" href="../public/styles/components/postLoader.css">
        ';
        include_once 'pages/index.php'; // Главная страница
        exit();
    }

    if ($urls[0] === 'register' && sizeof($urls) == 1){
        if ($_SESSION['id']) {header("Location: /");}
        $setStyleAndScript = '
            <title>TypeBlog - Регистрация</title>
            <script src="../public/js/pages/register.js"></script>
        ';
        include_once 'pages/register.php'; // Регистрация страница
        exit();
    }

    if ($urls[0] === 'authorization' && sizeof($urls) == 1){
        if ($_SESSION['id']) {header("Location: /");}
        $setStyleAndScript = '
            <title>TypeBlog - Авторизация</title>
            <script src="../public/js/pages/authorization.js"></script>
        ';
        include_once 'pages/authorization.php'; // Вход страница
        exit();
    }

    if ($urls[0] === 'lk' && sizeof($urls) == 1){
        if (!$_SESSION['id']) {header("Location: /");}
        $setStyleAndScript = '
            <title>TypeBlog - Личный кабинет</title>
            <link rel="stylesheet" href="../public/styles/pages/lk.css">
            <script src="../public/js/pages/lk.js"></script>
            <link rel="stylesheet" href="../public/styles/components/postLoader.css">
        ';
        include_once 'pages/lk.php'; // ЛК страница
        exit();
    }

    if ($urls[0] === 'post' && $urls[1] === 'create' && sizeof($urls) == 2){
        if (!$_SESSION['id']) {header("Location: /");}
        $setStyleAndScript = '
            <title>TypeBlog - Создание поста</title>
            <script src="../libs/EditorJS/editorjs.js"></script>
            <script src="../libs/EditorJS/blocks/simple-image.js"></script>
            <link rel="stylesheet" href="../libs/EditorJS/blocks/simple-image.css">
            <script src="../libs/EditorJS/blocks/header.js"></script>
            <script src="../libs/EditorJS/blocks/warning.js"></script>
            <script src="../libs/EditorJS/blocks/marker.js"></script>
            <script src="../libs/EditorJS/blocks/delimiter.js"></script>
            <script src="../libs/EditorJS/blocks/table.js"></script>
            <script src="../libs/EditorJS/blocks/quote.js"></script>
            <script src="../libs/EditorJS/blocks/inline-code.js"></script>
            <script src="../libs/EditorJS/blocks/nested-list.js"></script>
            <script src="../libs/EditorJS/blocks/underline.js"></script>
            <script src="../libs/EditorJS/blocks/paragraph.js"></script>
            <script src="../libs/EditorJS/blocks/text-variant-tune.js"></script>
            <script src="../libs/EditorJS/blocks/checklist.js"></script>
                    
            <link rel="stylesheet" href="../libs/taggingJS/tag-basic-style.css">
            <script src="../libs/taggingJS/tagging.min.js"></script>
                    
            <link rel="stylesheet" href="../public/styles/pages/post/create.css">
            <script src="../public/js/pages/post/create.js"></script>
        ';
        include_once 'pages/post/create.php'; // Создание поста страница
        exit();
    }

    if ($urls[0] === 'post' && $urls[1] === 'change' && sizeof($urls) == 2){
        if (!$_SESSION['id']) {header("Location: /");}
        $setStyleAndScript = '
            <title>TypeBlog - Изменение поста</title>
        ';
        include_once 'pages/post/change.php'; // Изменение поста страница
        exit();
    }

    if ($urls[0] === 'post' && sizeof($urls) == 2){
        $setStyleAndScript = '
            <title>TypeBlog - (вывести название поста)</title>
            <script src="../libs/EditorJS/editorjs.js"></script>
            <script src="../libs/EditorJS/blocks/simple-image.js"></script>
            <link rel="stylesheet" href="../libs/EditorJS/blocks/simple-image.css">
            <script src="../libs/EditorJS/blocks/header.js"></script>
            <script src="../libs/EditorJS/blocks/warning.js"></script>
            <script src="../libs/EditorJS/blocks/marker.js"></script>
            <script src="../libs/EditorJS/blocks/delimiter.js"></script>
            <script src="../libs/EditorJS/blocks/table.js"></script>
            <script src="../libs/EditorJS/blocks/quote.js"></script>
            <script src="../libs/EditorJS/blocks/inline-code.js"></script>
            <script src="../libs/EditorJS/blocks/nested-list.js"></script>
            <script src="../libs/EditorJS/blocks/underline.js"></script>
            <script src="../libs/EditorJS/blocks/paragraph.js"></script>
            <script src="../libs/EditorJS/blocks/text-variant-tune.js"></script>
            <script src="../libs/EditorJS/blocks/checklist.js"></script>
                    
            <link rel="stylesheet" href="../public/styles/pages/post.css">
            <script src="../public/js/pages/post.js"></script>
            
            <link rel="stylesheet" href="../public/styles/components/comments.css">
        ';
        include_once 'pages/post.php'; // Пост страница
        exit();
    }

    if ($urls[0] === 'profile' && sizeof($urls) == 2){
        $setStyleAndScript = '
            <title>TypeBlog - Профиль пользователя (логин)</title>
            <link rel="stylesheet" href="../public/styles/pages/profile.css">
            <script src="../public/js/pages/profile.js"></script>
            <link rel="stylesheet" href="../public/styles/components/postLoader.css">
        ';
        include_once 'pages/profile.php'; // Профиль пользователей страница
        exit();
    }

    $setStyleAndScript = '
        <title>TypeBlog - 404 page not found</title>
    ';
    http_response_code(404);
    include_once 'pages/404.php'; // 404 страница
