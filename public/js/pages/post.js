$(function() { // normalizeSpace()
    if (window?.XMLHttpRequest) {
        // возможно можно просто сразу в php выполнять запрос и выводить на страницу
        const id = window?.location?.pathname?.substr(6);

        let msgPost = $(".msg");
        let headingPost = $("#heading");
        let likesPost = $(".likesPost");
        let notify = $(".notify");

        let postHTML = $(".body_post");
        postHTML.on( "click", ".likePost", function() {
            let likePost = $(`.likePost`);
            let dislikePost = $(`.dislikePost`);

            $.ajax({
                url: '../api/post/like.php',
                method: 'POST',
                data: {
                    id
                },
                success: function (d) {
                    likePost.toggleClass('activeLike')
                    dislikePost.removeClass('activeDislike')

                    notify.html(`<div class="alert alert-success">${d?.result?.msg}</div>`);
                    setTimeout(function () {
                        notify.html(``);
                    }, 3000)
                },
                error: function (d) {
                    notify.html(`<div class="alert alert-danger">${d?.responseJSON?.result?.msg}</div>`);
                    setTimeout(function () {
                        notify.html(``);
                    }, 3000)
                }
            })
        });
        postHTML.on( "click", ".dislikePost", function() {
            let dislikePost = $(`.dislikePost`);
            let likePost = $(`.likePost`);

            $.ajax({
                url: '../api/post/dislike.php',
                method: 'POST',
                data: {
                    id
                },
                success: function (d) {
                    dislikePost.toggleClass('activeDislike')
                    likePost.removeClass('activeLike')

                    notify.html(`<div class="alert alert-success">${d?.result?.msg}</div>`);
                    setTimeout(function () {
                        notify.html(``);
                    }, 3000)
                },
                error: function (d) {
                    notify.html(`<div class="alert alert-danger">${d?.responseJSON?.result?.msg}</div>`);
                    setTimeout(function () {
                        notify.html(``);
                    }, 3000)
                }
            })
        });

        $.ajax({
            url: '../api/post/findById.php',
            data: {
                id
            },
            success: function (d) {
                d = d?.result?.data[0];
                d.body = JSON.parse(d?.body);
                // console.log(d);
                headingPost.html(`<h1 id="heading">${d?.heading}</h1>`);
                likesPost.html(`
                    <h3>Понравился пост?</h3>
                    <div class="btn-group">
                        <span class="btn btn-sm btn-default btn-hover-success likePost ${d?.myLike == true ? 'activeLike' : ''}" >
                            <i class="fa fa-thumbs-up"></i>
                        </span>
                        <span class="btn btn-sm btn-default btn-hover-danger dislikePost ${d?.myLike == false ? 'activeDislike' : ''}">
                            <i class="fa fa-thumbs-down"></i>
                        </span>
                    </div>
                `);
                $(".prevPost").html(`
                   <div class="hero-unit prev_content">
                        <img src="../data/posts/${d?.main_folder}/main_img.${d?.main_img_type}" alt="Картинка для ${d?.heading}"">
                        <br><br>
                        <p>${d?.description}</p>
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
                    data: d.body
                });
            },
            error: function (d) {
                msgPost.html(`<div class="alert alert-danger">${d?.responseJSON?.result?.msg}</div>`);
                headingPost.html(`<h1 id="heading">Неизвестный заголовок</h1>`);
            }
        })

        let commentsHTML = $(".comments");
        let loaderCommentHTML = $(".loader_comment");

        let loadComments = false;
        $(window).scroll(function() {
            if (!loadComments) {
                let top = $(window).scrollTop();
                let height = $(window).height();
                let set = commentsHTML.offset().top;
                let outerHeight = commentsHTML.outerHeight();
                let dHeight = $(document).height();
                if (top + height >= set || top + height === dHeight || outerHeight + set < top) {
                    getComments();
                    loaderCommentHTML.hide();
                    loadComments = true;
                }
            }
        });

        function getComments(refresh) {
            let msgComment = $('.msgEmptyComments');

            if (refresh) {
                let heightComments = commentsHTML.height();
                msgComment.css(({
                    height: heightComments
                }));
                commentsHTML.html('');
                loaderCommentHTML.show();
            }

            $.ajax({
                url: '../api/comment/findByPostId.php',
                method: 'POST',
                data: {
                    id
                },
                success: function (d) {
                    let comments = d?.result?.data;
                    if (comments?.length !== 0) {
                        renderComments(comments);
                    } else {
                        msgComment.html(`<div class="alert alert-success">Пока нету комментариев :(</div>`);
                    }
                    msgComment.css(({
                        height: "auto"
                    }));
                    loaderCommentHTML.hide();
                },
                error: function (d) {
                    msgComment.html(`<div class="alert alert-danger">${d?.responseJSON?.result?.msg}</div>`);
                    msgComment.css(({
                        height: "auto"
                    }));
                    loaderCommentHTML.hide();
                }
            })
        }

        commentsHTML.on( "click", ".like", function() {
            let getHTML = $(this).closest('.id_comment');
            let getId = getHTML.data('id');

            let like = $(`.like`);
            let dislike = $(`.dislike`);

            $.ajax({
                url: '../api/comment/like.php',
                method: 'POST',
                data: {
                    idComment: getId
                },
                success: function (d) {
                    like.toggleClass('activeLike')
                    dislike.removeClass('activeDislike')

                    notify.html(`<div class="alert alert-success">${d?.result?.msg}</div>`);
                    setTimeout(function () {
                        notify.html(``);
                    }, 3000)
                    getComments(1);
                },
                error: function (d) {
                    notify.html(`<div class="alert alert-danger">${d?.responseJSON?.result?.msg}</div>`);
                    setTimeout(function () {
                        notify.html(``);
                    }, 3000)
                }
            })
        });
        commentsHTML.on( "click", ".dislike", function() {
            let getHTML = $(this).closest('.id_comment');
            let getId = getHTML.data('id');

            let dislike = $(`.dislike`);
            let like = $(`.like`);

            $.ajax({
                url: '../api/comment/dislike.php',
                method: 'POST',
                data: {
                    idComment: getId
                },
                success: function (d) {
                    dislike.toggleClass('activeDislike')
                    like.removeClass('activeLike')

                    notify.html(`<div class="alert alert-success">${d?.result?.msg}</div>`);
                    setTimeout(function () {
                        notify.html(``);
                    }, 3000)
                    getComments(1);
                },
                error: function (d) {
                    notify.html(`<div class="alert alert-danger">${d?.responseJSON?.result?.msg}</div>`);
                    setTimeout(function () {
                        notify.html(``);
                    }, 3000)
                }
            })
        });


        //изменение комментария
        const activeBtnChange = (disabled) => {
            $('#btn_send_comment_change').prop('disabled', disabled);
        }

        commentsHTML.on( "click", ".change", function() {
            let body_change = $('.body_change');
            if (body_change?.length === 0) {
                notify.html(`<div class="alert alert-danger">Изменять свои комментарии могут только зарегистрированные пользователи</div>`);
                return setTimeout(function () {
                    notify.html(``);
                }, 3000);
            }
            body_change.html('');

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

        // вылазит предупреждение в консоль при отмене
        commentsHTML.on( "click", "#btn_send_comment_change_closed", function() {
            $('.body_change').html('');
        });

        commentsHTML.on( "keyup", "#text_change_comment", function() {
            let val = $(this).val();
            let errorCommentChange = $(".errorCommentChange");
            activeBtnChange(false);

            if (val?.length  < 1) {
                errorCommentChange.html('<div class="alert alert-warning">Ответный комментарий не может быть пустым!</div>')
                activeBtnChange(true);
            } else {
                errorCommentChange.html('');
            }
        });

        commentsHTML.on('blur', '#text_change_comment', function () {
            $(".errorCommentChange").html('');
        })

        commentsHTML.on("submit", "#formCommentChange", function (e) {
            e.preventDefault();
            activeBtnChange(true);

            let th = $(this);
            let msgCommentChange = $('.msgCommentChange');
            let text_change_comment = $("#text_change_comment");

            let getHTML = $(th).closest('.id_comment');
            let getChangeId = getHTML.data('id');

            $.ajax({
                url: '../api/comment/change.php',
                method: 'POST',
                data: th.serialize() + '&idComment=' + getChangeId,
                success: function (d) {
                    activeBtnChange(true);
                    msgCommentChange.html(`<div class="alert alert-success">${d?.result?.msg}</div>`);
                    text_change_comment.val('');
                    text_change_comment.css({
                        height: "auto"
                    });
                    setTimeout(function () {
                        msgCommentChange.html(``);
                    }, 3000);
                    getComments(1);
                },
                error: function (d) {
                    activeBtnChange(true);
                    msgCommentChange.html(`<div class="alert alert-danger">${d?.responseJSON?.result?.msg}</div>`);
                    setTimeout(function () {
                        msgCommentChange.html(``);
                    }, 3000);
                }
            })
        });


        // отправка ответного комментария
        const activeBtnReply = (disabled) => {
            $('#btn_send_comment_reply').prop('disabled', disabled);
        }

        commentsHTML.on( "click", ".reply", function() {
            let body_reply = $('.body_reply');
            if (body_reply?.length === 0) {
                notify.html(`<div class="alert alert-danger">Отвечать на комментарии могут только зарегистрированные пользователи</div>`);
                return setTimeout(function () {
                    notify.html(``);
                }, 3000);
            }
            body_reply.html('');

            let getHTML = $(this).closest('.id_comment');
            let getVal = getHTML.find('.val_login').text();
            getHTML.find('.body_reply').html(`
                <form id="formCommentReply">
                    <textarea name="parent_comment" maxlength="1000" id="text_parent_comment" class="form-control" rows="2" placeholder="Напишите Ваш ответный комментарий">${getVal}, </textarea>
                    <div class="errorCommentReply"></div>
                    <div class="msgCommentReply"></div>
                    <div class="mar-top clearfix">
                        <button id="btn_send_comment_reply" class="btn btn-sm btn-primary pull-right" type="submit">
                            <i class="fa fa-pencil fa-fw"></i> Ответить
                        </button>
                        <button id="btn_send_comment_reply_closed" class="btn btn-sm btn-danger pull-right" type="submit">
                            <i class="fa fa-pencil fa-fw"></i> Отмена
                        </button>
                    </div>
                </form>
            `)
        });

        // вылазит предупреждение в консоль при отмене
        commentsHTML.on( "click", "#btn_send_comment_reply_closed", function() {
            $('.body_reply').html('');
        });

        commentsHTML.on( "keyup", "#text_parent_comment", function() {
            let val = $(this).val();
            let errorCommentReply = $(".errorCommentReply");
            activeBtnReply(false);

            if (val?.length  < 1) {
                errorCommentReply.html('<div class="alert alert-warning">Ответный комментарий не может быть пустым!</div>')
                activeBtnReply(true);
            } else {
                errorCommentReply.html('');
            }
        });

        commentsHTML.on('blur', '#text_parent_comment', function () {
            $(".errorCommentReply").html('');
        })

        commentsHTML.on("submit", "#formCommentReply", function (e) {
            e.preventDefault();
            activeBtnReply(true);

            let th = $(this);
            let msgCommentReply = $('.msgCommentReply');
            let text_parent_comment = $("#text_parent_comment");

            let getHTML = $(th).closest('.id_comment');
            let getReplyId = getHTML.data('id');

            $.ajax({
                url: '../api/comment/createReply.php',
                method: 'POST',
                data: th.serialize() + '&idPost=' + id + '&idParentComment=' + getReplyId,
                success: function (d) {
                    activeBtnReply(true);
                    msgCommentReply.html(`<div class="alert alert-success">${d?.result?.msg}</div>`);
                    text_parent_comment.val('');
                    text_parent_comment.css({
                        height: "auto"
                    });
                    setTimeout(function () {
                        msgCommentReply.html(``);
                    }, 3000);
                    getComments(1);
                },
                error: function (d) {
                    activeBtnReply(true);
                    msgCommentReply.html(`<div class="alert alert-danger">${d?.responseJSON?.result?.msg}</div>`);
                    setTimeout(function () {
                        msgCommentReply.html(``);
                    }, 3000);
                }
            })
        });



        // отправка комментария
        const activeBtn = (disabled) => {
            $('#btn_send_comment').prop('disabled', disabled);
        }

        // fixme изменить потом
        document.querySelector('textarea').addEventListener('input', function (e) {
            e.target.style.height = 'auto'
            e.target.style.height = e.target.scrollHeight + 2 + "px"
        })

        let text_comment = $("#text_comment");

        text_comment.keyup(function () {
            let val = $(this).val();
            let errorComment = $(".errorComment");
            activeBtn(false);

            if (val?.length  < 1) {
                errorComment.html('<div class="alert alert-warning">Комментарий не может быть пустым!</div>')
                activeBtn(true);
            } else {
                errorComment.html('');
            }
        })

        text_comment.blur(function () {
            $(".errorComment").html('');
        })

        $('#formComment').submit(function (e) {
            e.preventDefault();
            activeBtn(true);

            let th = $(this);
            let msgComment = $('.msgComment');

            $.ajax({
                url: '../api/comment/create.php',
                method: 'POST',
                data: th.serialize() + '&idPost=' + id,
                success: function (d) {
                    activeBtn(true);
                    msgComment.html(`<div class="alert alert-success">${d?.result?.msg}</div>`);
                    text_comment.val('');
                    text_comment.css({
                        height: "auto"
                    });
                    setTimeout(function () {
                        msgComment.html(``);
                    }, 3000);
                    getComments(1);
                },
                error: function (d) {
                    activeBtn(true);
                    msgComment.html(`<div class="alert alert-danger">${d?.responseJSON?.result?.msg}</div>`);
                    setTimeout(function () {
                        msgComment.html(``);
                    }, 3000);
                }
            })
        });

        function renderComments(comments) {
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
                                                        <a href="/profile/${li_obj.value?.id_author}" class="btn-link text-semibold media-heading box-inline val_login" target="_blank">${li_obj.value?.login}</a>
                                                        <span 
                                                            class="comment_rating ${+li_obj.value?.like > 0 ? 'positiveRating' : +li_obj.value?.like < 0 ? 'negativeRating' : 'neutralRating'}"
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
                                                            <span class="btn btn-sm btn-default btn-hover-success like ${li_obj.value?.myLike == true ? 'activeLike' : ''}" >
                                                                <i class="fa fa-thumbs-up"></i>
                                                            </span>
                                                            <span class="btn btn-sm btn-default btn-hover-danger dislike ${li_obj.value?.myLike == false ? 'activeDislike' : ''}">
                                                                <i class="fa fa-thumbs-down"></i>
                                                            </span>
                                                        </div>
                                                        ${li_obj.value?.myComment
                            ? `<span class="btn btn-sm btn-default btn-hover-primary change">Изменить</span>`
                            : `<span class="btn btn-sm btn-default btn-hover-primary reply">Ответить</span>`}
                                                    </div>
                                                    <br>
                                                    ${li_obj.value?.myComment === true
                                                        ? `<div class="body_change"></div>`
                                                        : li_obj.value?.myComment === false
                                                        ?`<div class="body_reply"></div>`
                                                        : ''}
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
            commentsHTML.appendList(hierarchy);
        }
    } else {
        alert('Ваша версия браузера устарела, поддержки не будет)');
    }
});
