<?php
require_once "components/header.php";
require_once "src/db.php";
//    echo "<script>window.location.href='/posts?number=5'</script>";

//function setGetParam($get, $par) {
//    return http_build_query(array_merge([$get => $par], $_GET));
//}
// echo http_build_query($_GET);
$getCategory = $db->query("SELECT * FROM `category`");
$getTag = $db->query("SELECT * FROM `tag`");
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
            <div id="showmore-triger" data-page="1">
                <img src="https://i2.wp.com/apptractor.ru/wp-content/uploads/2017/04/spinner-icon-0.gif?fit=1567%2C1561&ssl=1" style="max-width: 100%" alt="">
            </div>
            <button type="button" class="btn btn-primary btn-lg btn-block" id="nextPosts">Загрузить еще</button>
            <br><br>
        </div>
    </div>
    <div class="col-md-4" style="position: sticky; top: 30px">
        <form action="">
            <div class="btn-group">
                <div class="btn-group">
                    <select name="order" >
                        <option value="1" <?php echo $_GET['order'] == 1 ? 'selected' : '' ?>>Сначала старые</option>
                        <option value="0" <?php echo $_GET['order'] == 0 ? 'selected' : '' ?>>Сначала новые</option>
                        <option value="2" <?php echo $_GET['order'] == 2 ? 'selected' : '' ?>>По популярности</option>
                        <option value="3" <?php echo $_GET['order'] == 3 ? 'selected' : '' ?>>По рейтингу</option>
                    </select>
                </div>
            </div>
            <br><br>
            <p><b>Категория:</b></p>
            <?php
                if ($getCategory) {
                    foreach ($getCategory as $el) {
                        $checked = $_GET['category'] === $el['id'] ? 'checked' : '';
                        echo "<p><input ".$checked." name='category' type='radio' value='".$el['id']."'> ".$el['name']."</p>";
                    }
                }
            ?>
            <p><b>Теги:</b></p>
            <p>
                <?php
                    if ($getTag) {
                        if ($_GET['tag']) {
                            foreach ($_GET['tag'] as $item) {
                                $getTag[$item - 1]['checked'] = true;
                            }
                        }
                        foreach ($getTag as $el) {
                            $checked = $el['checked'] ? 'checked' : '';
                            echo "<input ".$checked." type='checkbox' name='tag[]'' value='".$el['id']."'> ".$el['name']." ";
                        }
                    }
                ?>
            </p>
            <button type="submit" class="btn btn-default">Применить</button>
        </form>
        <a href="/"><input type="submit" class="btn btn-danger" value="Сбросить"></a>
    </div>
</div>

<script>
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

    if (window?.XMLHttpRequest) {
        // Supports Ajax.
    } else {
        alert('Ваша версия браузера устарела, поддержки не будет)');
    }

    let start = 0;

    let getUrlParams = window.location.search.replace( '?', '') + '&start=';

    $.ajax({
        url: '../api/post/search.php',
        data: getUrlParams  + start,
        success: function (data) {
            console.log(data);
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
        start += 2;

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
            data: getUrlParams + start,
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
