<?php
    require_once "components/header.php";
?>

    <div class="tabbable">
        <ul class="nav nav-tabs">
            <li class="active"><a href="/lk" data-toggle="tab">Профиль</a></li>
            <li><a href="/post/create">Создать пост</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active">
                <div class="bg_shapka_lk">
                    <br><br>
                    <div class="prfole_block">
                        <div class="ava_block">
                            <div class="block_ava_img">
                                <img class="ava_img" src="https://vjoy.cc/wp-content/uploads/2019/12/2-1.png" alt="avatarka">
                            </div>
                            <input id="btn-auth" type="submit" class="btn btn-primary change_profile_btn" value="Редактировать">
                        </div>
                        <div class="info_user">
                            <br><br>
                            <h2><?= $_SESSION['login'] ?></h2>
                            <p>Статус</p>
                        </div>
                    </div>
                </div>
                <div class="bg_shapka2_lk"></div>
                <div>
                    <h2>Мои посты</h2>
                    <hr>
                    <div class="posts"></div>
                    <div class="msg"></div>
                    <?php require_once "components/postLoader.php"?>
                </div>
            </div>
        </div>
    </div>

<!--    <style>@import url("../public/styles/pages/lk.css");</style>-->
<!--    <script src="../public/js/pages/lk.js"></script>-->

<?php
    require_once "components/footer.php";
?>
