<?php
    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        http_response_code(403);
        print_r([
            "status"=>403,
            "result"=>[
                "msg"=>"Нет доступа!"
            ]
        ]);
        exit();
    }
