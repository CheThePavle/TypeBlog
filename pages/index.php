<?php
require_once "components/header.php";
//    echo "<script>window.location.href='/posts?number=5'</script>";
?>

<h1>
    <?php
        switch ($_GET['order']) {
            case 1:
                echo "Старые посты";
                break;
            case 2:
                echo "Популярные посты";
                break;
            case 3:
                echo "Лучшие посты";
                break;
            default:
                echo "Последние посты";
        }
    ?>
</h1>
<hr>
<div class="row">
    <div class="col-md-8">
        <div class="posts"></div>
        <div style="max-width: 250px; margin: 0 auto">
            <button type="button" class="btn btn-primary btn-lg btn-block" id="nextPosts">Загрузить еще</button>
            <br><br>
        </div>
    </div>
    <div class="col-md-4" style="position: sticky; top: 30px">
        <div class="btn-group">
            <div class="btn-group">
                <button type="button" data-toggle="dropdown" class="btn btn-info dropdown-toggle">
                    Сортировка:
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="?order=1">Сначала старые</a></li>
                    <li><a href="/">Сначала новые</a></li>
                    <li><a href="?order=2">По популярности</a></li>
                    <li><a href="?order=3">По рейтингу</a></li>
                </ul>
            </div>
        </div>
        <br><br>
        <form action="">
            <p><b>Категория:</b></p>
            <p><input name="category" type="radio" value="1" <?php echo $_GET['category'] == 1 ? 'checked' : '' ?>> Категория 1</p>
            <p><input name="category" type="radio" value="2" <?php echo $_GET['category'] == 2 ? 'checked' : '' ?>> Категория 2</p>
            <p><input name="category" type="radio" value="3" <?php echo $_GET['category'] == 3 ? 'checked' : '' ?>> Категория 3</p>

            <p><b>Теги:</b></p>
            <p>
                <input type="checkbox" name="tag1" value="a1" <?php echo $_GET['tag1'] ? 'checked' : '' ?>> Тег 1
                <input type="checkbox" name="tag2" value="a2" <?php echo $_GET['tag2'] ? 'checked' : '' ?>> Тег 2
                <input type="checkbox" name="tag3" value="a3" <?php echo $_GET['tag3'] ? 'checked' : '' ?>> Тег 3
                <input type="checkbox" name="tag4" value="a4" <?php echo $_GET['tag4'] ? 'checked' : '' ?>> Тег 4
                <input type="checkbox" name="tag5" value="a5" <?php echo $_GET['tag5'] ? 'checked' : '' ?>> Тег 5
            </p>
            <button type="submit" class="btn btn-default">Применить</button>
        </form>
        <a href="/"><input type="submit" class="btn btn-danger" value="Сбросить"></a>
    </div>
</div>

<script>
    if (window?.XMLHttpRequest) {
        // Supports Ajax.
    } else {
        alert('Ваша версия браузера устарела, поддержки не будет)');
    }

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
                            <p>Автор: <a href="/profile/1">Author</a>, 12.09.2021</p>
                            <img src="../jsonData/posts/${el.body}.png" alt="${el.heading}" style="max-width: 770px">
                            <br><br>
                            <p>${el.description}</p>
                            <p>Категория: <a href="/profile/1">Категория 1</a></p>
                            <p>Теги: <a href="/profile/1">Тег 1, Тег 2, Тег 3</a></p>
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
