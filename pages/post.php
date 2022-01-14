<?php
    require_once "components/header.php";
    require_once "src/usecases/post/setView.php";
?>

    <h1 id="heading">Назавние</h1>
    <hr>
    <div class="row">
        <div class="col-md-12 body_post">
            <div class="msg"></div>
            <div class="prevPost"></div>
            <div id="editorjs"></div>
            <div class="likesPost"></div>
            <div class="repost">
                <h3>Поделиться в соц. сетях</h3>
                <script src="https://yastatic.net/share2/share.js"></script>
                <div class="ya-share2" data-curtain data-shape="round" data-limit="5" data-services="vkontakte,telegram,odnoklassniki,twitter,whatsapp,facebook,messenger,viber,moimir,skype,tumblr,evernote,linkedin,lj,blogger,delicious,digg,reddit,pocket,qzone,renren,sinaWeibo,surfingbird,tencentWeibo"></div>
            </div>
        </div>
    </div>

<?php
    require_once "components/comments.php";
    require_once "components/footer.php";
?>
