<?php
    require_once "components/header.php";
?>

    <h1 id="heading"></h1>
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
    ?>

    <!-- временно, потом убрать и строить пост из полученной data -->
    <!--<script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest"></script>-->
    <!--<script src="https://cdn.jsdelivr.net/npm/@editorjs/header@latest"></script>-->
    <!--<script src="https://cdn.jsdelivr.net/npm/@editorjs/warning@latest"></script>-->
    <!--<script src="https://cdn.jsdelivr.net/npm/@editorjs/marker@latest"></script>-->
    <!--<script src="https://cdn.jsdelivr.net/npm/@editorjs/delimiter@latest"></script>-->
    <!--<script src="https://cdn.jsdelivr.net/npm/@editorjs/table@latest"></script>-->
    <!--<script src="https://cdn.jsdelivr.net/npm/@editorjs/quote@latest"></script>-->
    <!--<script src="https://cdn.jsdelivr.net/npm/@editorjs/inline-code@latest"></script>-->
    <!--<script src="https://cdn.jsdelivr.net/npm/@editorjs/nested-list@latest"></script>-->
    <!--<script src="https://cdn.jsdelivr.net/npm/@editorjs/underline@latest"></script>-->
    <!--<script src="https://cdn.jsdelivr.net/npm/@editorjs/paragraph@latest"></script>-->
    <!--<script src="https://cdn.jsdelivr.net/npm/@editorjs/text-variant-tune@latest"></script>-->
    <!--<script src="https://cdn.jsdelivr.net/npm/@editorjs/checklist@latest"></script>-->
    <script src="../libs/EditorJS/editorjs.js"></script>
    <script src="../libs/EditorJS/blocks/simple-image.js"></script>
    <link href="../libs/EditorJS/blocks/simple-image.css" rel="stylesheet"/>
    <script src="../libs/EditorJS/blocks/header.js"></script>
    <script src="../libs/EditorJS/blocks/warning.js"></script>
    <script src="../libs/EditorJS/blocks/marker.js"></script>
    <script src="../libs/EditorJS/blocks/delimiter.js"></script>
    <script src="../libs/EditorJS/blocks/table.js"></script>
    <script src="../libs/EditorJS/blocks/quote.js"></script>
    <script src="../libs/EditorJS/blocks/inline-code.js"></script>
    <script src="../libs/EditorJS/blocks/nested-list.js"></script>
    <script src="../libs/EditorJS/blocks/underline.js"></script>
    <script src="../libs/EditorJS/blocks/paragraph.js"></script>
    <script src="../libs/EditorJS/blocks/text-variant-tune.js"></script>
    <script src="../libs/EditorJS/blocks/checklist.js"></script>

    <style>@import url("../public/styles/pages/post.css");</style>
    <script src="../public/js/pages/post.js"></script>

<?php
    require_once "components/footer.php";
?>
