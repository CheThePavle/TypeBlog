<?php
    require_once "src/db.php";

    $getComplaint = $db->query("SELECT uc.id_complaint, u1.id_user as 'id_witness', u1.login as 'witness', u2.id_user as 'id_accused', u2.login as 'accused', uc.body_complaint, uc.created_at
                                    FROM user as u1
                                    JOIN (user_complaint as uc
                                    JOIN user as u2 
                                    ON u2.id_user = uc.id_accused) 
                                    ON u1.id_user = uc.id_witness 
                                    ORDER BY uc.id_complaint DESC;");

    $treeComplaint = [];
    foreach($getComplaint as $k => $v) {
        $treeComplaint[$v['accused']][]=$v;
    }
