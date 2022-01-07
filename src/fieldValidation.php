<?php
    function validate($data) {
        $data = trim($data);
        $data = preg_replace('/\s+/', ' ', $data);
        // $data = strip_tags($data); // проверить что это
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }