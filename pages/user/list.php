<?php
    require_once "components/header.php";
    require_once "src/usecases/user/list.php" // return $getList
?>

<div class="tabbable">
    <ul class="nav nav-tabs">
        <li><a href="/lk">Профиль</a></li>
        <li><a href="/post/create">Создать пост</a></li>
        <li><a href="/user/change/password">Изменить пароль</a></li>
        <li><a href="/post/list/all">Список постов</a></li>
        <li><a href="/user/complaint">Жалобы на пользователей</a></li>
        <li class="active"><a href="/user/list" data-toggle="tab">Список пользователей</a></li>
        <li><a href="/category/list">Категории</a></li>
        <li><a href="/tag/list">Теги</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active table-responsive">
            <table class="table table-striped body_users">
                <thead>
                <tr>
                    <th scope="col">Id пользователя</th>
                    <th scope="col">Email</th>
                    <th scope="col">Логин</th>
                    <th scope="col">Роль</th>
                    <th scope="col">Дата и время регистрации</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($getList as $el) {
                        $idUser = $el['id_user'];
                        $emailUser = $el['email'];
                        $loginUser = $el['login'];
                        $roleNameUser = $el['name'];
                        $createdAtUser = $el['created_at'];
                        $blockedUser = $el['blocked'];


                        echo "<tr class='id_user' data-id='$idUser'>";
                        echo "<th scope='row'><a href='/profile/$idUser' target='_blank'>$idUser</a></th>";
                        echo "<td>$emailUser</td>";
                        echo "<td><a href='/profile/$idUser' target='_blank'>$loginUser</a></td>";
                        echo "<td>$roleNameUser</td>";
                        echo "<td>$createdAtUser</td>";
                        echo $blockedUser == '1'
                            ? "<td class='unblockedTd'><button type='button' class='btn btn-success unblocked'>Разблокировать</button></td>"
                            : "<td class='blockedTd'><button type='button' class='btn btn-danger blocked'>Заблокировать</button></td>";
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

