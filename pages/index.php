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
                <p><b>Категория:</b></p>
                <p><input checked name='category' type='radio' value='all'> Все</p>
                <?php
                    if ($getCategory) {
                        foreach ($getCategory as $el) {
                            $checked = $_GET['category'] === $el['id_category'] ? 'checked' : '';
                            echo "<p><input ".$checked." name='category' type='radio' value='".$el['id_category']."'> ".$el['name_category']."</p>";
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
                                echo "<input ".$checked." type='checkbox' name='tag[]' value='".$el['id_tag']."'> ".$el['name_tag']." ";
                            }
                        }
                    ?>
                </p>
                <button type="submit" class="btn btn-default">Применить</button>
            </form>
            <form action="/">
                <input type="submit" class="btn btn-danger" value="Сбросить">
            </form>
        </div>
    </div>

<!--    <style>@import url("../public/styles/pages/index.css");</style>-->
<!--    <script src="../public/js/pages/index.js"></script>-->

<?php
    require_once "components/footer.php";
?>
