<?php
	require_once "components/header.php";
?>

<div class="container">
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2">
            <h1 class="text-center">Регистрация</h1>

            <form id="formReg" role="form">
                <div class="controls">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="login">Логин *</label>
                                <input id="login" type="text" name="login" class="form-control" placeholder="Придумайте логин *" required="required">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Электронная почта *</label>
                                <input id="email" type="text" name="email" class="form-control" placeholder="Ваша эл. почта *" required="required">
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

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="about">О себе</label>
                                <textarea id="about" name="about" class="form-control" placeholder="Не обязательное поле" rows="4" required="required"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <input type="submit" class="btn btn-success btn-send" value="Зарегистрироваться">
                        </div>
                    </div>
                </div>
                <br>
                <div class="msg"></div>
            </form>

        </div><!-- /.col-lg-8 col-lg-offset-2 -->
    </div> <!-- /.row-->
</div> <!-- /.container-->

<script type="text/javascript">
    $('#formReg').submit(function (e) {
        e.preventDefault();
        let th = $(this);
        let msg = $('.msg');
        let btn = th.find('.btn');

        $.ajax({
            url: '../api/user/create.php',
            type: 'POST',
            data: th.serialize(),
            success: function (data) {
                alert(data);
                msg.html('<div class="alert alert-success">Успешная регистрация!</div>');
            },
            error: function () {
                msg.html('<div class="alert alert-danger">Что-то пошло не так(</div>');
            }
        })
    });
</script>

<?php
    require_once "components/footer.php";
?>
