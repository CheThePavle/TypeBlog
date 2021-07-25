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
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Пароль *</label>
                                <input id="password" type="password" name="password" class="form-control" placeholder="Ваш пароль *" required="required">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <input type="submit" class="btn btn-success btn-send" value="Войти">
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
    $('#formAuth').submit(function (e) {
        e.preventDefault();
        let th = $(this);
        let msg = $('.msg');

        $.ajax({
            url: '../api/user/auth.php',
            type: 'POST',
            data: th.serialize(),
            success: function (data) {
                console.log('das')
                if (data?.error) {
                    msg.html(`<div class="alert alert-warning">${data?.error}</div>`)
                } else {
                    msg.html(`<div class="alert alert-success">${data?.msg}</div>`);
                    setTimeout(function(){
                        window.location.href = '/';
                    }, 2000);
                }

            },
            error: function () {
                msg.html('<div class="alert alert-danger">Что-то пошло не так :(</div>');
            }
        })
    });
</script>
<?php
    require_once "components/footer.php";
?>
