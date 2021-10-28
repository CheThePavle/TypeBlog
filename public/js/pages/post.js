if (window?.XMLHttpRequest) {
    const id = window?.location?.pathname?.substr(6);

    $.ajax({
        url: '../api/post/findById.php',
        data: {
            id
        },
        success: function (data) {
            data = JSON.parse(data);
            const prevData = data?.dbData[0];
            const jsonData = data?.jsonData[0];
            if (data?.jsonData?.length === 0) {
                $(".msg").html('<div class="alert alert-danger">Пост не найден!</div>')
                $("#heading").html(`<h1 id="heading">Такого поста не существует (</h1>`);
            } else {
                $("#heading").html(`<h1 id="heading">${prevData?.heading}</h1>`);
                $(".prevPost").html(`
                    <div class="hero-unit prev_content">
                        <img src="../jsonData/posts/${prevData?.body}.png" alt="${prevData?.heading}">
                        <br><br>
                        <p>${prevData?.description}</p>
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
                    data: jsonData
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