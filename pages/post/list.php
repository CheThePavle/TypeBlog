<?php
    require_once "components/header.php";
    require_once "src/usecases/post/list.php" // return $getList
?>

<div class="tabbable">
    <ul class="nav nav-tabs">
        <li><a href="/lk">Профиль</a></li>
        <li><a href="/post/create">Создать пост</a></li>
        <li class="active"><a href="/post/list" data-toggle="tab">Список постов</a></li>
        <li><a href="/user/complaint">Жалобы на пользователей</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active table-responsive">
            <table class="table table-striped body_posts">
                <thead>
                <tr>
                    <th scope="col">Id поста</th>
                    <th scope="col">Заголовок</th>
                    <th scope="col">Создатель</th>
                    <th scope="col">Дата и время создания</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($getList as $el) {
                        $idPost = $el['id_post'];
                        $headerPost = $el['heading'];
                        $creatorIdPost = $el['id_user'];
                        $creatorPost = $el['login'];
                        $dataCreated = $el['created_at'];

                        echo "<tr class='id_post' data-id='$idPost'>";
                        echo "<th scope='row'><a href='/post/$idPost' target='_blank'>$idPost</a></th>";
                        echo "<td>$headerPost</td>";
                        echo "<td><a href='/profile/$creatorIdPost' target='_blank'>$creatorPost</a></td>";
                        echo "<td>$dataCreated</td>";
                        echo "<td><button type='button' class='btn btn-success published'>Опубликовать</button></td>";
                        echo "<td><button type='button' class='btn btn-danger rejected'>Отклонить</button></td>";
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

