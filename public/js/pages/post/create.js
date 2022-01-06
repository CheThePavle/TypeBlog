$(function() {
    if (window?.XMLHttpRequest) {
        const activeBtn = (bool) => {
            $('.btn-send').prop('disabled', bool);
        }

        $("#heading").keyup(function () {
            let h = $(this).val();
            $(this).val(normalizeSpace(h));

            $(".errorHeading").html('');
            activeBtn(false);

            if (h?.length  < 6) {
                $(".errorHeading").html('<div class="alert alert-warning">Название не может быть меньше 6 символов!</div>')
                activeBtn(true);
            }
        })

        $("#description").keyup(function () {
            let d = $(this).val();
            $(this).val(normalizeSpace(d));

            $(".errorDescription").html('');
            activeBtn(false);

            if (d?.length  < 20) {
                $(".errorDescription").html('<div class="alert alert-warning">Описание не может быть меньше 20 символов!</div>')
                activeBtn(true);
            }
        })

        // http://verstaemvse.ru/plagin-sozdaniya-polya-dlya-vvoda-tegov-taggingjs.html
        var my_custom_options = {
            "case-sensitive": false, // нижний регистр
            "no-duplicate": true, // нет повторений
            "forbidden-chars-text": '',
            "forbidden-chars-callback": function(el){
                $(".errorTags").html('<div class="alert alert-warning">Такой символ запрещен - '+el+'</div>');
                setTimeout(() => {
                    $(".errorTags").html('')
                }, 3000)
            },
            "no-duplicate-text": '',
            "no-duplicate-callback": function(el){
                $(".errorTags").html('<div class="alert alert-warning">Такой тег уже написан - '+el+'</div>');
                setTimeout(() => {
                    $(".errorTags").html('')
                }, 3000)
            },
            "edit-on-delete": false, // удалить тег сразу не изменяя его
            "forbidden-chars": [
                "-", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")",
                "=", "_", "+", "`", "~", "\"", "№", ";", "%", ":", "?",
                "|", "{", "}", "[", "]", "\\", "\'", "<", ">", ",", ".", "/"],
            "tag-char": "#",
            // "forbidden-words": [], // массив запрещенных слов
            // "forbidden-words-callback": ''
        };
        $("#tagBox").tagging(my_custom_options);
        // узнать почему спамит ошибка в консоль при удалении ничего в input)
        // добавить проверку на длину тега, проверять через массив тегов
        $(".type-zone").keyup(function () {
            let getLengthTags = $('#tagBox .tag').length;
            if (getLengthTags > 10) {
                $('#tagBox .tag:last').remove();
                $(".errorTags").html('<div class="alert alert-warning">Можно только не более 10 тегов!</div>');
                setTimeout(() => {
                    $(".errorTags").html('')
                }, 3000)
            }
        })

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
            // выводить название файла???
            $("#img_uploaded").html('Файл загружен');
            readURL(this);
        });

        const editor = new EditorJS({
            autofocus: true,
            tools: {
                image: {
                    class: SimpleImage,
                    inlineToolbar: true,
                    config: {
                        placeholder: 'Paste image URL'
                    }
                },
                checklist: { // ошибки валяться в консоль?
                    class: Checklist,
                    inlineToolbar: true,
                },
                textVariant: TextVariantTune,
                paragraph: {
                    class: Paragraph,
                    inlineToolbar: true,
                    tunes: ['textVariant']
                },
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
            }
        });

        $('#forCreatePost').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            let msg = $('.msg');

            if (!$('#controlFile').val()) {
                // баг если загрузили и захотели заменить, но не заменили, то условие выполняется fixme
                return msg.html(`<div class="alert alert-warning">Загрузите главное изображение</div>`);
            }

            // const output = document.getElementById('output');
            editor.save().then(savedData => {
                // возможно тут ограничение передачи body в php 27 000 байт
                formData.append('body', JSON.stringify(savedData));
                formData.set('tags', $("#tagBox").tagging( "getTags" ));
                // console.log(savedData)

                $.ajax({
                    url: '../../api/post/create.php',
                    type: 'POST',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: function (data) {
                        // console.log(data)
                        activeBtn(false);
                        if (data?.error) {
                            activeBtn(true);
                            msg.html(`<div class="alert alert-warning">${data?.error}</div>`);
                        } else if (data?.status == 200) {
                            activeBtn(true);
                            msg.html(`<div class="alert alert-success">Пост создан.</div>`);
                            setTimeout(function(){
                                window.location.href = '/';
                            }, 2000);
                        }
                    },
                    error: function (data) {
                        // console.log(data)
                        activeBtn(false);
                        msg.html(`<div class="alert alert-danger">${data?.responseJSON?.error || 'Что-то пошло не так!'}</div>`);
                    }
                })
                // output.innerHTML = JSON.stringify(savedData, null, 4);
            })
        });
    } else {
        alert('Ваша версия браузера устарела, поддержки не будет)');
    }
});
