<?php
    require_once "components/header.php";
    require_once "src/usecases/category/list.php" // return $getList
?>

<div class="tabbable">
    <ul class="nav nav-tabs">
        <li><a href="/lk">Профиль</a></li>
        <li><a href="/post/create">Создать пост</a></li>
        <li><a href="/user/change/password">Изменить пароль</a></li>
        <li><a href="/post/list/all">Список постов</a></li>
        <li><a href="/user/complaint">Жалобы на пользователей</a></li>
        <li><a href="/user/list">Список пользователей</a></li>
        <li class="active"><a href="/category/list" data-toggle="tab">Категории</a></li>
        <li><a href="/tag/list">Теги</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active table-responsive">
            <form id="formCreateCategory">
                <br>
                <div class="form-group col-md-3">
                    <input class="form-control" type="text" name="name_category" maxlength="30">
                </div>
                <button type='submit' class='btn btn-success add'>Добавить</button>
                <br>
            </form>
            <table class="table table-striped body_category">
                <thead>
                <tr>
                    <th scope="col">Id категории</th>
                    <th scope="col">Название</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($getList as $el) {
                        $idCategory = $el['id_category'];
                        $nameCategory = $el['name_category'];

                        echo "<tr class='id_category' data-id='$idCategory'>";
                        echo "<th scope='row'>$idCategory</th>";
                        echo "<td class='val_category'>$nameCategory</td>";
                        echo "<td class='body_change_category'></td>";
                        echo "<td class='changedTd'><button type='button' class='btn btn-primary changed'>Изменить</button></td>";
                        echo "<td class='deletedTd'><button type='button' class='btn btn-danger deleted'>Удалить</button></td>";
                        echo "</tr>";
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
    require_once "components/footer.php";
?>

