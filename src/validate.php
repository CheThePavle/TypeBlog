<?php
    function validate($data) {
        $data = trim($data);
        $data = preg_replace('/\s+/', ' ', $data);
        $data = strip_tags($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }