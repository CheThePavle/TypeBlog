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
                        <img class="ava_img" src="<?= $getUser[0]['folder'] && $getUser[0]['img_type'] ? '../data/users/' . $getUser[0]['folder'] . '/' . 'avatar.' . $getUser[0]['img_type'] : '../public/imgs/profile.png' ?>" alt="avatar">
                    </div>
                    <input id="btn-auth" type="submit" class="btn btn-danger complaint_profile_btn" value="Пожаловаться">
                </div>
                <div class="info_user">
                    <br><br>
                    <h2><?= $getUser[0]['login'] ?></h2>
                    <p>Статус: <?= $getUser[0]['name'] ?></p>
                    <p><?= $getUser[0]['about'] ?></p>
                </div>
            </div>
            <div class="modal modal_complaint" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Жалоба на пользователя</h5>
                        </div>
                        <form id="formComplaint">
                            <div class="modal-body">
                                <textarea maxlength="200" id="body_complaint" name="body_complaint" rows="1" placeholder="Что случилось?"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary send_complaint">Отправить</button>
                                <button type="button" class="btn btn-secondary closed_complaint" data-dismiss="modal">Отмена</button>
                            </div>
                        </form>
                    </div>
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
