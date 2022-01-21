$(function() {
    if (window?.XMLHttpRequest) {
        let usersHTML = $(".body_users");
        let notify = $(".notify");
        let timerId = 0;

        usersHTML.on( "change", ".change_role", function() {
            let getHTML = $(this).closest('.id_user');
            let idUser = getHTML.data('id');
            let idRole = $(this).val();

            $.ajax({
                url: '/api/user/changeRoleById.php',
                method: 'POST',
                data: {
                    idUser,
                    idRole
                },
                success: function (d) {
                    clearTimeout(timerId);
                    notify.html(`<div class="alert alert-success">${d?.result?.msg}</div>`);
                    timerId = setTimeout(function () {
                        notify.html(``);
                    }, 3000)
                },
                error: function (d) {
                    clearTimeout(timerId);
                    notify.html(`<div class="alert alert-danger">${d?.responseJSON?.result?.msg}</div>`);
                    timerId = setTimeout(function () {
                        notify.html(``);
                    }, 3000)
                }
            })
        });

        usersHTML.on( "click", ".unblocked", function() {
            let getHTML = $(this).closest('.id_user');
            let id = getHTML.data('id');

            $.ajax({
                url: '/api/user/unblocked.php',
                method: 'POST',
                data: {
                    id
                },
                success: function (d) {
                    clearTimeout(timerId);
                    let btnChange = getHTML.find(".unblockedTd");
                    btnChange.html("<button type='button' class='btn btn-danger blocked'>Заблокировать</button>");
                    btnChange.toggleClass("unblockedTd blockedTd");
                    notify.html(`<div class="alert alert-success">${d?.result?.msg}</div>`);
                    timerId = setTimeout(function () {
                        notify.html(``);
                    }, 3000)
                },
                error: function (d) {
                    clearTimeout(timerId);
                    notify.html(`<div class="alert alert-danger">${d?.responseJSON?.result?.msg}</div>`);
                    timerId = setTimeout(function () {
                        notify.html(``);
                    }, 3000)
                }
            })
        });
        usersHTML.on( "click", ".blocked", function() {
            let getHTML = $(this).closest('.id_user');
            let id = getHTML.data('id');

            $.ajax({
                url: '/api/user/blocked.php',
                method: 'POST',
                data: {
                    id
                },
                success: function (d) {
                    clearTimeout(timerId);
                    let btnChange = getHTML.find(".blockedTd");
                    btnChange.html("<button type='button' class='btn btn-success unblocked'>Разблокировать</button>");
                    btnChange.toggleClass("blockedTd unblockedTd");
                    notify.html(`<div class="alert alert-success">${d?.result?.msg}</div>`);
                    timerId = setTimeout(function () {
                        notify.html(``);
                    }, 3000)
                },
                error: function (d) {
                    clearTimeout(timerId);
                    notify.html(`<div class="alert alert-danger">${d?.responseJSON?.result?.msg}</div>`);
                    timerId = setTimeout(function () {
                        notify.html(``);
                    }, 3000)
                }
            })
        });
    } else {
        alert('Ваша версия браузера устарела, к сожалению поддержки пока нет');
    }
});
