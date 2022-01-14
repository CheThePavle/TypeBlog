$(function() {
    if (window?.XMLHttpRequest) { // сомнительная проверка ошибки в firefox 5.0 - знаки вопроса и объявления переменных через let
        let start = 0;
        let getUrlParams = window?.location?.search?.replace( '?', '') + '&start=';

        let nextHTML = $('#nextPosts');
        let showmoreHTML = $('#showmore-triger');
        let postsHTML = $('.posts');
        let loadPost = true;

        let window_scrolled;
        $(window).scroll(function() {
            window_scrolled = ($(document).height()/100)*90;
            if($(window).scrollTop() + $(window).height() >= window_scrolled) {
                if (loadPost) {
                    start += 5; //fixme
                    loadPost = false;
                    getPosts(start);
                }
            }
        });

        nextHTML.click(function (e) {
            start += 5; //fixme
            getPosts(start);
        });

        const getPosts = (start) => {
            let msg = $(".msg");
            return $.ajax({
                url: '../api/post/search.php',
                data: getUrlParams  + start,
                success: function (d) {
                    d = d?.result?.data;
                    if (d?.length === 0) {
                        nextHTML.remove();
                        showmoreHTML.remove();
                        msg.html('<div class="alert alert-danger">Постов больше нет</div>');
                        loadPost = false;
                    } else {
                        loadPost = true;
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
                                    <p>Автор: <a href="/profile/${el?.id_creator}">${el?.user_login}</a>, ${el?.created_at}</p>
                                    <img class="img_post" src="../data/posts/${el?.main_folder}/main_img.${el?.main_img_type}" alt="Картинка для ${el?.heading}">
                                    <br><br>
                                    <p>${el?.description}</p>
                                    <p>Категория: <a href="/?category=${el?.id_category}">${el?.name_category}</a></p>
                                    ${strTags && `<p>Теги: ${strTags}</p>`}
                                    <div class="statistics_post">
                                        <div>
                                            <i class="fas fa-heart"></i>
                                            ${el?.likes > 0 ? '+' + el?.likes : el?.likes}
                                        </div>
                                        <div class="block_statistics_post">
                                            <i class="fas fa-eye"></i>
                                            ${el?.views} ${+el?.views_day == '0' || !el?.views_day ? '' : '(+' + el?.views_day + ')'}
                                        </div>
                                        <div class="block_statistics_post">
                                            <i class="fa fa-comments" aria-hidden="true"></i>
                                            <a class="statistics_post_comment" href="/post/${el?.id_post}#redirect_comments">
                                                ${el?.comments_count}
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
            });
        }
        getPosts(start);
    } else {
        alert('Ваша версия браузера устарела, поддержки не будет)');
    }

// let getParams = window
//     .location
//     .search
//     .replace('?','')
//     .split('&')
//     .reduce(
//         function(p,e){
//             var a = e.split('=');
//             p[decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
//             return p;
//         },
//         {}
//     );

// if (window.location.pathname === '/posts' && getParams['']) {
//     window.location.href = `/posts?number=${number}`;
// }

// function getRequests() {
//     var s1 = location.search.substring(1, location.search.length).split('&'),
//         r = {}, s2, i;
//     for (i = 0; i < s1.length; i += 1) {
//         s2 = s1[i].split('=');
//         r[decodeURIComponent(s2[0]).toLowerCase()] = decodeURIComponent(s2[1]);
//     }
//     return r;
// };
// let QueryString = getRequests();
});
