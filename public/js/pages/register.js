$(function() {
    if (window?.XMLHttpRequest) {
        const activeBtn = (disabled) => {
            $('.btn-send').prop('disabled', disabled);
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

            if (p?.length  < 6) {
                $(".errorPassword").html('<div class="alert alert-warning">Пароль не может быть < 6 символов!</div>')
                activeBtn(true);
            }
            if (p?.length  > 20) {
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

            if (p2?.length  < 6) {
                $(".errorPassword").html('<div class="alert alert-warning">Пароль не может быть < 6 символов!</div>')
                activeBtn(true);
            }
            if (p2?.length  > 20) {
                $(".errorPassword").html('<div class="alert alert-warning">Пароль не может быть > 20 символов!</div>')
                activeBtn(true);
            }
        })

        $("#login").keyup(function() {
            let th = $(this);
            let val = th.val();
            let msg = $('.errorLogin');
            msg.html('');

            if (val?.length < 5) {
                msg.html('<div class="alert alert-warning">Логин не может быть меньше 5 символов!</div>')
                activeBtn(true);
            }
            if (val?.length > 25) {
                msg.html('<div class="alert alert-warning">Логин не может быть больше 25 символов!</div>')
                activeBtn(true);
            }

            activeBtn(false);
            // добавить условия, чтоб статусы не спамили в консоль
            $.ajax({
                url: '../api/user/findLogin.php',
                type: 'POST',
                data: th.serialize(),
                error: function (d) {
                    activeBtn(true);
                    msg.html(`<div class="alert alert-warning">${d?.responseJSON?.result?.msg}</div>`);
                }
            })
        });

        $("#email").keyup(function() {
            let th = $(this);
            let val = th.val();
            let msg = $('.errorEmail');
            msg.html('');

            if (val?.length < 1) {
                msg.html('<div class="alert alert-warning">Заполните почту!</div>')
                activeBtn(true);
            }
            if (val?.length > 100) {
                msg.html('<div class="alert alert-warning">Слишком длинная почта!</div>')
                activeBtn(true);
            }

            activeBtn(false);
            // добавить условия, чтоб статусы не спамили в консоль
            $.ajax({
                url: '../api/user/findEmail.php',
                type: 'POST',
                data: th.serialize(),
                error: function (d) {
                    activeBtn(true);
                    msg.html(`<div class="alert alert-warning">${d?.responseJSON?.result?.msg}</div>`);
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
                    if (data?.verifyEmail) {
                        activeBtn(false);
                        $('.verifyEmail').html(`
                        <div class="col-md-6">
                            <div class="form-group">
                                <br><br>
                                <label for="code">Код подтверждения из письма *</label>
                                <input id="code" type="text" name="code" class="form-control" placeholder="Введите код *" required="required">
                                <div class="errorCode"></div>
                            </div>
                        </div>
                    `);
                        msg.html(`<div class="alert alert-success">${data?.verifyEmail}</div>`);
                        setTimeout(function(){
                            msg.html(``);
                        }, 3000);
                    } else {
                        activeBtn(true);
                        msg.html(`<div class="alert alert-success">${data?.msg}</div>`);
                        setTimeout(function(){
                            window.location.href = '/authorization';
                        }, 3000);
                    }
                },
                error: function (data) {
                    activeBtn(true);
                    msg.html(`<div class="alert alert-danger">${data?.responseJSON?.error}</div>`);
                    setTimeout(function(){
                        window.location.href = '/register';
                    }, 2000);
                }
            })
        });
    } else {
        alert('Ваша версия браузера устарела, поддержки не будет)');
    }
});
