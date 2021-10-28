if (window?.XMLHttpRequest) {
    let start = 0;
    let getUrlParams = window.location.search.replace( '?', '') + '&start=';

    const id = window?.location?.pathname?.substr(9);

    const getPosts = () => {
        return $.ajax({
            url: '../api/post/searchPostProfile.php',
            data: getUrlParams  + start + '&id_user=' + id,
            success: function (data) {
                data = JSON.parse(data);
                if (data?.length === 0) {
                    $('#nextPosts').remove();
                    $('#showmore-triger').remove();
                } else {
                    data?.map(function(el) {
                        let strTags = '';
                        el?.tags?.map(function(item) {
                            strTags += `<a href="/?tag[]=${item?.id_tag}">${item?.name_tag + ', '}</a>`;
                        });
                        if (strTags) {
                            strTags = strTags?.slice(0, strTags?.length - 6) + '</a>';
                        }
                        $('.posts').append(`
                        <div class="hero-unit">
                            <h2>${el?.heading}</h2>
                            <p>Автор: <a href="/profile/${el?.id_creator}">${el?.user_login[0]?.login}</a>, ${el?.created_at}</p>
                            <img class="img_post" src="../jsonData/posts/${el?.body}.png" alt="${el?.heading}">
                            <br><br>
                            <p>${el?.description}</p>
                            <p>Категория: <a href="/?category=${el?.id_category}">${el?.name_category}</a></p>
                            ${strTags && `<p>Теги: ${strTags}</p>`}
                            <div class="statistics_post">
                                <div>
                                    <img src="http://s1.iconbird.com/ico/2013/9/452/w512h4481380476860heart.png" alt="Репутация" width="20px">
                                    +1
                                </div>
                                <div class="block_statistics_post">
                                    <img src="http://s1.iconbird.com/ico/2014/1/607/w512h5121390848413visible512.png" alt="Просмотры" width="20px">
                                    1
                                </div>
                                <div class="block_statistics_post">
                                    <a class="statistics_post_comment" href="/">
                                        <img src="http://s1.iconbird.com/ico/2013/8/429/w512h5121377939697185079bubblecommentstreamlinetalk.png" alt="Комментарии" width="20px">
                                        1
                                    </a>
                                </div>
                            </div>
                            <br>
                            <p>
                                <a href="/post/${el?.id_post}" class="btn btn-primary btn-large">
                                    Читать дальше
                                </a>
                            </p>
                        </div>
                        <br><br>
                    `);
                    });
                }
            },
            error: function () {
                $(".msg").html('<div class="alert alert-danger">Что-то пошло не так!</div>')
            }
        })
    }
    getPosts();

    var window_scrolled;
    // фикс спама при прокрутке страницы
    $(window).scroll(function() {
        window_scrolled = ($(document).height()/100)*90;
        if($(window).scrollTop() + $(window).height() >= window_scrolled) {
            start += 2;
            getPosts();
        }
    });

    $('#nextPosts').click(function (e) {
        start += 2;
        getPosts();
    });
} else {
    alert('Ваша версия браузера устарела, поддержки не будет)');
}