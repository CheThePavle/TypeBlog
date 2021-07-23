<?php
	require_once "components/header.php";
?>

<div class="container">
    <div class="row" >
        <div class="col-lg-8 col-lg-offset-2">
            <h1 class="text-center">Вход</h1>

            <form method="post" action="" role="form">
                <div class="controls">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="login">Логин *</label>
                                <input id="login" type="text" name="login" class="form-control" placeholder="Ваш логин *" required="required">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Пароль *</label>
                                <input id="password" type="password" name="password" class="form-control" placeholder="Ваш пароль *" required="required">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <input type="submit" class="btn btn-success btn-send" value="Войти">
                        </div>
                    </div>
                </div>
            </form>

        </div><!-- /.col-lg-8 col-lg-offset-2 -->
    </div> <!-- /.row-->
</div> <!-- /.container-->

<?php
    require_once "components/footer.php";
?>
