$(function() {
    if (window?.XMLHttpRequest) {
        const activeBtn = (bool) => {
            $('.btn-send').prop('disabled', bool);
        }

        $("#login").keyup(function () {
            let l = $(this).val();

            $(".errorLogin").html('');
            activeBtn(false);

            if (l?.length  < 5) {
                $(".errorLogin").html('<div class="alert alert-warning">Логин не может быть < 5 символов!</div>')
                activeBtn(true);
            }
            if (l?.length  > 20) {
                $(".errorLogin").html('<div class="alert alert-warning">Логин не может быть > 20 символов!</div>')
                activeBtn(true);
            }
        })

        $("#password").keyup(function () {
            let l = $(this).val();

            $(".errorPassword").html('');
            activeBtn(false);

            if (l?.length  < 5) {
                $(".errorPassword").html('<div class="alert alert-warning">Пароль не может быть < 6 символов!</div>')
                activeBtn(true);
            }
            if (l?.length  > 20) {
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
                        window.location.href = '/lk';
                    }, 2000);
                },
                error: function (data) {
                    activeBtn(true);
                    msg.html(`<div class="alert alert-danger">${data?.responseJSON?.error}</div>`);
                    setTimeout(function(){
                        window.location.href = '/authorization';
                    }, 2000);
                }
            })

        });
    } else {
        alert('Ваша версия браузера устарела, поддержки не будет)');
    }
});
