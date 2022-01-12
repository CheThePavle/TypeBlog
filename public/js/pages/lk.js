$(function() {
    if (window?.XMLHttpRequest) {
        let start = 0;
        let getUrlParams = window.location.search.replace( '?', '') + '&start=';

        let nextHTML = $('#nextPosts');
        let showmoreHTML = $('#showmore-triger');
        let postsHTML = $('.posts');

        // фикс спама при прокрутке страницы
        let window_scrolled;
        $(window).scroll(function() {
            window_scrolled = ($(document).height()/100)*90;
            if($(window).scrollTop() + $(window).height() >= window_scrolled) {
                start += 2;
                getPosts(start);
            }
        });

        nextHTML.click(function (e) {
            start += 2;
            getPosts(start);
        });

        const getPosts = (start) => {
            let msg = $(".msg");
            return $.ajax({
                url: '../api/post/searchPostLk.php',
                data: getUrlParams  + start + '&lk=true',
                success: function (d) {
                    d = d?.result?.data;
                    if (d?.length === 0) {
                        nextHTML.remove();
                        showmoreHTML.remove();
                        msg.html('<div class="alert alert-danger">Постов больше нет</div>');
                    } else {
                        d?.map(function(el) {
                            let strTags = '';
                            el?.tags?.map(function(item) {
                                strTags += `<a href="/?tag[]=${item?.id_tag}">${item?.name_tag + ', '}</a>`;
                            });
                            if (strTags) {
                                strTags = strTags?.slice(0, strTags?.length - 6) + '</a>';
                            }
                            el.body = JSON.parse(el?.body);
                            postsHTML.append(`
                                <div class="hero-unit">
                                    ${el?.read_post ? `
                                        <div class="header_block">
                                            <h2>${el?.heading}&nbsp;</h2>
                                            <img src="../../../public/imgs/readPost.png" alt="Просмотрен">
                                        </div>
                                    ` : `
                                        <h2>${el?.heading}</h2>
                                    `}
                                    <p>Автор: <a href="/profile/${el?.id_creator}">${el?.user_login[0]?.login}</a>, ${el?.created_at}</p>
                                    <img class="img_post" src="../data/posts/${el?.body?.uniqUrl}/main_img.${el?.body?.typeMainImg}" alt="Картинка для ${el?.heading}">
                                    <br><br>
                                    <p>${el?.description}</p>
                                    <p>Категория: <a href="/?category=${el?.id_category}">${el?.name_category}</a></p>
                                    ${strTags && `<p>Теги: ${strTags}</p>`}
                                    <div class="statistics_post">
                                        <div>
                                            <img src="http://s1.iconbird.com/ico/2013/9/452/w512h4481380476860heart.png" alt="Репутация" width="20px">
                                            +0
                                        </div>
                                        <div class="block_statistics_post">
                                            <img src="http://s1.iconbird.com/ico/2014/1/607/w512h5121390848413visible512.png" alt="Просмотры" width="20px">
                                            ${el?.views[0]?.number} ${+el?.views_day[0]?.number == '0' || !el?.views_day[0]?.number ? '' : '(+' + el?.views_day[0]?.number + ')'}
                                        </div>
                                        <div class="block_statistics_post">
                                            <a class="statistics_post_comment" href="/">
                                                <img src="http://s1.iconbird.com/ico/2013/8/429/w512h5121377939697185079bubblecommentstreamlinetalk.png" alt="Комментарии" width="20px">
                                                0
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
                    msg.html('<div class="alert alert-danger">Что-то пошло не так!</div>')
                }
            })
        }
        getPosts(start);
    } else {
        alert('Ваша версия браузера устарела, поддержки не будет)');
    }
});
