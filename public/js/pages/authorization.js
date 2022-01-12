$(function() {
    if (window?.XMLHttpRequest) {
        const activeBtn = (disabled) => {
            $('.btn-send').prop('disabled', disabled);
        }

        $('#formAuth').submit(function (e) {
            e.preventDefault();
            activeBtn(true);

            let th = $(this);
            let msg = $('.msg');

            $.ajax({
                url: '../api/user/auth.php',
                type: 'POST',
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
