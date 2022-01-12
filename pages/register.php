<?php
    require_once "components/header.php";
?>

    <h1 class="text-center">Регистрация</h1>

    <form id="formReg">
        <div class="controls">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="login">Логин *</label>
                        <input id="login" type="text" name="login" class="form-control" placeholder="Придумайте логин *" required="required">
                        <div class="errorLogin"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Электронная почта *</label>
                        <input id="email" type="text" name="email" class="form-control" placeholder="Ваша эл. почта *" required="required">
                        <div class="errorEmail"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password">Пароль *</label>
                        <input id="password" type="password" name="password" class="form-control" placeholder="Придумайте пароль *" required="required">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password2">Повторите пароль *</label>
                        <input id="password2" type="password" name="password2" class="form-control" placeholder="Введите еще раз ваш пароль *" required="required">
                    </div>
                </div>
            </div>
            <div class="errorPassword"></div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="about">О себе</label>
                        <textarea id="about" name="about" class="form-control" placeholder="Не обязательное поле" rows="4"></textarea>
                    </div>
                </div>

                <div class="form-check" style="margin-left: 15px">
                    <input class="form-check-input" name="checkbox" type="checkbox" value="true" required="required" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        Принять <a>пользовательское соглашение</a>
                    </label>
                </div>

                <div class="verifyEmail"></div>

                <div class="col-md-12">
                    <input type="submit" class="btn btn-success btn-send" value="Зарегистрироваться">
                </div>
            </div>
        </div>
        <br>
        <div class="msg"></div>
    </form>

<?php
    require_once "components/footer.php";
?>
