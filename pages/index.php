<?php
    require_once "components/header.php";
    require_once "src/usecases/index.php"; // return $getCategory, $getTag
?>

    <h1>
        <?php
            switch ($_GET['order']) {
                case 1:
                    echo "Старые посты";
                    break;
                case 2:
                    echo "Популярные посты";
                    break;
                case 3:
                    echo "Лучшие посты";
                    break;
                default:
                    echo "Последние посты";
            }
        ?>
    </h1>
    <hr>
    <div class="row">
        <div class="col-md-8">
            <div class="posts"></div>
            <div class="msg"></div>
            <?php require_once "components/postLoader.php"?>
        </div>
        <div class="col-md-4 sidebar">
            <form>
                <h3 class="header_sidebar">Сортировка</h3>
                <div class="btn-group">
                    <div class="btn-group">
                        <select name="order" >
                            <option value="1" <?php echo $_GET['order'] == 1 ? 'selected' : '' ?>>Сначала старые</option>
                            <option value="0" <?php echo $_GET['order'] == 0 ? 'selected' : '' ?>>Сначала новые</option>
                            <option value="2" <?php echo $_GET['order'] == 2 ? 'selected' : '' ?> disabled>По популярности</option>
                            <option value="3" <?php echo $_GET['order'] == 3 ? 'selected' : '' ?> disabled>По рейтингу</option>
                        </select>
                    </div>
                </div>
                <br><br>
                <h3 class="header_sidebar">Фильтр</h3>
                <p><b>Категория:</b></p>
                <?php
                    if ($getCategory) {
                        foreach ($getCategory as $el) {
                            $idCategory = $el['id_category'];
                            $checked = ($_GET['category'] === $idCategory ? 'checked' : $idCategory == 1) ? 'checked' : '';
                            echo "<label class='labelCategory' for='idC$idCategory'><input id='idC$idCategory' ".$checked." name='category' type='radio' value='".$idCategory."'> ".$el['name_category']."</label>";
                        }
                    }
                ?>
                <p><b>Теги:</b></p>
                <p>
                    <?php
                        if ($getTag) {
                            if ($_GET['tag']) {
                                foreach ($_GET['tag'] as $item) {
                                    $getTag[$item - 1]['checked'] = true;
                                }
                            }
                            foreach ($getTag as $el) {
                                $checked = $el['checked'] ? 'checked' : '';
                                $idTag = $el['id_tag'];
                                $nameTag = $el['name_tag'];
                                echo "<label class='labelTag' for='idT$idTag'><input id='idT$idTag' $checked type='checkbox' name='tag[]' value='$idTag'> $nameTag </label> ";
                            }
                        }
                    ?>
                </p>
                <button type="submit" class="btn btn-success btn_search">Применить</button>
                <input type="button" class="btn btn-danger reset_btn" value="Сбросить">
            </form>
        </div>
    </div>

<?php
    require_once "components/footer.php";
?>
