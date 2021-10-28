<?php
    require_once "components/header.php";

    //$data = ["id"=> 1,"test"=>"test"];file_put_contents('jsonData/posts/id.json', json_encode($data));
?>

<h1>Создание поста</h1>

<form id="forCreatePost" role="form" enctype="multipart/form-data">
    <div class="controls">
        <div class="row">
            <h2>Превью поста</h2>

            <div class="col-md-12">
                <div class="form-group">
                    <label for="about">Название *</label>
                    <input id="heading" type="text" name="heading" class="form-control" placeholder="Введите заголовок поста *" required="required">
                    <div class="errorHeading"></div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label for="exampleFormControlFile">Загрузить главное изображение *</label>
                    <input type="file" name="image" class="form-control-file" id="exampleFormControlFile" required="required">
                    <span>или перетащите его сюда</span>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label for="about">Кратко о чем данный пост *</label>
                    <textarea rows="10" id="description" type="text" name="description" class="form-control" placeholder="Опишите о чем будет пост *" required="required"></textarea>
                    <div class="errorDescription"></div>
                </div>
            </div>

            <h2>Содержание поста</h2>

            <div class="col-md-12">
                <div id="editorjs" style="max-width: 1000px; margin: 0 auto"></div>
            </div>

            <div class="col-md-12">
                <input type="submit" class="btn btn-success btn-send" value="Создать">
<!--                    <pre id="output"></pre>-->
            </div>
        </div>
    </div>
    <br>
    <div class="msg"></div>
</form>


<script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest"></script>

<script src="../../public/js/blocks/simple-image.js"></script>
<link href="../../public/js/blocks/simple-image.css" rel="stylesheet"/>

<script src="https://cdn.jsdelivr.net/npm/@editorjs/header@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/warning@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/marker@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/delimiter@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/table@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/quote@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/inline-code@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/nested-list@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/underline@latest"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/@editorjs/code@latest"></script> -->
<script src="https://cdn.jsdelivr.net/npm/@editorjs/paragraph@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/text-variant-tune@latest"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/@editorjs/raw"></script> -->
<script src="https://cdn.jsdelivr.net/npm/@editorjs/checklist@latest"></script>

<!--<script src="https://cdn.jsdelivr.net/npm/@editorjs/image@latest"></script>-->

<script src="../../public/js/pages/post/create.js"></script>

<?php
    require_once "components/footer.php";
?>
