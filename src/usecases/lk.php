<?php
    require_once "src/db.php";

    $getInfoUser = $db->query("SELECT about, folder, img_type, email FROM user WHERE id_user = ?;", [$_SESSION['id']]);

