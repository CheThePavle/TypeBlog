<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    $login = validate($_POST['login']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $password2 = validate($_POST['password2']);
    $about = validate($_POST['about']);
    $checkAccept = $_POST['checkbox'];

    if (!$login) {
        $db->response(409, "Логин должен быть указан обязательно!");
    }
    if (iconv_strlen($login) < 5) {
        $db->response(409, "Логин не может быть меньше 5 символов!");
    }
    if (iconv_strlen($login) > 25) {
        $db->response(409, "Логин не может быть больше 25 символов!");
    }
    if (!$email) {
        $db->response(409, "Почта должна быть указана обязательно!");
    }
    if (iconv_strlen($email) > 100) {
        $db->response(409, "Почта не может быть больше 100 символов!");
    }
    if (!$password) {
        $db->response(409, "Пароль должен быть указан обязательно!");
    }
    if (iconv_strlen($password) < 6) {
        $db->response(409, "Пароль не может быть меньше 6 символов!");
    }
    if (iconv_strlen($password) > 32) {
        $db->response(409, "Пароль не может быть больше 32 символов!");
    }
    if ($password == $password2) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    } else {
        $db->response(409, "Пароли не совпадают!");
    }
    if (iconv_strlen($about) > 1000) {
        $db->response(409, "О себе не может быть больше 1000 символов!");
    }
    if (!$checkAccept) {
        $db->response(409, "Вы не согласились с пользовательским соглашением!");
    }

    function deleteVerifySession() {
        unset($_SESSION['verifyCode']);
        unset($_SESSION['attempts']);
    }

    if (!$_SESSION['verifyCode']) {
        require_once('../../libs/phpmailer/PHPMailerAutoload.php');

        $mail = new PHPMailer;
        $mail->CharSet = 'utf-8';
        $mail->isSMTP();
        $mail->Host = 'smtp.yandex.ru';
        $mail->SMTPAuth = true;
        $mail->Username = 'verify.email.blog@yandex.ru'; // Ваш логин от почты с которой будут отправляться письма
        $mail->Password = 'test123'; // Ваш пароль от почты с которой будут отправляться письма
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->setFrom('verify.email.blog@yandex.ru'); // от кого будет уходить письмо?
        $mail->addAddress($email); // Кому будет уходить письмо
        $mail->isHTML(true);
        $mail->Subject = "Код для подтверждения регистрации пользователя - $login";
        $mail->AltBody = '';

        $code = mt_rand(10000, 99999);
        $_SESSION['verifyCode'] = $code;
        $_SESSION['attempts'] = 4;
        $mail->Body = "Ваш код $code";

        if(!$mail->send()) {
            deleteVerifySession();
            $db->response(409, "Невозможно отправить письмо на не существующую почту");
        } else {
            $db->response(200, "На вашу почту отправлен код подтверждения регистрации!");
        }
    }

    $code = validate($_POST['code']);
    if ($code) {
        if ($code == $_SESSION['verifyCode']) {
            $res = $db->make("INSERT INTO `user` (`email`, `login`, `password`, `about`, `updated_at`) VALUES (?, ?, ?, ?, ?);",
                [
                    $email,
                    $login,
                    $password,
                    $about,
                    NULL
                ]);

            if (!$res) {
                $db->response(409, "Что-то пошло не так, невозможно зарегистрироваться!");
            }

            deleteVerifySession();
            $db->response(200, "Успешная регистрация!", ["redirect"=>true]);
        }
        if ($code !== $_SESSION['verifyCode']) {
            $_SESSION['attempts'] = $_SESSION['attempts'] - 1;
            if ($_SESSION['attempts'] === 0) {
                deleteVerifySession();
                $db->response(409, "Не удалось подтвердить почту", ["redirect"=>true]);
            }
            if ($_SESSION['attempts'] === 1) {
                $db->response(409, "Код неверный, у вас осталась 1 попытка!");
            }
            if ($_SESSION['attempts'] === 2) {
                $db->response(409, "Код неверный, у вас осталась 2 попытки!");
            }
            if ($_SESSION['attempts'] === 3) {
                $db->response(409, "Код неверный, у вас осталась 3 попытки!");
            }
        }
    }
