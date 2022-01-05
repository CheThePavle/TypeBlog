$(function() {
    if (window?.XMLHttpRequest) {
        const id = window?.location?.pathname?.substr(6);

        $.ajax({
            url: '../api/post/findById.php',
            data: {
                id
            },
            success: function (data) {
                data = JSON.parse(data);
                data = data[0];
                // console.log(data)
                data.body = JSON.parse(data?.body);
                if (data?.jsonData?.length === 0) {
                    $(".msg").html('<div class="alert alert-danger">Пост не найден!</div>')
                    $("#heading").html(`<h1 id="heading">Такого поста не существует (</h1>`);
                } else {
                    $("#heading").html(`<h1 id="heading">${data?.heading}</h1>`);
                    $(".prevPost").html(`
                    <div class="hero-unit prev_content">
                        <img src="../data/posts/${data?.body?.uniqUrl}/main_img.${data?.body?.typeMainImg}" alt="Картинка для ${data?.heading}"">
                        <br><br>
                        <p>${data?.description}</p>
                    </div>
                `);

                    new EditorJS({
                        autofocus: true,
                        tools: {
                            checklist: {
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
                        data
                    });
                }
            },
            error: function () {
                $(".msg").html('<div class="alert alert-danger">Что-то пошло не так!</div>')
            }
        })
    } else {
        alert('Ваша версия браузера устарела, поддержки не будет)');
    }
});
