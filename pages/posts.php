<?php
    require_once "components/header.php";
//    echo "<script>window.location.href='/posts?number=5'</script>";
?>

<h1>Все посты</h1>
<hr>
<div class="row">
    <div class="col-md-8">
        <div class="posts"></div>
        <div style="max-width: 250px; margin: 0 auto">
            <button type="button" class="btn btn-primary btn-lg btn-block" id="nextPosts">Загрузить еще</button>
            <br><br>
        </div>
    </div>
    <div class="col-md-4">
        <div>sidebar</div>
    </div>
</div>

<script>
    let numberStart = 1;
    let numberEnd = 4;

    $.ajax({
        url: '../api/post/search.php',
        data: {
            numberStart: numberStart,
            numberEnd: numberEnd
        },
        success: function (data) {
            data = JSON.parse(data);
            if (data.length === 0) {
                $('#nextPosts').remove();
            } else {
                data.map(function(el) {
                    $('.posts').append(`
                        <div class="hero-unit">
                            <h2>${el.heading}</h2>
                            <img src="../jsonData/posts/${el.body}.png" alt="${el.heading}" style="max-width: 770px">
                            <br><br>
                            <p>${el.description}</p>
                            <p>
                                <a href="/post/${el.id}" class="btn btn-primary btn-large">
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

    $('#nextPosts').click(function (e) {
        numberStart += 4;
        numberEnd += 4;

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

        $.ajax({
            url: '../api/post/search.php',
            data: {
                numberStart: numberStart,
                numberEnd: numberEnd
            },
            success: function (data) {
                data = JSON.parse(data);
                if (data.length === 0) {
                    $('#nextPosts').remove();
                } else {
                    data.map(function(el) {
                        $('.posts').append(`
                            <div class="hero-unit">
                                <h2>${el.heading}</h2>
                                <img src="../jsonData/posts/${el.body}.png" alt="${el.heading}" style="max-width: 770px">
                                <br><br>
                                <p>${el.description}</p>
                                <p>
                                    <a href="/post/${el.id}" class="btn btn-primary btn-large">
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

<?php
    require_once "components/footer.php";
?>
