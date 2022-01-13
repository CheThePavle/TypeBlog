<?php
    require_once "components/header.php";
    require_once "src/usecases/post/setView.php";
?>

    <h1 id="heading">Назавние</h1>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <div class="msg"></div>
            <div class="prevPost"></div>
            <div id="editorjs"></div>
        </div>
    </div>

<?php
    require_once "components/comments.php";
    require_once "components/footer.php";
?>
