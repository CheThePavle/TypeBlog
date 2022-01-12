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
                        data: data.body
                    });
                }
            },
            error: function () {
                $(".msg").html('<div class="alert alert-danger">Что-то пошло не так!</div>')
            }
        })


        //динамическая подгрузка комментариев на страницу добавить при прокрутке до ввода комменатрия
        function getComments(refresh) {
            if (refresh) {
                let comments = $('.comments');
                let heightComments = comments.height();
                $('.msgEmptyComments').css(({
                    height: heightComments
                }));
                comments.html('');
                //добавить прелоадер при обновлении комментариев
            }

            let msg = $('.msgEmptyComments');

            // вывести вложенные комментарии + добавить onclick на лайки
            $.ajax({
                url: '../api/post/findCommentByPostId.php',
                type: 'POST',
                data: {
                    idPost: id
                },
                success: function (d) {
                    // console.log(data)
                    let comments = d?.result?.data;
                    if (d?.status == 200 && comments?.length != 0) {
                        //start func - вынести в отдельную функцию

                        function buildHierarchy(arry) {
                            let roots = [],
                                children = {};

                            // find the top level nodes and hash the children based on parent
                            for (let i = 0, len = arry.length; i < len; ++i) {
                                let item = arry[i],
                                    p = item.id_parent_comment,
                                    target = !p ? roots : (children[p] || (children[p] = []));

                                target.push({
                                    value: item
                                });
                            }

                            // function to recursively build the tree
                            let findChildren = function(id_parent_comment) {
                                if (children[id_parent_comment.value.id_comment]) {
                                    id_parent_comment.children = children[id_parent_comment.value.id_comment];
                                    for (let i = 0, len = id_parent_comment.children.length; i < len; ++i) {
                                        findChildren(id_parent_comment.children[i]);
                                    }
                                }
                            };

                            // enumerate through to handle the case where there are multiple roots
                            for (let i = 0, len = roots.length; i < len; ++i) {
                                findChildren(roots[i]);
                            }

                            return roots;
                        }

                        let hierarchy = buildHierarchy(comments);

                        // оптимизировать data-id="${li_obj.value?.id_comment}"
                        (function() {
                            $.fn.appendList = function(array) {
                                let $list = $('<ul></ul>');

                                array.forEach(function(li_obj) {
                                    //console.log(li_obj);
                                    let $li = $(`<li>
                                            <div class="media-block id_comment" data-id="${li_obj.value?.id_comment}">
                                                <a class="media-left" target="_blank" href="/profile/${li_obj.value?.id_author}">
                                                    <img class="img-circle img-sm" alt="Аватар ${li_obj.value?.login}" src="${li_obj.value?.avatar_url}">
                                                </a>
                                                <div class="media-body">
                                                    <div class="mar-btm">
                                                        <a href="/profile/${li_obj.value?.id_author}" class="btn-link text-semibold media-heading box-inline" target="_blank">${li_obj.value?.login}</a>
                                                        <span 
                                                            class="comment_rating ${+li_obj.value?.like > 0 ? 'positiveRating' : +li_obj.value?.like < 0 ? 'negativeRating' : 'neutralRating'}"
                                                            data-id="${li_obj.value?.id_comment}"
                                                        >
                                                            ${+li_obj.value?.like > 0 ? '+' +li_obj.value?.like : +li_obj.value?.like}
                                                        </span>
                                                        ${li_obj.value?.updated_at 
                                                            ? `<span class="text-muted text-sm"><i class="fa fa-lg"></i>Изменен (${li_obj.value?.updated_at})</span>`
                                                            : ''}
                                                        <p class="text-muted text-sm"><i class="fa fa-lg"></i>${li_obj.value?.created_at}</p>
                                                    </div>
                                                    <p class="val_comment">${li_obj.value?.body_comment}</p>
                                                    <div class="pad-ver">
                                                        <div class="btn-group">
                                                            <span data-id="${li_obj.value?.id_comment}" class="btn btn-sm btn-default btn-hover-success like ${li_obj.value?.myLike == true ? 'activeLike' : ''}" >
                                                                <i class="fa fa-thumbs-up"></i>
                                                            </span>
                                                            <span data-id="${li_obj.value?.id_comment}" class="btn btn-sm btn-default btn-hover-danger dislike ${li_obj.value?.myLike == false ? 'activeDislike' : ''}">
                                                                <i class="fa fa-thumbs-down"></i>
                                                            </span>
                                                        </div>
                                                        ${li_obj.value?.myComment
                                                            ? `<span class="btn btn-sm btn-default btn-hover-primary change" data-id="${li_obj.value?.id_comment}">Изменить</span>`
                                                            : `<span class="btn btn-sm btn-default btn-hover-primary reply" data-id="${li_obj.value?.id_comment}">Ответить</span>`}
                                                    </div>
                                                    <br>
                                                    <div class="body_reply" data-id="${li_obj.value?.id_comment}"></div>
                                                    <div class="body_change" data-id="${li_obj.value?.id_comment}"></div>
                                                </div>
                                            </div>
                                        </li>`);
                                    if (li_obj.children) {
                                        $li.appendList( li_obj.children );
                                    }
                                    $list.append($li);
                                });
                                return this.append($list);
                            };
                        })();
                        $('.comments').appendList(hierarchy);
                        // end func
                    } else {
                        msg.html(`<div class="alert alert-success">Пока нету комментариев :(</div>`);
                    }
                },
                error: function (data) {
                    msg.html(`<div class="alert alert-danger">${data?.responseJSON?.result?.msg}</div>`);
                }
            })
        }
        getComments();


        $(".comments").on( "click", ".like", function() {
            let getIdComment = $(this).data('id');
            $.ajax({
                url: '../api/post/likeComment.php',
                type: 'POST',
                data: {
                    getIdComment
                },
                success: function (data) {
                    // console.log(data)
                    if (data?.status === 200) {
                        // добавить изменение рейтинга коммента при оценивании (локально не обновляя все комменты)
                        let like = $(`.like[data-id="${getIdComment}"]`);
                        let dislike = $(`.dislike[data-id="${getIdComment}"]`);
                        //
                        // let arryClass = like.attr('class').split(' ');
                        // let checkActive = arryClass[arryClass.length - 1];
                        // if (checkActive != 'activeLike' && checkActive != 'activeDislike') {
                        //     let ratingHTML = $(`.comment_rating[data-id="${getIdComment}"]`);
                        //     let rating = +ratingHTML.text() + 1;
                        //     ratingHTML.text(rating);
                        //
                        //     if (rating == 0) {
                        //         ratingHTML.removeClass();
                        //         ratingHTML.addClass('comment_rating neutralRating');
                        //     } else if (rating > 0) {
                        //         ratingHTML.removeClass();
                        //         ratingHTML.addClass('comment_rating positiveRating');
                        //     } else {
                        //         ratingHTML.removeClass();
                        //         ratingHTML.addClass('comment_rating negativeRating');
                        //     }
                        // }
                        //
                        like.toggleClass('activeLike')
                        dislike.removeClass('activeDislike')

                        $(".notifyComments").html(`<div class="alert alert-success">${data?.result?.msg}</div>`);
                        setTimeout(function () {
                            $(".notifyComments").html(``);
                        }, 3000)
                        getComments(1);
                    }
                },
                error: function (data) {
                    $(".notifyComments").html(`<div class="alert alert-danger">${data?.responseJSON?.error}</div>`);
                    setTimeout(function () {
                        $(".notifyComments").html(``);
                    }, 3000)
                }
            })
        });
        $(".comments").on( "click", ".dislike", function() {
            let getIdComment = $(this).data('id');
            $.ajax({
                url: '../api/post/dislikeComment.php',
                type: 'POST',
                data: {
                    getIdComment
                },
                success: function (data) {
                    if (data?.status == 200) {
                        let dislike = $(`.dislike[data-id="${getIdComment}"]`);
                        let like = $(`.like[data-id="${getIdComment}"]`);
                        dislike.toggleClass('activeDislike')
                        like.removeClass('activeLike')

                        $(".notifyComments").html(`<div class="alert alert-success">${data?.result?.msg}</div>`);
                        setTimeout(function () {
                            $(".notifyComments").html(``);
                        }, 3000)
                        getComments(1);
                    }
                },
                error: function (data) {
                    $(".notifyComments").html(`<div class="alert alert-danger">${data?.responseJSON?.error}</div>`);
                    setTimeout(function () {
                        $(".notifyComments").html(``);
                    }, 3000)
                }
            })
        });


        // изменить id-ки комментов
        // body_reply
        // body_change выводить в зависимости от авторизации пользователя!

        //изменение комментария

        const activeBtnChange = (bool) => {
            $('#btn_send_comment_change').prop('disabled', bool);
        }

        $(".comments").on( "click", ".change", function() {
            $('.body_change').html('');
            let getHTML = $(this).closest('.id_comment');
            let getVal = getHTML.find('.val_comment').text();
            getHTML.find('.body_change').html(`
                <form id="formCommentChange">
                    <textarea name="change_comment" maxlength="1000" id="text_change_comment" class="form-control" rows="2" placeholder="Ваш комментарий">${getVal}</textarea>
                    <div class="errorCommentChange"></div>
                    <div class="msgCommentChange"></div>
                    <div class="mar-top clearfix">
                        <button id="btn_send_comment_change" class="btn btn-sm btn-primary pull-right" type="submit">
                            <i class="fa fa-pencil fa-fw"></i> Изменить
                        </button>
                        <button id="btn_send_comment_change_closed" class="btn btn-sm btn-danger pull-right" type="submit">
                            <i class="fa fa-pencil fa-fw"></i> Отмена
                        </button>
                    </div>
                </form>
            `);
        });

        $(".comments").on( "click", "#btn_send_comment_change_closed", function() {
            $('.body_change').html('');
        });

        $(".comments").on( "keyup", "#text_change_comment", function() {
            let c = $(this).val();
            activeBtnChange(false);

            if (c?.length  < 1) {
                $(".errorCommentChange").html('<div class="alert alert-warning">Ответный комментарий не может быть пустым!</div>')
                activeBtnChange(true);
            } else {
                $(".errorCommentChange").html('');
            }
        });

        $(".comments").on('blur', '#text_change_comment', function () {
            $(".errorCommentChange").html('');
        })

        $(".comments").on("submit", "#formCommentChange", function (e) {
            e.preventDefault();
            activeBtnChange(true);

            let th = $(this);
            let msg = $('.msgCommentChange');

            let getHTML = $(th).closest('.id_comment');
            let getChangeId = getHTML.data('id');

            $.ajax({
                url: '../api/post/changeComment.php',
                type: 'POST',
                data: th.serialize() + '&idComment=' + getChangeId,
                success: function (data) {
                    activeBtnChange(true);
                    if (data?.status == 200) {
                        msg.html(`<div class="alert alert-success">Ответный комментарий добавлен!</div>`);
                        $("#text_change_comment").val('');
                        $("#text_change_comment").css({
                            height: "auto"
                        });
                        setTimeout(function () {
                            msg.html(``);
                        }, 3000);
                        getComments(1);
                    }
                },
                error: function (data) {
                    activeBtnChange(true);
                    msg.html(`<div class="alert alert-danger">${data?.responseJSON?.error}</div>`);
                    setTimeout(function () {
                        msg.html(``);
                    }, 3000);
                }
            })
        });


        // отправка ответного комментария
        const activeBtnReply = (bool) => {
            $('#btn_send_comment_reply').prop('disabled', bool);
        }

        $(".comments").on( "click", ".reply", function() {
            $('.body_reply').html('');
            let getReplyId = $(this).data('id');
            $(`.body_reply[data-id="${getReplyId}"]`).html(`
                <form id="formCommentReply">
                    <textarea name="parent_comment" maxlength="1000" id="text_parent_comment" class="form-control" rows="2" placeholder="Напишите Ваш ответный комментарий"></textarea>
                    <div class="errorCommentReply"></div>
                    <div class="msgCommentReply"></div>
                    <div class="mar-top clearfix">
                        <input type="hidden" name="idParentComment" value="${getReplyId}">
                        <button id="btn_send_comment_reply" class="btn btn-sm btn-primary pull-right" type="submit">
                            <i class="fa fa-pencil fa-fw"></i> Ответить
                        </button>
                        <button id="btn_send_comment_reply_closed" class="btn btn-sm btn-danger pull-right" type="submit">
                            <i class="fa fa-pencil fa-fw"></i> Отмена
                        </button>
                    </div>
                </form>
            `);
        });

        // вылазит предупреждение в консоль при отмене
        $(".comments").on( "click", "#btn_send_comment_reply_closed", function() {
            $('.body_reply').html('');
        });

        $(".comments").on( "keyup", "#text_parent_comment", function() {
            let c = $(this).val();
            activeBtnReply(false);

            if (c?.length  < 1) {
                $(".errorCommentReply").html('<div class="alert alert-warning">Ответный комментарий не может быть пустым!</div>')
                activeBtnReply(true);
            } else {
                $(".errorCommentReply").html('');
            }
        });

        $(".comments").on('blur', '#text_parent_comment', function () {
            $(".errorCommentReply").html('');
        })

        $(".comments").on("submit", "#formCommentReply", function (e) {
            e.preventDefault();
            activeBtnReply(true);

            let th = $(this);
            let msg = $('.msgCommentReply');

            $.ajax({
                url: '../api/post/createCommentReply.php',
                type: 'POST',
                data: th.serialize() + '&idPost=' + id,
                success: function (data) {
                    activeBtnReply(true);
                    if (data?.status == 200) {
                        msg.html(`<div class="alert alert-success">Ответный комментарий добавлен!</div>`);
                        $("#text_parent_comment").val('');
                        $("#text_parent_comment").css({
                            height: "auto"
                        });
                        setTimeout(function () {
                            msg.html(``);
                        }, 3000);
                        getComments(1);
                    }
                },
                error: function (data) {
                    activeBtnReply(true);
                    msg.html(`<div class="alert alert-danger">${data?.responseJSON?.error}</div>`);
                    setTimeout(function () {
                        msg.html(``);
                    }, 3000);
                }
            })
        });



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
                        getComments(1);
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
