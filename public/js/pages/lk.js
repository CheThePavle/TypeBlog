$(function() {
    if (window?.XMLHttpRequest) {
        let start = 0;
        let getUrlParams = window.location.search.replace( '?', '') + '&start=';

        let nextHTML = $('#nextPosts');
        let showmoreHTML = $('#showmore-triger');
        let postsHTML = $('.posts');
        let loadPost = true;
        let notify = $(".notify");

        const readURL = (input) => {
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('.ava_img').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]); // convert to base64 string
            }
        }

        let uploadimage = $("#uploadimage");
        uploadimage.change(function () {
            $('#btn_uploadimage').trigger('click');
        });

        // смена аватарки
        $('#formAvatar').submit(function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: '/api/user/setAvatar.php',
                method: 'POST',
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                success: function (d) {
                    readURL(uploadimage[0]);
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
                url: '/api/post/searchPostLk.php',
                data: getUrlParams  + start + '&lk=true',
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
                                    <p>Статус: ${el?.name}</p>
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
            })
        }
        getPosts(start);
    } else {
        alert('Ваша версия браузера устарела, поддержки не будет)');
    }
});
