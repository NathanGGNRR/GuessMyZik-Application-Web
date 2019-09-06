<?php
    require_once('../ConnectionInfo.php');
    $connectionInfo = new ConnectionInfo();
    $bdd = $connectionInfo->GetConnection();
    if(!$bdd){
        echo 'Connexion Failed';
    } else {
        $content = trim(file_get_contents("php://input"));
        $value = json_decode($content);
        $username = $value[0];
        $xp = $value[1];
        $stmt = $bdd->query('UPDATE users SET xp='.intval($xp).' WHERE username="'.$username.'"');
        $stmt = null;
        $bdd = null;
        $connectionInfo->CloseConnection();
    }