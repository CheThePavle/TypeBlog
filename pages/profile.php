<?php
    require_once "components/header.php";
    require_once "src/usecases/profile.php";
?>

    <div class="tab-pane active" id="tab1">
        <div class="bg_shapka_lk">
            <br><br>
            <div class="prfole_block">
                <div class="ava_block">
                    <div class="block_ava_img">
                        <img class="ava_img" src="https://vjoy.cc/wp-content/uploads/2019/12/2-1.png" alt="avatarka">
                    </div>
                    <input id="btn-auth" type="submit" class="btn btn-primary change_profile_btn" value="Подписаться">
                </div>
                <div class="info_user">
                    <br><br>
                    <h2><?= $getUser[0]['login'] ?></h2>
                    <p>Статус</p>
                </div>
            </div>
        </div>
        <div class="bg_shapka2_lk"></div>
        <div>
            <h2>Публичные посты</h2>
            <hr>
            <div class="posts"></div>
            <div class="msg"></div>
            <?php require_once "components/postLoader.php"?>
        </div>
    </div>

<?php
    require_once "components/footer.php";
?>
