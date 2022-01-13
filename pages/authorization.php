<?php
	require_once "components/header.php";
?>

    <h1 class="text-center">Вход</h1>

    <form id="formAuth">
        <div class="controls">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="login">Логин <span class="required">*</span></label>
                        <input maxlength="25" id="login" type="text" name="login" class="form-control" placeholder="Ваш логин *" required="required">
                        <div class="errorLogin"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password">Пароль <span class="required">*</span></label>
                        <input maxlength="32" id="password" type="password" name="password" class="form-control" placeholder="Ваш пароль *" required="required">
                        <div class="errorPassword"></div>
                    </div>
                </div>
            </div>

            <div class="form-check">
                <input class="form-check-input" name="checkbox" type="checkbox" value="true" id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault" style="cursor: pointer">
                    Запомнить меня
                </label>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <input id="btn-auth" type="submit" class="btn btn-success btn-send" value="Войти">
                </div>
            </div>
        </div>
        <br>
        <div class="msg"></div>
    </form>

<?php
    require_once "components/footer.php";
?>
