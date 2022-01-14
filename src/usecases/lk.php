<?php
    require_once "src/db.php";

    $getAbout = $db->query("SELECT about FROM user WHERE id_user = ?", [$_SESSION['id']]);
    $getAbout = $getAbout[0]['about'];
