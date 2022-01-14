<?php
    require_once "components/header.php";
    require_once "src/usecases/lk.php"; //$getAbout
?>

    <div class="tabbable">
        <ul class="nav nav-tabs">
            <li class="active"><a href="/lk" data-toggle="tab">Профиль</a></li>
            <li><a href="/post/create">Создать пост</a></li>
            <?php
                if ($_SESSION['code_role'] === 'admin' || $_SESSION['code_role'] === 'moderator') {
                    echo '<li><a href="/post/list">Список постов</a></li>';
                }
            ?>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active">
                <div class="bg_shapka_lk">
                    <br><br>
                    <div class="prfole_block">
                        <div class="ava_block">
                            <div class="block_ava_img">
                                <img class="ava_img" src="../public/imgs/profile.png" alt="avatar">
                                <form id="formAvatar">
                                    <button type="button" class="btn btn-success href_avatar">Изменить аватар</button>
                                </form>
                            </div>
                            <input id="btn-auth" type="submit" class="btn btn-primary change_profile_btn" value="Редактировать">
                        </div>
                        <div class="info_user">
                            <br><br>
                            <h2><?= $_SESSION['login'] ?></h2>
                            <p>Статус: <?= $_SESSION['name_role'] ?></p>
                            <p><?= $getAbout ?></p>
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

<?php
    require_once "components/footer.php";
?>
