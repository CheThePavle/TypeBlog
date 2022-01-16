<?php
    require_once "components/header.php";
    require_once "src/usecases/tag/list.php" // return $getList
?>

<div class="tabbable">
    <ul class="nav nav-tabs">
        <li><a href="/lk">Профиль</a></li>
        <li><a href="/post/create">Создать пост</a></li>
        <li><a href="/post/list/all">Список постов</a></li>
        <li><a href="/user/complaint">Жалобы на пользователей</a></li>
        <li><a href="/user/list">Список пользователей</a></li>
        <li><a href="/category/list">Категории</a></li>
        <li class="active"><a href="/tag/list" data-toggle="tab">Теги</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active table-responsive">
            <table class="table table-striped body_tag">
                <thead>
                <tr>
                    <th scope="col">Id тега</th>
                    <th scope="col">Название</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($getList as $el) {
                        $idTag = $el['id_tag'];
                        $nameTag = $el['name_tag'];
                        $publicTag = $el['public_tag'];

                        echo "<tr class='id_tag' data-id='$idTag'>";
                        echo "<th scope='row'>$idTag</th>";
                        echo "<td class='val_tag'>$nameTag</td>";
                        echo $publicTag == '1'
                            ? "<td class='unpublishedTd'><button type='button' class='btn btn-warning unpublished'>Скрыть</button></td>"
                            : "<td class='publishedTd'><button type='button' class='btn btn-success published'>Опубликовать</button></td>";
                        echo "<td class='body_change_tag'></td>";
                        echo "<td class='deletedTd'><button type='button' class='btn btn-primary changed'>Изменить</button></td>";
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

