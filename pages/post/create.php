<?php
    require_once "components/header.php";
    require_once "src/usecases/post/create.php" // return $getCategory
?>

<div class="tabbable">
    <ul class="nav nav-tabs">
        <li><a href="/lk">Профиль</a></li>
        <li class="active"><a href="/lk" data-toggle="tab">Создать пост</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active">
            <form id="forCreatePost" role="form" enctype="multipart/form-data">
                <div class="controls">
                    <div class="row">
                        <div class="col-md-12">
                            <h2>Превью поста</h2>
                            <div class="form-group">
                                <label for="about">Название *</label>
                                <input id="heading" type="text" name="heading" class="form-control" placeholder="Введите заголовок поста *" required="required">
                                <div class="errorHeading"></div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="about">Загрузить главное изображение (формат только .png) *</label>
                            <div class="form-group download_img_prev">
                                <button type="button" id="name_btn_img_prev">Выбрать</button>
                                <div>Выберите файл или перетащите его сюда</div>
                                <input type="file" name="image" class="form-control-file" id="controlFile">
                            </div>
                            <div class="show_img_prev_post">
                                <img id="show_img_prev" src="#" alt="prev_img_post" />
                                <br><br>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="about">Кратко о чем пост *</label>
                                <textarea rows="10" id="description" type="text" name="description" class="form-control" placeholder="Опишите о чем будет пост *" required="required"></textarea>
                                <div class="errorDescription"></div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="about">Выберите категорию *</label>
                            <br>
                            <?php
                                if ($getCategory) {
                                    echo "<select name='category'>";
                                    foreach ($getCategory as $el) {
                                        echo "<option value='".$el['id_category']."'>".$el['name_category']."</option>";
                                    }
                                    echo "</select>";
                                } else {
                                    echo '<p>Категории не обнаружены(</p>';
                                }
                            ?>
                        </div>

                        <div class="col-md-12">
                            <br>
                            <label for="about">Теги *</label>
                            <br>
                            <div data-tags-input-name="tag" id="tagBox"></div>
                        </div>

                        <div class="col-md-12">
                            <h2>Содержание поста</h2>
                            <div id="editorjs"></div>
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
        </div>
    </div>
</div>

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

<style>@import url("../../public/styles/pages/post/create.css");</style>

<style>@import url("../../libs/taggingJS/tag-basic-style.css");</style>
<script src="../../libs/taggingJS/tagging.min.js"></script>

<script src="../../public/js/pages/post/create.js"></script>

<?php
    require_once "components/footer.php";
?>

