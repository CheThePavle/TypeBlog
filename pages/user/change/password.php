<?php
    require_once "components/header.php";
?>

<div class="tabbable">
    <ul class="nav nav-tabs">
        <li><a href="/lk">Профиль</a></li>
        <li><a href="/post/create">Создать пост</a></li>
        <li class="active"><a href="/user/change/password" data-toggle="tab">Изменить пароль</a></li>
        <?php
            if ($_SESSION['code_role'] === 'admin') {
                echo '<li><a href="/post/list/all">Список постов</a></li>';
            }
            if ($_SESSION['code_role'] === 'moderator') {
                echo '<li><a href="/post/list">Отправленные посты</a></li>';
            }
            if ($_SESSION['code_role'] === 'admin' || $_SESSION['code_role'] === 'moderator') {
                echo '<li><a href="/user/complaint">Жалобы на пользователей</a></li>';
            }
            if ($_SESSION['code_role'] === 'admin') {
                echo '<li><a href="/user/list">Список пользователей</a></li>';
                echo '<li><a href="/category/list">Категории</a></li>';
                echo '<li><a href="/tag/list">Теги</a></li>';
            }
        ?>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active">
            <form id="formChangePassword">
                <div class="controls">
                    <div class="row">
                        <br>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Старый пароль <span class="required">*</span></label>
                                <div class="passwordBlock">
                                    <input id="oldPassword" maxlength="32" type="password" name="oldPassword" class="form-control" placeholder="Введите свой старый пароль *" required="required">
                                    <i class="fas fa-eye-slash eyePassword eye1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Новый пароль <span class="required">*</span></label>
                                <div class="passwordBlock">
                                    <input id="newPassword" maxlength="32" type="password" name="newPassword" class="form-control" placeholder="Введите новый пароль *" required="required">
                                    <i class="fas fa-eye-slash eyePassword eye2"></i>
                                </div>
                                <div class="errorNewPassword"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Повторите новый пароль <span class="required">*</span></label>
                                <div class="passwordBlock">
                                    <input id="newPassword2" maxlength="32" type="password" name="newPassword2" class="form-control" placeholder="Введите снова новый пароль *" required="required">
                                    <i class="fas fa-eye-slash eyePassword eye3"></i>
                                </div>
                                <div class="errorNewPassword2"></div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <input type="submit" class="btn btn-success btn_change_password" value="Изменить">
                        </div>
                    </div>
                </div>
            </form>
            <br>
            <div class="msg"></div>
        </div>
    </div>
</div>

<?php
    require_once "components/footer.php";
?>

