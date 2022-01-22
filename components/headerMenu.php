<?php
    $lk = $urls[0] === 'lk' ? 'class="active"' : '';
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
            <a class="navbar-brand" href="/">TypeBlog</a>
        </div>

        <div id="slidemenu">
            <form class="navbar-form navbar-right">
                <div class="form-group" id="block_search">
                    <input type="search" placeholder="Найти" class="form-control" id="searchPostAndUser" maxlength="50">
                    <ul class="resultSearch"></ul>
                </div>
<!--                <button type="submit" class="btn btn-default">Поиск</button>-->
            </form>

            <ul class="nav navbar-nav">
                <li <?=$urls[0] === '' ? 'class="active"' : ''?>><a href="/">Все посты</a></li>
                <li class="randomPost"><a href="#">Случайный пост</a></li>
                <?php
                    if ($_SESSION['id']) {
                        echo '<li '.$lk.'><a href="/lk">Привет, '.$_SESSION['login'].'</a></li>';
                        echo '<li><a href="/api/user/out.php">Выход</a></li>';
                    } else {
                ?>
                        <li <?=$urls[0] === 'authorization' ? 'class="active"' : ''?>><a href="/authorization">Вход</a></li>
                        <li <?=$urls[0] === 'register' ? 'class="active"' : ''?>><a href="/register">Регистрация</a></li>
                <?php
                    }
                ?>
<!--                <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown">Ещё <b class="caret"></b></a>-->
<!--                    <ul class="dropdown-menu">-->
<!--                        <li><a href="#">test1</a></li>-->
<!--                        <li><a href="#">test2</a></li>-->
<!--                        <li><a href="#">test3</a></li>-->
<!--                    </ul>-->
<!--                </li>-->
            </ul>
        </div>
    </div>
</div>