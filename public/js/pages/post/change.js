$(function() {
    if (window?.XMLHttpRequest) {
        const activeBtn = (disabled) => {
            $('.btn-send').prop('disabled', disabled);
        }

        $("#heading").keyup(function () {
            let val = $(this).val();
            $(this).val(normalizeSpace(val));
            let errorHeading = $(".errorHeading");

            errorHeading.html('');
            activeBtn(false);

            if (val?.length  < 6) {
                errorHeading.html('<div class="alert alert-warning">Название не может быть меньше 6 символов!</div>')
                activeBtn(true);
            }
        })

        $("#description").keyup(function () {
            let val = $(this).val();
            $(this).val(normalizeSpace(val));
            let errorDescription = $(".errorDescription");

            errorDescription.html('');
            activeBtn(false);

            if (val?.length  < 20) {
                errorDescription.html('<div class="alert alert-warning">Описание не может быть меньше 20 символов!</div>')
                activeBtn(true);
            }
        })

        // http://verstaemvse.ru/plagin-sozdaniya-polya-dlya-vvoda-tegov-taggingjs.html
        let myOptionsTagging = {
            "case-sensitive": false, // нижний регистр
            "no-duplicate": true,
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
        $("#tagBox").tagging(myOptionsTagging);
        $(".type-zone").keyup(function () {
            let getLengthTags = $('#tagBox .tag').length;
            if (getLengthTags > 10) {
                $('#tagBox .tag:last').remove();
                let errorTags = $(".errorTags");
                errorTags.html('<div class="alert alert-warning">Можно только не более 10 тегов!</div>');
                setTimeout(() => {
                    errorTags.html('')
                }, 3000)
            }
        })

        const readURL = (input) => {
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#show_img_prev').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]); // convert to base64 string
            }
        }

        $("#controlFile").change(function(e) {
            let fileName = $(this).val().split('/').pop().split('\\').pop();
            if (!fileName) {
                $(".show_img_prev_post").css({'display':'none'});
                $("#name_btn_img_prev").html('Выбрать');
                $("#img_uploaded").html('Выберите файл или перетащите его сюда');
            } else {
                $(".show_img_prev_post").css({'display':'block'});
                $("#name_btn_img_prev").html('Заменить');
                $("#img_uploaded").html('Выбран: ' + fileName);
                readURL(this);
            }

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

            let formData = new FormData(this);
            let msg = $('.msg');

            editor.save().then(savedData => {
                // возможно тут ограничение передачи body в php 27 000 байт
                formData.append('body', JSON.stringify(savedData));
                formData.set('tags', $("#tagBox").tagging( "getTags" ));

                $.ajax({
                    url: '../../api/post/create.php',
                    method: 'POST',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: function (d) {
                        activeBtn(true);
                        msg.html(`<div class="alert alert-success">${d?.result?.msg}</div>`);
                        setTimeout(function(){
                            window.location.href = '/';
                        }, 2000);
                    },
                    error: function (d) {
                        activeBtn(true);
                        msg.html(`<div class="alert alert-danger">${d?.responseJSON?.result?.msg || 'Что-то пошло не так!'}</div>`);
                        setTimeout(function(){
                            activeBtn(false);
                        }, 1000);
                    }
                })
            })
        });
    } else {
        alert('Ваша версия браузера устарела, поддержки не будет)');
    }
});
