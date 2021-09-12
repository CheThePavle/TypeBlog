<?php
    require_once "components/header.php";
    // оптимизировать вкладки
?>

<div class="tabbable"> <!-- Only required for left/right tabs -->
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab1" data-toggle="tab">Профиль</a></li>
        <li><a href="#tab2" data-toggle="tab">Создать пост</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab1">
            <img src="https://freefrontend.com/assets/img/bootstrap-profiles/bootstrap-profile-page-with-widget.jpg" alt="">
        </div>
        <div class="tab-pane" id="tab2">
            <?php require_once "post/create.php"; ?>
        </div>
    </div>
</div>

<?php
    require_once "components/footer.php";
?>
