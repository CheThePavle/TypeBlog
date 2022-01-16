$(function() {
    if (window?.XMLHttpRequest) {
        let categoryHTML = $(".body_category");
        let notify = $(".notify");

        categoryHTML.on( "click", ".changed", function() {
            let body_change_category = $('.body_change_category');
            body_change_category.html('');

            let getHTML = $(this).closest('.id_category');
            let getVal = getHTML.find('.val_category').text();
            getHTML.find('.body_change_category').html(`
                <form id="formCategoryChange">
                    <input type="text" value="${getVal}" maxlength="30" id="text_change_category" name="change_category">
                    <div class="mar-top clearfix">
                        <button id="btn_send_category_change" class="btn btn-sm btn-primary pull-right" type="submit">
                            <i class="fa fa-pencil fa-fw"></i> Изменить
                        </button>
                        <button id="btn_send_category_change_closed" class="btn btn-sm btn-danger pull-right">
                            <i class="fa fa-pencil fa-fw"></i> Отмена
                        </button>
                    </div>
                </form>
            `);
        });

        categoryHTML.on( "click", "#btn_send_category_change_closed", function() {
            $(".body_change_category").html("");
        });

        categoryHTML.on("submit", "#formCategoryChange", function (e) {
            e.preventDefault();
            let th = $(this);
            let getHTML = $(th).closest('.id_category');
            let id = getHTML.data('id');

            $.ajax({
                url: '/api/category/change.php',
                method: 'POST',
                data: th.serialize() + '&id=' + id,
                success: function (d) {
                    let val = th.serializeArray();
                    getHTML.find(".val_category").text(val[0]['value']);
                    getHTML.find(".body_change_category").html("");
                    notify.html(`<div class="alert alert-success">${d?.result?.msg}</div>`);
                    setTimeout(function () {
                        notify.html(``);
                    }, 3000);
                },
                error: function (d) {
                    notify.html(`<div class="alert alert-danger">${d?.responseJSON?.result?.msg}</div>`);
                    setTimeout(function () {
                        notify.html(``);
                    }, 3000);
                }
            })
        });


        $('#formCreateCategory').submit(function (e) {
            e.preventDefault();
            let th = $(this);

            $.ajax({
                url: '/api/category/create.php',
                method: 'POST',
                data: th.serialize(),
                success: function (d) {
                    notify.html(`<div class="alert alert-success">${d?.result?.msg}</div>`);
                    setTimeout(function () {
                        // добавить динамический вывод в таблицу без перезагрузки
                        window.location.href = '/category/list';
                        notify.html(``);
                    }, 3000);
                },
                error: function (d) {
                    notify.html(`<div class="alert alert-danger">${d?.responseJSON?.result?.msg}</div>`);
                    setTimeout(function () {
                        notify.html(``);
                    }, 3000);
                }
            })
        });


        categoryHTML.on( "click", ".deleted", function() {
            let getHTML = $(this).closest('.id_category');
            let id = getHTML.data('id');

            $.ajax({
                url: '/api/category/delete.php',
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
