<!DOCTYPE html>
<html lang="zxx">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../libs/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="../libs/bootstrap/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="../libs/bootstrap/js/bootstrap.min.js">
        <link rel="stylesheet" href="../libs/FontAwesome6/css/all.min.css">
        <link rel="stylesheet" href="../public/styles/index.css">
        <link rel="shortcut icon" href="../public/imgs/favicon.png" type="image/x-icon">
        <script src="../libs/jQuery/jquery.min.js"></script>
        <script src="../public/js/loader.js"></script>
        <script src="../public/js/functions/normalizeSpace.js"></script>
        <script src="../public/js/index.js"></script>
        <?= $setStyleAndScript; ?>
    </head>
    <body>
        <div class="preloader">
            <div class="preloader__row">
                <div class="preloader__item"></div>
                <div class="preloader__item"></div>
            </div>
        </div>

        <div class="notify"></div>

        <?php require_once "headerMenu.php" ?>

        <div class="container">
            <div class="row" >
                <div class="col-lg-12">