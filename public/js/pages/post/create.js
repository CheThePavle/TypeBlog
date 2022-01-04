$(function() {
    if (window?.XMLHttpRequest) {
        // taggingJS
        // http://verstaemvse.ru/plagin-sozdaniya-polya-dlya-vvoda-tegov-taggingjs.html
        var my_custom_options = {
            "no-duplicate": true,
            "no-duplicate-callback": window.alert, // выводить валидацию на сайт
            "no-duplicate-text": "Duplicate tags",
            "type-zone-class": "type-zone",
            "tag-box-class": "tagging",
            "forbidden-chars": [",", ".", "_", "?"]
        };
        $("#tagBox").tagging(my_custom_options);

        const readURL = (input) => {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#show_img_prev').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]); // convert to base64 string
            }
        }

        $("#controlFile").change(function() {
            $(".show_img_prev_post").css({'display':'block'});
            $("#name_btn_img_prev").html('Заменить');
            readURL(this);
        });

        const editor = new EditorJS({
            autofocus: true,
            tools: {
                // image: {
                //     class: ImageTool,
                //     inlineToolbar: true,
                //     config: {
                //         endpoints: {
                //             byFile: 'http://localhost:8000/image', // Your backend file uploader endpoint
                //             byUrl: 'http://localhost:8000/image-by-url', // Your endpoint that provides uploading by Url
                //         },
                //         field: 'image',
                //         types: 'image/*'
                //     }
                // },
                image: { // кастомный компонент image
                    class: SimpleImage,
                    inlineToolbar: true,
                    config: {
                        placeholder: 'Paste image URL'
                    }
                },
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

        // $("#controlFile").click(function () {
        //     console.log($(this))
        // }) добавить вывод выбранного изображения

        $("#heading").keyup(function () {
            let h = $(this).val();

            $(".errorHeading").html('');
            activeBtn(false);

            if (h?.length  > 50) {
                $(".errorHeading").html('<div class="alert alert-warning">Заголовок не может быть > 50 символов!</div>')
                activeBtn(true);
            }

            if (h?.length  < 6) {
                $(".errorHeading").html('<div class="alert alert-warning">Заголовок не может быть < 6 символов!</div>')
                activeBtn(true);
            }
        })

        $("#description").keyup(function () {
            let d = $(this).val();

            $(".errorDescription").html('');
            activeBtn(false);

            if (d?.length  > 777) {
                $(".errorDescription").html('<div class="alert alert-warning">Заголовок не может быть > 777 символов!</div>')
                activeBtn(true);
            }

            if (d?.length  < 20) {
                $(".errorDescription").html('<div class="alert alert-warning">Описание не может быть < 20 символов!</div>')
                activeBtn(true);
            }
        })

        $('#forCreatePost').submit(function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            let msg = $('.msg');

            // const output = document.getElementById('output');
            editor.save().then(savedData => {
                formData.append('body', JSON.stringify(savedData));
                formData.set('tags', $("#tagBox").tagging( "getTags" ));

                $.ajax({
                    url: '../../api/post/create.php',
                    type: 'POST',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: function (data) {
                        console.log(data)
                        activeBtn(false);
                        if (data?.error) {
                            activeBtn(true);
                            msg.html(`<div class="alert alert-warning">${data?.error}</div>`);
                        } else {
                            activeBtn(true);
                            msg.html(`<div class="alert alert-success">Пост создан.</div>`);
                            setTimeout(function(){
                                window.location.href = '/';
                            }, 2000);
                        }
                    },
                    error: function (data) {
                        activeBtn(false);
                        msg.html(`<div class="alert alert-danger">${data?.responseJSON?.error}</div>`);
                    }
                })
                // output.innerHTML = JSON.stringify(savedData, null, 4);
            })
        });
    } else {
        alert('Ваша версия браузера устарела, поддержки не будет)');
    }
});
