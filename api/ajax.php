<?php
    define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && session_id());

    if (!IS_AJAX) {
        http_response_code(403);
        echo 'нет прав!';
        exit();
    }