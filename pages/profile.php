<?php
    require_once "components/header.php";
    require_once "src/db.php";

    $getUser = $db->query("SELECT u.id_user, u.login FROM `user` as u WHERE u.id_user = ?", [$urls[1]]);
?>

<div class="tab-pane active" id="tab1">
    <div class="bg_shapka_lk">
        <br><br>
        <div class="prfole_block">
            <div style="text-align: center">
                <div class="block_ava_img">
                    <img class="ava_img" src="https://vjoy.cc/wp-content/uploads/2019/12/2-1.png" alt="avatarka">
                </div>
                <input id="btn-auth" type="submit" class="btn btn-primary change_profile_btn" value="Подписаться">
            </div>
            <div class="info_user">
                <br><br>
                <h2><?= $getUser[0]['login'] ?></h2>
                <p>Статус</p>
            </div>
        </div>
    </div>
    <div class="bg_shapka2_lk"></div>
    <div>
        <h2>Публичные посты</h2>
        <hr>
        <div class="posts"></div>
        <div class="msg"></div>
        <div style="max-width: 250px; margin: 0 auto">
            <div id="showmore-triger">
                <img src="https://i2.wp.com/apptractor.ru/wp-content/uploads/2017/04/spinner-icon-0.gif?fit=1567%2C1561&ssl=1" style="max-width: 100%" alt="">
            </div>
            <button type="button" class="btn btn-primary btn-lg btn-block" id="nextPosts">Загрузить еще</button>
            <br><br>
        </div>
    </div>
</div>

<script>
    let start = 0;

    let getUrlParams = window.location.search.replace( '?', '') + '&start=';

    // передавать id по другому!
    let getIdUser = <?= $urls[1] ?>;
    $.ajax({
        url: '../api/post/search.php',
        data: getUrlParams  + start + '&id_user=' + getIdUser,
        success: function (data) {
            console.log(data);
            data = JSON.parse(data);
            console.log(data);
            if (data.length === 0) {
                $('#nextPosts').remove();
                $('#showmore-triger').remove();
            } else {
                data.map(function(el) {
                    let strTags = '';
                    el.tags.map(function(item) {
                        strTags += `<a href="/?tag[]=${item.id_tag}">${item.name_tag + ', '}</a>`;
                    });
                    $('.posts').append(`
                        <div class="hero-unit">
                            <h2>${el.heading}</h2>
                            <p>Автор: <a href="/profile/${el.id_creator}">${el.user_login[0].login}</a>, ${el.created_at}</p>
                            <img src="../jsonData/posts/${el.body}.png" alt="${el.heading}" style="max-width: 100%">
                            <br><br>
                            <p>${el.description}</p>
                            <p>Категория: <a href="/?category=${el.id_category}">${el.name_category}</a></p>
                            ${strTags ? `<p>Теги: ${strTags}</p>` : ''}
                            <div style="display: flex;">
                                <div>
                                    <img src="http://s1.iconbird.com/ico/2013/9/452/w512h4481380476860heart.png" alt="Репутация" width="20px">
                                    +1
                                </div>
                                <div style="margin-left: 10px">
                                    <img src="http://s1.iconbird.com/ico/2014/1/607/w512h5121390848413visible512.png" alt="Просмотры" width="20px">
                                    1
                                </div>
                                <div style="margin-left: 10px">
                                    <a href="/" style="color: black">
                                        <img src="http://s1.iconbird.com/ico/2013/8/429/w512h5121377939697185079bubblecommentstreamlinetalk.png" alt="Комментарии" width="20px">
                                        1
                                    </a>
                                </div>
                            </div>
                            <br>
                            <p>
                                <a href="/post/${el.id_post}" class="btn btn-primary btn-large">
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

    var percent = 90;
    var window_scrolled;

    // фикс спама при прокрутке страницы
    $(window).scroll(function() {
        window_scrolled = ($(document).height()/100)*90;

        if($(window).scrollTop() + $(window).height() >= window_scrolled) {
            start += 2;

            $.ajax({
                url: '../api/post/search.php',
                data: getUrlParams  + start + '&id_user=' + getIdUser,
                success: function (data) {
                    data = JSON.parse(data);
                    if (data.length === 0) {
                        $('#nextPosts').remove();
                        $('#showmore-triger').remove();
                    } else {
                        data.map(function(el) {
                            let strTags = '';
                            el.tags.map(function(item) {
                                strTags += `<a href="/?tag[]=${item.id_tag}">${item.name_tag + ', '}</a>`;
                            });
                            $('.posts').append(`
                        <div class="hero-unit">
                            <h2>${el.heading}</h2>
                            <p>Автор: <a href="/profile/${el.id_creator}">${el.user_login[0].login}</a>, ${el.created_at}</p>
                            <img src="../jsonData/posts/${el.body}.png" alt="${el.heading}" style="max-width: 100%">
                            <br><br>
                            <p>${el.description}</p>
                            <p>Категория: <a href="/?category=${el.id_category}">${el.name_category}</a></p>
                            ${strTags ? `<p>Теги: ${strTags}</p>` : ''}
                            <div style="display: flex;">
                                <div>
                                    <img src="http://s1.iconbird.com/ico/2013/9/452/w512h4481380476860heart.png" alt="Репутация" width="20px">
                                    +1
                                </div>
                                <div style="margin-left: 10px">
                                    <img src="http://s1.iconbird.com/ico/2014/1/607/w512h5121390848413visible512.png" alt="Просмотры" width="20px">
                                    1
                                </div>
                                <div style="margin-left: 10px">
                                    <a href="/" style="color: black">
                                        <img src="http://s1.iconbird.com/ico/2013/8/429/w512h5121377939697185079bubblecommentstreamlinetalk.png" alt="Комментарии" width="20px">
                                        1
                                    </a>
                                </div>
                            </div>
                            <br>
                            <p>
                                <a href="/post/${el.id_post}" class="btn btn-primary btn-large">
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
    });

    $('#nextPosts').click(function (e) {
        start += 2;

        $.ajax({
            url: '../api/post/search.php',
            data: getUrlParams  + start + '&id_user=' + getIdUser,
            success: function (data) {
                data = JSON.parse(data);
                if (data.length === 0) {
                    $('#nextPosts').remove();
                    $('#showmore-triger').remove();
                } else {
                    data.map(function(el) {
                        let strTags = '';
                        el.tags.map(function(item) {
                            strTags += `<a href="/?tag[]=${item.id_tag}">${item.name_tag + ', '}</a>`;
                        });
                        $('.posts').append(`
                        <div class="hero-unit">
                            <h2>${el.heading}</h2>
                            <p>Автор: <a href="/profile/${el.id_creator}">${el.user_login[0].login}</a>, ${el.created_at}</p>
                            <img src="../jsonData/posts/${el.body}.png" alt="${el.heading}" style="max-width: 100%">
                            <br><br>
                            <p>${el.description}</p>
                            <p>Категория: <a href="/?category=${el.id_category}">${el.name_category}</a></p>
                            ${strTags ? `<p>Теги: ${strTags}</p>` : ''}
                            <div style="display: flex;">
                                <div>
                                    <img src="http://s1.iconbird.com/ico/2013/9/452/w512h4481380476860heart.png" alt="Репутация" width="20px">
                                    +1
                                </div>
                                <div style="margin-left: 10px">
                                    <img src="http://s1.iconbird.com/ico/2014/1/607/w512h5121390848413visible512.png" alt="Просмотры" width="20px">
                                    1
                                </div>
                                <div style="margin-left: 10px">
                                    <a href="/" style="color: black">
                                        <img src="http://s1.iconbird.com/ico/2013/8/429/w512h5121377939697185079bubblecommentstreamlinetalk.png" alt="Комментарии" width="20px">
                                        1
                                    </a>
                                </div>
                            </div>
                            <br>
                            <p>
                                <a href="/post/${el.id_post}" class="btn btn-primary btn-large">
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
    });
</script>

<style>
    .bg_shapka_lk{
        background-color: dimgray;
        height: 200px;
    }
    .bg_shapka2_lk{
        background-color: #e9e9e9;
        height: 100px;
        text-align: right;
        position: relative;
        z-index: -1;
    }
    .prfole_block{
        display: flex;
        margin-left: 40px;
    }
    .ava_img{
        display: block;
        width: 100%;
    }
    .block_ava_img{
        padding: 5px;
        background-color: #e9e9e9;
        box-shadow: -1px 1px 3px black;
        max-width: 200px;
    }
    .info_user{
        margin-left: 10px;
        color: white;
    }
    .change_profile_btn{
        margin-top: 14px;
    }
</style>

<?php
    require_once "components/footer.php";
?>
