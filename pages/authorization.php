<?php
	require_once "components/header.php";
?>
<div class="container">
    <div class="row" >
        <div class="col-lg-8 col-lg-offset-2">
            <h1 class="text-center">Вход</h1>

            <form id="formAuth" role="form">
                <div class="controls">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="login">Логин *</label>
                                <input id="login" type="text" name="login" class="form-control" placeholder="Ваш логин *" required="required">
                                <div class="errorLogin"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Пароль *</label>
                                <input id="password" type="password" name="password" class="form-control" placeholder="Ваш пароль *" required="required">
                                <div class="errorPassword"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" name="checkbox" type="checkbox" value="true" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
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

        </div><!-- /.col-lg-8 col-lg-offset-2 -->
    </div> <!-- /.row-->
</div> <!-- /.container-->

<script type="text/javascript">
    const activeBtn = (bool) => {
        $('.btn-send').prop('disabled', bool);
    }

    $("#login").keyup(function () {
        let l = $(this).val();

        $(".errorLogin").html('');
        activeBtn(false);

        if (l.length  < 5) {
            $(".errorLogin").html('<div class="alert alert-warning">Логин не может быть < 5 символов!</div>')
            activeBtn(true);
        }
        if (l.length  > 20) {
            $(".errorLogin").html('<div class="alert alert-warning">Логин не может быть > 20 символов!</div>')
            activeBtn(true);
        }
    })

    $("#password").keyup(function () {
        let l = $(this).val();

        $(".errorPassword").html('');
        activeBtn(false);

        if (l.length  < 5) {
            $(".errorPassword").html('<div class="alert alert-warning">Пароль не может быть < 6 символов!</div>')
            activeBtn(true);
        }
        if (l.length  > 20) {
            $(".errorPassword").html('<div class="alert alert-warning">Пароль не может быть > 20 символов!</div>')
            activeBtn(true);
        }
    })

    $('#formAuth').submit(function (e) {
        e.preventDefault();
        activeBtn(true);

        let th = $(this);
        let msg = $('.msg');

        $.ajax({
            url: '../api/user/auth.php',
            type: 'POST',
            data: th.serialize(),
            success: function (data) {
                activeBtn(true);
                msg.html(`<div class="alert alert-success">${data?.msg}</div>`);
                setTimeout(function(){
                    window.location.href = '/';
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
