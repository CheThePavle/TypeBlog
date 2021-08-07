<?php
    require_once "components/header.php";
//    echo "<script>window.location.href='/posts?number=5'</script>";
?>

<h1>Все посты</h1>
<div class="posts"></div>
<div style="max-width: 250px; margin: 0 auto">
    <button type="button" class="btn btn-primary btn-lg btn-block" id="nextPosts">Загрузить дальше</button>
    <br><br>
</div>

<script>
    let number = 0;
    $('#nextPosts').click(function (e) {
        number++;

        let getParams = window
            .location
            .search
            .replace('?','')
            .split('&')
            .reduce(
                function(p,e){
                    var a = e.split('=');
                    p[decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
                    return p;
                },
                {}
            );

        // if (window.location.pathname === '/posts' && getParams['']) {
        //     window.location.href = `/posts?number=${number}`;
        // }

        $.ajax({
            url: '../api/post/search.php',
            data: {
                number: number
            },
            success: function (data) {
                data = JSON.parse(data);
                if (data.length === 0) {
                    $('#nextPosts').remove();
                } else {
                    $('.posts').append(`
                        <h2>${data[0].heading}</h2>
                        <h3>${data[0].created_at}</h3>
                        <br><br><br>
                    `);
                }
            },
            error: function () {
                console.log('error')
            }
        })
    });
</script>

<?php
    require_once "components/footer.php";
?>
