<?php
    require_once "components/header.php";
    require_once "src/usecases/post/change.php" // return $getCategory
?>

<div class="tabbable">
    <ul class="nav nav-tabs">
        <li><a href="/lk">Профиль</a></li>
        <li><a href="/post/create">Создать пост</a></li>
        <li><a href="/user/change/password">Изменить пароль</a></li>
        <?php
        if ($_SESSION['code_role'] === 'admin') {
            echo '<li><a href="/post/list/all">Список постов</a></li>';
        }
        if ($_SESSION['code_role'] === 'moderator') {
            echo '<li><a href="/post/list">Список отправленных постов</a></li>';
        }
        if ($_SESSION['code_role'] === 'admin' || $_SESSION['code_role'] === 'moderator') {
            echo '<li><a href="/user/complaint">Жалобы на пользователей</a></li>';
        }
        if ($_SESSION['code_role'] === 'admin') {
            echo '<li><a href="/user/list">Список пользователей</a></li>';
            echo '<li><a href="/category/list">Категории</a></li>';
            echo '<li><a href="/tag/list">Теги</a></li>';
        }
        ?>
        <li class="active"><a href="/post/change/<?= $urls[2] ?>" data-toggle="tab">Изменение поста</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active">
            <form id="formChangePost" enctype="multipart/form-data">
                <div class="controls">
                    <div class="row">
                        <div class="col-md-12">
                            <h2>Превью поста</h2>
                            <div class="form-group">
                                <label>Название <span class="required">*</span></label>
                                <input value="<?= $getPostInfo['heading']; ?>" id="heading" maxlength="200" type="text" name="heading" class="form-control" placeholder="Введите заголовок поста *" required="required">
                                <div class="errorHeading"></div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label>Загрузить главное изображение (форматы jpg, png и gif, не более 5 Мегабайт) <span class="required">*</span></label>
                            <div class="form-group download_img_prev">
                                <button type="button" id="name_btn_img_prev">Заменить</button>
                                <div id="img_uploaded">Выберите файл или перетащите его сюда</div>
                                <input type="file" name="image" class="form-control-file" id="controlFile" accept=".jpg, .jpeg, .png, .gif">
                            </div>
                            <div class="show_img_prev_post">
                                <img id="show_img_prev" src="/data/posts/<?= $getPostInfo['main_folder'] . '/main_img.' . $getPostInfo['main_img_type']; ?>" alt="prev_img_post" />
                                <br><br>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Кратко о чем пост <span class="required">*</span></label>
                                <textarea maxlength="1000" rows="10" id="description" name="description" class="form-control" placeholder="Опишите о чем будет пост *" required="required"><?= $getPostInfo['description']; ?></textarea>
                                <div class="errorDescription"></div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label>Выберите категорию <span class="required">*</span></label>
                            <br>
                            <?php
                            if ($getCategory) {
                                echo "<select name='category'>";
                                foreach ($getCategory as $el) {
                                    if ($el['id_category'] == $getPostInfo['id_category']) {
                                        echo "<option selected value='".$el['id_category']."'>".$el['name_category']."</option>";
                                    } else {
                                        echo "<option value='".$el['id_category']."'>".$el['name_category']."</option>";
                                    }
                                }
                                echo "</select>";
                            } else {
                                echo "<select name='category'>";
                                echo "<option value='1'>Разное</option>";
                                echo "</select>";
                            }
                            ?>
                        </div>

                        <div class="col-md-12">
                            <br>
                            <label>Теги</label>
                            <br>
                            <div data-tags-input-name="tag" id="tagBox"></div>
                            <div class="errorTags"></div>
                        </div>

                        <div class="col-md-12">
                            <h2>Содержание поста</h2>
                            <div id="editorjs"></div>
                        </div>

                        <div class="col-md-12">
                            <input type="submit" class="btn btn-success btn-send" value="Изменить">
                        </div>
                    </div>
                </div>
                <br>
                <div class="msg"></div>
            </form>
        </div>
    </div>
</div>

<?php
    require_once "components/footer.php";
?>
