$(function() { // normalizeSpace()
    if (window?.XMLHttpRequest) {
        // возможно можно просто сразу в php выполнять запрос и выводить на страницу
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

        //динамическая подгрузка комментариев на страницу добавить при прокрутке до ввода комменатрия
                let msg = $('.msgEmptyComments');

        // вывести вложенные комментарии + добавить onclick на лайки
                $.ajax({
                    url: '../api/post/findCommentByPostId.php',
                    type: 'POST',
                    data: {
                        idPost: id
                    },
                    success: function (data) {
                        if (data?.status == 200 && data?.result?.length != 0) {
                            data?.result?.map(function(el) {
                                console.log(el)
                                $('.comments').append(`
                                    <div class="media-block">
                                        <a class="media-left" target="_blank" href="/profile/${el?.id_author}">
                                            <img class="img-circle img-sm" alt="Аватар ${el?.login}" src="${el?.avatar_url}">
                                        </a>
                                        <div class="media-body">
                                            <div class="mar-btm">
                                                <a href="/profile/${el?.id_author}" class="btn-link text-semibold media-heading box-inline" target="_blank">${el?.login}</a>
                                                <span style="color: ${+el?.like > 0 ? 'green' : +el?.like < 0 ? 'red' : 'gray'}">
                                                    ${+el?.like > 0 ? '+' + +el?.like : +el?.like}
                                                </span>
                                                <p class="text-muted text-sm"><i class="fa fa-lg"></i>${el?.created_at}</p>
                                            </div>
                                            <p>${el?.body_comment}</p>
                                            <div class="pad-ver">
                                                <div class="btn-group">
                                                    <span class="btn btn-sm btn-default btn-hover-success like">
                                                        <i class="fa fa-thumbs-up"></i>
                                                    </span>
                                                    <span class="btn btn-sm btn-default btn-hover-danger dislike">
                                                        <i class="fa fa-thumbs-down"></i>
                                                    </span>
                                                </div>
                                                <a class="btn btn-sm btn-default btn-hover-primary" href="#">Ответить</a>
                                            </div>
                                            <br>
                                        </div>
                                    </div>
                                `);
                            });
                        } else {
                            msg.html(`<div class="alert alert-success">Пока нету комментариев :(</div>`);
                        }
                    },
                    error: function (data) {
                        console.log(data)
                        msg.html(`<div class="alert alert-danger">${data?.responseJSON?.error}</div>`);
                    }
                })

        // отправка комментария
        const activeBtn = (bool) => {
            $('#btn_send_comment').prop('disabled', bool);
        }

        // изменить
        document.querySelector('textarea').addEventListener('input', function (e) {
            e.target.style.height = 'auto'
            e.target.style.height = e.target.scrollHeight + 2 + "px"
        })

        $("#text_comment").keyup(function () {
            let c = $(this).val();
            //$(this).val(normalizeSpace(c));
            $(".errorLogin").html('');
            activeBtn(false);

            if (c?.length  < 1) {
                $(".errorComment").html('<div class="alert alert-warning">Комментарий не может быть пустым!</div>')
                activeBtn(true);
            } else {
                $(".errorComment").html('');
            }
        })

        $("#text_comment").blur(function () {
            $(".errorComment").html('');
        })

        $('#formComment').submit(function (e) {
            e.preventDefault();
            activeBtn(true);

            let th = $(this);
            let msg = $('.msgComment');

            $.ajax({
                url: '../api/post/createComment.php',
                type: 'POST',
                data: th.serialize() + '&idPost=' + id,
                success: function (data) {
                    activeBtn(true);
                    if (data?.status == 200) {
                        msg.html(`<div class="alert alert-success">Комментарий успешно добавлен!</div>`);
                        $("#text_comment").val('');
                        $("#text_comment").css({
                            height: "auto"
                        });
                        setTimeout(function () {
                            msg.html(``);
                        }, 3000);
                    }
                },
                error: function (data) {
                    activeBtn(true);
                    msg.html(`<div class="alert alert-danger">${data?.responseJSON?.error}</div>`);
                    setTimeout(function () {
                        msg.html(``);
                    }, 3000);
                }
            })
        });
    } else {
        alert('Ваша версия браузера устарела, поддержки не будет)');
    }
});
