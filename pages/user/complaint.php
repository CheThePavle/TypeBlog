<?php
    require_once "components/header.php";
    require_once "src/usecases/user/complaint.php"; // return $tree
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
                echo '<li><a href="/post/list">Отправленные посты</a></li>';
            }
        ?>
        <li class="active"><a href="/user/complaint" data-toggle="tab">Жалобы на пользователей</a></li>
        <?php
            if ($_SESSION['code_role'] === 'admin') {
                echo '<li><a href="/user/list">Список пользователей</a></li>';
                echo '<li><a href="/category/list">Категории</a></li>';
                echo '<li><a href="/tag/list">Теги</a></li>';
            }
        ?>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active table-responsive">
            <table class="table table-striped body_complaints">
                <thead>
                <tr>
                    <th scope="col">Id жалобы</th>
                    <th scope="col">Причина</th>
                    <th scope="col">Обвиняемый</th>
                    <th scope="col">Отправитель</th>
                    <th scope="col">Дата жалобы</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($treeComplaint as $el) {
                            if (count($el) === 1) {
                                $idComplaint = $el[0]['id_complaint'];
                                $bodyComplaint = $el[0]['body_complaint'];
                                $idAccused = $el[0]['id_accused'];
                                $loginAccused = $el[0]['accused'];
                                $idWitness = $el[0]['id_witness'];
                                $loginWitness = $el[0]['witness'];
                                $createdAt = $el[0]['created_at'];

                                echo "<tr class='id_complaint' data-id='$idComplaint'>";
                                echo "<th>$idComplaint</th>";
                                echo "<td>$bodyComplaint</td>";
                                echo "<td><a href='/profile/$idAccused' target='_blank'>$loginAccused</a></td>";
                                echo "<td><a href='/profile/$idWitness' target='_blank'>$loginWitness</a></td>";
                                echo "<td>$createdAt</td>";
                                echo "<td><button type='button' class='btn btn-danger blocked'>Заблокировать</button></td>";
                                echo "<td><button type='button' class='btn btn-success rejected'>Отклонить</button></td>";
                                echo "</tr>";
                            } else {
                                $idComplaint = $el[0]['id_complaint'];
                                $idAccused = $el[0]['id_accused'];
                                $loginAccused = $el[0]['accused'];

                                echo "<tr class='more id_complaint' data-id='$idComplaint'>";
                                echo "<td><i class='glyphicon glyphicon-plus glyphicon$idComplaint'></i></td>";
                                echo "<td></td>";
                                echo "<td><a href='/profile/$idAccused' target='_blank'>$loginAccused</td>";
                                echo "<td></td>";
                                echo "<td></td>";
                                echo "<td><button type='button' class='btn btn-danger blocked'>Заблокировать</button></td>";
                                echo "<td><button type='button' class='btn btn-success rejected'>Отклонить</button></td>";
                                echo "</tr>";

                                foreach ($el as $item) {
                                    $childIdComplaint = $item['id_complaint'];
                                    $idWitness = $item['id_witness'];
                                    $loginWitness = $item['witness'];
                                    $bodyComplaint = $item['body_complaint'];
                                    $createdAt = $item['created_at'];

                                    echo "<tr class='collapse row$idComplaint'>";
                                    echo "<th>$childIdComplaint</a></th>";
                                    echo "<td>$bodyComplaint</td>";
                                    echo "<td></td>";
                                    echo "<td><a href='/profile/$idWitness' target='_blank'>$loginWitness</a></td>";
                                    echo "<td>$createdAt</td>";
                                    echo "</tr>";
                                }
                            }
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

