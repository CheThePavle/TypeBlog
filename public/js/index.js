$(function() { // normalizeSpace()
    if (window?.XMLHttpRequest) {
        // Поиск по постам и пользователям (поставить задержку отправки запросов)
        const getResSearch = () => {
            let name = $('#searchPostAndUser').val();
            $('#searchPostAndUser').val(normalizeSpace(name));
            if (name === "") {
                $(".resultSearch").html("");
            }
            else {
                $.ajax({
                    method: "POST",
                    url: "../../../api/searchPostAndUser.php",
                    data: {
                        searchPostAndUser: name
                    },
                    success: function(data) {
                        $('.resultSearch').html('');
                        data = JSON.parse(data);
                        if (data.length == 0) {
                            return $('.resultSearch').append(`
                                    <li>
                                        Ничего не найдено
                                    </li>
                                `)
                        }
                        data?.map(function(el) {
                            $('.resultSearch').append(`
                                <li>
                                    <a href="${el?.id_post ? '/post/' + el?.id_post : '/profile/' + el?.id_user}">
                                        ${el?.heading ? el?.heading + ' (пост)' : el?.login + ' (пользователь)'}
                                    </a>
                                </li>
                            `)
                        });
                    }
                });
            }
        }
        $("#searchPostAndUser").focus(function() {
            $('.resultSearch').show();
            getResSearch();

            $("#searchPostAndUser").keyup(function() {
                getResSearch();
            });
        })
        $("#searchPostAndUser").blur(function() {
            setTimeout(function () {
                $('.resultSearch').hide();
            }, 200)
        })
    } else {
        alert('Ваша версия браузера устарела, поддержки не будет)');
    }
});