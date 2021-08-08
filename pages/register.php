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
    const activeBtn = (bool) => {
        $('.btn-send').prop('disabled', bool);
    }

    $("#password").keyup(function () {
        let p = $(this).val();
        let p2 = $("#password2").val();

        $(".errorPassword").html('');
        activeBtn(false);

        if (p !== p2) {
            activeBtn(true);
            $(".errorPassword").html('<div class="alert alert-warning">Пароли не совпадают(</div>')
        }

        if (p.length  < 6) {
            $(".errorPassword").html('<div class="alert alert-warning">Пароль не может быть < 6 символов!</div>')
            activeBtn(true);
        }
        if (p.length  > 20) {
            $(".errorPassword").html('<div class="alert alert-warning">Пароль не может быть > 20 символов!</div>')
            activeBtn(true);
        }
    })

    $("#password2").keyup(function () {
        let p = $("#password").val();
        let p2 = $(this).val();

        $(".errorPassword").html('');
        activeBtn(false);

        if (p !== p2) {
            activeBtn(true);
            $(".errorPassword").html('<div class="alert alert-warning">Пароли не совпадают(</div>')
        }

        if (p2.length  < 6) {
            $(".errorPassword").html('<div class="alert alert-warning">Пароль не может быть < 6 символов!</div>')
            activeBtn(true);
        }
        if (p2.length  > 20) {
            $(".errorPassword").html('<div class="alert alert-warning">Пароль не может быть > 20 символов!</div>')
            activeBtn(true);
        }
    })

    $("#login").keyup(function() {
        let th = $(this);
        let msg = $('.errorLogin');

        $(".errorLogin").html('');
        activeBtn(false);
// добавить условия, что статусы не спамили в консоль
        $.ajax({
            url: '../api/user/findLogin.php',
            type: 'POST',
            data: th.serialize(),
            error: function (data) {
                activeBtn(true);
                msg.html(`<div class="alert alert-warning">${data.responseJSON.error}</div>`);
            }
        })
    });

    $("#email").keyup(function() {
        let th = $(this);
        let msg = $('.errorEmail');

        $(".errorEmail").html('');
        activeBtn(false);
// добавить условия, что статусы не спамили в консоль
        $.ajax({
            url: '../api/user/findEmail.php',
            type: 'POST',
            data: th.serialize(),
            error: function (data) {
                activeBtn(true);
                msg.html(`<div class="alert alert-warning">${data.responseJSON.error}</div>`);
            }
        })
    });

    $('#formReg').submit(function (e) {
        e.preventDefault();
        activeBtn(true);

        let th = $(this);
        let msg = $('.msg');

        $.ajax({
            url: '../api/user/create.php',
            type: 'POST',
            data: th.serialize(),
            success: function (data) {
                activeBtn(true);
                msg.html(`<div class="alert alert-success">${data?.msg}</div>`);
                setTimeout(function(){
                    window.location.href = '/authorization';
                }, 2000);
            },
            error: function (data) {
                activeBtn(true);
                msg.html(`<div class="alert alert-danger">${data.responseJSON.error}</div>`);
                setTimeout(function(){
                    window.location.href = '/';
                }, 2000);
            }
        })
    });
</script>

<?php
require_once "components/footer.php";
?>
