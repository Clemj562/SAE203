<?php

function  check_login($username, $password) {
    $mdp = md5($password);
    $result = dbquery("SELECT * FROM utilisateurs WHERE username = ?", [$username]);

    if (count($result) > 0) {
        $user = $result[0];
        if ($user['password'] == $mdp) {
            return $user;
        }
    } else{
        return false;
    }
}


?>

