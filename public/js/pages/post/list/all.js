$(function() {
    if (window?.XMLHttpRequest) {
        let postsHTML = $(".body_posts");
        let notify = $(".notify");

        postsHTML.on( "click", ".published", function() {
            let getHTML = $(this).closest('.id_post');
            let id = getHTML.data('id');

            $.ajax({
                url: '../../api/post/published.php',
                method: 'POST',
                data: {
                    id
                },
                success: function (d) {
                    let btnChange = getHTML.find(".publishedTd");
                    btnChange.html("<button type='button' class='btn btn-primary rejected'>Отклонить</button>");
                    btnChange.toggleClass("publishedTd rejectedTd");
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
        postsHTML.on( "click", ".rejected", function() {
            let getHTML = $(this).closest('.id_post');
            let id = getHTML.data('id');

            $.ajax({
                url: '../../api/post/rejected.php',
                method: 'POST',
                data: {
                    id
                },
                success: function (d) {
                    let btnChange = getHTML.find(".rejectedTd");
                    btnChange.html("<button type='button' class='btn btn-success published'>Опубликовать</button>");
                    btnChange.toggleClass("rejectedTd publishedTd");
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
        postsHTML.on( "click", ".deleted", function() {
            let getHTML = $(this).closest('.id_post');
            let id = getHTML.data('id');

            $.ajax({
                url: '../../api/post/delete.php',
                method: 'POST',
                data: {
                    id
                },
                success: function (d) {
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
