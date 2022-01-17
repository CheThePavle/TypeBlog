<?php
    if ($_SERVER['REQUEST_URI'] === '/index.php') {header("Location: /");}

    $url = (isset($_GET['q'])) ? $_GET['q'] : '';
    $url = rtrim($url, '/');
    $urls = explode('/', $url);

    //print_r(session_save_path());

    if ($urls[0] === '' && sizeof($urls) == 1){
        $setStyleAndScript = '
            <title>TypeBlog - Главная</title>
            <link rel="stylesheet" href="/public/styles/pages/index.css">
            <script src="/public/js/pages/index.js"></script>
            <link rel="stylesheet" href="/public/styles/components/postLoader.css">
        ';
        include_once 'pages/index.php'; // Главная страница
        exit();
    }

    if ($urls[0] === 'register' && sizeof($urls) == 1){
        if ($_SESSION['id']) {header("Location: /");exit();}
        $setStyleAndScript = '
            <title>TypeBlog - Регистрация</title>
            <link rel="stylesheet" href="/public/styles/pages/register.css">
            <script src="/public/js/pages/register.js"></script>
        ';
        include_once 'pages/register.php'; // Регистрация страница
        exit();
    }

    if ($urls[0] === 'authorization' && sizeof($urls) == 1){
        if ($_SESSION['id']) {header("Location: /");exit();}
        $setStyleAndScript = '
            <title>TypeBlog - Авторизация</title>
            <link rel="stylesheet" href="/public/styles/pages/authorization.css">
            <script src="/public/js/pages/authorization.js"></script>
        ';
        include_once 'pages/authorization.php'; // Вход страница
        exit();
    }

    if ($urls[0] === 'lk' && sizeof($urls) == 1){
        if (!$_SESSION['id']) {header("Location: /");exit();}
        $setStyleAndScript = '
            <title>TypeBlog - Личный кабинет</title>
            <link rel="stylesheet" href="/public/styles/pages/lk.css">
            <script src="/public/js/pages/lk.js"></script>
            <link rel="stylesheet" href="/public/styles/components/postLoader.css">
        ';
        include_once 'pages/lk.php'; // ЛК страница
        exit();
    }

    if ($urls[0] === 'post' && $urls[1] === 'create' && sizeof($urls) == 2){
        if (!$_SESSION['id']) {header("Location: /");exit();}
        $setStyleAndScript = '
            <title>TypeBlog - Создание поста</title>
            <script src="/libs/EditorJS/editorjs.js"></script>
            <script src="/libs/EditorJS/blocks/simple-image.js"></script>
            <link rel="stylesheet" href="/libs/EditorJS/blocks/simple-image.css">
            <script src="/libs/EditorJS/blocks/header.js"></script>
            <script src="/libs/EditorJS/blocks/warning.js"></script>
            <script src="/libs/EditorJS/blocks/marker.js"></script>
            <script src="/libs/EditorJS/blocks/delimiter.js"></script>
            <script src="/libs/EditorJS/blocks/table.js"></script>
            <script src="/libs/EditorJS/blocks/quote.js"></script>
            <script src="/libs/EditorJS/blocks/inline-code.js"></script>
            <script src="/libs/EditorJS/blocks/nested-list.js"></script>
            <script src="/libs/EditorJS/blocks/underline.js"></script>
            <script src="/libs/EditorJS/blocks/paragraph.js"></script>
            <script src="/libs/EditorJS/blocks/text-variant-tune.js"></script>
            <script src="/libs/EditorJS/blocks/checklist.js"></script>
                    
            <link rel="stylesheet" href="/libs/taggingJS/tag-basic-style.css">
            <script src="/libs/taggingJS/tagging.min.js"></script>
                    
            <link rel="stylesheet" href="/public/styles/pages/post/create.css">
            <script src="/public/js/pages/post/create.js"></script>
        ';
        include_once 'pages/post/create.php'; // Создание поста страница
        exit();
    }

    if ($urls[0] === 'user' && $urls[1] === 'change' && $urls[2] === 'password' && sizeof($urls) == 3){
        if (!$_SESSION['id']) {header("Location: /");exit();}
        $setStyleAndScript = '
                <title>TypeBlog - Изменение пароля</title>
                <link rel="stylesheet" href="/public/styles/pages/user/change/password.css">
                <script src="/public/js/pages/user/change/password.js"></script>
            ';
        include_once 'pages/user/change/password.php'; // Изменение пароля страница
        exit();
    }

    if ($urls[0] === 'post' && $urls[1] === 'change' && sizeof($urls) == 3){
        if (!$_SESSION['id']) {
            header("Location: /");exit();
        }
        if ($_SESSION['code_role'] === 'admin' || $_SESSION['code_role'] === 'moderator') {
            $setStyleAndScript = '
                <title>TypeBlog - Изменение поста</title>
                <script src="/libs/EditorJS/editorjs.js"></script>
                <script src="/libs/EditorJS/blocks/simple-image.js"></script>
                <link rel="stylesheet" href="/libs/EditorJS/blocks/simple-image.css">
                <script src="/libs/EditorJS/blocks/header.js"></script>
                <script src="/libs/EditorJS/blocks/warning.js"></script>
                <script src="/libs/EditorJS/blocks/marker.js"></script>
                <script src="/libs/EditorJS/blocks/delimiter.js"></script>
                <script src="/libs/EditorJS/blocks/table.js"></script>
                <script src="/libs/EditorJS/blocks/quote.js"></script>
                <script src="/libs/EditorJS/blocks/inline-code.js"></script>
                <script src="/libs/EditorJS/blocks/nested-list.js"></script>
                <script src="/libs/EditorJS/blocks/underline.js"></script>
                <script src="/libs/EditorJS/blocks/paragraph.js"></script>
                <script src="/libs/EditorJS/blocks/text-variant-tune.js"></script>
                <script src="/libs/EditorJS/blocks/checklist.js"></script>

                <link rel="stylesheet" href="/libs/taggingJS/tag-basic-style.css">
                <script src="/libs/taggingJS/tagging.min.js"></script>

                <link rel="stylesheet" href="/public/styles/pages/post/change.css">
                <script src="/public/js/pages/post/change.js"></script>
            ';
            include_once 'pages/post/change.php'; // Изменение поста страница для модеров и админов
            exit();
        } else {
            header("Location: /");exit();
        }
    }

    if ($urls[0] === 'post' && $urls[1] === 'list' && sizeof($urls) == 2){
        if (!$_SESSION['id']) {
            header("Location: /");exit();
        }
        if ($_SESSION['code_role'] === 'admin' || $_SESSION['code_role'] === 'moderator') {
            $setStyleAndScript = '
                <title>TypeBlog - Список отправленных постов</title>
                <link rel="stylesheet" href="/public/styles/pages/post/list.css">
                <script src="/public/js/pages/post/list.js"></script>
            ';
            include_once 'pages/post/list.php'; // Список отправленных постов страница для модеров и админов
            exit();
        } else {
            header("Location: /");exit();
        }
    }

    if ($urls[0] === 'post' && $urls[1] === 'list' && $urls[2] === 'all' && sizeof($urls) == 3){
        if (!$_SESSION['id']) {
            header("Location: /");exit();
        }
        if ($_SESSION['code_role'] === 'admin') {
            $setStyleAndScript = '
                    <title>TypeBlog - Список постов</title>
                    <link rel="stylesheet" href="/public/styles/pages/post/list/all.css">
                    <script src="/public/js/pages/post/list/all.js"></script>
                ';
            include_once 'pages/post/list/all.php'; // Список постов страница для админов
            exit();
        } else {
            header("Location: /");exit();
        }
    }

    if ($urls[0] === 'user' && $urls[1] === 'list' && sizeof($urls) == 2){
        if (!$_SESSION['id']) {
            header("Location: /");exit();
        }
        if ($_SESSION['code_role'] === 'admin') {
            $setStyleAndScript = '
                    <title>TypeBlog - Список пользователей</title>
                    <link rel="stylesheet" href="/public/styles/pages/user/list.css">
                    <script src="/public/js/pages/user/list.js"></script>
                ';
            include_once 'pages/user/list.php'; // Список пользователей страница для админов
            exit();
        } else {
            header("Location: /");exit();
        }
    }

    if ($urls[0] === 'user' && $urls[1] === 'complaint' && sizeof($urls) == 2){
        if (!$_SESSION['id']) {
            header("Location: /");exit();
        }
        if ($_SESSION['code_role'] === 'admin' || $_SESSION['code_role'] === 'moderator') {
            $setStyleAndScript = '
                    <title>TypeBlog - Жалобы на пользователей</title>
                    <link rel="stylesheet" href="/public/styles/pages/user/complaint.css">
                    <script src="/public/js/pages/user/complaint.js"></script>
                ';
            include_once 'pages/user/complaint.php'; // Жалоба на пользователей страница для админов и модеров
            exit();
        } else {
            header("Location: /");exit();
        }
    }

    if ($urls[0] === 'category' && $urls[1] === 'list' && sizeof($urls) == 2){
        if (!$_SESSION['id']) {
            header("Location: /");exit();
        }
        if ($_SESSION['code_role'] === 'admin') {
            $setStyleAndScript = '
                        <title>TypeBlog - Категории</title>
                        <link rel="stylesheet" href="/public/styles/pages/category/list.css">
                        <script src="/public/js/pages/category/list.js"></script>
                    ';
            include_once 'pages/category/list.php'; // Категории страница для админов
            exit();
        } else {
            header("Location: /");exit();
        }
    }

    if ($urls[0] === 'tag' && $urls[1] === 'list' && sizeof($urls) == 2){
        if (!$_SESSION['id']) {
            header("Location: /");exit();
        }
        if ($_SESSION['code_role'] === 'admin') {
            $setStyleAndScript = '
                            <title>TypeBlog - Теги</title>
                            <link rel="stylesheet" href="/public/styles/pages/tag/list.css">
                            <script src="/public/js/pages/tag/list.js"></script>
                        ';
            include_once 'pages/tag/list.php'; // Теги страница для админов
            exit();
        } else {
            header("Location: /");exit();
        }
    }

    if ($urls[0] === 'post' && sizeof($urls) == 2){
        $setStyleAndScript = '
            <title>TypeBlog - (вывести название поста)</title>
            <!-- для Facebook и ВКонтакте (протокол Open Graph) -->
            <meta property="og:title" content="Заголовок поста" />
            <meta property="og:description" content="Описание поста" />
            <meta property="og:image" content="Ссылка на изображение" />
            <meta property="og:url" content="Адрес страницы" />
            <meta property="og:site_name" content="Название сайта" />
            <!-- для Twitter -->
            <meta name="twitter:site" content="Название сайта" />
            <meta name="twitter:title" content="Заголовок поста" />
            <meta name="twitter:description" content="Описание поста" />
            <script src="/libs/EditorJS/editorjs.js"></script>
            <script src="/libs/EditorJS/blocks/simple-image.js"></script>
            <link rel="stylesheet" href="/libs/EditorJS/blocks/simple-image.css">
            <script src="/libs/EditorJS/blocks/header.js"></script>
            <script src="/libs/EditorJS/blocks/warning.js"></script>
            <script src="/libs/EditorJS/blocks/marker.js"></script>
            <script src="/libs/EditorJS/blocks/delimiter.js"></script>
            <script src="/libs/EditorJS/blocks/table.js"></script>
            <script src="/libs/EditorJS/blocks/quote.js"></script>
            <script src="/libs/EditorJS/blocks/inline-code.js"></script>
            <script src="/libs/EditorJS/blocks/nested-list.js"></script>
            <script src="/libs/EditorJS/blocks/underline.js"></script>
            <script src="/libs/EditorJS/blocks/paragraph.js"></script>
            <script src="/libs/EditorJS/blocks/text-variant-tune.js"></script>
            <script src="/libs/EditorJS/blocks/checklist.js"></script>
            
            <script src="https://yastatic.net/share2/share.js"></script>
                    
            <link rel="stylesheet" href="/public/styles/pages/post.css">
            <script src="/public/js/pages/post.js"></script>
            
            <link rel="stylesheet" href="/public/styles/components/comments.css">
        ';
        include_once 'pages/post.php'; // Пост страница
        exit();
    }

    if ($urls[0] === 'profile' && sizeof($urls) == 2){
        $setStyleAndScript = '
            <title>TypeBlog - Профиль пользователя (логин)</title>
            <link rel="stylesheet" href="/public/styles/pages/profile.css">
            <script src="/public/js/pages/profile.js"></script>
            <link rel="stylesheet" href="/public/styles/components/postLoader.css">
        ';
        include_once 'pages/profile.php'; // Профиль пользователей страница
        exit();
    }

    $setStyleAndScript = '
        <title>TypeBlog - 404 page not found</title>
    ';
    http_response_code(404);
    include_once 'pages/404.php'; // 404 страница
