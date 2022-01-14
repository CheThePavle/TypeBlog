$(function() {
    if (window?.XMLHttpRequest) {
        const activeBtn = (disabled) => {
            $('.btn-send').prop('disabled', disabled);
        }

        $("#login").keyup(function() {
            let th = $(this);
            let val = th.val();
            th.val(normalizeSpace(val));
        });

        $(".eyePassword").click(function () {
            let passwordHTML = $("#password");
            let passwordType = $("#password[type='password']");
            if (passwordType?.length) {
                passwordHTML.attr('type','text');
            } else {
                passwordHTML.attr('type','password');
            }
            $(this).toggleClass('fa-eye-slash fa-eye');
        })

        $("#password").keyup(function() {
            let th = $(this);
            let val = th.val();
            th.val(normalizeSpace(val));
        });

        $('#formAuth').submit(function (e) {
            e.preventDefault();
            activeBtn(true);

            let th = $(this);
            let msg = $('.msg');

            $.ajax({
                url: '../api/user/auth.php',
                method: 'POST',
                data: th.serialize(),
                success: function (d) {
                    activeBtn(true);
                    msg.html(`<div class="alert alert-success">${d?.result?.msg}</div>`);
                    setTimeout(function(){
                        window.location.href = '/lk';
                    }, 2000);
                },
                error: function (d) {
                    activeBtn(true);
                    msg.html(`<div class="alert alert-danger">${d?.responseJSON?.result?.msg}</div>`);
                    setTimeout(function(){
                        activeBtn(false);
                    }, 1000);
                }
            })

        });
    } else {
        alert('Ваша версия браузера устарела, поддержки не будет)');
    }
});
