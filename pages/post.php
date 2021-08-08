<?php
    require_once "components/header.php";
?>

<h1 id="heading"></h1>
<hr>
<div class="row">
    <div class="col-md-8">
        <div class="msg"></div>
        <div class="prevPost"></div>
        <div id="editorjs" style="max-width: 1000px; margin: 0 auto"></div>
    </div>
    <div class="col-md-4">
        <div>sidebar</div>
    </div>
</div>

<!-- временно, потом убрать -->
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
<script src="https://cdn.jsdelivr.net/npm/@editorjs/paragraph@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/text-variant-tune@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/checklist@latest"></script>

<script>
    const id = <?= $urls[1] ?>;

    $.ajax({
        url: '../api/post/findById.php',
        data: {
            id: id
        },
        success: function (data) {
            data = JSON.parse(data);
            const prevData = data.dbData[0];
            const jsonData = data.jsonData[0];
            if (data.jsonData.length === 0) {
                $(".msg").html('<div class="alert alert-danger">Пост не найден!</div>')
                $("#heading").html(`<h1 id="heading">Такого поста не существует (</h1>`);
            } else {
                $("#heading").html(`<h1 id="heading">${prevData.heading}</h1>`);
                $(".prevPost").html(`
                    <div class="hero-unit">
                        <img src="../jsonData/posts/${prevData.body}.png" alt="${prevData.heading}" style="max-width: 770px">
                        <br><br>
                        <p>${prevData.description}</p>
                    </div>
                `);

                new EditorJS({
                    autofocus: true,
                    tools: {
                        checklist: { // ошибки валяться в консоль
                            class: Checklist,
                            inlineToolbar: true,
                        },
                        //raw: RawTool,
                        textVariant: TextVariantTune,
                        paragraph: {
                            class: Paragraph,
                            inlineToolbar: true,
                            tunes: ['textVariant']
                        },
                        // code: CodeTool,
                        underline: {
                            class: Underline,
                            shortcut: 'SHIFT+B'
                        },
                        list: {
                            class: NestedList,
                            inlineToolbar: true,
                        },
                        inlineCode: {
                            class: InlineCode,
                            shortcut: 'SHIFT+C',
                        },
                        quote: {
                            class: Quote,
                            inlineToolbar: true,
                            shortcut: 'SHIFT+O',
                            config: {
                                quotePlaceholder: 'Enter a quote',
                                captionPlaceholder: 'Quote\'s author',
                            },
                        },
                        table: {
                            class: Table,
                            inlineToolbar: true,
                            config: {
                                rows: 2,
                                cols: 3,
                            },
                        },
                        delimiter: Delimiter,
                        Marker: {
                            class: Marker,
                            shortcut: 'SHIFT+M',
                        },
                        warning: {
                            class: Warning,
                            inlineToolbar: true,
                            shortcut: 'SHIFT+W',
                            config: {
                                titlePlaceholder: 'Title',
                                messagePlaceholder: 'Message',
                            },
                        },
                        header: {
                            class: Header,
                            shortcut: 'SHIFT+H', //при нажатии клавиш будет создан заголовок
                            // levels: [2, 3, 4],
                            // defaultLevel: 3
                        },
                        image: { // кастомный компонент image
                            class: SimpleImage,
                            inlineToolbar: true,
                            config: {
                                placeholder: 'Paste image URL'
                            }
                        }
                    },
                    data: jsonData
                });
            }
        },
        error: function () {
            $(".msg").html('<div class="alert alert-danger">Что-то пошло не так!</div>')
        }
    })
</script>

<?php
    require_once "components/footer.php";
?>
