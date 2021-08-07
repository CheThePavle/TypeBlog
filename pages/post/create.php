<?php
    require_once "components/header.php";

    //$data = [
    //    "id"=> 1,
    //    "test"=>"test"
    //];
    //file_put_contents('jsonData/posts/id.json', json_encode($data));
?>

<div>
    <h1>Создание поста</h1>
    <form id="forCreatePost" role="form">
        <div class="controls">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="about">Название</label>
                        <input id="heading" type="text" name="heading" class="form-control" placeholder="Введите заголовок поста *" required="required">
                        <div class="errorHeading"></div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div id="editorjs" style="max-width: 1000px; margin: 0 auto"></div>
                </div>

                <div class="col-md-12">
                    <input type="submit" class="btn btn-success btn-send" value="Создать">
                    <pre id="output"></pre>
                </div>
            </div>
        </div>
        <br>
        <div class="msg"></div>
    </form>
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

<script>
    const editor = new EditorJS({
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
        // data: {
        //     time: 1552744582955,
        //     blocks: [
        //         {
        //             type: "image",
        //             data: {
        //                 url: "https://cdn.pixabay.com/photo/2017/09/01/21/53/blue-2705642_1280.jpg",
        //                 caption: 'Here is a caption field',
        //                 withBorder: false,
        //                 withBackground: false,
        //                 stretched: false
        //             }
        //         }
        //     ],
        //     version: "2.11.10"
        // }
    });

    const activeBtn = (bool) => {
        $('.btn-send').prop('disabled', bool);
    }

    $("#heading").keyup(function () {
        let h = $(this).val();

        $(".errorHeading").html('');
        activeBtn(false);

        if (h.length  > 30) {
            $(".errorHeading").html('<div class="alert alert-warning">Заголовок не может быть > 30 символов!</div>')
            activeBtn(true);
        }

        if (h.length  < 6) {
            $(".errorHeading").html('<div class="alert alert-warning">Заголовок не может быть < 6 символов!</div>')
            activeBtn(true);
        }
    })

    $('#forCreatePost').submit(function (e) {
        e.preventDefault();

        let th = $(this);
        let msg = $('.msg');

        const output = document.getElementById('output');
        editor.save().then(savedData => {
            $.ajax({
                url: '../../api/post/create.php',
                type: 'POST',
                data: `${th.serialize()}&body=${JSON.stringify(savedData)}`,
                success: function (data) {
                    activeBtn(false);
                    if (data?.error) {
                        msg.html(`<div class="alert alert-warning">${data?.error}</div>`);
                    } else {
                        activeBtn(true);
                        msg.html(`<div class="alert alert-success">Пост создан.</div>`);
                    }
                },
                error: function () {
                    activeBtn(true);
                    alert('error');
                    msg.html('<div class="alert alert-danger">Что-то пошло не так :(</div>');
                }
            })
            output.innerHTML = JSON.stringify(savedData, null, 4);
        })
        //console.log(`${th.serialize()}body=${data}`)

    });
</script>

<?php
    require_once "components/footer.php";
?>
