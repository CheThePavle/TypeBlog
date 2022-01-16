$(function() {
    if (window?.XMLHttpRequest) {
        let complaintsHTML = $(".body_complaints");
        let notify = $(".notify");

        complaintsHTML.on( "click", ".more", function() {
            let getId = $(this).data('id');
            $('.row' + getId).toggleClass('collapse');
            $('.glyphicon' + getId).toggleClass('glyphicon-plus glyphicon-minus');
        });

        complaintsHTML.on( "click", ".blocked", function() {
            let getHTML = $(this).closest('.id_complaint');
            let id = getHTML.data('id');

            $.ajax({
                url: '/api/user/blockedComplaint.php',
                method: 'POST',
                data: {
                    id
                },
                success: function (d) {
                    $(".row"+id).remove();
                    getHTML.remove();
                    notify.html(`<div class="alert alert-success">${d?.result?.msg}</div>`);
                    setTimeout(function () {
                        notify.html(``);
                    }, 3000)
                },
                error: function (d) {
                    notify.html(`<div class="alert alert-danger">${d?.responseJSON?.result?.msg}</div>`);
                    setTimeout(function () {
                        notify.html(``);
                    }, 3000)
                }
            })
        });
        complaintsHTML.on( "click", ".rejected", function() {
            let getHTML = $(this).closest('.id_complaint');
            let id = getHTML.data('id');

            $.ajax({
                url: '/api/user/rejectedComplaint.php',
                method: 'POST',
                data: {
                    id
                },
                success: function (d) {
                    $(".row"+id).remove();
                    getHTML.remove();
                    notify.html(`<div class="alert alert-success">${d?.result?.msg}</div>`);
                    setTimeout(function () {
                        notify.html(``);
                    }, 3000)
                },
                error: function (d) {
                    notify.html(`<div class="alert alert-danger">${d?.responseJSON?.result?.msg}</div>`);
                    setTimeout(function () {
                        notify.html(``);
                    }, 3000)
                }
            })
        });
    } else {
        alert('Ваша версия браузера устарела, поддержки не будет)');
    }
});
