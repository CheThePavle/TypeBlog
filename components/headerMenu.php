<?php
    $lk = $urls[0] === 'lk' ? 'class="active"' : '';
    $pc = $urls[0] === 'post' && $urls[1] === 'create' ? 'class="active"' : '';
?>
<div class="navbar navbar-default" role="navigation" id="slide-nav" style="z-index: 999">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-toggle">
                <span class="sr-only">Открыть меню</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a class="navbar-brand" href="https://bootstraptema.ru/">CheThePavle - Blog</a>
        </div>

        <div id="slidemenu">

            <form class="navbar-form navbar-right" role="form">
                <div class="form-group">
                    <input type="search" placeholder="Найти" class="form-control">
                </div>
                <button type="submit" class="btn btn-default">Поиск</button>
            </form>

            <ul class="nav navbar-nav">
                <li <?=$urls[0] === '' ? 'class="active"' : ''?>><a href="/">Главная</a></li>
                <?php
                    if ($_SESSION['id']) {
                        echo '<li '.$lk.'><a href="/lk">Привет, '.$_SESSION['login'].'</a></li>';
                        echo '<li '.$pc.'><a href="/post/create">Создать пост</a></li>';
                        echo '<li><a href="../api/user/out.php">Выход</a></li>';
                    } else {
                ?>
                        <li <?=$urls[0] === 'authorization' ? 'class="active"' : ''?>><a href="/authorization">Вход</a></li>
                        <li <?=$urls[0] === 'register' ? 'class="active"' : ''?>><a href="/register">Регистрация</a></li>
                <?php
                    }
                ?>
                <li <?=$urls[0] === 'posts' ? 'class="active"' : ''?>><a href="/posts">Все посты</a></li>
                <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown">Ещё <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="https://bootstraptema.ru/stuff/0-0-0-0-1">Заработать</a></li>
                        <li><a href="https://bootstraptema.ru/index/best_for_bootstrap/0-4">Топ 100</a></li>
                        <li><a href="https://bootstraptema.ru/index/feedback/0-3">Связь</a></li>
                    </ul>
                </li>
            </ul>

        </div>
    </div>
</div>
