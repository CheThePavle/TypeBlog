<?php
    require_once "../ajax.php";
    require_once "../../src/db.php";
    require_once "../../src/validate.php";

    session_start(); // сессия для подтверждения почты fixme

    if (!$_SESSION['verifyEmail']) {
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

        $mail->Subject = 'Код подтверждения регистрации';

        $code = mt_rand(10000, 99999);
        $_SESSION['verifyEmail'] = $code;

        $mail->Body    = $code . ' код подтверждения для регистрации.';
        $mail->AltBody = '';

        if(!$mail->send()) {
            session_destroy();
            $db->response(409, "Что-то пошло не так, невозможно отправить письмо на вашу почту!");
        } else {
            $db->response(200, "На вашу почту отправлен код подтверждения регистрации!");
        }
    }

    $code = validate($_POST['code']);
    if ($code != $_SESSION['verifyEmail']) {
        session_destroy(); // добавить попытки ввода кода
        $db->response(409, "Код неверный!");
    }

    // добавить проверки допы
    $email = validate($_POST['email']);
    $login = validate($_POST['login']);
    $password = validate($_POST['password']);
    $password2 = validate($_POST['password2']);
    if ($password === $password2) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    } else {
        $db->response(409, "Пароли не совпадают!");
    }

    $about = validate($_POST['about']);
    $created_at = date("Y-m-d H:i:s");

    if (!$_POST['checkbox']) {
        $db->response(409, "Вы не согласились с пользовательским соглашением.");
    }

    $db->make("INSERT INTO `user` (`email`, `login`, `password`, `about`, `created_at`) VALUES (?, ?, ?, ?, ?)",
    [
        $email,
        $login,
        $password,
        $about,
        $created_at
    ]);

    session_destroy();

    $db->response(200, "Успешная регистрация!");

