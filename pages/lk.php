<?php
    require_once "components/header.php";
?>

<div class="tabbable"> <!-- Only required for left/right tabs -->
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab1" data-toggle="tab">Профиль</a></li>
        <li><a href="#tab2" data-toggle="tab">Создать пост</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab1">
            <p>Вывод данных по пользователю</p>
        </div>
        <div class="tab-pane" id="tab2">
            <p>Создание поста</p>
        </div>
    </div>
</div>

<?php
    require_once "components/footer.php";
?>
